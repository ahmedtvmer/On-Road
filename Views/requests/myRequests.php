<?php
require_once '../../Controllers/ValidationController.php';
ValidationController::validateSession('client');
require_once '../../Models/request.php';

$clientId = $_SESSION['user_id'];

$request = new Request();

// Get all requests (both active and completed) for the client
$allRequests = $request->getAllRequestsByClientId($clientId);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>On-Road | My Requests</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../../root/css/main.css">
</head>
<body>
    <?php include_once '../includes/navbar.php'; ?>
    
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-4">My Requests</h2>
            
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <?php if (empty($allRequests)): ?>
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle me-2"></i>
                            You don't have any requests at the moment.
                            <div class="mt-3">
                                <a href="makeRequest.php" class="btn btn-primary">Make a New Request</a>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="card shadow">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">All Your Requests</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Request ID</th>
                                                <th>Description</th>
                                                <th>Location</th>
                                                <th>Status</th>
                                                <th>Created At</th>
                                                <th>Assigned Mechanic</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($allRequests as $request): ?>
                                                <tr>
                                                    <td>#<?php echo $request['id']; ?></td>
                                                    <td><?php echo $request['description']; ?></td>
                                                    <td><?php echo $request['location']; ?></td>
                                                    <td>
                                                        <?php if (empty($request['mechanic_id']) && empty($request['completed_at'])): ?>
                                                            <span class="badge bg-warning text-dark">Pending Assignment</span>
                                                        <?php elseif (!empty($request['mechanic_id']) && empty($request['completedAt'])): ?>
                                                            <span class="badge bg-info">Mechanic Assigned</span>
                                                        <?php elseif (!empty($request['completedAt'])): ?>
                                                            <span class="badge bg-success">Completed</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?php echo date('M d, Y H:i', strtotime($request['createdAt'])); ?></td>
                                                    <td>
                                                        <?php if (empty($request['mechanic_id'])): ?>
                                                            <span class="text-muted">Not assigned yet</span>
                                                        <?php else: ?>
                                                            <a href="mechanicProfile.php?id=<?php echo $request['mechanic_id']; ?>"><?php echo $request['mechanicName']; ?></a>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <a href="requestDetails.php?id=<?php echo $request['id']; ?>" class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-eye"></i> View Details
                                                        </a>
                                                        
                                                        <?php if (!empty($request['completedAt']) && $request['completedAt'] != '0000-00-00 00:00:00'): ?>
                                                            <?php
                                                            require_once '../../Models/feedback.php';
                                                            $feedback = new Feedback();
                                                            $feedbackExists = $feedback->checkFeedbackExists($request['id']);
                                                            ?>
                                                            
                                                            <?php if (!$feedbackExists): ?>
                                                                <a href="submitFeedback.php?id=<?php echo $request['id']; ?>" class="btn btn-sm btn-outline-success mt-1">
                                                                    <i class="fas fa-star"></i> Rate Service
                                                                </a>
                                                            <?php else: ?>
                                                                <span class="badge bg-success mt-1 d-block"><i class="fas fa-check-circle"></i> Feedback Submitted</span>
                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
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