<?php
include 'auth_check.php';
include 'db.php';

$user_id = getCurrentUserId();
$user_name = getCurrentUserName();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gym Attendance Tracker | GymBeast.Ai</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #2A2A72;
            --secondary-color: #009FFD;
            --accent-color: #FFA400;
            --background-color: #f8f9fa;
        }

        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background: var(--background-color);
            min-height: 100vh;
        }

        .navbar {
            background: var(--primary-color);
            padding: 15px 5%;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-sizing: border-box;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logo img {
            height: 40px;
        }

        .logo span {
            color: white;
            font-size: 1.5rem;
            font-weight: bold;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-menu span {
            color: white;
        }

        .nav-btn {
            background: var(--accent-color);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            transition: 0.3s;
        }

        .nav-btn:hover {
            background: #ff8c00;
        }

        .container {
            max-width: 800px;
            margin: 100px auto 30px;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }

        h1 {
            color: var(--primary-color);
            text-align: center;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: var(--primary-color);
            font-weight: 500;
        }

        input[type="text"],
        select,
        textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s;
            box-sizing: border-box;
        }

        input[type="text"]:focus,
        select:focus,
        textarea:focus {
            border-color: var(--secondary-color);
            outline: none;
        }

        button {
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
            transition: 0.3s;
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        #suggestion {
            margin-top: 30px;
            padding: 20px;
            border-radius: 8px;
            background: #f8f9fa;
            display: none;
        }

        .loading {
            display: none;
            text-align: center;
            margin: 20px 0;
        }

        .loading i {
            font-size: 24px;
            color: var(--primary-color);
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @media (max-width: 768px) {
            .container {
                margin: 80px 10px 20px;
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="logo">
            <img src="logo1.png" alt="GymBeast">
            <span>GymBeast.Ai</span>
        </div>
        <div class="user-menu">
            <span>Welcome, <?php echo htmlspecialchars($user_name); ?></span>
            <a href="dashboard.php" class="nav-btn">Dashboard</a>
            <a href="logout.php" class="nav-btn">Logout</a>
        </div>
    </nav>

    <div class="container">
        <h1>Gym Attendance Tracker</h1>
        
        <form id="missedGymForm">
            <div class="form-group">
                <label for="reason">Reason for Missing Gym</label>
                <select id="reason" name="reason" required>
                    <option value="">Select a reason</option>
                    <option value="work">Work Commitments</option>
                    <option value="health">Health Issues</option>
                    <option value="travel">Traveling</option>
                    <option value="motivation">Lack of Motivation</option>
                    <option value="time">Time Management</option>
                    <option value="other">Other</option>
                </select>
            </div>

            <div class="form-group">
                <label for="details">Additional Details</label>
                <textarea id="details" name="details" rows="4" required></textarea>
            </div>

            <div class="form-group">
                <label for="frequency">How often do you usually go to the gym?</label>
                <select id="frequency" name="frequency" required>
                    <option value="">Select frequency</option>
                    <option value="daily">Daily</option>
                    <option value="3-4 times/week">3-4 times per week</option>
                    <option value="1-2 times/week">1-2 times per week</option>
                    <option value="irregular">Irregular</option>
                </select>
            </div>

            <div class="form-group">
                <label for="goal">Your Current Fitness Goal</label>
                <select id="goal" name="goal" required>
                    <option value="">Select goal</option>
                    <option value="weight_loss">Weight Loss</option>
                    <option value="muscle_gain">Muscle Gain</option>
                    <option value="endurance">Endurance</option>
                    <option value="overall_fitness">Overall Fitness</option>
                </select>
            </div>

            <button type="submit">Get AI Suggestions</button>
        </form>

        <div class="loading">
            <i class="fas fa-spinner"></i>
            <p>Generating personalized suggestions...</p>
        </div>

        <div id="suggestion"></div>
    </div>

    <script>
        document.getElementById('missedGymForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const loading = document.querySelector('.loading');
            const suggestionDiv = document.getElementById('suggestion');
            loading.style.display = 'block';
            suggestionDiv.style.display = 'none';

            const formData = {
                reason: document.getElementById('reason').value,
                details: document.getElementById('details').value,
                frequency: document.getElementById('frequency').value,
                goal: document.getElementById('goal').value
            };

            fetch('process.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                loading.style.display = 'none';
                suggestionDiv.style.display = 'block';
                suggestionDiv.innerHTML = `
                    <h3>AI Suggestions</h3>
                    <p>${data.suggestion}</p>
                `;
            })
            .catch(error => {
                loading.style.display = 'none';
                suggestionDiv.style.display = 'block';
                suggestionDiv.innerHTML = `
                    <p style="color: red;">Error: Could not generate suggestions. Please try again.</p>
                `;
                console.error('Error:', error);
            });
        });
    </script>
</body>
</html> 