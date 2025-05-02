$(document).ready(function() {
    $('#signupForm').on('submit', function(e) {
        e.preventDefault();
        
        let isValid = true;
        const fullname = $('#fullname').val().trim();
        const email = $('#email').val().trim();
        const username = $('#username').val().trim();
        const password = $('#password').val().trim();
        const confirmPassword = $('#confirm_password').val().trim();
        const termsChecked = $('#terms').is(':checked');
        
        $('.form-control').removeClass('is-invalid');
        $('.error-message').hide();
        $('#signup-error').hide();
        
        if (fullname === '') {
            $('#fullname').addClass('is-invalid');
            $('#fullname-error').show();
            isValid = false;
        }
        
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (email === '' || !emailRegex.test(email)) {
            $('#email').addClass('is-invalid');
            $('#email-error').show();
            isValid = false;
        }
        
        if (username === '' || username.length < 4) {
            $('#username').addClass('is-invalid');
            $('#username-error').show();
            isValid = false;
        }
        
        if (password === '' || password.length < 6) {
            $('#password').addClass('is-invalid');
            $('#password-error').show();
            isValid = false;
        }
        
        if (confirmPassword === '' || confirmPassword !== password) {
            $('#confirm_password').addClass('is-invalid');
            $('#confirm-password-error').show();
            isValid = false;
        }
        
        if (!termsChecked) {
            $('#terms-error').show();
            isValid = false;
        }
        
        if (isValid) {
            this.submit();
        } else {
            $('#signup-error').text('Please fix the errors in the form.').show();
        }
    });
    
    $('.form-control').on('focus', function() {
        $(this).removeClass('is-invalid');
        $(this).next().next('.error-message').hide();
    });
    
    $('#terms').on('change', function() {
        if ($(this).is(':checked')) {
            $('#terms-error').hide();
        }
    });
});

$(document).ready(function() {
    $('#toggleSidebar').click(function() {
        $('#adminSidebar').toggleClass('show');
    });
    
    $(document).click(function(event) {
        if (!$(event.target).closest('#adminSidebar').length && 
            !$(event.target).closest('#toggleSidebar').length && 
            $('#adminSidebar').hasClass('show')) {
            $('#adminSidebar').removeClass('show');
        }
    });
});
