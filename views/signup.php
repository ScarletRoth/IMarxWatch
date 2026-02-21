<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - IMarxWatch</title>
    <link rel="stylesheet" href="/css/signup.css">
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

    <script>
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