<?php
require 'auth.php';
require 'config.php';

$result = NULL;
$searched = false;
$notFound = false;

if(isset($_POST['search'])){

    $stmt = $conn->prepare(
        "SELECT * FROM incidents
        WHERE incident_id=?"
    );

    $stmt->bind_param(
        "s",
        $_POST['incident_id']
    );

    $stmt->execute();

    $result = $stmt->get_result();
    $searched = true;
    
    if($result->num_rows == 0){
        $notFound = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Incident | Incident Management System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Segoe UI', system-ui, -apple-system, 'Roboto', Helvetica, Arial, sans-serif;
            background: linear-gradient(145deg, #e0f2fe 0%, #bae6fd 100%);
            min-height: 100vh;
            padding: 2rem;
            position: relative;
        }

        /* animated background */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: radial-gradient(circle at 20% 30%, rgba(14, 165, 233, 0.1) 2%, transparent 2.5%);
            background-size: 55px 55px;
            pointer-events: none;
        }

        /* main container */
        .search-container {
            max-width: 1100px;
            margin: 0 auto;
            position: relative;
            z-index: 2;
        }

        /* navigation header */
        .nav-header {
            background: linear-gradient(135deg, #0f172a, #1e293b);
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
            color: #7dd3fc;
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

        /* search card */
        .search-card {
            background: white;
            border-radius: 32px;
            box-shadow: 0 20px 35px -12px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .search-header {
            background: linear-gradient(120deg, #0284c7, #0ea5e9);
            padding: 1.8rem 2rem;
            text-align: center;
        }

        .search-header h1 {
            font-size: 1.9rem;
            font-weight: 700;
            color: white;
            letter-spacing: -0.3px;
        }

        .search-header p {
            color: #e0f2fe;
            margin-top: 0.4rem;
            font-size: 0.9rem;
        }

        .search-form {
            padding: 2rem;
        }

        .search-input-group {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            align-items: flex-end;
        }

        .input-field {
            flex: 1;
            min-width: 250px;
        }

        .input-field label {
            display: block;
            font-size: 0.75rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #475569;
            margin-bottom: 0.5rem;
        }

        .input-wrapper {
            display: flex;
            align-items: center;
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 20px;
            transition: all 0.25s;
            padding: 0 1.2rem;
        }

        .input-wrapper:focus-within {
            border-color: #0ea5e9;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.15);
        }

        .input-icon {
            font-size: 1.2rem;
            margin-right: 0.8rem;
            opacity: 0.7;
        }

        .input-wrapper input {
            width: 100%;
            padding: 0.85rem 0;
            border: none;
            background: transparent;
            font-size: 0.95rem;
            font-weight: 500;
            color: #0f172a;
            outline: none;
            font-family: inherit;
        }

        .input-wrapper input::placeholder {
            color: #94a3b8;
            font-weight: 400;
        }

        .search-button {
            background: linear-gradient(105deg, #0284c7, #0ea5e9);
            border: none;
            padding: 0.85rem 2rem;
            font-size: 0.9rem;
            font-weight: 700;
            font-family: inherit;
            color: white;
            border-radius: 40px;
            cursor: pointer;
            transition: all 0.25s;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 10px rgba(2, 132, 199, 0.3);
        }

        .search-button:hover {
            background: linear-gradient(105deg, #0369a1, #0284c7);
            transform: translateY(-2px);
            box-shadow: 0 8px 18px rgba(2, 132, 199, 0.4);
        }

        /* results section */
        .results-section {
            background: white;
            border-radius: 32px;
            box-shadow: 0 20px 35px -12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .results-header {
            background: #f1f5f9;
            padding: 1rem 2rem;
            border-bottom: 2px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .results-header h3 {
            font-size: 1.2rem;
            font-weight: 700;
            color: #0f172a;
        }

        .result-count {
            background: #3b82f6;
            color: white;
            padding: 0.2rem 0.7rem;
            border-radius: 30px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        /* incident detail card */
        .incident-detail {
            padding: 1.5rem 2rem;
            border-bottom: 1px solid #eef2ff;
            transition: background 0.2s;
        }

        .incident-detail:hover {
            background: #fefce8;
        }

        .detail-row {
            display: flex;
            margin-bottom: 1rem;
            align-items: flex-start;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .detail-label {
            min-width: 130px;
            font-weight: 800;
            color: #475569;
            text-transform: uppercase;
            font-size: 0.7rem;
            letter-spacing: 0.5px;
            background: #f1f5f9;
            padding: 0.3rem 0.8rem;
            border-radius: 30px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .detail-value {
            flex: 1;
            font-weight: 600;
            color: #1e293b;
            font-size: 0.95rem;
            word-break: break-word;
        }

        .severity-badge, .status-badge {
            display: inline-block;
            padding: 0.3rem 1rem;
            border-radius: 30px;
            font-size: 0.75rem;
            font-weight: 700;
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

        .description-box {
            background: #f8fafc;
            padding: 1rem;
            border-radius: 20px;
            margin-top: 0.5rem;
            line-height: 1.5;
            color: #334155;
        }

        .divider {
            margin: 1rem 0;
            border-top: 1px dashed #cbd5e1;
        }

        /* empty & not found states */
        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
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

        .search-suggestion {
            background: #e0f2fe;
            border-radius: 20px;
            padding: 1rem;
            margin-top: 1rem;
            font-size: 0.85rem;
        }

        /* footer */
        .footer-note {
            text-align: center;
            margin-top: 2rem;
            color: #5b6e8c;
            font-size: 0.7rem;
        }

        /* responsive */
        @media (max-width: 680px) {
            body {
                padding: 1rem;
            }
            .search-form {
                padding: 1.5rem;
            }
            .search-input-group {
                flex-direction: column;
            }
            .search-button {
                justify-content: center;
                width: 100%;
            }
            .detail-label {
                min-width: 100%;
            }
            .incident-detail {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>

<div class="search-container">
    
    <!-- Header Navigation -->
    <div class="nav-header">
        <div class="brand">
            <div class="brand-icon">🔍📋</div>
            <div class="brand-text">
                <h2>Incident Management System</h2>
                <p>Search & retrieve incident records</p>
            </div>
        </div>
        <div class="nav-actions">
            <a href="dashboard.php" class="nav-btn">🏠 Dashboard</a>
            <a href="view_incidents.php" class="nav-btn">📋 All Incidents</a>
            <a href="report_incident.php" class="nav-btn">➕ Report New</a>
        </div>
    </div>

    <!-- Search Card -->
    <div class="search-card">
        <div class="search-header">
            <h1>🔎 Search Incident</h1>
            <p>Enter the unique Incident ID to retrieve details</p>
        </div>
        
        <form method="POST" class="search-form">
            <div class="search-input-group">
                <div class="input-field">
                    <label>📌 INCIDENT ID</label>
                    <div class="input-wrapper">
                        <span class="input-icon">🆔</span>
                        <input type="text" name="incident_id" placeholder="e.g., INC-2025-001" value="<?php echo isset($_POST['incident_id']) ? htmlspecialchars($_POST['incident_id']) : ''; ?>">
                    </div>
                </div>
                <button type="submit" name="search" class="search-button">
                    <span>🔍</span> SEARCH
                </button>
            </div>
        </form>
    </div>

    <!-- Results Section -->
    <?php if($searched): ?>
        <div class="results-section">
            <div class="results-header">
                <span>📋</span>
                <h3>Search Results</h3>
                <?php if($result && $result->num_rows > 0): ?>
                    <span class="result-count"><?php echo $result->num_rows; ?> found</span>
                <?php endif; ?>
            </div>

            <?php if($notFound): ?>
                <div class="empty-state">
                    <div class="empty-icon">🔍❌</div>
                    <h3>Incident Not Found</h3>
                    <p>No incident with ID "<?php echo htmlspecialchars($_POST['incident_id']); ?>" exists in the system.</p>
                    <div class="search-suggestion">
                        💡 <strong>Tip:</strong> Make sure you entered the correct Incident ID. Check the <a href="view_incidents.php" style="color:#0284c7;">View Incidents</a> page for a list of all IDs.
                    </div>
                </div>
            <?php elseif($result && $result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): 
                    $severityClass = '';
                    if($row['severity'] == 'Low') $severityClass = 'severity-low';
                    elseif($row['severity'] == 'Medium') $severityClass = 'severity-medium';
                    elseif($row['severity'] == 'High') $severityClass = 'severity-high';
                    
                    $statusClass = '';
                    if($row['status'] == 'Open') $statusClass = 'status-open';
                    elseif($row['status'] == 'Investigating') $statusClass = 'status-investigating';
                    elseif($row['status'] == 'Closed') $statusClass = 'status-closed';
                ?>
                    <div class="incident-detail">
                        <div class="detail-row">
                            <div class="detail-label">🆔 Incident ID</div>
                            <div class="detail-value"><strong><?php echo htmlspecialchars($row['incident_id']); ?></strong></div>
                        </div>
                        
                        <div class="detail-row">
                            <div class="detail-label">🏷️ Incident Type</div>
                            <div class="detail-value"><?php echo htmlspecialchars($row['incident_type']); ?></div>
                        </div>
                        
                        <div class="detail-row">
                            <div class="detail-label">⚠️ Severity</div>
                            <div class="detail-value">
                                <span class="severity-badge <?php echo $severityClass; ?>">
                                    <?php echo htmlspecialchars($row['severity']); ?>
                                </span>
                            </div>
                        </div>
                        
                        <div class="detail-row">
                            <div class="detail-label">📌 Status</div>
                            <div class="detail-value">
                                <span class="status-badge <?php echo $statusClass; ?>">
                                    <?php echo htmlspecialchars($row['status']); ?>
                                </span>
                            </div>
                        </div>
                        
                        <div class="detail-row">
                            <div class="detail-label">📅 Date Reported</div>
                            <div class="detail-value"><?php echo date('F j, Y', strtotime($row['date_reported'])); ?></div>
                        </div>
                        
                        <div class="detail-row">
                            <div class="detail-label">👤 Reporter</div>
                            <div class="detail-value"><?php echo htmlspecialchars($row['reporter_name'] ?: '—'); ?></div>
                        </div>
                        
                        <div class="detail-row">
                            <div class="detail-label">📄 Description</div>
                            <div class="detail-value">
                                <div class="description-box">
                                    <?php echo nl2br(htmlspecialchars($row['description'] ?: 'No description provided.')); ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="divider"></div>
                        <div class="detail-row" style="margin-bottom: 0;">
                            <div class="detail-label">🔗 Actions</div>
                            <div class="detail-value">
                                <a href="view_incidents.php" style="color: #0284c7; text-decoration: none; margin-right: 1rem;">📋 View All Incidents</a>
                                <a href="report_incident.php" style="color: #f59e0b; text-decoration: none;">➕ Report New Incident</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
    <?php elseif(isset($_POST['search']) && !$result): ?>
        <!-- This case handles when search was performed but result is empty (caught by notFound already) -->
    <?php else: ?>
        <!-- Initial state - no search performed yet -->
        <div class="results-section">
            <div class="empty-state">
                <div class="empty-icon">🔎📋</div>
                <h3>Ready to Search</h3>
                <p>Enter an Incident ID above and click Search to view incident details.</p>
                <div class="search-suggestion">
                    💡 Example: INC-2025-001, INC-2024-045, or your custom incident identifier.
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="footer-note">
        🛡️ Secure incident lookup • Authorized personnel only
    </div>
</div>

</body>
</html>