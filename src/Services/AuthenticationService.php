<?php

declare(strict_types=1);

namespace Dhiraj\PhpFileManager\Services;

use Dhiraj\PhpFileManager\Config\Configuration;

/**
 * Authentication service for file manager
 *
 * This service handles user authentication, session management,
 * and access control for the file manager application.
 *
 * @package Dhiraj\PhpFileManager\Services
 * @author Dhiraj Dhiman <dhiraj@example.com>
 * @version 1.0.0
 * @since 1.0.0
 */
class AuthenticationService
{
    /**
     * Configuration instance
     */
    private Configuration $config;

    /**
     * Session key for authentication status
     */
    private const SESSION_KEY = 'authenticated';

    /**
     * Session key for last activity
     */
    private const LAST_ACTIVITY_KEY = 'last_activity';

    /**
     * Session timeout in seconds (30 minutes)
     */
    private const SESSION_TIMEOUT = 1800;

    /**
     * AuthenticationService constructor
     *
     * @param Configuration $config Configuration instance
     */
    public function __construct(Configuration $config)
    {
        $this->config = $config;
    }

    /**
     * Check if user is authenticated
     *
     * @return bool True if user is authenticated
     */
    public function isAuthenticated(): bool
    {
        // Check if session exists and is valid
        if (!isset($_SESSION[self::SESSION_KEY]) || $_SESSION[self::SESSION_KEY] !== true) {
            return false;
        }

        // Check session timeout
        if (isset($_SESSION[self::LAST_ACTIVITY_KEY])) {
            $lastActivity = $_SESSION[self::LAST_ACTIVITY_KEY];
            if ((time() - $lastActivity) > self::SESSION_TIMEOUT) {
                $this->logout();
                return false;
            }
        }

        // Update last activity
        $_SESSION[self::LAST_ACTIVITY_KEY] = time();

        return true;
    }

    /**
     * Attempt to log in with provided password
     *
     * @param string $password Password to verify
     * @return bool True if login successful
     */
    public function attemptLogin(string $password): bool
    {
        if (empty($password)) {
            return false;
        }

        // Verify password
        if (!$this->verifyPassword($password)) {
            $this->logFailedLogin();
            return false;
        }

        // Set authentication session
        $_SESSION[self::SESSION_KEY] = true;
        $_SESSION[self::LAST_ACTIVITY_KEY] = time();

        $this->logSuccessfulLogin();
        return true;
    }

    /**
     * Logout user and destroy session
     *
     * @return void
     */
    public function logout(): void
    {
        // Clear authentication session variables
        unset($_SESSION[self::SESSION_KEY]);
        unset($_SESSION[self::LAST_ACTIVITY_KEY]);

        // Regenerate session ID for security
        session_regenerate_id(true);

        $this->logLogout();
    }

    /**
     * Verify provided password against configured password
     *
     * @param string $password Password to verify
     * @return bool True if password matches
     */
    private function verifyPassword(string $password): bool
    {
        $expectedPassword = $this->config->getPassword();

        // Use hash_equals to prevent timing attacks
        return hash_equals($expectedPassword, $password);
    }

    /**
     * Get session timeout in seconds
     *
     * @return int Session timeout
     */
    public function getSessionTimeout(): int
    {
        return self::SESSION_TIMEOUT;
    }

    /**
     * Get remaining session time
     *
     * @return int Remaining time in seconds, 0 if not authenticated
     */
    public function getRemainingSessionTime(): int
    {
        if (!$this->isAuthenticated() || !isset($_SESSION[self::LAST_ACTIVITY_KEY])) {
            return 0;
        }

        $elapsed = time() - $_SESSION[self::LAST_ACTIVITY_KEY];
        $remaining = self::SESSION_TIMEOUT - $elapsed;

        return max(0, $remaining);
    }

    /**
     * Refresh session timeout
     *
     * @return void
     */
    public function refreshSession(): void
    {
        if ($this->isAuthenticated()) {
            $_SESSION[self::LAST_ACTIVITY_KEY] = time();
        }
    }

    /**
     * Log successful login attempt
     *
     * @return void
     */
    private function logSuccessfulLogin(): void
    {
        $this->logAuthEvent('successful_login');
    }

    /**
     * Log failed login attempt
     *
     * @return void
     */
    private function logFailedLogin(): void
    {
        $this->logAuthEvent('failed_login');
    }

    /**
     * Log logout event
     *
     * @return void
     */
    private function logLogout(): void
    {
        $this->logAuthEvent('logout');
    }

    /**
     * Log authentication event
     *
     * @param string $event Event type
     * @return void
     */
    private function logAuthEvent(string $event): void
    {
        $logEntry = [
            'timestamp' => date('Y-m-d H:i:s'),
            'event' => $event,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
        ];

        error_log('FileManager Auth: ' . json_encode($logEntry));
    }

    /**
     * Generate login form HTML
     *
     * @param string $error Error message to display
     * @return string Login form HTML
     */
    public function generateLoginForm(string $error = ''): string
    {
        $errorHtml = $error ? '<div class="error">' . htmlspecialchars($error) . '</div>' : '';

        return '
        <!DOCTYPE html>
        <html>
        <head>
            <title>PHP File Manager - Login</title>
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <style>
                body { font-family: Arial, sans-serif; background: #f5f5f5; margin: 0; padding: 50px; }
                .login-form { max-width: 300px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
                input[type="password"] { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 4px; }
                input[type="submit"] { width: 100%; padding: 10px; background: #007cba; color: white; border: none; border-radius: 4px; cursor: pointer; }
                input[type="submit"]:hover { background: #005a87; }
                .error { color: #dc3545; margin: 10px 0; padding: 10px; background: #f8d7da; border-radius: 4px; }
            </style>
        </head>
        <body>
            <form method="post" class="login-form">
                <h2>File Manager Login</h2>
                ' . $errorHtml . '
                <input type="password" name="password" placeholder="Password" required>
                <input type="submit" value="Login">
                <p><small>Default password: admin123</small></p>
            </form>
        </body>
        </html>';
    }
}
