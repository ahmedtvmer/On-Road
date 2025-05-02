$(document).ready(function() {
    // Form validation
    $('#signupForm').on('submit', function(e) {
        e.preventDefault();
        
        let isValid = true;
        const fullname = $('#fullname').val().trim();
        const email = $('#email').val().trim();
        const username = $('#username').val().trim();
        const password = $('#password').val().trim();
        const confirmPassword = $('#confirm_password').val().trim();
        const termsChecked = $('#terms').is(':checked');
        const selectedRole = $('#selected_role').val();
        
        // Reset previous error states
        $('.form-control').removeClass('is-invalid');
        $('.error-message').hide();
        $('#signup-error').hide();
        
        // Validate full name
        if (fullname === '') {
            $('#fullname').addClass('is-invalid');
            $('#fullname-error').show();
            isValid = false;
        }
        
        // Validate email
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (email === '' || !emailRegex.test(email)) {
            $('#email').addClass('is-invalid');
            $('#email-error').show();
            isValid = false;
        }
        
        // Validate username
        if (username === '' || username.length < 4) {
            $('#username').addClass('is-invalid');
            $('#username-error').show();
            isValid = false;
        }
        
        // Validate password
        if (password === '' || password.length < 6) {
            $('#password').addClass('is-invalid');
            $('#password-error').show();
            isValid = false;
        }
        
        // Validate confirm password
        if (confirmPassword === '' || confirmPassword !== password) {
            $('#confirm_password').addClass('is-invalid');
            $('#confirm-password-error').show();
            isValid = false;
        }
        
        // Validate mechanic-specific fields if mechanic role is selected
        if (selectedRole === 'mechanic') {
            const specialization = $('#specialization').val().trim();
            const location = $('#Location').val().trim();
            const experience = $('#experience').val().trim();
            
            if (specialization === '') {
                $('#specialization').addClass('is-invalid');
                isValid = false;
            }
            
            if (location === '') {
                $('#Location').addClass('is-invalid');
                isValid = false;
            }
            
            if (experience === '') {
                $('#experience').addClass('is-invalid');
                isValid = false;
            }
        }
        
        // Validate admin-specific fields if admin role is selected
        if (selectedRole === 'admin') {
            const adminCode = $('#admin_code').val().trim();
            if (adminCode === '') {
                $('#admin_code').addClass('is-invalid');
                $('#admin-code-error').show();
                isValid = false;
            }
        }
        
        // Validate terms
        if (!termsChecked) {
            $('#terms-error').show();
            isValid = false;
        }
        
        // If form is valid, submit the form to navigate to home.php
        // If not valid, show the error message
        if (isValid) {
            // Allow the form to submit naturally to home.php
            this.submit();
        } else {
            // Show general error message
            $('#signup-error').text('Please fix the errors in the form.').show();
        }
    });
    
    // Clear error state on input focus
    $('.form-control').on('focus', function() {
        $(this).removeClass('is-invalid');
        $(this).next().next('.error-message').hide();
    });
    
    // Clear terms error when checkbox is clicked
    $('#terms').on('change', function() {
        if ($(this).is(':checked')) {
            $('#terms-error').hide();
        }
    });
    
    // Role selection toggle
    $('.role-option').click(function() {
        // Remove active class from all options
        $('.role-option').removeClass('active');
        
        // Add active class to clicked option
        $(this).addClass('active');
        
        // Get selected role
        const selectedRole = $(this).data('role');
        
        // Update hidden input value
        $('#selected_role').val(selectedRole);
        
        // Hide all role-specific fields
        $('#mechanic-fields, #admin-fields').hide();
        
        // Show fields based on selected role
        if (selectedRole === 'mechanic') {
            $('#mechanic-fields').show();
        } else if (selectedRole === 'admin') {
            $('#admin-fields').show();
        }
    });
    
    // Toggle sidebar on mobile (for dashboard pages)
    $('#toggleSidebar').click(function() {
        $('#adminSidebar').toggleClass('show');
    });
    
    // Close sidebar when clicking outside on mobile
    $(document).click(function(event) {
        if (!$(event.target).closest('#adminSidebar').length && 
            !$(event.target).closest('#toggleSidebar').length && 
            $('#adminSidebar').hasClass('show')) {
            $('#adminSidebar').removeClass('show');
        }
    });
});
