const signinForm = document.getElementById('signinForm');
const signinEmail = document.querySelector('[name="signinemail"]');
const signinPassword = document.querySelector('[name="signinpassword"]');

const setError = (element, message) => {
  const inputField = element.parentElement;
  const errorDisplay = inputField.querySelector('.error');

  errorDisplay.innerText = message;
  inputField.classList.add('error');
  inputField.classList.remove('success');
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
  const sEmailValue = signinEmail.value.trim();
  const sPasswordValue = signinPassword.value.trim();

  if (sEmailValue === '') {
    setError(signinEmail, 'Email is required');
  } else if (!isValidEmail(sEmailValue)) {
    setError(signinEmail, 'Provide a valid email address.');
  } else {
    setSuccess(signinEmail);
  }

  if (sPasswordValue === '') {
    setError(signinPassword, 'Password is required');
  } else if (sPasswordValue.length < 6) {
    setError(signinPassword, 'Password must be at least 6 characters.');
  } else {
    setSuccess(signinPassword);
  }
};


signinForm.addEventListener('submit', e => {
    e.preventDefault();
    if (validateInputs()) {
        signinForm.submit();
    }
});