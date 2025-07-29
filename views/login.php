<!DOCTYPE html>
<html>
<head>
    <title>PHP File Manager - Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-container {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        .login-header {
            margin-bottom: 30px;
        }
        .login-header h1 {
            color: #333;
            margin: 0 0 10px 0;
            font-size: 28px;
        }
        .login-header p {
            color: #666;
            margin: 0;
            font-size: 16px;
        }
        .login-form {
            margin-top: 30px;
        }
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
        }
        .form-group input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
            transition: border-color 0.3s;
            box-sizing: border-box;
        }
        .form-group input:focus {
            outline: none;
            border-color: #667eea;
        }
        .login-button {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .login-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        .login-button:active {
            transform: translateY(0);
        }
        .error-message {
            background: #fee;
            color: #c33;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
            border: 1px solid #fcc;
        }
        .info-message {
            background: #f0f8ff;
            color: #0066cc;
            padding: 12px;
            border-radius: 6px;
            margin-top: 20px;
            border: 1px solid #cce7ff;
            font-size: 14px;
        }
        .footer {
            margin-top: 30px;
            color: #999;
            font-size: 14px;
        }
        @media (max-width: 480px) {
            .login-container {
                margin: 20px;
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>📁 File Manager</h1>
            <p>Please enter your password to continue</p>
        </div>

        <?php if (isset($_POST['password']) && !empty($_POST['password'])) : ?>
            <div class="error-message">
                ❌ Invalid password. Please try again.
            </div>
        <?php endif; ?>

        <form method="post" class="login-form">
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required autocomplete="current-password">
            </div>
            
            <button type="submit" class="login-button">
                🔐 Login
            </button>
        </form>

        <div class="info-message">
            <strong>Security Notice:</strong><br>
            This file manager requires authentication to access your files safely.
        </div>

        <div class="footer">
            <p>🚀 PHP File Manager v1.0</p>
        </div>
    </div>

    <script>
        // Focus password field on page load
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('password').focus();
        });

        // Handle form submission
        document.querySelector('.login-form').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            if (!password.trim()) {
                e.preventDefault();
                alert('Please enter a password');
                return false;
            }
        });
    </script>
</body>
</html>
