<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SecureTrack | Incident Management System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Segoe UI', system-ui, -apple-system, 'Roboto', Helvetica, Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }

        /* Animated gradient background */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 20% 50%, rgba(255,255,255,0.1) 0%, transparent 50%);
            pointer-events: none;
        }

        /* Floating particles animation */
        .particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 0;
        }

        .particle {
            position: absolute;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 50%;
            animation: float 20s infinite ease-in-out;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0) translateX(0);
                opacity: 0.3;
            }
            25% {
                transform: translateY(-30px) translateX(20px);
                opacity: 0.6;
            }
            50% {
                transform: translateY(-60px) translateX(-10px);
                opacity: 0.4;
            }
            75% {
                transform: translateY(-30px) translateX(30px);
                opacity: 0.7;
            }
        }

        /* Main container */
        .home-container {
            position: relative;
            z-index: 2;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Navigation Bar */
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 1rem 2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .nav-container {
            max-width: 1300px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo-icon {
            font-size: 2rem;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
        }

        .logo-text h1 {
            font-size: 1.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, #667eea, #764ba2);
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
        }

        .logo-text p {
            font-size: 0.7rem;
            color: #6c86a3;
        }

        .nav-buttons {
            display: flex;
            gap: 1rem;
        }

        .nav-btn {
            padding: 0.6rem 1.5rem;
            border-radius: 40px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nav-btn-login {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .nav-btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        }

        .nav-btn-register {
            background: transparent;
            color: #667eea;
            border: 2px solid #667eea;
        }

        .nav-btn-register:hover {
            background: #667eea;
            color: white;
            transform: translateY(-2px);
        }

        /* Hero Section */
        .hero-section {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem 2rem;
        }

        .hero-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
            align-items: center;
        }

        /* Hero Content */
        .hero-content {
            color: white;
        }

        .hero-badge {
            display: inline-block;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            padding: 0.4rem 1rem;
            border-radius: 40px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
        }

        .hero-content h1 {
            font-size: 3.5rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 1rem;
        }

        .hero-gradient {
            background: linear-gradient(120deg, #fff, #e0d4ff);
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
        }

        .hero-description {
            font-size: 1.1rem;
            line-height: 1.6;
            opacity: 0.9;
            margin-bottom: 2rem;
        }

        .hero-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .hero-btn {
            padding: 0.9rem 2rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 700;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-size: 1rem;
        }

        .hero-btn-primary {
            background: white;
            color: #667eea;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .hero-btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.25);
        }

        .hero-btn-secondary {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .hero-btn-secondary:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-3px);
        }

        /* Hero Stats */
        .hero-stats {
            display: flex;
            gap: 2rem;
            margin-top: 2.5rem;
        }

        .stat-item {
            display: flex;
            flex-direction: column;
        }

        .stat-number {
            font-size: 1.8rem;
            font-weight: 800;
        }

        .stat-label {
            font-size: 0.8rem;
            opacity: 0.8;
        }

        /* Hero Image / Illustration */
        .hero-image {
            text-align: center;
        }

        .feature-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 32px;
            padding: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation: floatCard 6s infinite ease-in-out;
        }

        @keyframes floatCard {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }

        .feature-icon {
            font-size: 3.5rem;
            margin-bottom: 1rem;
        }

        .feature-card h3 {
            color: white;
            margin-bottom: 0.5rem;
        }

        .feature-card p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
            line-height: 1.5;
        }

        /* Features Section */
        .features-section {
            background: white;
            padding: 4rem 2rem;
        }

        .section-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .section-title {
            text-align: center;
            margin-bottom: 3rem;
        }

        .section-title h2 {
            font-size: 2.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, #667eea, #764ba2);
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
            margin-bottom: 0.5rem;
        }

        .section-title p {
            color: #6c86a3;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
        }

        .feature-box {
            background: #f8fafc;
            padding: 2rem;
            border-radius: 24px;
            transition: all 0.3s;
            text-align: center;
        }

        .feature-box:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 35px -12px rgba(0, 0, 0, 0.15);
        }

        .feature-box-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .feature-box h3 {
            color: #1e293b;
            margin-bottom: 0.5rem;
        }

        .feature-box p {
            color: #5b6e8c;
            line-height: 1.5;
        }

        /* CTA Section */
        .cta-section {
            background: linear-gradient(135deg, #667eea, #764ba2);
            margin: 2rem;
            border-radius: 48px;
            padding: 3rem;
            text-align: center;
            color: white;
        }

        .cta-section h2 {
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        .cta-section p {
            margin-bottom: 2rem;
            opacity: 0.9;
        }

        .cta-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .cta-btn {
            padding: 0.8rem 2rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 700;
            transition: all 0.3s;
        }

        .cta-btn-primary {
            background: white;
            color: #667eea;
        }

        .cta-btn-primary:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .cta-btn-secondary {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .cta-btn-secondary:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: scale(1.05);
        }

        /* Footer */
        .footer {
            background: #0f172a;
            color: #94a3b8;
            padding: 2rem;
            text-align: center;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
        }

        .footer p {
            margin-top: 1rem;
            font-size: 0.8rem;
        }

        /* Responsive */
        @media (max-width: 968px) {
            .hero-container {
                grid-template-columns: 1fr;
                text-align: center;
            }

            .hero-content h1 {
                font-size: 2.5rem;
            }

            .hero-stats {
                justify-content: center;
            }

            .hero-buttons {
                justify-content: center;
            }

            .section-title h2 {
                font-size: 2rem;
            }

            .cta-section {
                margin: 1rem;
                padding: 2rem;
            }
        }

        @media (max-width: 480px) {
            .navbar {
                padding: 1rem;
            }

            .nav-container {
                flex-direction: column;
            }

            .hero-content h1 {
                font-size: 2rem;
            }

            .feature-card {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>

<div class="particles" id="particles"></div>

<div class="home-container">
    
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="logo">
                <div class="logo-icon">🛡️</div>
                <div class="logo-text">
                    <h1>SecureTrack</h1>
                    <p>Incident Management System</p>
                </div>
            </div>
            <div class="nav-buttons">
                <a href="login.php" class="nav-btn nav-btn-login">
                    🔐 Login
                </a>
                <a href="register.php" class="nav-btn nav-btn-register">
                    📝 Register
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-container">
            <div class="hero-content">
                <div class="hero-badge">
                    ⚡ Enterprise Security Solution
                </div>
                <h1>
                    Manage & Track<br>
                    <span class="hero-gradient">Security Incidents</span><br>
                    With Confidence
                </h1>
                <p class="hero-description">
                    SecureTrack provides a comprehensive platform for reporting, tracking, 
                    and managing security incidents. Streamline your security operations 
                    with our powerful incident management system.
                </p>
                <div class="hero-buttons">
                    <a href="login.php" class="hero-btn hero-btn-primary">
                        🔐 Login to Dashboard →
                    </a>
                    <a href="register.php" class="hero-btn hero-btn-secondary">
                        📝 Create New Account
                    </a>
                </div>
                <div class="hero-stats">
                    <div class="stat-item">
                        <span class="stat-number">24/7</span>
                        <span class="stat-label">Monitoring</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">100%</span>
                        <span class="stat-label">Secure</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">Real-time</span>
                        <span class="stat-label">Tracking</span>
                    </div>
                </div>
            </div>
            <div class="hero-image">
                <div class="feature-card">
                    <div class="feature-icon">🛡️🔒</div>
                    <h3>Enterprise Grade Security</h3>
                    <p>Role-based access control with Admin and Security Officer privileges</p>
                    <div style="margin-top: 1.5rem;">
                        <div style="display: inline-block; background: rgba(255,255,255,0.2); padding: 0.3rem 0.8rem; border-radius: 20px; font-size: 0.7rem;">✓ Incident Reporting</div>
                        <div style="display: inline-block; background: rgba(255,255,255,0.2); padding: 0.3rem 0.8rem; border-radius: 20px; font-size: 0.7rem; margin-left: 0.5rem;">✓ Search & Filter</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section">
        <div class="section-container">
            <div class="section-title">
                <h2>Powerful Features</h2>
                <p>Everything you need to manage security incidents effectively</p>
            </div>
            <div class="features-grid">
                <div class="feature-box">
                    <div class="feature-box-icon">📝</div>
                    <h3>Report Incidents</h3>
                    <p>Quickly log new security incidents with detailed descriptions, severity levels, and status tracking.</p>
                </div>
                <div class="feature-box">
                    <div class="feature-box-icon">📋</div>
                    <h3>View Records</h3>
                    <p>Access all incident reports in a organized table with sorting and filtering capabilities.</p>
                </div>
                <div class="feature-box">
                    <div class="feature-box-icon">🔍</div>
                    <h3>Search Incidents</h3>
                    <p>Find specific incidents instantly using unique incident IDs and other search criteria.</p>
                </div>
                <div class="feature-box">
                    <div class="feature-box-icon">👥</div>
                    <h3>Role Management</h3>
                    <p>Admin and Security Officer roles with appropriate access permissions.</p>
                </div>
                <div class="feature-box">
                    <div class="feature-box-icon">🔐</div>
                    <h3>Secure Authentication</h3>
                    <p>Password hashing and secure session management for user protection.</p>
                </div>
                <div class="feature-box">
                    <div class="feature-box-icon">📊</div>
                    <h3>Real-time Updates</h3>
                    <p>Track incident status changes from Open to Investigating to Closed.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <h2>Ready to Get Started?</h2>
        <p>Join SecureTrack today and take control of your security incident management</p>
        <div class="cta-buttons">
            <a href="login.php" class="cta-btn cta-btn-primary">
                🔐 Login Now
            </a>
            <a href="register.php" class="cta-btn cta-btn-secondary">
                📝 Register Account
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div style="display: flex; justify-content: center; gap: 2rem; flex-wrap: wrap;">
                <span>🛡️ SecureTrack IMS</span>
                <span>🔒 Enterprise Security</span>
                <span>⚡ Real-time Monitoring</span>
            </div>
            <p>&copy; <?php echo date('Y'); ?> SecureTrack Incident Management System. All rights reserved.</p>
        </div>
    </footer>
</div>

<script>
    // Create floating particles
    function createParticles() {
        const particlesContainer = document.getElementById('particles');
        const particleCount = 30;
        
        for (let i = 0; i < particleCount; i++) {
            const particle = document.createElement('div');
            particle.classList.add('particle');
            const size = Math.random() * 80 + 20;
            particle.style.width = size + 'px';
            particle.style.height = size + 'px';
            particle.style.left = Math.random() * 100 + '%';
            particle.style.top = Math.random() * 100 + '%';
            particle.style.animationDelay = Math.random() * 20 + 's';
            particle.style.animationDuration = (Math.random() * 15 + 10) + 's';
            particlesContainer.appendChild(particle);
        }
    }
    
    createParticles();
</script>

</body>
</html>