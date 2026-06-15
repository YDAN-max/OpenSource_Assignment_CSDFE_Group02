<?php
require 'auth.php';
require 'config.php';

$message = "";

if(isset($_POST['save'])){

    $stmt = $conn->prepare(
        "INSERT INTO report_incident(
            incident_id,
            incident_type,
            description,
            severity,
            date_reported,
            reporter_name,
            status
        )
        VALUES(?,?,?,?,?,?,?)"
    );

    $stmt->bind_param(
        "sssssss",
        $_POST['incident_id'],
        $_POST['incident_type'],
        $_POST['description'],
        $_POST['severity'],
        $_POST['date_reported'],
        $_POST['reporter_name'],
        $_POST['status']
    );

    if($stmt->execute()){
        $message = "✅ Incident Saved Successfully!";
    } else {
        $message = "❌ Failed to save incident. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Incident | SecureTrack</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Segoe UI', system-ui, -apple-system, 'Roboto', Helvetica, Arial, sans-serif;
            background: linear-gradient(145deg, #fef3c7 0%, #fde68a 100%);
            min-height: 100vh;
            padding: 2rem;
            position: relative;
        }

        /* Animated background pattern */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200"><path fill="rgba(251,191,36,0.08)" d="M70 30l30-20 30 20v40l-30 20-30-20zM30 90l30-20 30 20v40l-30 20-30-20zM110 90l30-20 30 20v40l-30 20-30-20z"/></svg>');
            background-repeat: repeat;
            background-size: 60px;
            opacity: 0.4;
            pointer-events: none;
            z-index: 0;
        }

        /* Main container */
        .incident-container {
            max-width: 800px;
            margin: 0 auto;
            position: relative;
            z-index: 2;
        }

        /* Header / Navigation bar */
        .top-nav {
            background: linear-gradient(135deg, #1e293b, #0f172a);
            border-radius: 28px;
            padding: 1rem 2rem;
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
            box-shadow: 0 10px 25px -8px rgba(0, 0, 0, 0.2);
        }

        .nav-brand {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .nav-brand span {
            font-size: 1.8rem;
        }

        .nav-brand h2 {
            color: white;
            font-size: 1.3rem;
            font-weight: 600;
        }

        .back-btn {
            background: rgba(255, 255, 255, 0.15);
            padding: 0.5rem 1.2rem;
            border-radius: 40px;
            color: #fde68a;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .back-btn:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateX(-3px);
        }

        /* Form card */
        .form-card {
            background: white;
            border-radius: 32px;
            box-shadow: 0 25px 45px -15px rgba(0, 0, 0, 0.25);
            overflow: hidden;
            transition: transform 0.2s;
        }

        .form-header {
            background: linear-gradient(120deg, #f59e0b, #d97706);
            padding: 1.8rem 2rem;
            text-align: center;
        }

        .form-header h1 {
            font-size: 1.9rem;
            font-weight: 700;
            color: white;
            letter-spacing: -0.3px;
        }

        .form-header p {
            color: #fef3c7;
            margin-top: 0.4rem;
            font-size: 0.9rem;
        }

        /* Alert message */
        .alert-message {
            margin: 1.5rem 2rem 0 2rem;
            padding: 1rem 1.3rem;
            border-radius: 20px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 12px;
            animation: slideDown 0.4s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-15px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-success {
            background: #ecfdf5;
            border-left: 5px solid #10b981;
            color: #065f46;
        }

        .alert-error {
            background: #fef2f2;
            border-left: 5px solid #ef4444;
            color: #991b1b;
        }

        /* Form styling */
        .incident-form {
            padding: 2rem;
        }

        .form-row {
            margin-bottom: 1.5rem;
            display: flex;
            flex-direction: column;
        }

        .form-row label {
            font-size: 0.8rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #4b5563;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-row label i {
            font-size: 1rem;
            font-style: normal;
        }

        .input-wrapper {
            display: flex;
            align-items: center;
            background: #f9fafb;
            border: 2px solid #e5e7eb;
            border-radius: 20px;
            transition: all 0.25s;
            padding: 0 1.2rem;
        }

        .input-wrapper:focus-within {
            border-color: #f59e0b;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.15);
        }

        .input-icon {
            font-size: 1.2rem;
            margin-right: 0.8rem;
            opacity: 0.7;
        }

        .input-wrapper input,
        .input-wrapper select,
        .input-wrapper textarea {
            width: 100%;
            padding: 0.85rem 0;
            border: none;
            background: transparent;
            font-size: 0.95rem;
            font-weight: 500;
            color: #1f2937;
            outline: none;
            font-family: inherit;
        }

        .input-wrapper textarea {
            padding: 0.85rem 0;
            resize: vertical;
            min-height: 100px;
        }

        .input-wrapper select {
            cursor: pointer;
            appearance: none;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="%236b7280" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>') no-repeat right 0.5rem center;
            background-size: 18px;
        }

        .input-wrapper input::placeholder,
        .input-wrapper textarea::placeholder {
            color: #9ca3af;
            font-weight: 400;
        }

        /* two columns layout */
        .two-columns {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        /* Save button */
        .save-button {
            width: 100%;
            background: linear-gradient(105deg, #f59e0b, #ea580c);
            border: none;
            padding: 1rem 1.5rem;
            font-size: 1rem;
            font-weight: 700;
            font-family: inherit;
            color: white;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-top: 0.8rem;
            box-shadow: 0 8px 18px -6px rgba(245, 158, 11, 0.4);
        }

        .save-button:hover {
            background: linear-gradient(105deg, #d97706, #c2410c);
            transform: translateY(-2px);
            box-shadow: 0 14px 24px -8px rgba(245, 158, 11, 0.5);
        }

        .save-button:active {
            transform: translateY(1px);
        }

        /* helper text */
        .helper-text {
            font-size: 0.7rem;
            color: #6c86a3;
            margin-top: 0.3rem;
            margin-left: 0.8rem;
        }

        /* responsive */
        @media (max-width: 640px) {
            body {
                padding: 1rem;
            }
            .incident-form {
                padding: 1.5rem;
            }
            .two-columns {
                grid-template-columns: 1fr;
                gap: 0;
            }
            .top-nav {
                padding: 1rem;
            }
            .form-header h1 {
                font-size: 1.5rem;
            }
        }

        /* Required field indicator */
        .required-star {
            color: #ef4444;
            margin-left: 4px;
        }
    </style>
</head>
<body>

<div class="incident-container">
    
    <!-- Top Navigation -->
    <div class="top-nav">
        <div class="nav-brand">
            <span>⚠️📋</span>
            <h2>SecureTrack Incident Hub</h2>
        </div>
        <a href="dashboard.php" class="back-btn">
            <span>←</span> Dashboard
        </a>
    </div>

    <!-- Form Card -->
    <div class="form-card">
        <div class="form-header">
            <h1>📝 Report New Incident</h1>
            <p>Fill in the details below to register an incident in the system</p>
        </div>

        <?php if (!empty($message)): ?>
            <?php $isSuccess = strpos($message, '✅') !== false; ?>
            <div class="alert-message <?php echo $isSuccess ? 'alert-success' : 'alert-error'; ?>">
                <span><?php echo $isSuccess ? '✓' : '⚠️'; ?></span>
                <span><?php echo htmlspecialchars($message); ?></span>
            </div>
        <?php endif; ?>

        <form method="POST" class="incident-form">
            <div class="form-row">
                <label>
                    <span>🆔</span> INCIDENT ID <span class="required-star">*</span>
                </label>
                <div class="input-wrapper">
                    <span class="input-icon">🔢</span>
                    <input type="text" name="incident_id" placeholder="e.g., INC-2025-001" required>
                </div>
                <div class="helper-text">Unique identifier for this incident</div>
            </div>

            <div class="form-row">
                <label>
                    <span>🏷️</span> INCIDENT TYPE <span class="required-star">*</span>
                </label>
                <div class="input-wrapper">
                    <span class="input-icon">⚠️</span>
                    <input type="text" name="incident_type" placeholder="e.g., Theft, Cyber Attack, Accident" required>
                </div>
            </div>

            <div class="form-row">
                <label>
                    <span>📄</span> DESCRIPTION
                </label>
                <div class="input-wrapper">
                    <span class="input-icon">✏️</span>
                    <textarea name="description" placeholder="Provide detailed information about the incident..."></textarea>
                </div>
            </div>

            <div class="two-columns">
                <div class="form-row">
                    <label>
                        <span>⚡</span> SEVERITY <span class="required-star">*</span>
                    </label>
                    <div class="input-wrapper">
                        <span class="input-icon">🎯</span>
                        <select name="severity" required>
                            <option value="Low">🟢 Low - Minor issue</option>
                            <option value="Medium">🟡 Medium - Requires attention</option>
                            <option value="High">🔴 High - Critical emergency</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <label>
                        <span>📅</span> DATE REPORTED
                    </label>
                    <div class="input-wrapper">
                        <span class="input-icon">🗓️</span>
                        <input type="date" name="date_reported" value="<?php echo date('Y-m-d'); ?>">
                    </div>
                </div>
            </div>

            <div class="two-columns">
                <div class="form-row">
                    <label>
                        <span>👤</span> REPORTER NAME
                    </label>
                    <div class="input-wrapper">
                        <span class="input-icon">📛</span>
                        <input type="text" name="reporter_name" placeholder="Full name or officer ID">
                    </div>
                </div>

                <div class="form-row">
                    <label>
                        <span>📌</span> STATUS <span class="required-star">*</span>
                    </label>
                    <div class="input-wrapper">
                        <span class="input-icon">🔘</span>
                        <select name="status" required>
                            <option value="Open">🟢 Open - New report</option>
                            <option value="Investigating">🟠 Investigating - Under review</option>
                            <option value="Closed">🔵 Closed - Resolved</option>
                        </select>
                    </div>
                </div>
            </div>

            <button type="submit" name="save" class="save-button">
                <span>💾</span> SAVE INCIDENT REPORT
            </button>
        </form>
    </div>
</div>

</body>
</html>