<?php
require_once '../../utils.php';
require_once '../../mailer.php';
require_once 'client_functions.php';

// Create a Client instance
$action = new Client();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Decode the request body JSON data into an associative array
    $requestBody = json_decode(file_get_contents('php://input'), true);

    // Switch statement based on the value of 'formType' in the request body
    switch ($requestBody['formType']) {
        case 'signup':
            // Define an array to hold the required fields and their corresponding names
            $fields = [
                'email' => 'Email',
                'username' => 'Username',
                'password' => 'Password',
                'contact' => 'Contact'
            ];

            // Defined an array to hold the error messages
            $errorMessages = [];

            // Check if any required field(s) is empty
            foreach ($fields as $fieldKey => $fieldName) {
                $fieldValue = isset($requestBody[$fieldKey]) ? Utils::sanitizeInput($requestBody[$fieldKey]) : '';
                if (empty($fieldValue)) {
                    $errorMessages[] = $fieldName . ' cannot be blank!';
                }
            }

            if (!empty($errorMessages)) {
                // Construct error messages by iterating through the array and appending them
                $errorMessage = '';
                foreach ($errorMessages as $message) {
                    $errorMessage .= Utils::showMessage('error', $message);
                }

                // Prepare the response with the error messages
                $response = [
                    'success' => false,
                    'message' => $errorMessage
                ];
            } else {
                // Sanitize the email field
                $email = isset($requestBody['email']) ? Utils::sanitizeInput($requestBody['email']) : '';

                // Check if a user with the provided email already exists
                $userExists = $action->user_exists($email);

                if ($userExists && $userExists['email'] === $email) {
                    // Construct response if a user with the email already exists
                    $response = [
                        'success' => false,
                        'message' => 'A user with this email is already registered'
                    ];
                } else {
                    // Process signup logic
                    // ...

                    // Example response for successful signup
                    $response = [
                        'success' => true,
                        'message' => 'Signup successful'
                    ];
                }
            }
            break;
        case 'signin':
            // Handle signin logic
            // ...
            break;
        default:
            // Handle other form types, if needed
            // ...
            break;
    }
}



if (isset($_POST["signin-btn"])) {
}

if (isset($_POST["contact-btn"])) {
    try {
        $email = isset($_POST["email_from"]) && !empty($_POST["email_from"]) ? Utils::sanitizeInput($_POST["email_from"]) : Utils::redirect_with_message('../../index.php',  'error', 'Email cannot be blank!');
        $sub = isset($_POST["subject"]) && !empty($_POST["subject"]) ? Utils::sanitizeInput($_POST["subject"]) : Utils::redirect_with_message('../../index.php',  'error', 'Subject cannot be blank!');
        $message = isset($_POST["message"]) && !empty($_POST["message"]) ? Utils::sanitizeInput($_POST["message"]) : Utils::redirect_with_message('../../index.php',  'error', 'Message cannot be blank!');

        $mailBody = "You have a new inquiry from" . $email . "<br><br>" . $message;

        if (Mailer::sendMail("adm1n.tickectok@gmail.com", $sub, $mailBody)) {
            Utils::redirect_with_message('../../index.php','success', 'Message sent! Thanks for contacting us.');
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
