const signinForm = document.getElementById("signinForm");
        const signinFormTypeEl = document.querySelector('[name="formType-signin"]');
        const signinEmailEl = document.querySelector('[name="signin-email"]');
        const signinPasswordEl = document.querySelector('[name="signin-password"]');
        const msgSignin = document.querySelector('#signin small');

        const showErrorOnSignIn = (element, message) => {
            const inputField = element.parentElement;
            const errorDisplay = inputField.querySelector('.error');

            errorDisplay.innerText = message;
            inputField.classList.add('error');
            inputField.classList.remove('success');
        };

        const showSuccessOnSignIn = element => {
            const inputField = element.parentElement;
            const errorDisplay = inputField.querySelector('.error');

            errorDisplay.innerText = '';
            inputField.classList.add('success');
            inputField.classList.remove('error');
        };

        // Function to display an error message in the sign-in form
        const displayErrorMessageOnSignin = (message) => {
            msgSignin.innerHTML = `
        <div class="alert error">
            <p class="text">
                <strong>${message}</strong>
            </p>
        </div>`;
        };

        const displaySuccessMessageOnSignin = (message) => {
            msgSignin.innerHTML = `
        <div class="alert success">
            <p class="text">
                <strong>${message}</strong>
            </p>
        </div>`;
        };

        const isRequiredOnSignIn = value => Boolean(value);

        const isValidOnSignIn = (input, pattern, message) => {
            const value = input.value.trim();
            if (!isRequiredOnSignIn(value)) {
                showErrorOnSignIn(input, `${input.name} cannot be blank.`);
                return false;
            } else if (!pattern.test(value)) {
                showErrorOnSignIn(input, message);
                return false;
            } else {
                showSuccessOnSignIn(input);
                return true;
            }
        };

        // Function to clear error/success messages in the sign-in form
        const clearMessagesOnSignin = () => {
            msgSignin.innerHTML = '';
        };

        // Function to validate the sign-in form inputs
        const validateSigninForm = () => {
            // Validate each input field and return true if all inputs are valid
            const isEmailValid = isValidOnSignIn(signinEmailEl, /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/, 'Email is not valid.');
            const isPasswordValid = isValidOnSignIn(signinPasswordEl, /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*]).{8,}$/, 'Password must have at least 8 characters that include at least 1 lowercase character, 1 uppercase character, 1 number, and 1 special character.');

            return isEmailValid && isPasswordValid;
        };

        // Handle sign-in form submission
        signinForm.addEventListener('submit', async (e) => {
            e.preventDefault(); // Prevent form submission

            clearMessagesOnSignin(); // Clear previous messages

            if (validateSigninForm()) {
                const formType = signinFormTypeEl.value;
                const email = signinEmailEl.value;
                const password = signinPasswordEl.value;

                const requestBody = {
                    formType,
                    email,
                    password
                };

                try {
                    const response = await fetch('assets/php/action.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(requestBody)
                    });

                    if (response.ok) {
                        const responseData = await response.json();
                        if (responseData.success) {
                            switch (responseData.message) {
                                case 'client':
                                    // window.location.href('interfaces/events.php');
                                    window.location.href = 'http://127.0.0.1/ticketingapp/interfaces/events.php';
                                    break;
                                case 'admin':
                                    window.location.href = 'http://127.0.0.1/ticketingapp/interfaces/admin.php';
                                    break;
                                default:
                                    displayErrorMessageOnSignin(`case ${responseData.message} not implemented yet`);
                                    break;
                            }
                        } else {
                            displayErrorMessageOnSignin(responseData.message);
                        }

                    }
                } catch (error) {
                    displayErrorMessageOnSignin('An error occurred during the network call.');
                }
            }
        });