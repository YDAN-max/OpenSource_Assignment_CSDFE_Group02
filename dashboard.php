<?php
require 'auth.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Security Management System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Segoe UI', system-ui, -apple-system, 'Roboto', Helvetica, Arial, sans-serif;
            background: linear-gradient(145deg, #eef2ff 0%, #d9e4f5 100%);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }

        /* animated background particles effect */
        body::before {
            content: '';
            position: fixed;
            width: 100%;
            height: 100%;
            background-image: radial-gradient(circle at 20% 40%, rgba(79, 70, 229, 0.08) 2%, transparent 2.5%);
            background-size: 45px 45px;
            pointer-events: none;
            z-index: 0;
        }

        /* Navbar styling */
        .navbar {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            padding: 1rem 2rem;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            position: sticky;
            top: 0;
            z-index: 100;
            backdrop-filter: blur(8px);
        }

        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .logo-area {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo-icon {
            font-size: 2rem;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));
        }

        .logo-text h1 {
            color: white;
            font-size: 1.4rem;
            font-weight: 700;
            letter-spacing: -0.3px;
        }

        .logo-text p {
            color: #a5b4fc;
            font-size: 0.7rem;
            letter-spacing: 0.5px;
        }

        .user-welcome {
            background: rgba(255, 255, 255, 0.12);
            padding: 0.5rem 1.2rem;
            border-radius: 40px;
            backdrop-filter: blur(4px);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-avatar {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            font-weight: bold;
            color: white;
        }

        .user-welcome span {
            color: #e2e8f0;
            font-size: 0.9rem;
        }

        .user-welcome strong {
            color: white;
            font-weight: 700;
        }

        /* main dashboard container */
        .dashboard-container {
            max-width: 1300px;
            margin: 2rem auto;
            padding: 0 2rem;
            position: relative;
            z-index: 2;
        }

        /* greeting card */
        .greeting-card {
            background: white;
            border-radius: 28px;
            padding: 1.8rem 2rem;
            margin-bottom: 2.5rem;
            box-shadow: 0 10px 30px -12px rgba(0, 0, 0, 0.12);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
            border: 1px solid rgba(255,255,255,0.5);
            backdrop-filter: blur(2px);
            transition: transform 0.2s;
        }

        .greeting-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 20px 35px -15px rgba(0, 0, 0, 0.15);
        }

        .greeting-text h2 {
            font-size: 1.8rem;
            font-weight: 700;
            background: linear-gradient(120deg, #1e293b, #4f46e5);
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
        }

        .greeting-text p {
            color: #475569;
            margin-top: 0.3rem;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .date-badge {
            background: #eef2ff;
            padding: 0.5rem 1rem;
            border-radius: 40px;
            font-size: 0.8rem;
            font-weight: 500;
            color: #4f46e5;
        }

        /* action cards grid */
        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.8rem;
            margin-bottom: 2.5rem;
        }

        .action-card {
            background: white;
            border-radius: 28px;
            padding: 1.8rem 1.5rem;
            transition: all 0.3s cubic-bezier(0.2, 0.9, 0.4, 1.1);
            cursor: pointer;
            text-decoration: none;
            color: inherit;
            display: block;
            box-shadow: 0 12px 24px -12px rgba(0, 0, 0, 0.08);
            border: 1px solid #f1f5f9;
            position: relative;
            overflow: hidden;
        }

        .action-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 6px;
            background: linear-gradient(90deg, #4f46e5, #7c3aed);
            transform: scaleX(0);
            transition: transform 0.3s;
            transform-origin: left;
        }

        .action-card:hover::before {
            transform: scaleX(1);
        }

        .action-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 24px 36px -16px rgba(0, 0, 0, 0.2);
            border-color: #cbd5e1;
        }

        .card-icon {
            font-size: 2.8rem;
            margin-bottom: 1rem;
            display: inline-block;
        }

        .action-card h3 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 0.5rem;
        }

        .action-card p {
            color: #5b6e8c;
            font-size: 0.9rem;
            line-height: 1.4;
        }

        .card-arrow {
            margin-top: 1.2rem;
            display: flex;
            align-items: center;
            gap: 6px;
            font-weight: 600;
            font-size: 0.85rem;
            color: #4f46e5;
            transition: gap 0.2s;
        }

        .action-card:hover .card-arrow {
            gap: 12px;
        }

        /* special card for logout */
        .logout-card {
            background: linear-gradient(105deg, #fff5f5, #ffffff);
            border: 1px solid #ffe2e2;
        }

        .logout-card .card-icon {
            filter: drop-shadow(0 2px 4px rgba(220,38,38,0.2));
        }

        /* stats or info row */
        .info-row {
            background: #f1f5f9;
            border-radius: 24px;
            padding: 1.2rem 1.8rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
            margin-top: 1rem;
        }

        .info-chip {
            display: flex;
            align-items: center;
            gap: 12px;
            background: white;
            padding: 0.5rem 1.2rem;
            border-radius: 60px;
            font-size: 0.85rem;
            font-weight: 500;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        /* footer */
        .footer-note {
            text-align: center;
            margin-top: 3rem;
            padding: 1rem;
            color: #5b6e8c;
            font-size: 0.75rem;
            border-top: 1px solid #cbd5e1;
        }

        /* Responsive */
        @media (max-width: 680px) {
            .dashboard-container {
                padding: 0 1rem;
            }
            .greeting-card {
                flex-direction: column;
                text-align: center;
            }
            .nav-container {
                flex-direction: column;
                text-align: center;
            }
            .user-welcome {
                width: 100%;
                justify-content: center;
            }
            .actions-grid {
                gap: 1rem;
            }
            .action-card {
                padding: 1.4rem;
            }
        }

        /* hover animation for all cards */
        @keyframes fadeSlideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .action-card {
            animation: fadeSlideUp 0.4s ease backwards;
        }

        .action-card:nth-child(1) { animation-delay: 0.05s; }
        .action-card:nth-child(2) { animation-delay: 0.1s; }
        .action-card:nth-child(3) { animation-delay: 0.15s; }
        .action-card:nth-child(4) { animation-delay: 0.2s; }
    </style>
</head>
<body>

<!-- Navigation Bar -->
<div class="navbar">
    <div class="nav-container">
        <div class="logo-area">
            <div class="logo-icon">🛡️</div>
            <div class="logo-text">
                <h1>SecureTrack</h1>
                <p>Incident Management System</p>
            </div>
        </div>
        <div class="user-welcome">
            <div class="user-avatar">
                <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
            </div>
            <span>Welcome back,</span>
            <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>
        </div>
    </div>
</div>

<!-- Main Dashboard Content -->
<div class="dashboard-container">
    
    <!-- Greeting Section -->
    <div class="greeting-card">
        <div class="greeting-text">
            <h2>📊 Dashboard Overview</h2>
            <p>🔐 You have full access to security modules & reports</p>
        </div>
        <div class="date-badge">
            <?php 
                date_default_timezone_set('UTC');
                echo date('l, F j, Y'); 
            ?>
        </div>
    </div>

    <!-- Action Cards Grid -->
    <div class="actions-grid">
        
        <!-- Report Incident Card -->
        <a href="report_incident.php" class="action-card">
            <div class="card-icon">📝⚠️</div>
            <h3>Report Incident</h3>
            <p>Log a new security incident, add description, location, and severity level.</p>
            <div class="card-arrow">
                <span>Report now</span> <span>→</span>
            </div>
        </a>

        <!-- View Incidents Card -->
        <a href="view_incidents.php" class="action-card">
            <div class="card-icon">📋🔍</div>
            <h3>View Incidents</h3>
            <p>Browse all reported incidents, filter by date, status, and category.</p>
            <div class="card-arrow">
                <span>Browse records</span> <span>→</span>
            </div>
        </a>

        <!-- Search Incident Card -->
        <a href="search_incident.php" class="action-card">
            <div class="card-icon">🔎📌</div>
            <h3>Search Incident</h3>
            <p>Find specific incidents by ID, keyword, or involved personnel.</p>
            <div class="card-arrow">
                <span>Quick search</span> <span>→</span>
            </div>
        </a>

        <!-- Logout Card -->
        <a href="logout.php" class="action-card logout-card">
            <div class="card-icon">🚪🔓</div>
            <h3>Logout</h3>
            <p>Securely end your session and return to login page.</p>
            <div class="card-arrow">
                <span>Sign out</span> <span>→</span>
            </div>
        </a>
    </div>

    <!-- Additional Info Row (system status) -->
    <div class="info-row">
        <div class="info-chip">
            <span>🟢</span> System Status: Operational
        </div>
        <div class="info-chip">
            <span>🛡️</span> Role: <?php echo isset($_SESSION['role']) ? htmlspecialchars($_SESSION['role']) : 'Authorized User'; ?>
        </div>
        <div class="info-chip">
            <span>⏱️</span> Session Active
        </div>
    </div>

    <div class="footer-note">
        ⚡ Secure incident reporting platform • All actions are logged for audit purposes
    </div>
</div>

</body>
</html>