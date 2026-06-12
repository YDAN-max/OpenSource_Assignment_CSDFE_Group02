<?php
// search.php
require_once 'auth.php';
$auth->requireLogin();

$isAdmin = $auth->isAdmin();
$userId = $_SESSION['user_id'];
$results = [];
$searchTerm = '';

if (isset($_GET['q']) && !empty(trim($_GET['q']))) {
    $searchTerm = trim($_GET['q']);
    $searchPattern = "%$searchTerm%";
    
    if ($isAdmin) {
        $stmt = $pdo->prepare("
            SELECT i.*, u.username 
            FROM incidents i 
            JOIN users u ON i.reported_by = u.id 
            WHERE i.title LIKE ? OR i.description LIKE ? OR i.incident_type LIKE ?
            ORDER BY i.reported_at DESC
        ");
        $stmt->execute([$searchPattern, $searchPattern, $searchPattern]);
    } else {
        $stmt = $pdo->prepare("
            SELECT * FROM incidents 
            WHERE reported_by = ? AND (title LIKE ? OR description LIKE ? OR incident_type LIKE ?)
            ORDER BY reported_at DESC
        ");
        $stmt->execute([$userId, $searchPattern, $searchPattern, $searchPattern]);
    }
    $results = $stmt->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Incidents - Security Incident Reporting System</title>
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
        <div class="search-container">
            <h1>Search Incidents</h1>
            
            <form method="GET" action="" class="search-form">
                <div class="search-box">
                    <input type="text" name="q" placeholder="Search by title, description, or type..."
