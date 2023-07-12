const signinForm = document.getElementById("signinForm");
const signinformTypeEl = document.querySelector('[name="formType"]');
const signinEmailEl = document.querySelector('[name="signin-email"]');
const signinPasswordEl = document.querySelector('[name="signin-password"]');
const msg_signin = document.querySelector('small');

const isRequiredOnSignIn = value => Boolean(value);

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

const checkEmailOnSignIn = () => isValidOnSignIn(signinEmailEl, /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/, 'Email is not valid.');
const checkPasswordOnSignIn = () => isValidOnSignIn(signinPasswordEl, /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*]).{8,}$/, 'Password must have at least 8 characters that include at least 1 lowercase character, 1 uppercase character, 1 number, and 1 special character.');

const displayErrorMessageOnSignIn = (message) => {
  msg_signin.innerHTML = `
    <div class="alert error">
      <p class="text">
        <strong>${message}</strong>
      </p>
    </div>`;
};


const clearMessagesOnSignIn = () => {
  msg_signin.innerHTML = '';
};

// Check each input field for errors and
// prevent form submission if any fields has errors
signinForm.addEventListener('submit', async (e) => {
  e.preventDefault(); // Prevent form submission

  clearMessagesOnSignIn(); // Clear previous messages

  let isFormValidOnSignIn = true;

  if (!checkEmailOnSignIn()) {
    isFormValidOnSignIn = false;
  }

  if (!checkPasswordOnSignIn()) {
    isFormValidOnSignIn = false;
  }

  if (isFormValidOnSignIn) {
    const formType = signinformTypeEl.value;
    const emailSignIn = signinEmailEl.value;
    const passwordSignIn = signinPasswordEl.value;

    // Construct request body object
    const requestBodyOnSignIn = {
      formType,
      emailSignIn,
      passwordSignIn
    };

    try {
      const responseOnSignIn = await fetch('assets/php/action.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(requestBodyOnSignIn)
      });

      if (responseOnSignIn.ok) {
        const responseOnSignInData = await responseOnSignIn.json();
        displayErrorMessageOnSignIn(responseOnSignInData.message);
      }
    } catch (error) {
      displayErrorMessageOnSignIn('An error occurred during the network call.');
    }
  }
});
