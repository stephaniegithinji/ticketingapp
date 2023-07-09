const contactUsForm = document.getElementById("contactForm");
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

const checkRecipientEmail = () => isValidOnContact(emailFromEl, /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/, 'Email is not valid.');
const checkSubject = () => isValidOnContact(subjectEl, /^[a-zA-Z\s]+$/, 'Subject should only contain letters.');
const checkMessage = () => isValidOnContact(messageEl, /^[a-zA-Z\s]+$/, 'Message should only contain letters.');

contactUsForm.addEventListener('submit', e => {
  let isFormValid = true;

  if (!checkRecipientEmail()) {
    isFormValid = false;
  }

  if (!checkSubject()) {
    isFormValid = false;
  }

  if (!checkMessage()) {
    isFormValid = false;
  }
  // Check each field for errors and prevent form submission if any fields have errors
  if (isFormValid) { } else {
    // Prevent form submission
    e.preventDefault();
  }
});