
<?php
// incidents.php
require_once 'auth.php';
$auth->requireLogin();

$userId = $_SESSION['user_id'];
$isAdmin = $auth->isAdmin();
$incidentId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$showAll = isset($_GET['all']) && $isAdmin;

// Handle status update (admin only)
if ($isAdmin && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $incidentId = (int)$_POST['incident_id'];
    $newStatus = $_POST['status'];
    $validStatuses = ['reported', 'investigating', 'resolved', 'closed'];
    
    if (in_array($newStatus, $validStatuses)) {
        $resolvedAt = ($newStatus === 'resolved' || $newStatus === 'closed') ? 'NOW()' : 'NULL';
        $stmt = $pdo->prepare("UPDATE incidents SET status = ?, resolved_at = IF(? IN ('resolved', 'closed'), NOW(), NULL) WHERE id = ?");
        $stmt->execute([$newStatus, $newStatus, $incidentId]);
    }
    header("Location: incidents.php?id=$incidentId");
    exit();
}

// Get single incident details
if ($incidentId) {
    if ($isAdmin || $showAll) {
        $stmt = $pdo->prepare("SELECT i.*, u.username FROM incidents i JOIN users u ON i.reported_by = u.id WHERE i.id = ?");
        $stmt->execute([$incidentId]);
    } else {
        $stmt = $pdo->prepare("SELECT * FROM incidents WHERE id = ? AND reported_by = ?");
        $stmt->execute([$incidentId, $userId]);
    }
    $incident = $stmt->fetch();
    
    if (!$incident) {
        header('Location: incidents.php');
        exit();
    }
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Incident Details - Security Incident Reporting System</title>
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
        <nav class="navbar">
            <div class="nav-container">
                <a href="dashboard.php" class="nav-brand">🛡️ SIRS</a>
                <ul class="nav-menu">
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="add_incident.php">Report Incident</a></li>
                    <li><a href="incidents.php">My Incidents</a></li>
                    <li><a href="search.php">Search</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </div>
        </nav>
        
        <div class="container">
            <div class="incident-detail">
                <div class="detail-header">
                    <h2><?php echo htmlspecialchars($incident['title']); ?></h2>
                    <div class="detail-badges">
                        <span class="severity-badge <?php echo $incident['severity']; ?>"><?php echo ucfirst($incident['severity']); ?></span>
                        <span class="status-badge <?php echo $incident['status']; ?>"><?php echo ucfirst($incident['status']); ?></span>
                    </div>
                </div>
                
                <div class="detail-info">
                    <p><strong>Type:</strong> <?php echo ucfirst(str_replace('_', ' ', $incident['incident_type'])); ?></p>
                    <p><strong>Reported By:</strong> <?php echo htmlspecialchars($incident['username'] ?? 'You'); ?></p>
                    <p><strong>Reported At:</strong> <?php echo date('F j, Y g:i A', strtotime($incident['reported_at'])); ?></p>
                    <?php if ($incident['resolved_at']): ?>
                        <p><strong>Resolved At:</strong> <?php echo date('F j, Y g:i A', strtotime($incident['resolved_at'])); ?></p>
                    <?php endif; ?>
                </div>
                
                <div class="detail-description">
                    <h3>Description</h3>
                    <p><?php echo nl2br(htmlspecialchars($incident['description'])); ?></p>
                </div>
                
                <?php if ($isAdmin): ?>
                <div class="admin-actions">
                    <h3>Update Status</h3>
                    <form method="POST" action="" class="inline-form">
                        <input type="hidden" name="incident_id" value="<?php echo $incident['id']; ?>">
                        <select name="status">
                            <option value="reported" <?php echo $incident['status'] == 'reported' ? 'selected' : ''; ?>>Reported</option>
                            <option value="investigating" <?php echo $incident['status'] == 'investigating' ? 'selected' : ''; ?>>Investigating</option>
                            <option value="resolved" <?php echo $incident['status'] == 'resolved' ? 'selected' : ''; ?>>Resolved</option>
                            <option value="closed" <?php echo $incident['status'] == 'closed' ? 'selected' : ''; ?>>Closed</option>
                        </select>
                        <button type="submit" name="update_status" class="btn-small">Update</button>
                    </form>
                </div>
                <?php endif; ?>
                
                <a href="incidents.php" class="btn-secondary">← Back to Incidents</a>
            </div>
        </div>
    </body>
    </html>
    <?php
    exit();
}

// List all incidents
if ($showAll) {
    $stmt = $pdo->query("SELECT i.*, u.username FROM incidents i JOIN users u ON i.reported_by = u.id ORDER BY i.reported_at DESC");
    $incidents = $stmt->fetchAll();
    $pageTitle = "All Incidents";
} else {
    $stmt = $pdo->prepare("SELECT * FROM incidents WHERE reported_by = ? ORDER BY reported_at DESC");
    $stmt->execute([$userId]);
    $incidents = $stmt->fetchAll();
    $pageTitle = "My Incidents";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> - Security Incident Reporting System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <a href="dashboard.php" class="nav-brand">🛡️ SIRS</a>
            <ul class="nav-menu">
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="add_incident.php">Report Incident</a></li>
                <li><a href="incidents.php">My Incidents</a></li>
                <li><a href="search.php">Search</a></li>
                <?php if ($isAdmin): ?>
                    <li><a href="incidents.php?all=1">All Incidents</a></li>
                <?php endif; ?>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>
    
    <div class="container">
        <h1><?php echo $pageTitle; ?></h1>
        
        <?php if (empty($incidents)): ?>
            <p>No incidents found. <a href="add_incident.php">Report your first incident</a></p>
        <?php else: ?>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Severity</th>
                        <th>Status</th>
                        <th>Reported At</th>
                        <?php if ($showAll): ?><th>Reported By</th><?php endif; ?>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($incidents as $incident): ?>
                        <tr>
                            <td><?php echo $incident['id']; ?></td>
                            <td><?php echo htmlspecialchars($incident['title']); ?></td>
                            <td><?php echo htmlspecialchars($incident['incident_type']); ?></td>
                            <td><span class="severity-badge <?php echo $incident['severity']; ?>"><?php echo ucfirst($incident['severity']); ?></span></td>
                            <td><span class="status-badge <?php echo $incident['status']; ?>"><?php echo ucfirst($incident['status']); ?></span></td>
                            <td><?php echo date('Y-m-d', strtotime($incident['reported_at'])); ?></td>
                            <?php if ($showAll): ?>
                                <td><?php echo htmlspecialchars($incident['username']); ?></td>
                            <?php endif; ?>
                            <td><a href="incidents.php?id=<?php echo $incident['id']; ?>" class="btn-small">View</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>