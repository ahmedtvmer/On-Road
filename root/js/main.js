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
        const selectedRole = $('#selected_role').val();
        
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
        
        if (selectedRole === 'mechanic') {
            const specialization = $('#specialization').length ? $('#specialization').val().trim() : '';
            const location = $('#location').length ? $('#location').val().trim() : '';
            const experience = $('#experience').length ? $('#experience').val().trim() : '';
            
            if (specialization === '') {
                $('#specialization').addClass('is-invalid');
                isValid = false;
            }
            
            if (location === '') {
                $('#location').addClass('is-invalid');
                isValid = false;
            }
            
            if (experience === '') {
                $('#experience').addClass('is-invalid');
                isValid = false;
            }
        }
        
        if (selectedRole === 'admin') {
            const adminCode = $('#admin_code').val().trim();
            if (adminCode === '') {
                $('#admin_code').addClass('is-invalid');
                $('#admin-code-error').show();
                isValid = false;
            }
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
    
    $('.role-option').click(function() {
        $('.role-option').removeClass('active');
        
        $(this).addClass('active');
        
        const selectedRole = $(this).data('role');
        
        $('#selected_role').val(selectedRole);
        
        $('#mechanic-fields, #admin-fields').hide();
        
        if (selectedRole === 'mechanic') {
            $('#mechanic-fields').show();
        } else if (selectedRole === 'admin') {
            $('#admin-fields').show();
        }
    });
    
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

document.addEventListener('DOMContentLoaded', function() {
    const roleOptions = document.querySelectorAll('.role-option');
    const selectedRoleInput = document.getElementById('selected_role');
    const mechanicFields = document.getElementById('mechanic-fields');
    const adminFields = document.getElementById('admin-fields');
    
    roleOptions.forEach(option => {
        option.addEventListener('click', function() {
            roleOptions.forEach(opt => opt.classList.remove('active'));
            
            this.classList.add('active');
            
            const role = this.getAttribute('data-role');
            
            if (role === 'user' || role == "") {
                selectedRoleInput.value = 'client';
            } else {
                selectedRoleInput.value = role;
            }
            
            if (role === 'mechanic') {
                mechanicFields.style.display = 'block';
                adminFields.style.display = 'none';
            } else if (role === 'admin') {
                mechanicFields.style.display = 'none';
                adminFields.style.display = 'block';
            } else {
                mechanicFields.style.display = 'none';
                adminFields.style.display = 'none';
            }
        });
    });
    
    const form = document.getElementById('signupForm');
    form.addEventListener('submit', function(event) {
        let isValid = true;
        
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirm_password').value;
        if (password !== confirmPassword) {
            document.getElementById('confirm-password-error').style.display = 'block';
            isValid = false;
        } else {
            document.getElementById('confirm-password-error').style.display = 'none';
        }
        
        if (selectedRoleInput.value === 'mechanic') {
            const specialization = document.getElementById('specialization').value;
            const location = document.getElementById('location').value;  
            const experience = document.getElementById('experience').value;
            
            if (!specialization || !location || !experience) {
                isValid = false;
                alert('All mechanic fields are required');
            }
        }
        
         if (selectedRoleInput.value === 'admin') {
            const adminCode = document.getElementById('admin_code').value;
            
            if (!adminCode) {
                document.getElementById('admin-code-error').style.display = 'block';
                isValid = false;
            } else {
                document.getElementById('admin-code-error').style.display = 'none';
            }
        }
        
        if (!isValid) {
            event.preventDefault();
        }
    });
    
    
});
