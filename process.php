<?php
require_once 'utils.php';

class Db
{
    private $dsn;
    private $dbuser;
    private $dbpass;

    public $conn;

    public function __construct()
    {

        // https://www.phptutorial.net/php-pdo
        $this->dsn = "mysql:host=localhost;dbname=ticketingapp";
        $this->dbuser = "root";
        $this->dbpass = 'tiger';
        try {
            $this->conn = new PDO($this->dsn, $this->dbuser, $this->dbpass);
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
}

class Client extends Db
{
    /**
     * Check if a user with the specified email  exists in the users table
     *
     * @param string $email The service number of the user to check
     * @return int The number of row(s) in the users table that match the given email
     */
    public function user_exists($email)
    {
        $sql = "SELECT email FROM users WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['email' => $email]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
    /**
     * Registers a new user in the database.
     * 
     * @param string $name The name of the user
     * @param string $email The email of the user
     * @param string $password The password of the user
     * @return bool Returns true on successful registration, false otherwise
     */
    public function createUserAccount($username, $email, $contact, $password)
    {
        $sql = "INSERT INTO users(username, email, contact, password)VALUES(:name, :email, :phone, :pass)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['uname' => $username, 'email' => $email, 'phone' => $contact, 'pass' => $password]);
        return true;
    }

    /**
     * @param string $email A users's email
     * @return array
     * @desc Returns username and password records from db based on the method parameters
     */

    public function loginIntoAccount($email)
    {
        $sql = "SELECT username, password, is_admin FROM users WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['email' => $email]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
}

// Create a Client instance
$action = new Client();

// Decode the request body JSON data into an associative array
$requestBody = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $requestBody['formType'] == 'signup') {

    $response = null;
    try {
        // Sanitize the inputs
        $name = isset($requestBody['username']) ? Utils::sanitizeInput($requestBody['username']) : '';
        $email = isset($requestBody['email']) ? Utils::sanitizeInput($requestBody['email']) : '';
        $contact = isset($requestBody['contact']) ? Utils::sanitizeInput($requestBody['contact']) : '';
        $password = isset($requestBody['password']) ? Utils::sanitizeInput($requestBody['password']) : '';
        $hpass = password_hash($password, PASSWORD_DEFAULT);

        // Defined an array to hold the error messages
        $errorMessages = [];

        if (empty($name)) {
            $errorMessages[] = 'Username cannot be blank!';
        }
        if (empty($email)) {
            $errorMessages[] = 'Email cannot be blank!';
        }
        if (empty($password)) {
            $errorMessages[] = 'Password cannot be blank!';
        }
        if (empty($contact)) {
            $errorMessages[] = 'Contact cannot be blank!';
        }

        if (!empty($errorMessages)) {
            // Construct error messages by iterating through the array and appending them
            $errorMessage = '';
            foreach ($errorMessages as $message) {
                $errorMessage .= $message;
            }

            // Prepare the response with the error messages
            $response = [
                'success' => false,
                'message' => $errorMessage
            ];
        } else {
            // Check if a user with the provided email already exists
            $userExists = $action->user_exists($email);

            if ($userExists && $userExists['email'] === $email) {
                // Construct response if a user with the email already exists
                $response = [
                    'success' => false,
                    'message' => 'A user with this email is already registered'
                ];
            } else {
                // Create an account for a user
                if ($action->createUserAccount($name, $email, $contact, $hpass)) {
                    // Construct success response
                    $response = [
                        'success' => true,
                        'message' => 'Account created successfully. Please login'
                    ];
                } else {
                    // Construct response if account creation fails
                    $response = [
                        'success' => false,
                        'message' => 'Failed to create account. Please try again.'
                    ];
                }
            }
        }
    } catch (Exception $e) {
        $response = [
            'success' => false,
            'message' => 'Oops... Some error occurred: ' . $e->getMessage()
        ];
    }
    echo json_encode($response);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && $requestBody['formType'] === 'signin') {
    $response = null;

    try {

        $email = isset($requestBody['email']) ? Utils::sanitizeInput($requestBody['email']) : '';
        $password = isset($requestBody['password']) ? Utils::sanitizeInput($requestBody['password']) : '';

        $errorMessages = [];

        if (empty($email)) {
            $errorMessages[] = 'Email cannot be blank!';
        }

        if (empty($password)) {
            $errorMessages[] = 'Password cannot be blank!';
        }

        if (!empty($errorMessages)) {
            $errorMessage = '';
            foreach ($errorMessages as $message) {
                $errorMessage .= $message;
            }
            $response = [
                'success' => false,
                'message' => $errorMessage
            ];
        }
        // Call the [loginInAccount] method for a user
        $result = $action->loginIntoAccount($email);

        if (!empty($result)) {
            if (password_verify($password, $result['password'])) {
                // User provided correct password
                $isAdmin = ($result['is_admin'] == 1);
                $_SESSION['admin'] = $isAdmin ? $result['username'] : null;
                $_SESSION['adminEmail'] = $isAdmin ? $result['email'] : null;
                $_SESSION['client'] = !$isAdmin ? $result['username'] : null;
                $_SESSION['clientEmail'] = !$isAdmin ? $result['email'] : null;

                Utils::redirect_to($isAdmin ? '../../interfaces/admin.php' : '../../interfaces/events.php');
            } else {
                // User provided incorrect password
                $response = [
                    'success' => false,
                    'message' => 'Invalid email or password.'
                ];
            }
        } else {
            // User not found
            $response = [
                'success' => false,
                'message' => 'User not found.'
            ];
        }
    } catch (Exception $e) {
        $response = [
            'success' => false,
            'message' => 'Oops... Some error occurred: ' . $e->getMessage()
        ];
        return;
    }
    echo json_encode($response);
}
