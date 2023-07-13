<?php
require_once '../../utils.php';
require_once '../../mailer.php';
require_once 'client_functions.php';

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


if (isset($_POST["contact-btn"])) {
    try {
        $email = isset($_POST["email_from"]) && !empty($_POST["email_from"]) ? Utils::sanitizeInput($_POST["email_from"]) : Utils::redirect_with_message('../../index.php',  'error', 'Email cannot be blank!');
        $sub = isset($_POST["subject"]) && !empty($_POST["subject"]) ? Utils::sanitizeInput($_POST["subject"]) : Utils::redirect_with_message('../../index.php',  'error', 'Subject cannot be blank!');
        $message = isset($_POST["message"]) && !empty($_POST["message"]) ? Utils::sanitizeInput($_POST["message"]) : Utils::redirect_with_message('../../index.php',  'error', 'Message cannot be blank!');

        $mailBody = "You have a new inquiry from" . $email . "<br><br>" . $message;

        if (Mailer::sendMail("adm1n.tickectok@gmail.com", $sub, $mailBody)) {
            Utils::redirect_with_message('../../index.php', 'success', 'Message sent! Thanks for contacting us.');
            return;
        } else {
            Utils::redirect_with_message('../../index.php', 'error', 'Email not sent. An error was encountered.');
            return;
        }
    } catch (Exception $e) {
        // Handle exceptions by returning an error mailBody to user
        Utils::redirect_with_message('../../index.php', 'error', 'Opps...Some error occurred: ' . $e->getMessage());
    }
}
