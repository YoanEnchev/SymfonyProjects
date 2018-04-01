(function validate() {
    //fields:
    const usermameBox = $('#user_username');
    const emailBox = $('#user_email');
    const phoneBox = $('#user_phone');
    const cityBox = $('#user_city');
    const passwordBox = $('#user_password_first');
    const repeatPasswordBox = $('#user_password_second');

    const allFields = $('input');
    const submitBtn = $('#user_submit');

    //regexes:
    const usernameRegex_length = /^.{3,30}$/;
    const usernameRegex_characters = /^\w*$/;
    const usernameRegex_complete = /^\w{3,30}$/;

    const emailRegex = /^(\w+)@(\w+)\.(\w+)$/;

    const passwordRegex_length = /^.{6,30}$/;
    const passwordRegex_symbols = /^(.*[A-Z].*[a-z].*[0-9].*)$/;
    const passwordRegex_complete = /^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9]).{8,}$/;

    //error message boxes:
    const usernameError = $('#invalidUsername');
    const emailError = $('#invalidEmail');
    const cityError = $('#invalidCity');
    const phoneError = $('#invalidPhone');
    const passwordError = $('#invalidPassword');
    const confirmPasswordError = $('#invalidConfirmPassword');

    //events:
    usermameBox.keyup(() => invalidAction(usermameBox, usernameRegex_length, usernameError, 'Username length should be between 3 and 30 symbols.'));
    usermameBox.keyup(() => invalidAction(usermameBox, usernameRegex_characters, usernameError, 'Username can contain only alphanumeric symbols and underscore.'));
    usermameBox.keyup(() => validAction(usermameBox, usernameRegex_complete, usernameError));

    emailBox.keyup(() => invalidAction(emailBox, emailRegex, emailError, 'Invalid email'));
    emailBox.keyup(() => validAction(emailBox, emailRegex, emailError));

    passwordBox.keyup(() => invalidAction(passwordBox, passwordRegex_symbols, passwordError, 'Password should contain at least one digit, uppercase and lowercase letter'));
    passwordBox.keyup(() => invalidAction(passwordBox, passwordRegex_length, passwordError, 'Password length should be between 6 and 30 symbols.'));
    passwordBox.keyup(() => validAction(passwordBox, passwordRegex_complete, passwordError));
    passwordBox.keyup(() => passwordMatchAction('Passwords do not match.'));

    repeatPasswordBox.keyup(() => passwordMatchAction('Passwords do not match.'));

    phoneBox.keyup(() => validatePhoneOrCity(phoneBox, phoneError, 'Please fill your phone.'));
    cityBox.keyup(() => validatePhoneOrCity(cityBox, cityError, 'Please fill your location.'));


    allFields.keyup(checkIfAllValid);

    function invalidAction(textfield ,regex, errorBox, message) {
        if (!regex.test(textfield.val())) {
            showInvalid(textfield, errorBox, message)
        }
    }

    function validAction(textfield ,regex, errorBox) {
        if (regex.test(textfield.val())) {
            showValid(textfield, errorBox)
        }
    }

    function passwordMatchAction(message) {
        if (passwordBox.val() === repeatPasswordBox.val()) {
            showValid(repeatPasswordBox, confirmPasswordError);
        }
        else {
            showInvalid(repeatPasswordBox, confirmPasswordError, message);
        }
    }
    
    function showInvalid(textfield, errorBox ,message) {
        textfield.css('border-color', 'red');
        textfield.css('background', '#FAF4F4');
        errorBox.text(message);
    }

    function showValid(textfield, errorBox) {
        textfield.css('border-color', 'green');
        textfield.css('background', '#EDF4EC');
        errorBox.text('');
    }

    function validatePhoneOrCity(textfield, errorBox, message) {
        if(textfield.val() === "") {
            showInvalid(textfield, errorBox, message)
        }
        else {
            showValid(textfield, errorBox);
        }
    }

    function checkIfAllValid() {
        if(usernameRegex_complete.test(usermameBox.val()) && emailRegex.test(emailBox.val()) &&
            passwordRegex_complete.test(passwordBox.val()) && passwordBox.val() === repeatPasswordBox.val() &&
            phoneBox.val() !== "" && cityBox.val() !== "")
        {
            submitBtn.removeAttr("disabled")
        } else {
            submitBtn.prop("disabled", true)
        }
    }
})();