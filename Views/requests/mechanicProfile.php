<?php
require_once '../../Controllers/ValidationController.php';
ValidationController::validateSession('client');
require_once '../../Models/mechanic.php';

$errorMessage = '';
$mechanic = null;

// Check if mechanic ID is provided
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $mechanicId = $_GET['id'];
    
    // Create Mechanic object
    $mechanic = new Mechanic();
    
    // Get mechanic details
    if (!$mechanic->getMechanicById($mechanicId)) {
        $errorMessage = "Mechanic not found.";
    }
} else {
    $errorMessage = "Mechanic ID is required.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>On-Road | Mechanic Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../../root/css/main.css">
</head>
<body>
    <?php include_once '../includes/navbar.php'; ?>
    
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <?php if (!empty($errorMessage)): ?>
                        <div class="alert alert-danger text-center">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <?php echo $errorMessage; ?>
                            <div class="mt-3">
                                <a href="myRequests.php" class="btn btn-primary">Back to My Requests</a>
                            </div>
                        </div>
                    <?php elseif ($mechanic): ?>
                        <div class="card shadow">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">Mechanic Profile</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 text-center mb-4 mb-md-0">
                                        <img src="../../root/img/Avatar.png" alt="Mechanic Avatar" class="rounded-circle img-fluid" style="width: 150px;">
                                        <div class="mt-3">
                                            <div class="d-flex justify-content-center">
                                                <?php
                                                $rating = $mechanic->getRating();
                                                $fullStars = floor($rating);
                                                $halfStar = $rating - $fullStars >= 0.5;
                                                $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                                                
                                                for ($i = 0; $i < $fullStars; $i++) {
                                                    echo '<i class="fas fa-star text-warning"></i>';
                                                }
                                                
                                                if ($halfStar) {
                                                    echo '<i class="fas fa-star-half-alt text-warning"></i>';
                                                }
                                                
                                                for ($i = 0; $i < $emptyStars; $i++) {
                                                    echo '<i class="far fa-star text-warning"></i>';
                                                }
                                                ?>
                                                <span class="ms-2">(<?php echo $mechanic->getTotalReviews(); ?> reviews)</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <h4 class="mb-3"><?php echo $mechanic->getFullName(); ?></h4>
                                        <div class="mb-3">
                                            <p class="mb-1"><strong>Specialization:</strong> <?php echo $mechanic->getSpecialization(); ?></p>
                                            <p class="mb-1"><strong>Location:</strong> <?php echo $mechanic->getLocation(); ?></p>
                                            <p class="mb-1"><strong>Experience:</strong> <?php echo $mechanic->getExperience(); ?> years</p>
                                        </div>
                                        <div class="mb-3">
                                            <h5 class="mb-2">Contact Information</h5>
                                            <p class="mb-1"><i class="fas fa-envelope me-2"></i> <?php echo $mechanic->getEmail(); ?></p>
                                            <p class="mb-1"><i class="fas fa-user me-2"></i> @<?php echo $mechanic->getUsername(); ?></p>
                                        </div>
                                    </div>
                                </div>
                                
                                <hr>
                                
                                <div class="text-center mt-3">
                                    <a href="myRequests.php" class="btn btn-primary">
                                        <i class="fas fa-arrow-left me-2"></i> Back to My Requests
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../root/js/main.js"></script>
</body>
</html>