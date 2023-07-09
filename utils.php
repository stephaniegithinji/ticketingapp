<?php

require_once 'session.php';

/**
 * Class Utils
 * It includes functions to check and sanitize inputs
 * Display error/success messages
 * handle CSRF protection
 * Shows the elapsed time in a human-readable format.
 *
 */


class Utils
{
    // static modifier in php is used to access properties of method
    // directly without instantiating it.
    /**
     * Check and sanitize input data and remove invalid characters
     * @param string $input_data The input data to sanitize.
     * @return string|null The sanitized input data.
     */
    public static function sanitizeInput(string $input_data): ?string
    {
        // Convert special characters to HTML entities to prevent XSS attacks
        $data = htmlspecialchars($input_data, ENT_QUOTES);

        return $data;
    }

    /**
     * Display an alert message with a given type and message.
     *
     * @param string $type The type of alert (e.g. success, warning, danger).
     * @param string $message The message to display in the alert.
     *
     * @return string The HTML code for the alert.
     */
    public static function showMessage(string $type, string $message): string
    {
        return '<div class="alert ' . $type . '">
                    <p class="text">
                        <strong>' . $message . '</strong>
                    </p>
                </div>';
    }

    /**
     * Display time elapsed in human-readable format
     * @param string|int $timestamp The timestamp to convert to human-readable format
     * @param string $timezone The timezone to use for the timestamp (default is UTC)
     * @return string The elapsed time in human-readable format
     */
    public static function timeInAgo(string $timestamp, string $timezone = 'UTC'): string
    {
        if($timestamp == "0000-00-00 00:00:00"){
            return 'No data';
        }
        $datetime1 = new DateTime($timestamp, new DateTimeZone($timezone));
        $datetime2 = new DateTime('now', new DateTimeZone($timezone));
        $interval = $datetime1->diff($datetime2);

        if ($interval->y > 0) {
            return ($interval->y == 1) ? 'a year ago' : $interval->y . ' years ago';
        } elseif ($interval->m > 0) {
            return ($interval->m == 1) ? 'a month ago' : $interval->m . ' months ago';
        } elseif ($interval->d > 0) {
            return ($interval->d == 1) ? 'a day ago' : $interval->d . ' days ago';
        } elseif ($interval->h > 0) {
            return ($interval->h == 1) ? 'an hour ago' : $interval->h . ' hours ago';
        } elseif ($interval->i > 0) {
            return ($interval->i == 1) ? 'a minute ago' : $interval->i . ' minutes ago';
        } elseif ($interval->s > 0) {
            return ($interval->s == 1) ? 'a second ago' : $interval->s . ' seconds ago';
        } else {
            return 'just now';
        }
    }


    /**
     *
     * Generates a CSRF token and stores it in the session
     * @return void
     */
    public static function generateCsrfToken(): void
    {
        // Start session if it's not already active
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        // Generate the token if it's not already set
        if (empty($_SESSION['token'])) {
            try {
                $_SESSION['token'] = bin2hex(random_bytes(32));
                // regenerates the session ID to prevent session fixation attacks, 
                // where an attacker hijacks a user's session by stealing their session ID.
                session_regenerate_id();
            } catch (Exception $e) {
                // Log the error or handle it as appropriate
                echo 'Error generating token: ' . $e->getMessage();
                exit();
            }
        }
    }

    /**
     *
     * Inserts the CSRF token as a hidden input field in a form
     *  @return void
     */
    public static function insertCsrfToken(): void
    {
        // Generate the CSRF token
        self::generateCsrfToken();

        // Insert the token as a hidden input field in the form
        echo '<input type="hidden" name="token" value="' . ($_SESSION['token'] ?? '') . '" />';
    }

    /**
     *
     * Verifies the CSRF token submitted with a form
     * @return bool
     */
    public static function verifyCsrfToken(): bool
    {
        // Generate the CSRF token
        self::generateCsrfToken();

        // Verify the token submitted with the form
        if (!empty($_POST['token']) && hash_equals($_SESSION['token'], $_POST['token'])) {
            return true;
        }

        return false;
    }

    /**
     * Takes a URL and redirects the user to that URL.
     *
     * @param string $url The URL to redirect to.
     * @return void
     */
    public static function redirect_to(string $url): void
    {
        // Use the header() function to send a Location header to the browser with the given URL.
        header('Location:' . $url);
        // Exit the script to prevent further execution.
        exit;
    }

    /**
     * Sets a flash message via the $_SESSION superglobal and redirects the user to the given URL
     *
     * @param string $url The URL to redirect to.
     * @param string $type The type of message (e.g. "success" or "error").
     * @param string $msg The message to display to the user.
     * @return void
     */
    public static function redirect_with_message(string $url, string $type, string $msg): void
    {
        // Store the message in the $_SESSION superglobal using the showMessage() function.
        $_SESSION[$type] = self::showMessage($type, $msg); 
        // Use the redirect_to() function to redirect the user to the given URL.
        self::redirect_to($url);
    }

    public static function redirect_and_destroy(string $url, string $type, string $msg): void
    {
        session_destroy();

        session_start();
        // Store the message in the $_SESSION superglobal using the showMessage() function.
        $_SESSION[$type] = self::showMessage($type, $msg);

        // Redirect the user to the given URL
        self::redirect_to($url);
    }
}
