const signupForm = document.getElementById("signupForm");
const signupFormTypeEl = document.querySelector('[name="formType-signup"]');
const nameEl = document.querySelector('[name="username"]');
const emailEl = document.querySelector('[name="email"]');
const contactEl = document.querySelector('[name="contact"]');
const passwordEl = document.querySelector('[name="password"]');
const confirmPasswordEl = document.querySelector('[name="confirm_password"]');
const msgSignup = document.querySelector('#signup small');

const isRequiredOnSignUp = value => Boolean(value);

const isValidOnSignUp = (input, pattern, message) => {
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

// Function to display an error message in the sign-up form
const displayErrorMessageOnSignup = (message) => {
  msgSignup.innerHTML = `
        <div class="alert error">
            <p class="text">
                <strong>${message}</strong>
            </p>
        </div>`;
};

// Function to display a success message in the sign-up form
const displaySuccessMessageOnSignup = (message) => {
  msgSignup.innerHTML = `
        <div class="alert success">
            <p class="text">
                <strong>${message}</strong>
            </p>
        </div>`;
};

// Function to clear error/success messages in the sign-up form
const clearMessagesOnSignup = () => {
  msgSignup.innerHTML = '';
};

// Function to validate the sign-up form inputs
const validateSignupForm = () => {
  // Validate each input field and return true if all inputs are valid
  const isNameValid = isValidOnSignUp(nameEl, /^[a-zA-Z\s]+$/, 'Username should only contain letters and be between 4 and 22 characters in length.');
  const isEmailValid = isValidOnSignUp(emailEl, /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/, 'Email is not valid.');
  const isContactValid = isValidOnSignUp(contactEl, /^(\+254|0)?[071]\d{8}$/, "Contact number must begin with +254 or 0 then followed by 1 or 7, and other 7 digits");
  const isPasswordValid = isValidOnSignUp(passwordEl, /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*]).{8,}$/, 'Password must have at least 8 characters that include at least 1 lowercase character, 1 uppercase character, 1 number, and 1 special character.');
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
  const isConfirmPasswordValid = checkPasswordMatch();

  return isNameValid && isEmailValid && isContactValid && isPasswordValid && isConfirmPasswordValid;
};

// Handle sign-up form submission
signupForm.addEventListener('submit', async (e) => {
  e.preventDefault(); // Prevent form submission

  clearMessagesOnSignup(); // Clear previous messages

  if (validateSignupForm()) {
    const formType = signupFormTypeEl.value;
    const username = nameEl.value;
    const email = emailEl.value;
    const contact = contactEl.value;
    const password = passwordEl.value;

    const requestBody = {
      formType,
      username,
      email,
      contact,
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
          displaySuccessMessageOnSignup(responseData.message);
        } else {
          displayErrorMessageOnSignup(responseData.message);
        }
      } else {
        throw new Error('Network response was not OK.');
      }
    } catch (error) {
      displayErrorMessageOnSignup('Some error occurred. Please try again.');
    }
  }
});