<?php
// dashboard.php
require_once 'auth.php';
$auth->requireLogin();

$userId = $_SESSION['user_id'];
$isAdmin = $auth->isAdmin();

// Get statistics
if ($isAdmin) {
    // Admin sees all incidents
    $totalIncidents = $pdo->query("SELECT COUNT(*) FROM incidents")->fetchColumn();
    $openIncidents = $pdo->query("SELECT COUNT(*) FROM incidents WHERE status != 'closed' AND status != 'resolved'")->fetchColumn();
    $criticalIncidents = $pdo->query("SELECT COUNT(*) FROM incidents WHERE severity = 'critical' AND status != 'closed'")->fetchColumn();
    
    $recentIncidents = $pdo->query("SELECT i.*, u.username FROM incidents i JOIN users u ON i.reported_by = u.id ORDER BY i.reported_at DESC LIMIT 10")->fetchAll();
    
    // Severity breakdown
    $severityStats = $pdo->query("SELECT severity, COUNT(*) as count FROM incidents GROUP BY severity")->fetchAll();
} else {
    // Regular user sees only their incidents
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM incidents WHERE reported_by = ?");
    $stmt->execute([$userId]);
    $totalIncidents = $stmt->fetchColumn();
    
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM incidents WHERE reported_by = ? AND status != 'closed' AND status != 'resolved'");
    $stmt->execute([$userId]);
    $openIncidents = $stmt->fetchColumn();
    
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM incidents WHERE reported_by = ? AND severity = 'critical' AND status != 'closed'");
    $stmt->execute([$userId]);
    $criticalIncidents = $stmt->fetchColumn();
    
    $stmt = $pdo->prepare("SELECT * FROM incidents WHERE reported_by = ? ORDER BY reported_at DESC LIMIT 10");
    $stmt->execute([$userId]);
    $recentIncidents = $stmt->fetchAll();
    
    $severityStats = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Security Incident Reporting System</title>
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
                <li><a href="logout.php">Logout (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a></li>
            </ul>
        </div>
    </nav>
    
    <div class="container">
        <h1>Dashboard</h1>
        
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Incidents</h3>
                <div class="stat-number"><?php echo $totalIncidents; ?></div>
            </div>
            <div class="stat-card">
                <h3>Open Incidents</h3>
                <div class="stat-number"><?php echo $openIncidents; ?></div>
            </div>
            <div class="stat-card">
                <h3>Critical Active</h3>
                <div class="stat-number"><?php echo $criticalIncidents; ?></div>
            </div>
        </div>
        
        <?php if (!empty($severityStats) && $isAdmin): ?>
        <div class="severity-breakdown">
            <h3>Incidents by Severity</h3>
            <div class="severity-bars">
                <?php foreach ($severityStats as $stat): ?>
                    <div class="severity-item">
                        <span class="severity-label <?php echo $stat['severity']; ?>"><?php echo ucfirst($stat['severity']); ?></span>
                        <span class="severity-count"><?php echo $stat['count']; ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <div class="recent-incidents">
            <h3>Recent Incidents</h3>
            <?php if (empty($recentIncidents)): ?>
                <p>No incidents reported yet. <a href="add_incident.php">Report your first incident</a></p>
            <?php else: ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Type</th>
                            <th>Severity</th>
                            <th>Status</th>
                            <th>Reported At</th>
                            <?php if ($isAdmin): ?><th>Reported By</th><?php endif; ?>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentIncidents as $incident): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($incident['title']); ?></td>
                                <td><?php echo htmlspecialchars($incident['incident_type']); ?></td>
                                <td><span class="severity-badge <?php echo $incident['severity']; ?>"><?php echo ucfirst($incident['severity']); ?></span></td>
                                <td><span class="status-badge <?php echo $incident['status']; ?>"><?php echo ucfirst($incident['status']); ?></span></td>
                                <td><?php echo date('Y-m-d H:i', strtotime($incident['reported_at'])); ?></td>
                                <?php if ($isAdmin): ?>
                                    <td><?php echo htmlspecialchars($incident['username'] ?? 'Unknown'); ?></td>
                                <?php endif; ?>
                                <td><a href="incidents.php?id=<?php echo $incident['id']; ?>" class="btn-small">View</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>