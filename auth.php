<?php
// add_incident.php
require_once 'auth.php';
$auth->requireLogin();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $incident_type = $_POST['incident_type'] ?? '';
    $severity = $_POST['severity'] ?? '';
    $userId = $_SESSION['user_id'];
    
    if (empty($title) || empty($description) || empty($incident_type) || empty($severity)) {
        $error = 'All fields are required';
    } else {
        $stmt = $pdo->prepare("INSERT INTO incidents (title, description, incident_type, severity, reported_by) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$title, $description, $incident_type, $severity, $userId])) {
            $success = 'Incident reported successfully!';
            // Clear form
            $title = $description = $incident_type = $severity = '';
        } else {
            $error = 'Failed to report incident';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Incident - Security Incident Reporting System</title>
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
        <div class="form-card">
            <h2>Report New Security Incident</h2>
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="title">Incident Title</label>
                    <input type="text" id="title" name="title" required value="<?php echo htmlspecialchars($title ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label for="incident_type">Incident Type</label>
                    <select id="incident_type" name="incident_type" required>
                        <option value="">Select type</option>
                        <option value="malware" <?php echo (isset($incident_type) && $incident_type == 'malware') ? 'selected' : ''; ?>>Malware</option>
                        <option value="phishing" <?php echo (isset($incident_type) && $incident_type == 'phishing') ? 'selected' : ''; ?>>Phishing</option>
                        <option value="unauthorized_access" <?php echo (isset($incident_type) && $incident_type == 'unauthorized_access') ? 'selected' : ''; ?>>Unauthorized Access</option>
                        <option value="data_breach" <?php echo (isset($incident_type) && $incident_type == 'data_breach') ? 'selected' : ''; ?>>Data Breach</option>
                        <option value="ddos" <?php echo (isset($incident_type) && $incident_type == 'ddos') ? 'selected' : ''; ?>>DDoS Attack</option>
                        <option value="other" <?php echo (isset($incident_type) && $incident_type == 'other') ? 'selected' : ''; ?>>Other</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="severity">Severity Level</label>
                    <select id="severity" name="severity" required>
                        <option value="">Select severity</option>
                        <option value="low" <?php echo (isset($severity) && $severity == 'low') ? 'selected' : ''; ?>>Low</option>
                        <option value="medium" <?php echo (isset($severity) && $severity == 'medium') ? 'selected' : ''; ?>>Medium</option>
                        <option value="high" <?php echo (isset($severity) && $severity == 'high') ? 'selected' : ''; ?>>High</option>
                        <option value="critical" <?php echo (isset($severity) && $severity == 'critical') ? 'selected' : ''; ?>>Critical</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" rows="6" required><?php echo htmlspecialchars($description ?? ''); ?></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary btn-block">Submit Incident Report</button>
            </form>
        </div>
    </div>
</body>
</html>
