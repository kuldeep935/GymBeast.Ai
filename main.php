<?php
session_start();
include 'db.php';

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']);
$userName = $isLoggedIn ? $_SESSION['user_name'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Transform your health with GymBeast - Premium workouts, nutrition plans, and expert coaching">
    <title>GymBeast.Ai - Transform Your Fitness Journey</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* ... existing styles ... */
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="logo">
            <img src="logo1.png" alt="GymBeast">
            <span>GymBeast.Ai</span>
        </div>
        <div class="menu">
            <a href="#home">Home</a>
            <a href="#features">Features</a>
            <a href="#programs">Programs</a>
            <a href="#nutrition">Nutrition</a>
            <a href="#contact">Contact</a>
        </div>
        <div class="auth-buttons">
            <?php if ($isLoggedIn): ?>
                <div class="user-menu">
                    <span>Welcome, <?php echo htmlspecialchars($userName); ?></span>
                    <a href="dashboard.php" class="auth-btn">Dashboard</a>
                    <a href="logout.php" class="auth-btn">Logout</a>
                </div>
            <?php else: ?>
                <a href="login.html" class="auth-btn">Login</a>
                <a href="register.html" class="auth-btn">Sign Up</a>
            <?php endif; ?>
        </div>
    </nav>

    <section class="hero" id="home">
        <div class="hero-content">
            <h1>Transform Your Health & Fitness</h1>
            <p>Join 50,000+ members achieving their fitness goals with personalized programs and expert coaching</p>
            <?php if ($isLoggedIn): ?>
                <a href="dashboard.php" class="cta-button">Go to Dashboard</a>
            <?php else: ?>
                <a href="login.html" class="cta-button">Start Your Journey</a>
            <?php endif; ?>
        </div>
    </section>

    <section class="features" id="features">
        <div class="feature-card">
            <i class="fas fa-dumbbell feature-icon"></i>
            <h3>AI-Powered Health Plans</h3>
            <p>Get personalized workout and nutrition plans tailored to your goals and needs.</p>
            <?php if ($isLoggedIn): ?>
                <a href="get-plan.php" class="feature-btn">Get Your Plan</a>
            <?php else: ?>
                <a href="login.html" class="feature-btn">Learn More</a>
            <?php endif; ?>
        </div>
        <div class="feature-card">
            <i class="fas fa-calendar-check feature-icon"></i>
            <h3>Gym Attendance Tracker</h3>
            <p>Track your gym attendance and get personalized suggestions when you miss workouts.</p>
            <?php if ($isLoggedIn): ?>
                <a href="gym_miss.php" class="feature-btn">Track Attendance</a>
            <?php else: ?>
                <a href="login.html" class="feature-btn">Learn More</a>
            <?php endif; ?>
        </div>
        <div class="feature-card">
            <i class="fas fa-chart-line feature-icon"></i>
            <h3>Progress Tracking</h3>
            <p>Monitor your fitness progress and get insights to improve your results.</p>
            <?php if ($isLoggedIn): ?>
                <a href="dashboard.php" class="feature-btn">View Progress</a>
            <?php else: ?>
                <a href="login.html" class="feature-btn">Learn More</a>
            <?php endif; ?>
        </div>
    </section>

    <footer class="footer">
        <div class="footer-section">
            <h4>GymBeast.Ai</h4>
            <p>Transform your life through fitness and proper nutrition</p>
        </div>
        <div class="footer-section">
            <h4>Quick Links</h4>
            <a href="#home">Home</a><br>
            <a href="#features">Features</a><br>
            <a href="#programs">Programs</a>
        </div>
        <div class="footer-section">
            <h4>Contact Us</h4>
            <p>Email: info@gymbeast.com</p>
            <div class="social-links">
                <a href="#"><i class="fab fa-facebook"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
            </div>
        </div>
    </footer>

    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', () => {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Smooth scroll for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>
</html> 