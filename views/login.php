<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - IMarxWatch</title>
    <link rel="stylesheet" href="/css/login.css">
</head>

<body>
    <div class="login-container">
        <div class="logo-container">
            <div class="logo">🎬</div>
            <h1>Welcome Back</h1>
            <p class="subtitle">Sign in to your IMarxWatch account</p>
        </div>

        <div class="login-card">
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

</body>

</html>