<?php
require_once 'Controllers/RegisterController.php';

$registerController = new RegisterController();
$registerController->processRegistration();
$error = $registerController->getError();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>On-Road | Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="root/css/main.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <section class="login-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5">
                    <div class="diagonal-line"></div>
                    <div class="login-form">
                        <div class="login-icon">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <h2 class="login-title">Sign Up</h2>
                        
                        <div class="role-selector mb-4">
                            <div class="role-option active" data-role="user">
                                <i class="fas fa-user"></i>
                                <span>Client</span>
                            </div>
                            <div class="role-option" data-role="mechanic">
                                <i class="fas fa-wrench"></i>
                                <span>Mechanic</span>
                            </div>
                            <div class="role-option" data-role="admin">
                                <i class="fas fa-user-shield"></i>
                                <span>Admin</span>
                            </div>
                        </div>
                        
                        <form id="signupForm" action="" method="POST">
                            <input type="hidden" id="selected_role" name="selected_role" value="client">
                            
                            <div class="form-floating">
                                <input type="text" class="form-control" id="fullname" name="fullname" placeholder="Full Name" required>
                                <label for="fullname">Full Name</label>
                                <div class="error-message" id="fullname-error">Please enter your full name</div>
                            </div>
                            <div class="form-floating">
                                <input type="email" class="form-control" id="email" name="email" placeholder="Email Address" required>
                                <label for="email">Email Address</label>
                                <div class="error-message" id="email-error">Please enter a valid email address</div>
                            </div>
                            <div class="form-floating">
                                <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                                <label for="username">Username</label>
                                <div class="error-message" id="username-error">Username must be at least 4 characters</div>
                            </div>
                            <div class="form-floating">
                                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                                <label for="password">Password</label>
                                <div class="error-message" id="password-error">Password must be at least 6 characters</div>
                            </div>
                            <div class="form-floating">
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
                                <label for="confirm_password">Confirm Password</label>
                                <div class="error-message" id="confirm-password-error">Passwords do not match</div>
                            </div>
                            
                            <div id="mechanic-fields" style="display: none;">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="specialization" name="specialization" placeholder="Specialization">
                                    <label for="specialization">Specialization</label>
                                </div>
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="location" name="location" placeholder="Location">
                                    <label for="location">Location</label>
                                </div>
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="experience" name="experience" placeholder="Years of Experience">
                                    <label for="experience">Years of Experience</label>
                                </div>
                                <input type="hidden" id="rating" name="rating" value="0">
                                <input type="hidden" id="total_reviews" name="total_reviews" value="0">
                            </div>
                            
                            <div id="admin-fields" style="display: none;">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="admin_code" name="admin_code" placeholder="Admin Code">
                                    <label for="admin_code">Admin Code</label>
                                    <div class="error-message" id="admin-code-error">Please enter a valid admin code</div>
                                </div>
                            </div>
                            
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                                <label class="form-check-label" for="terms">
                                    I agree to the <a href="#" style="color: #ff6b6b;">Terms and Conditions</a>
                                </label>
                                <div class="error-message" id="terms-error">You must agree to the terms</div>
                            </div>
                            <button type="submit" class="btn btn-primary login-btn">Create Account</button>
                            <div class="alert alert-danger mt-3" id="signup-error" style="display: none;">
                                An error occurred during registration
                            </div>
                        </form>
                        <div class="login-footer">
                            <p>Already have an account? <a href="Views/login.php">Login</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="root/js/main.js"></script>
    <script>
        <?php if (!empty($error)): ?>
    document.getElementById('signup-error').textContent = "<?php echo $error; ?>";
    document.getElementById('signup-error').style.display = 'block';
    <?php endif; ?>
    </script>
    
</body>
</html>