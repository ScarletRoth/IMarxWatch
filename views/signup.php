<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - IMarxWatch</title>
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
            padding: 40px 20px;
        }

        .signup-container {
            width: 100%;
            max-width: 440px;
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

        .signup-card {
            background: #1e2530;
            border-radius: 12px;
            padding: 32px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
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

        input[type="text"],
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

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #f59e0b;
        }

        input[type="text"]::placeholder,
        input[type="email"]::placeholder,
        input[type="password"]::placeholder {
            color: #6b7280;
        }

        .password-strength {
            margin-top: 8px;
            font-size: 12px;
        }

        .strength-bar {
            height: 4px;
            background: #374151;
            border-radius: 2px;
            overflow: hidden;
            margin-bottom: 4px;
        }

        .strength-bar-fill {
            height: 100%;
            width: 0%;
            transition: width 0.3s, background-color 0.3s;
        }

        .strength-bar-fill.weak {
            width: 33%;
            background: #ef4444;
        }

        .strength-bar-fill.medium {
            width: 66%;
            background: #f59e0b;
        }

        .strength-bar-fill.strong {
            width: 100%;
            background: #10b981;
        }

        .strength-text {
            color: #8e9199;
        }

        .terms-group {
            margin-bottom: 24px;
        }

        .terms-checkbox {
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: #f59e0b;
            margin-top: 2px;
            flex-shrink: 0;
        }

        .terms-checkbox label {
            margin: 0;
            font-size: 13px;
            cursor: pointer;
            color: #d1d5db;
            line-height: 1.5;
        }

        .terms-checkbox a {
            color: #f59e0b;
            text-decoration: none;
        }

        .terms-checkbox a:hover {
            text-decoration: underline;
        }

        .btn-signup {
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

        .btn-signup:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 12px rgba(245, 158, 11, 0.3);
        }

        .btn-signup:active {
            transform: translateY(0);
        }

        .btn-signup:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }

        .login-link {
            text-align: center;
            margin-top: 24px;
            font-size: 14px;
            color: #8e9199;
        }

        .login-link a {
            color: #f59e0b;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s;
        }

        .login-link a:hover {
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

        .success-message {
            background: #065f46;
            color: #6ee7b7;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            border: 1px solid #047857;
        }
    </style>
</head>

<body>
    <div class="signup-container">
        <div class="logo-container">
            <div class="logo">🎬</div>
            <h1>Create Account</h1>
            <p class="subtitle">Join IMarxWatch and start booking movies</p>
        </div>

        <div class="signup-card">
            <?php
            $error = $_GET['error'] ?? null;

            if ($error === 'email_exists'):
            ?>
                <div class="error-message">An account with this email already exists</div>
            <?php elseif ($error === 'invalid_email'): ?>
                <div class="error-message">Please enter a valid email address</div>
            <?php elseif ($error === 'weak_password'): ?>
                <div class="error-message">Password must be at least 8 characters long</div>
            <?php elseif ($error === 'password_mismatch'): ?>
                <div class="error-message">Passwords do not match</div>
            <?php elseif ($error === 'empty'): ?>
                <div class="error-message">Please fill in all fields</div>
            <?php elseif ($error === 'terms'): ?>
                <div class="error-message">You must agree to the terms and conditions</div>
            <?php elseif ($error): ?>
                <div class="error-message">An error occurred. Please try again.</div>
            <?php endif; ?>

            <form action="/signup" method="POST" id="signupForm">

                <div class="form-group">
                    <label for="name">Full Name</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" width="20" height="20" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <input type="text" id="name" name="name" placeholder="John Doe" required>
                    </div>
                </div>

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
                    <div class="password-strength">
                        <div class="strength-bar">
                            <div class="strength-bar-fill" id="strengthBar"></div>
                        </div>
                        <div class="strength-text" id="strengthText">Enter a password</div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" width="20" height="20" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <input type="password" id="confirm_password" name="confirm_password" placeholder="••••••••"
                            required>
                    </div>
                </div>

                <div class="terms-group">
                    <div class="terms-checkbox">
                        <input type="checkbox" id="terms" name="terms" required>
                        <label for="terms">
                            I agree to the <a href="#">Terms and Conditions</a> and <a href="#">Privacy Policy</a>
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn-signup">Create Account</button>
            </form>

            <div class="login-link">
                Already have an account? <a href="/login">Sign in</a>
            </div>
        </div>
    </div>

    <script>/login
        const passwordInput = document.getElementById('password');
        const strengthBar = document.getElementById('strengthBar');
        const strengthText = document.getElementById('strengthText');

        passwordInput.addEventListener('input', function() {
            const password = this.value;
            const strength = calculatePasswordStrength(password);

            strengthBar.className = 'strength-bar-fill';
            if (strength.score === 0) {
                strengthBar.classList.add('weak');
                strengthText.textContent = 'Weak password';
                strengthText.style.color = '#ef4444';
            } else if (strength.score === 1) {
                strengthBar.classList.add('medium');
                strengthText.textContent = 'Medium password';
                strengthText.style.color = '#f59e0b';
            } else {
                strengthBar.classList.add('strong');
                strengthText.textContent = 'Strong password';
                strengthText.style.color = '#10b981';
            }
        });

        function calculatePasswordStrength(password) {
            let score = 0;

            if (password.length >= 8) score++;
            if (/[a-z]/.test(password) && /[A-Z]/.test(password)) score++;
            if (/\d/.test(password)) score++;
            if (/[^a-zA-Z\d]/.test(password)) score++;

            return {
                score: Math.min(score / 2, 2)
            };
        }

        document.getElementById('signupForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const terms = document.getElementById('terms').checked;

            if (password.length < 8) {
                e.preventDefault();
                alert('Password must be at least 8 characters long');
                return;
            }

            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Passwords do not match!');
                return;
            }

            if (!terms) {
                e.preventDefault();
                alert('You must agree to the terms and conditions');
                return;
            }
        });
    </script>
</body>

</html>