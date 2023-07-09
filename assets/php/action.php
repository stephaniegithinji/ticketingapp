<?php
require_once '../../utils.php';
require_once '../../mailer.php';
require_once 'client_functions.php';

// Create a Client instance
$action = new Client();

if (isset($_POST["signup-btn"])) {

    $username = isset($_POST["username"]) && !empty($_POST["username"]) ? Utils::sanitizeInput(ucfirst($_POST["username"])) : Utils::redirect_with_message('../../index.php', 'error', 'Username cannot be blank!');
    $email = isset($_POST["email"]) && !empty($_POST["email"]) ? strtolower(Utils::sanitizeInput($_POST["email"])) : Utils::redirect_with_message('../../index.php', 'error', 'Email cannot be blank!');
    $phone = isset($_POST["contact"]) && !empty($_POST["contact"]) ? Utils::sanitizeInput($_POST["contact"]) : Utils::redirect_with_message('../../index.php', 'error', 'Contact number cannot be blank!');
    $pass = isset($_POST["password"]) && !empty($_POST["password"]) ? Utils::sanitizeInput($_POST["password"]) : Utils::redirect_with_message('../../index.php', 'error', 'Password cannot be blank!');
    $hpass = password_hash($pass, PASSWORD_DEFAULT);

    $userExists = $action->user_exists($email);

    if ($userExists && $userExists['email'] === $email) {

        Utils::redirect_with_message('../../index.php', 'error', 'A user with this email is already registered');
        return;
    }
}


if (isset($_POST["login-btn"])) {
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
