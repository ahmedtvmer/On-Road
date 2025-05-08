<?php
require_once '../../Controllers/ValidationController.php';
ValidationController::validateSession('admin');
require_once '../../Models/request.php';
require_once '../../Models/solution.php';
require_once '../../Models/feedback.php';
require_once '../../Models/client.php'; // Changed from user.php to client.php
require_once '../../Models/mechanic.php';

// Get all requests with their associated data
$request = new Request();
$solution = new Solution();
$feedback = new Feedback();
$client = new Client(); // Changed variable name from $user to $client
$mechanic = new Mechanic();

$allRequests = $request->getAllRequests();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ON-ROAD BREAKDOWN ASSISTANCE | Order Reports</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../../root/css/main.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="admin-section">
        <?php include_once '../includes/adminsidebar.php';?>
        <?php include_once '../includes/navbar.php';?>
        
        <div class="admin-content-wrapper">
            <div class="admin-content">
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="../adminDashboard.php"><i class="fas fa-home"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Order Reports</li>
                    </ol>
                </nav>
                
                <h4 class="mb-4">Order Reports and Feedback</h4>
                
                <div class="card shadow mb-4">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">All Orders and Feedback</h5>
                        <div>
                            <button class="btn btn-sm btn-light" id="refreshBtn">
                                <i class="fas fa-sync-alt"></i> Refresh
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Client</th>
                                        <th>Mechanic</th>
                                        <th>Description</th>
                                        <th>Location</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th>Rating</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($allRequests)): ?>
                                        <?php foreach ($allRequests as $req): ?>
                                            <?php 
                                                $clientInfo = $client->getClientById($req['client_id']); // Changed from $user to $client
                                                
                                                $mechanicInfo = null;
                                                if (!empty($req['mechanic_id'])) {
                                                    $mechanicInfo = $mechanic->getMechanicById($req['mechanic_id']);
                                                }
                                                
                                                $feedbackInfo = $feedback->getFeedbackByRequestId($req['id']);
                                                $solutionInfo = $solution->getSolutionByRequestId($req['id']);
                                            ?>
                                            <tr>
                                                <td><?php echo $req['id']; ?></td>
                                                <td>
                                                    <?php if ($clientInfo): ?>
                                                        <a href="../../Views/requests/clientProfile.php?id=<?php echo $client->getId(); ?>" class="text-decoration-none">
                                                            <?php echo $client->getFullName(); ?>
                                                        </a>
                                                    <?php else: ?>
                                                        <span class="text-muted">Unknown</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($mechanicInfo): ?>
                                                        <a href="../../Views/requests/mechanicProfile.php?id=<?php echo $mechanic->getId(); ?>" class="text-decoration-none">
                                                            <?php echo $mechanic->getFullName(); ?>
                                                        </a>
                                                    <?php else: ?>
                                                        <span class="text-muted">Not Assigned</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?php echo $req['description']; ?></td>
                                                <td><?php echo $req['location']; ?></td>
                                                <td>
                                                    <?php if ($req['status'] == 'pending'): ?>
                                                        <span class="badge bg-warning">Pending</span>
                                                    <?php elseif ($req['status'] == 'assigned'): ?>
                                                        <span class="badge bg-info">Assigned</span>
                                                    <?php elseif ($req['status'] == 'completed'): ?>
                                                        <span class="badge bg-success">Completed</span>
                                                    <?php elseif ($req['status'] == 'cancelled'): ?>
                                                        <span class="badge bg-danger">Cancelled</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?php echo date('M d, Y', strtotime($req['createdAt'])); ?></td>
                                                <td>
                                                    <?php if ($feedbackInfo): ?>
                                                        <button type="button" class="btn btn-sm btn-outline-info view-feedback" 
                                                                data-bs-toggle="modal" data-bs-target="#feedbackModal"
                                                                data-cost-rating="<?php echo $feedback->getCostRating(); ?>"
                                                                data-service-rating="<?php echo $feedback->getServiceRating(); ?>">
                                                            View Feedback
                                                        </button>
                                                    <?php else: ?>
                                                        <span class="text-muted">No Feedback</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($feedbackInfo): ?>
                                                        <div class="rating-stars">
                                                            <?php 
                                                            $avgRating = ($feedback->getCostRating() + $feedback->getServiceRating()) / 2;
                                                            for ($i = 1; $i <= 5; $i++): 
                                                            ?>
                                                                <?php if ($i <= $avgRating): ?>
                                                                    <i class="fas fa-star"></i>
                                                                <?php else: ?>
                                                                    <i class="far fa-star"></i>
                                                                <?php endif; ?>
                                                            <?php endfor; ?>
                                                        </div>
                                                    <?php else: ?>
                                                        <span class="text-muted">N/A</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <a href="generateReport.php?id=<?php echo $req['id']; ?>" class="btn btn-sm btn-success" target="_blank">
                                                        <i class="fas fa-download"></i> Download Report
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="10" class="text-center">No requests found</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Feedback Modal -->
    <div class="modal fade" id="feedbackModal" tabindex="-1" aria-labelledby="feedbackModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="feedbackModalLabel">Client Feedback</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Cost Rating:</label>
                        <div class="rating-stars modal-cost-rating"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Service Rating:</label>
                        <div class="rating-stars modal-service-rating"></div>
                    </div>
                </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            // Toggle sidebar
            $('#sidebarToggle').on('click', function() {
                $('#adminSidebar').toggleClass('collapsed');
                $('.admin-content-wrapper').toggleClass('expanded');
            });
            
            // View feedback modal
            $('.view-feedback').on('click', function() {
                const feedback = $(this).data('feedback');
                const rating = $(this).data('rating');
                
                $('#feedbackComment').text(feedback);
                
                // Set stars in modal
                let starsHtml = '';
                for (let i = 1; i <= 5; i++) {
                    if (i <= rating) {
                        starsHtml += '<i class="fas fa-star"></i> ';
                    } else {
                        starsHtml += '<i class="far fa-star"></i> ';
                    }
                }
                $('.modal-rating').html(starsHtml);
            });
            
            // Refresh button
            $('#refreshBtn').on('click', function() {
                location.reload();
            });
            $('.view-feedback').on('click', function() {
        var costRating = $(this).data('cost-rating');
        var serviceRating = $(this).data('service-rating');
        
        // Clear previous stars
        $('.modal-cost-rating, .modal-service-rating').empty();
        
        // Add cost rating stars
        for (var i = 1; i <= 5; i++) {
            if (i <= costRating) {
                $('.modal-cost-rating').append('<i class="fas fa-star"></i>');
            } else {
                $('.modal-cost-rating').append('<i class="far fa-star"></i>');
            }
        }
        
        // Add service rating stars
        for (var i = 1; i <= 5; i++) {
            if (i <= serviceRating) {
                $('.modal-service-rating').append('<i class="fas fa-star"></i>');
            } else {
                $('.modal-service-rating').append('<i class="far fa-star"></i>');
            }
        }
    });
        });
    </script>
</body>
</html>