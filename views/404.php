<?php
SessionManager::init();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Not Found - IMarxWatch</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #0f1419 0%, #1a1f2e 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #e4e6eb;
        }

        .container {
            text-align: center;
        }

        h1 {
            font-size: 72px;
            margin: 0;
            color: #f59e0b;
        }

        p {
            font-size: 18px;
            margin: 20px 0;
            color: #8e9199;
        }

        a {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            margin-top: 20px;
            transition: transform 0.3s;
        }

        a:hover {
            transform: translateY(-2px);
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>404</h1>
        <p>Page Not Found</p>
        <p>The page you're looking for doesn't exist or has been moved.</p>
        <a href="/">Back to Home</a>
    </div>
</body>

</html>
