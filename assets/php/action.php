<?php
require_once '../../utils.php';
require_once '../../mailer.php';
require_once 'client_functions.php';

// Create a Client instance
$action = new Client();



if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $requestBody = json_decode(file_get_contents('php://input'), true);

    switch ($requestBody['formType']) {
        case 'signup':
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
                // construct error messages then pass it along to the response
                $errorMessage = '';

                foreach ($errorMessages as $message) {
                    $errorMessage .= Utils::showMessage('error', $message);
                }

                $response = [
                    'success' => false,
                    'message' => $errorMessage
                ];
            } else {
                $email = isset($requestBody['email']) ? Utils::sanitizeInput($requestBody['email']) : '';

                $userExists = $action->user_exists($email);

                if ($userExists && $userExists['email'] === $email) {
                    $response = [
                        'success' => false,
                        'message' => 'A user with this email is already registered'
                    ];
                } else {
                    // Process signup logic
                    //

                }
            }
            break;
        case 'signin':
            break;
        default:
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
