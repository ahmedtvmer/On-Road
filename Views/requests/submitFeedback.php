<?php
require_once '../../Controllers/FeedbackController.php';

$feedbackController = new FeedbackController();
$feedbackController->processFeedback();
$errorMessage = $feedbackController->getErrorMessage();
$successMessage = $feedbackController->getSuccessMessage();
$requestData = $feedbackController->getRequestData();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>On-Road | Submit Feedback</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../../root/css/main.css">
    <style>
        .rating {
            display: flex;
            flex-direction: row-reverse;
            justify-content: flex-end;
        }
        .rating input {
            display: none;
        }
        .rating label {
            cursor: pointer;
            width: 40px;
            height: 40px;
            margin: 0;
            padding: 0;
            font-size: 30px;
            color: #ddd;
        }
        .rating label:before {
            content: '\f005';
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
        }
        .rating input:checked ~ label {
            color: #ffc107;
        }
        .rating label:hover,
        .rating label:hover ~ label {
            color: #ffc107;
        }
    </style>
</head>
<body>
    <?php include_once '../includes/navbar.php'; ?>
    
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <?php if (!empty($successMessage)): ?>
                        <div class="alert alert-success text-center">
                            <i class="fas fa-check-circle me-2"></i>
                            <?php echo $successMessage; ?>
                            <div class="mt-3">
                                <a href="myRequests.php" class="btn btn-primary">Back to My Requests</a>
                            </div>
                        </div>
                    <?php elseif (!empty($errorMessage)): ?>
                        <div class="alert alert-danger text-center">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <?php echo $errorMessage; ?>
                            <div class="mt-3">
                                <a href="myRequests.php" class="btn btn-primary">Back to My Requests</a>
                            </div>
                        </div>
                    <?php elseif ($requestData): ?>
                        <div class="card shadow">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">Submit Feedback</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-4">
                                    <h6>Request Details</h6>
                                    <p><strong>Request ID:</strong> #<?php echo $requestData['id']; ?></p>
                                    <p><strong>Description:</strong> <?php echo $requestData['description']; ?></p>
                                    <p><strong>Location:</strong> <?php echo $requestData['location']; ?></p>
                                    <p><strong>Mechanic:</strong> <?php echo $requestData['mechanicName']; ?></p>
                                </div>
                                
                                <form method="POST" action="">
                                    <input type="hidden" name="request_id" value="<?php echo $requestData['id']; ?>">
                                    <input type="hidden" name="mechanic_id" value="<?php echo $requestData['mechanicId']; ?>">
                                    
                                    <div class="mb-4">
                                        <label class="form-label"><strong>How would you rate the cost of the service?</strong></label>
                                        <div class="rating mb-3">
                                            <input type="radio" id="cost_star5" name="cost_rating" value="5" required />
                                            <label for="cost_star5" title="5 stars"></label>
                                            <input type="radio" id="cost_star4" name="cost_rating" value="4" />
                                            <label for="cost_star4" title="4 stars"></label>
                                            <input type="radio" id="cost_star3" name="cost_rating" value="3" />
                                            <label for="cost_star3" title="3 stars"></label>
                                            <input type="radio" id="cost_star2" name="cost_rating" value="2" />
                                            <label for="cost_star2" title="2 stars"></label>
                                            <input type="radio" id="cost_star1" name="cost_rating" value="1" />
                                            <label for="cost_star1" title="1 star"></label>
                                        </div>
                                        <div class="text-muted small">1 star = Very expensive, 5 stars = Very affordable</div>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label class="form-label"><strong>How would you rate the quality of service?</strong></label>
                                        <div class="rating mb-3">
                                            <input type="radio" id="service_star5" name="service_rating" value="5" required />
                                            <label for="service_star5" title="5 stars"></label>
                                            <input type="radio" id="service_star4" name="service_rating" value="4" />
                                            <label for="service_star4" title="4 stars"></label>
                                            <input type="radio" id="service_star3" name="service_rating" value="3" />
                                            <label for="service_star3" title="3 stars"></label>
                                            <input type="radio" id="service_star2" name="service_rating" value="2" />
                                            <label for="service_star2" title="2 stars"></label>
                                            <input type="radio" id="service_star1" name="service_rating" value="1" />
                                            <label for="service_star1" title="1 star"></label>
                                        </div>
                                        <div class="text-muted small">1 star = Poor service, 5 stars = Excellent service</div>
                                    </div>
                                    
                                    <div class="text-center mt-4">
                                        <a href="myRequests.php" class="btn btn-secondary me-2">Cancel</a>
                                        <button type="submit" name="submit_feedback" class="btn btn-primary">Submit Feedback</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>