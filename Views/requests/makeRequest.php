<?php
require_once '../../Controllers/ValidationController.php';
ValidationController::validateSession('client');
require_once '../../Models/request.php';

$successMessage = '';
$errorMessage = '';
$clientId = $_SESSION['user_id'];

// Create request object to check for existing requests
$request = new Request();
$activeRequests = $request->getActiveRequestsByClientId($clientId);

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Only process if client doesn't have active requests
    if (empty($activeRequests)) {
        $description = $_POST['description'];
        $location = $_POST['location'];
        
        $request = new Request("", $clientId, "", $description, $location);
        
        if ($request->createRequest()) {
            $successMessage = "Your request has been submitted successfully. A mechanic will be assigned to you shortly.";
        } else {
            $errorMessage = "Failed to submit your request. Please try again.";
        }
    } else {
        $errorMessage = "You already have an active request. Please wait until your current request is completed before submitting a new one.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>On-Road | Make a Request</title>
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
                    <div class="card shadow">
                        <div class="card-body p-5">
                            <h2 class="text-center mb-4">Make Your Request</h2>
                            
                            <?php if (!empty($successMessage)): ?>
                                <div class="alert alert-success" role="alert">
                                    <?php echo $successMessage; ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($errorMessage)): ?>
                                <div class="alert alert-danger" role="alert">
                                    <?php echo $errorMessage; ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (empty($activeRequests)): ?>
                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                    <div class="mb-4">
                                        <label for="description" class="form-label">Describe Your Problem</label>
                                        <textarea class="form-control" id="description" name="description" rows="5" placeholder="Please describe the issue with your vehicle in detail..." required></textarea>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="location" class="form-label">Your Current Location</label>
                                        <input type="text" class="form-control" id="location" name="location" placeholder="Enter your current location..." required>
                                        <small class="text-muted">Please provide as much detail as possible about your location</small>
                                    </div>
                                    
                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-primary btn-lg">Submit Request</button>
                                        <a href="../home.php" class="btn btn-outline-secondary">Cancel</a>
                                    </div>
                                </form>
                            <?php else: ?>
                                <div class="alert alert-warning" role="alert">
                                    <h5><i class="fas fa-exclamation-triangle me-2"></i>You already have an active request</h5>
                                    <p>You can only have one active request at a time. Please wait until your current request is completed before submitting a new one.</p>
                                    <div class="mt-3">
                                        <a href="myRequests.php" class="btn btn-primary">View My Requests</a>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="card mt-4">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-info-circle me-2"></i>What happens next?</h5>
                            <ol class="mb-0">
                                <li>Your request will be reviewed by our team</li>
                                <li>A qualified mechanic will be assigned to your case</li>
                                <li>The mechanic will contact you and provide an estimated arrival time</li>
                                <li>Once the service is completed, you can rate your experience</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>