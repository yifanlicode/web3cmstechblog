$(document).ready(function() {
  // Validate registration form
  $('#register-form').validate({
    // Specify validation rules
    rules: {
      username: {
        required: true,
        minlength: 4,
        remote: {
          url: 'check_username.php',
          type: 'post'
        }
      },
      email: {
        required: true,
        email: true,
        remote: {
          url: 'check_email.php',
          type: 'post'
        }
      },
      password: {
        required: true,
        minlength: 8
      },
      confirm_password: {
        required: true,
        equalTo: '#password'
      }
    },

    // Specify validation error messages
    messages: {
      username: {
        required: 'Please enter a username.',
        minlength: 'Username must be at least 4 characters long.',
        remote: 'Username already exists. Please choose another.'
      },
      email: {
        required: 'Please enter an email address.',
        email: 'Please enter a valid email address. ',
        remote: 'Email address already exists. Please choose another.'
      },
      password: {
        required: 'Please enter a password.',
        minlength: 'Password must be at least 8 characters long. '
      },
      confirm_password: {
        required: 'Please confirm your password.',
        equalTo: 'Passwords do not match. Please try again.'
      }
    },

    // Submit form via normal form submission
    submitHandler: function(form) {
      form.submit();
    }
  });
});
