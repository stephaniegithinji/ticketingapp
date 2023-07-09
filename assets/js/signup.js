
const nameEl = document.querySelector('[name="username"]');
const emailEl = document.querySelector('[name="email"]');
const contactEl = document.querySelector('[name="contact"]');
const passwordEl = document.querySelector('[name="password"]');
const confirmPasswordEl = document.querySelector('[name="confirm_password"]');
const signUpBtn = document.querySelector('[name="signup-btn"]');

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
  } else {
    // Prevent form submission
    e.preventDefault();
  }
});
