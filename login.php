<?php
session_start();

require 'config.php';

$message = "";

if (isset($_POST['login'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare(
        "SELECT password
         FROM users
         WHERE username=?"
    );

    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {

        if (password_verify($password, $row['password'])) {

            $_SESSION['username'] = $username;

            header("Location: dashboard.php");
            exit();
        }
    }

    $message = "Invalid Login";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Login | Access Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', 'Inter', system-ui, -apple-system, 'Roboto', Helvetica, Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }

        /* Animated background shapes */
        body::before {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            top: -100px;
            left: -100px;
            animation: float 20s infinite ease-in-out;
        }

        body::after {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
            bottom: -150px;
            right: -150px;
            animation: float 25s infinite reverse;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            33% { transform: translate(30px, -30px) rotate(120deg); }
            66% { transform: translate(-20px, 20px) rotate(240deg); }
        }

        /* Main card container */
        .login-card {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 32px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35), 0 8px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 460px;
            overflow: hidden;
            backdrop-filter: blur(2px);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            z-index: 1;
        }

        .login-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 35px 60px -15px rgba(0, 0, 0, 0.4);
        }

        /* Header section with gradient */
        .card-header {
            background: linear-gradient(135deg, #1e2a3e 0%, #0f172a 100%);
            padding: 2rem 2rem 1.8rem;
            text-align: center;
            position: relative;
        }

        .card-header h2 {
            font-size: 2.2rem;
            font-weight: 700;
            letter-spacing: -0.5px;
            background: linear-gradient(120deg, #fff, #c7d2fe);
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
            margin-bottom: 0.5rem;
        }

        .card-header p {
            color: #a5b4fc;
            font-size: 0.9rem;
            font-weight: 400;
            opacity: 0.9;
        }

        .header-icon {
            font-size: 3rem;
            margin-bottom: 0.5rem;
        }

        /* Message alert styling */
        .alert-message {
            margin: 1.5rem 1.8rem 0 1.8rem;
            padding: 0.9rem 1rem;
            background: linear-gradient(100deg, #fee2e2, #ffebeb);
            border-left: 4px solid #dc2626;
            border-radius: 14px;
            color: #991b1b;
            font-weight: 600;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
            animation: shake 0.4s ease;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-6px); }
            75% { transform: translateX(6px); }
        }

        .alert-message span {
            background: #dc2626;
            color: white;
            border-radius: 30px;
            width: 24px;
            height: 24px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }

        .alert-message:empty {
            display: none;
        }

        /* Form styling */
        .login-form {
            padding: 2rem 2rem 2.5rem;
        }

        .input-wrapper {
            margin-bottom: 1.6rem;
        }

        .input-label {
            display: block;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #334155;
            margin-bottom: 0.6rem;
        }

        .input-group {
            display: flex;
            align-items: center;
            background: #f1f5f9;
            border: 1.5px solid #e2e8f0;
            border-radius: 20px;
            transition: all 0.25s;
            padding: 0 1.2rem;
        }

        .input-group:focus-within {
            border-color: #6366f1;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15);
        }

        .input-icon {
            font-size: 1.2rem;
            color: #6c86a3;
            margin-right: 0.8rem;
            font-weight: 400;
        }

        .input-group input {
            width: 100%;
            padding: 0.9rem 0;
            border: none;
            background: transparent;
            font-size: 1rem;
            font-weight: 500;
            color: #0f172a;
            outline: none;
            font-family: inherit;
        }

        .input-group input::placeholder {
            color: #94a3b8;
            font-weight: 400;
            font-size: 0.9rem;
        }

        /* Login button */
        .login-button {
            width: 100%;
            background: linear-gradient(95deg, #1e2a3e, #2d3a4e);
            border: none;
            padding: 0.9rem 1rem;
            font-size: 1rem;
            font-weight: 700;
            font-family: inherit;
            color: white;
            border-radius: 40px;
            cursor: pointer;
            transition: all 0.25s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 1rem;
            box-shadow: 0 8px 14px -6px rgba(0, 0, 0, 0.2);
        }

        .login-button:hover {
            background: linear-gradient(95deg, #0f172a, #1e293b);
            transform: scale(1.01);
            box-shadow: 0 12px 22px -10px rgba(0, 0, 0, 0.3);
        }

        .login-button:active {
            transform: scale(0.98);
        }

        /* footer info */
        .form-footer {
            text-align: center;
            margin-top: 1.8rem;
            font-size: 0.75rem;
            color: #5b6e8c;
            border-top: 1px solid #eef2ff;
            padding-top: 1.2rem;
        }

        .form-footer a {
            color: #4f46e5;
            text-decoration: none;
            font-weight: 600;
        }

        .form-footer a:hover {
            text-decoration: underline;
        }

        /* responsive */
        @media (max-width: 480px) {
            .login-card {
                margin: 0 10px;
            }
            .login-form {
                padding: 1.5rem;
            }
            .card-header h2 {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="card-header">
        <div class="header-icon">🔐</div>
        <h2>Welcome Back</h2>
        <p>Sign in to access your dashboard</p>
    </div>

    <?php if (!empty($message)): ?>
        <div class="alert-message">
            <span>!</span> <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="login-form">
        <div class="input-wrapper">
            <label class="input-label">👤 USERNAME</label>
            <div class="input-group">
                <span class="input-icon">👤</span>
                <input type="text" name="username" placeholder="Enter your username" required autocomplete="username">
            </div>
        </div>

        <div class="input-wrapper">
            <label class="input-label">🔒 PASSWORD</label>
            <div class="input-group">
                <span class="input-icon">🔑</span>
                <input type="password" name="password" placeholder="Enter your password" required autocomplete="current-password">
            </div>
        </div>

        <button type="submit" name="login" class="login-button">
            <span>→</span> LOGIN TO DASHBOARD
        </button>

        <div class="form-footer">
            <span>🔒 Secure authentication system</span>
        </div>
    </form>
</div>

</body>
</html>