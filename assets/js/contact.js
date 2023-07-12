const contactUsBtn = document.querySelector('[name="contact-btn"]');
const emailFromEl = document.querySelector('[name="email_from"]');
const subjectEl = document.querySelector('[name="subject"]');
const messageEl = document.querySelector('[name="message"]');

const isRequiredOnContact = value => Boolean(value);

const showSuccessOnContact = input => {
  const formField = input.parentElement;
  formField.classList.remove('error');
  formField.classList.add('success');
  const error = formField.querySelector('.error');
  error.innerText = '';
};

const showErrorOnContact = (input, message) => {
  const formField = input.parentElement;
  formField.classList.remove('success');
  formField.classList.add('error');
  const error = formField.querySelector('.error');
  error.innerText = message;
};

const isValidOnContact = (input, pattern, message) => {
  const value = input.value.trim();
  if (!isRequiredOnContact(value)) {
    showErrorOnContact(input, `${input.name} cannot be blank.`);
    return false;
  } else if (!pattern.test(value)) {
    showErrorOnContact(input, message);
    return false;
  } else {
    showSuccessOnContact(input);
    return true;
  }
};

const checkEmailOnContact = () => isValidOnContact(emailFromEl, /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/, 'Email is not valid.');
const checkSubjectOnContact = () => isValidOnContact(subjectEl, /^[a-zA-Z\s]+$/, 'Subject should only contain letters.');
const checkMessageOnContact = () => isValidOnContact(messageEl, /^[a-zA-Z\s]+$/, 'Message should only contain letters.');

// Check each input field for errors and
// prevent form submission if any fields has errors
contactUsBtn.addEventListener('click', e => {
  let isFormValidOnContact = true;

  if (!checkEmailOnContact()) {
    isFormValidOnContact = false;
  }

  if (!checkSubjectOnContact()) {
    isFormValidOnContact = false;
  }

  if (!checkMessageOnContact()) {
    isFormValidOnContact = false;
  }
  if (isFormValidOnContact) { } else {
    // Prevent form submission
    e.preventDefault();
  }
});