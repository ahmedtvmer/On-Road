<?php
require_once '../Controllers/ProfileController.php';

$profileController = new ProfileController();
$profileController->processProfileActions();
$user = $profileController->getUser();
$userRole = $profileController->getUserRole();
$successMessage = $profileController->getSuccessMessage();
$errorMessage = $profileController->getErrorMessage();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>On-Road | Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../root/css/main.css">
</head>
<body>
    <?php include_once 'includes/navbar.php'; ?>
    
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-body text-center">
                        <img src="../root/img/Avatar.png" alt="avatar" class="rounded-circle img-fluid" style="width: 150px;">
                        <h5 class="my-3"><?php echo $user->getFullName(); ?></h5>
                        <p class="text-muted mb-1"><?php echo ucfirst($userRole); ?></p>
                        <p class="text-muted mb-4">
                            <?php 
                            if ($userRole == 'mechanic') {
                                echo $user->getSpecialization() . ' - ' . $user->getLocation();
                            }
                            ?>
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Edit Profile</h5>
                    </div>
                    <div class="card-body">
                        
                        <div class="container mt-5">
                            <div class="row">
                                <div class="col-md-8 mx-auto">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4>Edit Profile</h4>
                                        </div>
                                        <div class="card-body">
                                            <?php if (!empty($successMessage)): ?>
                                                <div class="alert alert-success">
                                                    <?php echo $successMessage; ?>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <?php if (!empty($errorMessage)): ?>
                                                <div class="alert alert-danger">
                                                    <?php echo $errorMessage; ?>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <form method="POST" action="">
                                                <div class="form-group mb-3">
                                                    <label for="fullName">Full Name</label>
                                                    <input type="text" class="form-control" id="fullName" name="fullName" value="<?php echo $user->getFullName(); ?>" required>
                                                </div>
                                                
                                                <div class="form-group mb-3">
                                                    <label for="email">Email</label>
                                                    <input type="email" class="form-control" id="email" name="email" value="<?php echo $user->getEmail(); ?>" required>
                                                </div>
                                                
                                                <div class="form-group mb-3">
                                                    <label for="username">Username</label>
                                                    <input type="text" class="form-control" id="username" name="username" value="<?php echo $user->getUsername(); ?>" required>
                                                </div>
                                                
                                                <div class="form-group mb-3">
                                                    <label for="password">Password (leave blank to keep current password)</label>
                                                    <input type="password" class="form-control" id="password" name="password">
                                                </div>
                                                
                                                <?php if ($userRole == 'mechanic'): ?>
                                                <div class="form-group mb-3">
                                                    <label for="location">Location</label>
                                                    <input type="text" class="form-control" id="location" name="location" value="<?php echo $user->getLocation(); ?>" required>
                                                </div>
                                                
                                                <div class="form-group mb-3">
                                                    <label for="specialization">Specialization</label>
                                                    <input type="text" class="form-control" id="specialization" name="specialization" value="<?php echo $user->getSpecialization(); ?>" required>
                                                </div>
                                                
                                                <div class="form-group mb-3">
                                                    <label for="experience">Experience</label>
                                                    <input type="text" class="form-control" id="experience" name="experience" value="<?php echo $user->getExperience(); ?>" required>
                                                </div>
                                                <?php endif; ?>
                                                
                                                <?php if ($userRole == 'admin'): ?>
                                                <div class="form-group mb-3">
                                                    <label for="adminCode">Admin Code</label>
                                                    <input type="text" class="form-control" id="adminCode" name="adminCode" value="<?php echo $user->getAdminCode(); ?>" readonly>
                                                    <small class="text-muted">Admin code cannot be changed</small>
                                                </div>
                                                <?php endif; ?>
                                                                                                
                                                <div class="d-flex justify-content-between">
                                                    <button type="submit" name="update_profile" class="btn btn-primary">Update Profile</button>
                                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                                                        Delete Account
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteAccountModalLabel">Confirm Account Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete your account? This action cannot be undone.</p>
                <p class="text-danger fw-bold">All your data will be permanently removed.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" action="">
                    <input type="hidden" name="delete_account" value="1">
                    <button type="submit" class="btn btn-danger">Yes, Delete My Account</button>
                </form>
            </div>
        </div>
    </div>
</div>