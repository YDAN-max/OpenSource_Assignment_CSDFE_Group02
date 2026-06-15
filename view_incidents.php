<?php
require 'auth.php';
require 'config.php';

$result = $conn->query(
    "SELECT * FROM incidents ORDER BY date_reported DESC"
);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Incidents | Incident Management System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Segoe UI', system-ui, -apple-system, 'Roboto', Helvetica, Arial, sans-serif;
            background: linear-gradient(145deg, #e0e7ff 0%, #c7d2fe 100%);
            min-height: 100vh;
            padding: 2rem;
            position: relative;
        }

        /* decorative background */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: radial-gradient(circle at 25% 40%, rgba(79, 70, 229, 0.08) 2%, transparent 2.5%);
            background-size: 50px 50px;
            pointer-events: none;
        }

        /* main container */
        .incidents-container {
            max-width: 1400px;
            margin: 0 auto;
            position: relative;
            z-index: 2;
        }

        /* navigation header */
        .nav-header {
            background: linear-gradient(135deg, #1e293b, #0f172a);
            border-radius: 28px;
            padding: 1rem 2rem;
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
            box-shadow: 0 12px 28px -10px rgba(0, 0, 0, 0.25);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-icon {
            font-size: 2rem;
            filter: drop-shadow(0 2px 6px rgba(0,0,0,0.3));
        }

        .brand-text h2 {
            color: white;
            font-size: 1.4rem;
            font-weight: 700;
        }

        .brand-text p {
            color: #a5b4fc;
            font-size: 0.75rem;
        }

        .nav-actions {
            display: flex;
            gap: 12px;
        }

        .nav-btn {
            background: rgba(255, 255, 255, 0.12);
            padding: 0.6rem 1.3rem;
            border-radius: 40px;
            color: #e2e8f0;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.85rem;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nav-btn:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-2px);
            color: white;
        }

        /* stats cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1.2rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: 24px;
            padding: 1.2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transition: transform 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-3px);
        }

        .stat-icon {
            font-size: 2.2rem;
        }

        .stat-info h3 {
            font-size: 1.8rem;
            font-weight: 800;
            color: #1e293b;
        }

        .stat-info p {
            font-size: 0.75rem;
            color: #5b6e8c;
            font-weight: 600;
            text-transform: uppercase;
        }

        /* title section */
        .title-section {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            flex-wrap: wrap;
            margin-bottom: 1.5rem;
            padding: 0 0.5rem;
        }

        .title-section h1 {
            font-size: 1.8rem;
            font-weight: 700;
            background: linear-gradient(120deg, #1e293b, #4f46e5);
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
        }

        .record-count {
            background: #eef2ff;
            padding: 0.4rem 1rem;
            border-radius: 40px;
            font-size: 0.8rem;
            font-weight: 600;
            color: #4f46e5;
        }

        /* table container with responsive overflow */
        .table-wrapper {
            background: white;
            border-radius: 28px;
            box-shadow: 0 20px 35px -12px rgba(0, 0, 0, 0.15);
            overflow-x: auto;
            padding: 0;
            transition: all 0.2s;
        }

        .incident-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            font-size: 0.85rem;
        }

        .incident-table th {
            background: #f8fafc;
            color: #1e293b;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.7rem;
            letter-spacing: 1px;
            padding: 1.2rem 1rem;
            border-bottom: 2px solid #e2e8f0;
            text-align: left;
        }

        .incident-table td {
            padding: 1rem;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
            font-weight: 500;
            vertical-align: middle;
        }

        .incident-table tr:hover td {
            background-color: #fefce8;
            transition: background 0.2s;
        }

        /* severity & status badges */
        .severity-badge {
            display: inline-block;
            padding: 0.3rem 0.9rem;
            border-radius: 30px;
            font-size: 0.7rem;
            font-weight: 700;
            text-align: center;
            min-width: 70px;
        }

        .severity-low {
            background: #d1fae5;
            color: #065f46;
        }

        .severity-medium {
            background: #fed7aa;
            color: #9b2c1d;
        }

        .severity-high {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-badge {
            display: inline-block;
            padding: 0.3rem 0.9rem;
            border-radius: 30px;
            font-size: 0.7rem;
            font-weight: 700;
        }

        .status-open {
            background: #dbeafe;
            color: #1e40af;
        }

        .status-investigating {
            background: #fef3c7;
            color: #92400e;
        }

        .status-closed {
            background: #e0e7ff;
            color: #3730a3;
        }

        /* description preview */
        .desc-preview {
            max-width: 220px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* empty state */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: white;
            border-radius: 28px;
        }

        .empty-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }

        .empty-state h3 {
            color: #334155;
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            color: #6c86a3;
        }

        .report-link {
            display: inline-block;
            margin-top: 1.5rem;
            background: #4f46e5;
            color: white;
            padding: 0.6rem 1.5rem;
            border-radius: 40px;
            text-decoration: none;
            font-weight: 600;
        }

        /* footer */
        .footer-note {
            text-align: center;
            margin-top: 2rem;
            color: #5b6e8c;
            font-size: 0.7rem;
        }

        /* responsive */
        @media (max-width: 768px) {
            body {
                padding: 1rem;
            }
            .incident-table th, 
            .incident-table td {
                padding: 0.75rem 0.5rem;
                font-size: 0.75rem;
            }
            .title-section h1 {
                font-size: 1.3rem;
            }
            .stats-grid {
                gap: 0.8rem;
            }
        }
    </style>
</head>
<body>

<div class="incidents-container">
    
    <!-- Header Navigation -->
    <div class="nav-header">
        <div class="brand">
            <div class="brand-icon">📋🔍</div>
            <div class="brand-text">
                <h2>Incident Management System</h2>
                <p>Secure monitoring & reporting platform</p>
            </div>
        </div>
        <div class="nav-actions">
            <a href="dashboard.php" class="nav-btn">🏠 Dashboard</a>
            <a href="report_incident.php" class="nav-btn">➕ New Incident</a>
        </div>
    </div>

    <!-- Stats Section -->
    <?php 
        // Fetch statistics for better UX
        $totalQuery = $conn->query("SELECT COUNT(*) as total FROM incidents");
        $totalIncidents = $totalQuery->fetch_assoc()['total'];
        
        $openQuery = $conn->query("SELECT COUNT(*) as openCount FROM incidents WHERE status = 'Open'");
        $openIncidents = $openQuery->fetch_assoc()['openCount'];
        
        $highQuery = $conn->query("SELECT COUNT(*) as highCount FROM incidents WHERE severity = 'High'");
        $highSeverity = $highQuery->fetch_assoc()['highCount'];
    ?>
    
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">📊</div>
            <div class="stat-info">
                <h3><?php echo $totalIncidents; ?></h3>
                <p>Total Incidents</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">🟢</div>
            <div class="stat-info">
                <h3><?php echo $openIncidents; ?></h3>
                <p>Open Cases</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">🔴</div>
            <div class="stat-info">
                <h3><?php echo $highSeverity; ?></h3>
                <p>High Severity</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">✅</div>
            <div class="stat-info">
                <h3><?php echo $totalIncidents - $openIncidents; ?></h3>
                <p>Resolved/Closed</p>
            </div>
        </div>
    </div>

    <!-- Title & Records -->
    <div class="title-section">
        <h1>📌 Incident Reports</h1>
        <div class="record-count">📋 <?php echo $totalIncidents; ?> record(s) found</div>
    </div>

    <!-- Table -->
    <?php if ($totalIncidents > 0): ?>
    <div class="table-wrapper">
        <table class="incident-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Type</th>
                    <th>Description</th>
                    <th>Severity</th>
                    <th>Date Reported</th>
                    <th>Reporter</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    // Reset result pointer (since we used another query above)
                    $result = $conn->query("SELECT * FROM incidents ORDER BY date_reported DESC");
                    while($row = $result->fetch_assoc()): 
                    
                    // Severity class
                    $severityClass = '';
                    $severityText = $row['severity'];
                    if($row['severity'] == 'Low') $severityClass = 'severity-low';
                    elseif($row['severity'] == 'Medium') $severityClass = 'severity-medium';
                    elseif($row['severity'] == 'High') $severityClass = 'severity-high';
                    
                    // Status class
                    $statusClass = '';
                    if($row['status'] == 'Open') $statusClass = 'status-open';
                    elseif($row['status'] == 'Investigating') $statusClass = 'status-investigating';
                    elseif($row['status'] == 'Closed') $statusClass = 'status-closed';
                ?>
                <tr>
                    <td><strong><?php echo htmlspecialchars($row['incident_id']); ?></strong></td>
                    <td><?php echo htmlspecialchars($row['incident_type']); ?></td>
                    <td class="desc-preview" title="<?php echo htmlspecialchars($row['description']); ?>">
                        <?php echo htmlspecialchars(substr($row['description'], 0, 60)) . (strlen($row['description']) > 60 ? '...' : ''); ?>
                    </td>
                    <td>
                        <span class="severity-badge <?php echo $severityClass; ?>">
                            <?php echo htmlspecialchars($row['severity']); ?>
                        </span>
                    </td>
                    <td><?php echo date('M d, Y', strtotime($row['date_reported'])); ?></td>
                    <td><?php echo htmlspecialchars($row['reporter_name'] ?: '—'); ?></td>
                    <td>
                        <span class="status-badge <?php echo $statusClass; ?>">
                            <?php echo htmlspecialchars($row['status']); ?>
                        </span>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
        <!-- Empty State -->
        <div class="empty-state">
            <div class="empty-icon">📭</div>
            <h3>No incidents reported yet</h3>
            <p>Be the first to report a security incident.</p>
            <a href="report_incident.php" class="report-link">+ Report Incident</a>
        </div>
    <?php endif; ?>

    <div class="footer-note">
        🛡️ Secure system • All incident records are logged for compliance
    </div>
</div>

</body>
</html>