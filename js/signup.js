const username = document.querySelector('[name="username"]');
const email = document.querySelector('[name="email"]');
const contact = document.querySelector('[name="contact"]');
const password = document.querySelector('[name="password"]');
const confirm_password = document.querySelector('[name="confirm_password"]');
const signUpForm = document.getElementById('signupForm');

const setError = (element, message) => {
  const inputField = element.parentElement;
  
  inputField.classList.add('error');
  inputField.classList.remove('success');
  const errorDisplay = inputField.querySelector('.error');

  errorDisplay.innerText = message;
};

const setSuccess = element => {
  const inputField = element.parentElement;
  const errorDisplay = inputField.querySelector('.error');

  errorDisplay.innerText = '';
  inputField.classList.add('success');
  inputField.classList.remove('error');
};

const isValidEmail = email => {
  const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return re.test(String(email).toLowerCase());
};

const validateInputs = () => {
  const usernameValue = username.value.trim();
  const emailValue = email.value.trim();
  const contactValue = contact.value.trim();
  const passwordValue = password.value.trim();
  const confirm_passwordValue = confirm_password.value.trim();
  let isValid = true;

  if (usernameValue === '') {
    setError(username, 'Username is required.');
    isValid = false;
  } else {
    setSuccess(username);
  }

  if (emailValue === '') {
    setError(email, 'Email is required');
    isValid = false;
  } else if (!isValidEmail(emailValue)) {
    setError(email, 'Provide a valid email address.');
    isValid = false;
  } else {
    setSuccess(email);
  }

  if (contactValue === '') {
    setError(contact, 'Contact is required');
    isValid = false;
  } else if (contactValue.length !== 10) {
    setError(contact, 'Contact must be exactly 10 characters.');
    isValid = false;
  } else {
    setSuccess(contact);
  }

  if (passwordValue === '') {
    setError(password, 'Password is required');
    isValid = false;
  } else if (passwordValue.length < 6) {
    setError(password, 'Password must be at least 6 characters.');
    isValid = false;
  } else {
    setSuccess(password);
  }

  if (confirm_passwordValue === '') {
    setError(confirm_password, 'Please confirm your password.');
    isValid = false;
  } else if (confirm_passwordValue !== passwordValue) {
    setError(confirm_password, "Passwords don't match!");
    isValid = false;
  } else {
    setSuccess(confirm_password);
  }

  return isValid;
};

signUpForm.addEventListener('submit', e => {
  e.preventDefault();
  if (validateInputs()) {
    // All inputs are valid, proceed with form submission
    signUpForm.submit();
  }
});
