<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - IMarxWatch</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #0f1419 0%, #1a1f2e 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #e4e6eb;
        }

        .login-container {
            width: 100%;
            max-width: 440px;
            padding: 20px;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            margin-bottom: 20px;
        }

        h1 {
            font-size: 32px;
            font-weight: 600;
            margin-bottom: 8px;
            color: #ffffff;
        }

        .subtitle {
            color: #8e9199;
            font-size: 15px;
        }

        .login-card {
            background: #1e2530;
            border-radius: 12px;
            padding: 32px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
        }

        .demo-mode {
            background: #1a3a52;
            border: 1px solid #2563eb;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 24px;
        }

        .demo-mode-header {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #60a5fa;
            font-size: 14px;
            margin-bottom: 12px;
        }

        .demo-mode-header svg {
            width: 18px;
            height: 18px;
        }

        .demo-buttons {
            display: flex;
            gap: 10px;
        }

        .demo-btn {
            flex: 1;
            padding: 10px 16px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .demo-btn:hover {
            background: #1d4ed8;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 500;
            color: #d1d5db;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #6b7280;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px 14px 12px 42px;
            background: #2a3340;
            border: 1px solid #374151;
            border-radius: 8px;
            color: #e4e6eb;
            font-size: 15px;
            transition: border-color 0.2s;
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #f59e0b;
        }

        input[type="email"]::placeholder,
        input[type="password"]::placeholder {
            color: #6b7280;
        }

        .form-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: #f59e0b;
        }

        .remember-me label {
            margin: 0;
            font-size: 14px;
            cursor: pointer;
            color: #d1d5db;
        }

        .forgot-password {
            color: #f59e0b;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.2s;
        }

        .forgot-password:hover {
            color: #fbbf24;
        }

        .btn-signin {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .btn-signin:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 12px rgba(245, 158, 11, 0.3);
        }

        .btn-signin:active {
            transform: translateY(0);
        }

        .signup-link {
            text-align: center;
            margin-top: 24px;
            font-size: 14px;
            color: #8e9199;
        }

        .signup-link a {
            color: #f59e0b;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s;
        }

        .signup-link a:hover {
            color: #fbbf24;
        }

        .error-message {
            background: #7f1d1d;
            color: #fca5a5;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            border: 1px solid #991b1b;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="logo-container">
            <div class="logo">🎬</div>
            <h1>Welcome Back</h1>
            <p class="subtitle">Sign in to your IMarxWatch account</p>
        </div>

        <div class="login-card">
            <div class="demo-mode">
                <div class="demo-mode-header">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Demo Mode - Try these accounts:
                </div>
                <div class="demo-buttons">
                    <button class="demo-btn" onclick="fillDemo('user')">User Demo</button>
                    <button class="demo-btn" onclick="fillDemo('admin')">Admin Demo</button>
                </div>
            </div>

            <?php if (isset($_GET['error'])): ?>
                <div class="error-message">
                    <?php
                    $error = $_GET['error'];
                    if ($error === 'invalid') {
                        echo 'Invalid email or password';
                    } elseif ($error === 'empty') {
                        echo 'Please fill in all fields';
                    } else {
                        echo 'An error occurred. Please try again.';
                    }
                    ?>
                </div>
            <?php endif; ?>

            <form action="/login" method="POST">

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" width="20" height="20" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                            </path>
                        </svg>
                        <input type="email" id="email" name="email" placeholder="your@email.com" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" width="20" height="20" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                            </path>
                        </svg>
                        <input type="password" id="password" name="password" placeholder="••••••••" required>
                    </div>
                </div>

                <div class="form-footer">
                    <div class="remember-me">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Remember me</label>
                    </div>
                    <a href="#" class="forgot-password">Forgot password?</a>
                </div>

                <button type="submit" class="btn-signin">Sign In</button>
            </form>

            <div class="signup-link">
                Don't have an account? <a href="/signup">Sign up</a>
            </div>
        </div>
    </div>

    <script>
        function fillDemo(type) {
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');

            if (type === 'user') {
                emailInput.value = 'user@demo.com';
                passwordInput.value = 'demo123';
            } else if (type === 'admin') {
                emailInput.value = 'admin@demo.com';
                passwordInput.value = 'admin123';
            }
        }
    </script>
</body>

</html>