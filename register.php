<?php
require 'config.php';

$message = "";

if(isset($_POST['register'])){

    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $stmt = $conn->prepare(
        "INSERT INTO users(username,password,role)
         VALUES(?,?,?)"
    );

    $stmt->bind_param(
        "sss",
        $username,
        $password,
        $role
    );

    if($stmt->execute()){
        $message = "User Registered Successfully!";
    }else{
        $message = "Registration Failed: Username may already exist";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Create Account</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Segoe UI', system-ui, -apple-system, 'Roboto', Helvetica, Arial, sans-serif;
            background: linear-gradient(145deg, #0f172a 0%, #1e293b 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }

        /* Animated background elements */
        body::before {
            content: '';
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(56, 189, 248, 0.15), transparent 70%);
            border-radius: 50%;
            top: -200px;
            left: -200px;
            animation: pulse 15s infinite ease-in-out;
        }

        body::after {
            content: '';
            position: absolute;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(168, 85, 247, 0.12), transparent 70%);
            border-radius: 50%;
            bottom: -250px;
            right: -250px;
            animation: pulse 20s infinite reverse;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1) translate(0, 0); opacity: 0.5; }
            50% { transform: scale(1.1) translate(30px, -20px); opacity: 0.8; }
        }

        /* Main card container */
        .register-card {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 36px;
            box-shadow: 0 30px 60px -20px rgba(0, 0, 0, 0.45), 0 8px 20px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 500px;
            overflow: hidden;
            backdrop-filter: blur(2px);
            transition: transform 0.3s cubic-bezier(0.2, 0.9, 0.4, 1.1), box-shadow 0.3s;
            z-index: 2;
        }

        .register-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 40px 70px -18px rgba(0, 0, 0, 0.5);
        }

        /* Header styling */
        .card-header {
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
            padding: 2rem 2rem 1.8rem;
            text-align: center;
            position: relative;
        }

        .card-header h2 {
            font-size: 2rem;
            font-weight: 700;
            letter-spacing: -0.3px;
            background: linear-gradient(120deg, #ffffff, #c4b5fd, #a5f3fc);
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
            margin-bottom: 0.5rem;
        }

        .card-header p {
            color: #9ca3af;
            font-size: 0.85rem;
            font-weight: 400;
        }

        .header-icon {
            font-size: 2.8rem;
            margin-bottom: 0.3rem;
            display: inline-block;
            filter: drop-shadow(0 4px 6px rgba(0,0,0,0.2));
        }

        /* Message alert styling - success/error */
        .alert-message {
            margin: 1.5rem 1.8rem 0 1.8rem;
            padding: 1rem 1.2rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 12px;
            backdrop-filter: blur(4px);
            animation: slideDown 0.4s ease;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
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
            background: linear-gradient(100deg, #d1fae5, #a7f3d0);
            border-left: 5px solid #10b981;
            color: #065f46;
        }

        .alert-error {
            background: linear-gradient(100deg, #fee2e2, #ffebeb);
            border-left: 5px solid #dc2626;
            color: #991b1b;
        }

        .alert-message span:first-child {
            font-size: 1.3rem;
        }

        .alert-message:empty {
            display: none;
        }

        /* Form styling */
        .register-form {
            padding: 2rem 2rem 2rem;
        }

        .input-wrapper {
            margin-bottom: 1.6rem;
        }

        .input-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.75rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #334155;
            margin-bottom: 0.6rem;
        }

        .input-label span {
            font-size: 1rem;
        }

        .input-group {
            display: flex;
            align-items: center;
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 24px;
            transition: all 0.25s;
            padding: 0 1.2rem;
        }

        .input-group:focus-within {
            border-color: #8b5cf6;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(139, 92, 246, 0.15);
        }

        .input-icon {
            font-size: 1.2rem;
            margin-right: 0.8rem;
            opacity: 0.7;
        }

        .input-group input, 
        .input-group select {
            width: 100%;
            padding: 0.9rem 0;
            border: none;
            background: transparent;
            font-size: 0.95rem;
            font-weight: 500;
            color: #0f172a;
            outline: none;
            font-family: inherit;
            cursor: pointer;
        }

        .input-group select {
            padding: 0.9rem 0;
            appearance: none;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="%236b7280" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>') no-repeat right 0.5rem center;
            background-size: 18px;
        }

        .input-group input::placeholder {
            color: #94a3b8;
            font-weight: 400;
        }

        /* Register button */
        .register-button {
            width: 100%;
            background: linear-gradient(105deg, #4f46e5, #7c3aed);
            border: none;
            padding: 1rem 1rem;
            font-size: 1rem;
            font-weight: 700;
            font-family: inherit;
            color: white;
            border-radius: 40px;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-top: 0.8rem;
            box-shadow: 0 10px 18px -8px rgba(79, 70, 229, 0.4);
        }

        .register-button:hover {
            background: linear-gradient(105deg, #4338ca, #6d28d9);
            transform: translateY(-2px);
            box-shadow: 0 16px 24px -10px rgba(79, 70, 229, 0.5);
        }

        .register-button:active {
            transform: translateY(1px);
        }

        /* Footer link section */
        .form-footer {
            text-align: center;
            margin-top: 1.8rem;
            padding-top: 1.2rem;
            border-top: 1px solid #eef2ff;
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            flex-wrap: wrap;
        }

        .footer-link {
            color: #4f46e5;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
            padding: 6px 12px;
            border-radius: 30px;
            background: #f1f5f9;
        }

        .footer-link:hover {
            background: #e0e7ff;
            color: #3730a3;
            transform: translateX(3px);
        }

        /* role hint */
        .role-hint {
            font-size: 0.7rem;
            color: #6c86a3;
            margin-top: 5px;
            margin-left: 12px;
        }

        /* responsive */
        @media (max-width: 520px) {
            .register-card {
                margin: 0 12px;
            }
            .register-form {
                padding: 1.5rem;
            }
            .card-header h2 {
                font-size: 1.6rem;
            }
            .input-group {
                padding: 0 1rem;
            }
        }
    </style>
</head>
<body>

<div class="register-card">
    <div class="card-header">
        <div class="header-icon">📝✨</div>
        <h2>Create Account</h2>
        <p>Register to access the secure system</p>
    </div>

    <?php if (!empty($message)): ?>
        <?php 
            $isSuccess = strpos($message, 'Successfully') !== false;
            $alertClass = $isSuccess ? 'alert-success' : 'alert-error';
        ?>
        <div class="alert-message <?php echo $alertClass; ?>">
            <span><?php echo $isSuccess ? '✅' : '⚠️'; ?></span>
            <span><?php echo htmlspecialchars($message); ?></span>
        </div>
    <?php endif; ?>

    <form method="POST" class="register-form">
        <div class="input-wrapper">
            <label class="input-label">
                <span>👤</span> USERNAME
            </label>
            <div class="input-group">
                <span class="input-icon">✨</span>
                <input type="text" name="username" placeholder="Choose a username" required autocomplete="username">
            </div>
        </div>

        <div class="input-wrapper">
            <label class="input-label">
                <span>🔒</span> PASSWORD
            </label>
            <div class="input-group">
                <span class="input-icon">🔑</span>
                <input type="password" name="password" placeholder="Create a strong password" required autocomplete="new-password">
            </div>
        </div>

        <div class="input-wrapper">
            <label class="input-label">
                <span>🎭</span> ROLE / ACCESS LEVEL
            </label>
            <div class="input-group">
                <span class="input-icon">⚙️</span>
                <select name="role" required>
                    <option value="Admin">👑 Admin</option>
                    <option value="Security Officer">🛡️ Security Officer</option>
                </select>
            </div>
            <div class="role-hint">* Determines dashboard permissions</div>
        </div>

        <button type="submit" name="register" class="register-button">
            <span>🚀</span> REGISTER NOW
        </button>

        <div class="form-footer">
            <a href="login.php" class="footer-link">
                🔐 Already have an account? <strong>Login</strong>
            </a>
        </div>
    </form>
</div>

</body>
</html>