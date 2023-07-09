<?php
require_once 'utils.php';
require_once 'mailer.php';
require_once 'connect/config.php';


class Client extends Db
{
    public function user_exists($email){
        $sql = "SELECT email FROM users WHERE email = :email";
		$stmt = $this->conn->prepare($sql);
		$stmt->execute(['email' => $email]);
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		return $result;
    }
}

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

        Utils::redirect_with_message('test.php', 'error', 'A user with this email is already registered');
        return;
    }
}


$message = $_SESSION['success'] ?? $_SESSION['error'] ?? null;

// `unset()` function destroys a variable. Once a variable is unset, it's no longer accessible
unset($_SESSION['success'], $_SESSION['error']);

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="assets/css/alerts.css">

</head>

<body>
    <?= $message ?>
    <div class="modal" id="signup">
        <div class="form-box">
            
            <h1>Sign Up</h1>
            <form id="signupForm" method="POST" action="#">
                <small></small>
                <div class="input-group">
                    <div class="input-field">
                        <input type="text" name="username" placeholder="Username">
                        <div class="error"></div>
                    </div>
                    <div class="input-field">
                        <input type="text" name="email" placeholder="Email">
                        <div class="error"></div>
                    </div>
                    <div class="input-field">
                        <input type="text" name="contact" placeholder="Contact">
                        <div class="error"></div>
                    </div>
                    <div class="input-field">
                        <input type="text" name="password" placeholder="Password">
                        <div class="error"></div>
                    </div>
                    <div class="input-field">
                        <input type="text" name="confirm_password" placeholder="Confirm Password">
                        <div class="error"></div>
                    </div>
                </div>
                <div class="btn-field">
                    <button type="button" style="background-color: #999999; margin-top: 120px" id="signupClose">Close</button>
                    <button type="submit" style="margin-top: 120px" name="signup-btn">Signup &rarr;</button>
                </div>
            </form>
            <br>
        </div>
    </div>

    <script>
        const nameEl = document.querySelector('[name="username"]');
        const emailEl = document.querySelector('[name="email"]');
        const contactEl = document.querySelector('[name="contact"]');
        const passwordEl = document.querySelector('[name="password"]');
        const confirmPasswordEl = document.querySelector('[name="confirm_password"]');
        const signUpBtn = document.querySelector('[name="signup-btn"]');
        const msg = document.querySelector('small');

        const isRequiredOnSignUp = value => Boolean(value);

        const showErrorOnSignUp = (element, message) => {
            const inputField = element.parentElement;
            const errorDisplay = inputField.querySelector('.error');

            inputField.classList.add('error');
            inputField.classList.remove('success');
            errorDisplay.innerText = message;
        };

        const showSuccessOnSignUp = element => {
            const inputField = element.parentElement;
            const errorDisplay = inputField.querySelector('.error');

            errorDisplay.innerText = '';
            inputField.classList.add('success');
            inputField.classList.remove('error');
        };

        const isValid = (input, pattern, message) => {
            const value = input.value.trim();
            if (!isRequiredOnSignUp(value)) {
                showErrorOnSignUp(input, `${input.name} cannot be blank.`);
                return false;
            } else if (!pattern.test(value)) {
                showErrorOnSignUp(input, message);
                return false;
            } else {
                showSuccessOnSignUp(input);
                return true;
            }
        };

        const checkName = () => isValid(nameEl, /^[a-zA-Z\s]+$/, 'Username should only contain letters and be between 4 and 22 characters in length.');
        const checkEmail = () => isValid(emailEl, /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/, 'Email is not valid.');
        const checkPassword = () => isValid(passwordEl, /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*]).{8,}$/, 'Password must have at least 8 characters that include at least 1 lowercase character, 1 uppercase character, 1 number, and 1 special character.');

        const checkPasswordMatch = () => {
            const password = passwordEl.value.trim();
            const confirmPassword = confirmPasswordEl.value.trim();
            if (!isRequiredOnSignUp(confirmPassword)) {
                showErrorOnSignUp(confirmPasswordEl, 'Confirm password cannot be blank.');
                return false;
            } else if (password !== confirmPassword) {
                showErrorOnSignUp(confirmPasswordEl, 'Passwords do not match.');
                return false;
            } else {
                showSuccessOnSignUp(confirmPasswordEl);
                return true;
            }
        };


        signUpBtn.addEventListener('click', e => {
            // e.preventDefault();

            let isValidForm = true;

            if (!checkName()) {
                isValidForm = false;
            }

            if (!checkEmail()) {
                isValidForm = false;
            }

            if (!checkPassword()) {
                isValidForm = false;
            }

            if (!checkPasswordMatch()) {
                isValidForm = false;
            }

            if (isValidForm) {
                // signUpForm.submit();
            } else {
                // Prevent form submission
                e.preventDefault();
            }
        });
    </script>
</body>

</html>