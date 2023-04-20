
function displayServerErrors(errors) {
  const errorKeys = Object.keys(errors);
  errorKeys.forEach((key) => {
    const errorElement = document.getElementById(`${key}-error`);
    if (errorElement) {
      errorElement.textContent = errors[key];
    }
  });
}

function validateEmpty(inputField, errorElement) {
  const fieldName = inputField.name.charAt(0).toUpperCase() + inputField.name.slice(1);
  if (inputField.value.trim() === "") {
    errorElement.textContent = `${fieldName} cannot be empty`;
    return false;
  } else {
    errorElement.textContent = "";
    return true;
  }
}

//// Validate username use AJAX
function validateUsername() {
  const usernameField = document.getElementById("username");
  const usernameError = document.getElementById("username-error");
  return validateEmpty(usernameField, usernameError);
}

function validateEmail() {
  const emailInput = document.getElementById('email');
  const emailError = document.getElementById('email-error');
  const emailRegex = /^[\w-]+(\.[\w-]+)*@([\w-]+\.)+[a-zA-Z]{2,7}$/;

  if (!validateEmpty(emailInput, emailError)) {
    return false;
  } else if (!emailRegex.test(emailInput.value)) {
    emailError.textContent = 'Please enter a valid email address';
    return false;
  } else {
    emailError.textContent = '';
    return true;
  }
}

function validatePassword() {
  const passwordField = document.getElementById("password");
  const passwordError = document.getElementById("password-error");
  const specialCharRegex = /^(?=.*[a-zA-Z])(?=.*\d).{8,}$/;

  if (!validateEmpty(passwordField, passwordError)) {
    return false;
  } else if (passwordField.value.length < 8) {
    passwordError.textContent = "Password must be at least 8 characters long";
    return false;
  } else if (!specialCharRegex.test(passwordField.value)) {
    passwordError.textContent = "Password must contain at least one special character";
    return false;
  } else {
    passwordError.textContent = "";
    return true;
  }
}

function validateConfirmPassword() {
  const passwordField = document.getElementById("password");
  const confirmPasswordField = document.getElementById("confirm_password");
  const confirmPasswordError = document.getElementById("confirm-password-error");

  if (!validateEmpty(confirmPasswordField, confirmPasswordError)) {
    return false;
  } else if (passwordField.value !== confirmPasswordField.value) {
    confirmPasswordError.textContent = "Passwords do not match";
    return false;
  } else {
    confirmPasswordError.textContent = "";
    return true;
  }
}

function validateForm(event) {
  event.preventDefault();
  let isFormValid = true;

  if (!validateUsername()) {
    isFormValid = false;
  }

  if (!validateEmail()) {
    isFormValid = false;
  }

  if (!validatePassword()) {
    isFormValid = false;
  }

  if (!validateConfirmPassword()) {
    isFormValid = false;
  }

  if (isFormValid) {
    event.target.submit();
  } else {
    const emptyFields = document.querySelectorAll('input:required:not(:valid)');
    if (emptyFields.length === 4) {
      const formError = document.getElementById('form-error');
      formError.textContent = "All fields are required";
    }
  }
}

// document.getElementById("register-form").addEventListener("submit", validateForm);

window.onload = function() {
  document.getElementById("register-form").addEventListener("submit", validateForm);
};
