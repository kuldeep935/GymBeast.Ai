<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Personalized AI Fitness Plan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #3b82f6;
            --accent-color: #10b981;
            --text-color: #1e293b;
            --background-color:rgb(0, 186, 242);
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--background-color);
            margin: 0;
            padding: 20px;
            color: var(--text-color);
        }

        .container {
            max-width: 960px;
            margin: auto;
            background-color: #ffffff;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .section-title {
            color: var(--primary-color);
            font-size: 28px;
            margin-top: 30px;
            text-align: center;
        }

        .plan-cards {
            display: flex;
            flex-direction: column;
            gap: 30px;
            margin-top: 20px;
        }

        .plan-section {
            background: #f1f8f9;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.1);
        }

        .plan-heading {
            font-size: 22px;
            color: var(--text-color);
            margin-bottom: 20px;
            border-bottom: 2px solid #b2dfdb;
            padding-bottom: 8px;
        }

        .plan-card {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 15px 20px;
            margin: 10px 0;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 16px;
        }

        .plan-card strong {
            color: var(--primary-color);
            font-weight: 600;
        }

        .plan-card span {
            color: #333;
        }

        .plan-note {
            margin: 10px 0;
            font-style: italic;
            color: #555;
        }

        .error {
            color: #d32f2f;
            background: #ffebee;
            padding: 15px;
            border-left: 5px solid #c62828;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        #loadingSpinner {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255,255,255,0.85);
            z-index: 9999;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .spinner {
            border: 8px solid #e0e0e0;
            border-top: 8px solid var(--primary-color);
            border-radius: 50%;
            width: 70px;
            height: 70px;
            animation: spin 1s linear infinite;
            margin-bottom: 20px;
        }

        .loading-text {
            font-size: 20px;
            color: var(--primary-color);
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        @media (max-width: 600px) {
            .plan-card {
                flex-direction: column;
                align-items: flex-start;
                gap: 5px;
            }

            .section-title {
                font-size: 22px;
            }

            .plan-heading {
                font-size: 18px;
            }
        }
    </style>
</head>
<body>
<div id="loadingSpinner">
    <div class="spinner"></div>
    <div class="loading-text">⏳ Generating your personalized plan... Get ready to transform! 💪</div>
</div>

<div class="container">
<?php

$apiKey = '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';

$age = $_POST['age'] ?? '';
$gender = $_POST['gender'] ?? '';
$height = $_POST['height'] ?? '';
$weight = $_POST['weight'] ?? '';
$goal = $_POST['goal'] ?? '';
$conditions = $_POST['conditions'] ?? '';
$diet = $_POST['diet'] ?? '';

if (empty($age) || empty($gender) || empty($height) || empty($weight) || empty($goal)) {
    echo "<div class='error'><strong>Error:</strong> Missing required fields.</div>";
    exit;
}

$messages = [
    ["role" => "system", "content" => "You are a health and fitness coach."],
    ["role" => "user", "content" => "Create a personalized workout and diet plan for a $age-year-old $gender who is $height cm tall, weighs $weight kg, wants to achieve the goal: $goal. Diet preference: $diet. Health conditions: $conditions."]
];

echo '<script>document.getElementById("loadingSpinner").style.display = "flex";</script>';
@flush();
@ob_flush();

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://openrouter.ai/api/v1/chat/completions');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $apiKey,
    'HTTP-Referer: https://yourwebsite.com',
    'X-Title: AI Fitness Plan'
]);

curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    'model' => 'openai/gpt-3.5-turbo', // ✅ Changed to ChatGPT 3.5
    'messages' => $messages,
    'temperature' => 0.7,
    'max_tokens' => 800
]));

$response = curl_exec($ch);
echo '<script>document.getElementById("loadingSpinner").style.display = "none";</script>';

if (curl_errno($ch)) {
    echo "<div class='error'><strong>cURL Error:</strong> " . curl_error($ch) . "</div>";
    curl_close($ch);
    exit;
}
curl_close($ch);

$result = json_decode($response, true);

if (isset($result['choices'][0]['message']['content'])) {
    $output = $result['choices'][0]['message']['content'];
    echo '<h2 class="section-title">🎯 Your Personalized Health Plan</h2>';
    $formatted = nl2br(htmlspecialchars($output));
    $lines = explode("<br />", $formatted);

    echo '<div class="plan-cards">';
    $currentSection = '';

    foreach ($lines as $line) {
        if (stripos($line, 'Workout Plan') !== false || stripos($line, 'Diet Plan') !== false || stripos($line, 'Tips') !== false) {
            if ($currentSection) echo '</div>';
            $currentSection = $line;
            echo '<div class="plan-section">';
            echo "<h3 class='plan-heading'>📋 $currentSection</h3>";
        } elseif (stripos($line, ':') !== false) {
            $key = trim(strstr($line, ':', true));
            $value = trim(substr(strstr($line, ':'), 1));
            echo "<div class='plan-card'><strong>$key:</strong><span>$value</span></div>";
        } else {
            echo "<div class='plan-note'>$line</div>";
        }
    }
    if ($currentSection) echo '</div>';
    echo '</div>';

    echo '<div style="text-align:center; margin-top: 30px;">
            <button onclick="window.print()" style="background:#00796b; color:white; border:none; padding:12px 20px; font-size:16px; border-radius:8px; cursor:pointer;">
                📄 Download as PDF
            </button>
          </div>';

} elseif (isset($result['error']['message'])) {
    echo "<div class='error'><strong>API Error:</strong> " . htmlspecialchars($result['error']['message']) . "</div>";
} else {
    echo "<div class='error'><strong>Unexpected Error:</strong><pre>";
    print_r($result);
    echo "</pre></div>";
}
?>


</div>
</body>
</html>