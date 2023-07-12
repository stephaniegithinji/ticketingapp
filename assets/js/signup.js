const signupForm = document.getElementById("signupForm");
const formTypeEl = document.querySelector('[name="formType"]');
const nameEl = document.querySelector('[name="username"]');
const emailEl = document.querySelector('[name="email"]');
const contactEl = document.querySelector('[name="contact"]');
const passwordEl = document.querySelector('[name="password"]');
const confirmPasswordEl = document.querySelector('[name="confirm_password"]');
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

const isValidOnSignUp = (input, pattern, message) => {
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

const checkName = () => isValidOnSignUp(nameEl, /^[a-zA-Z\s]+$/, 'Username should only contain letters and be between 4 and 22 characters in length.');
const checkEmail = () => isValidOnSignUp(emailEl, /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/, 'Email is not valid.');
const checkContact = () => isValidOnSignUp(contactEl, /^(\+254|0)?[071]\d{8}$/, "Contact number must begin with +254 or 0 then followed by 1 or 7, and other 7 digits");
const checkPassword = () => isValidOnSignUp(passwordEl, /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*]).{8,}$/, 'Password must have at least 8 characters that include at least 1 lowercase character, 1 uppercase character, 1 number, and 1 special character.');

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

const displayErrorMessage = (message) => {
  msg.innerHTML = `
    <div class="alert error">
      <p class="text">
        <strong>${message}</strong>
      </p>
    </div>`;
};


const clearMessages = () => {
  msg.innerHTML = '';
};

signupForm.addEventListener('submit', async (e) => {
  e.preventDefault(); // Prevent form submission

  clearMessages(); // Clear previous messages

  let isFormValidOnSignUp = true;

  if (!checkName()) {
    isFormValidOnSignUp = false;
  }

  if (!checkEmail()) {
    isFormValidOnSignUp = false;
  }

  if (!checkContact()) {
    isFormValidOnSignUp = false;
  }

  if (!checkPassword()) {
    isFormValidOnSignUp = false;
  }

  if (!checkPasswordMatch()) {
    isFormValidOnSignUp = false;
  }

  if (isFormValidOnSignUp) {
    const formType = formTypeEl.value;
    const username = nameEl.value;
    const email = emailEl.value;
    const contact = contactEl.value;
    const password = passwordEl.value;

    // Construct request body object
    const requestBody = {
      formType,
      username,
      email,
      contact,
      password
    };

    try {
      const response = await fetch('process.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(requestBody)
      });

      if (response.ok) {
        const responseData = await response.json();

          displayErrorMessage(responseData.message);

      } else {
        displayErrorMessage('An error occurred during the network call.');
      }
    } catch (error) {
      displayErrorMessage('An error occurred during the network call.');
    }
  }
});