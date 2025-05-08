<?php
require_once '../../Controllers/ValidationController.php';
ValidationController::validateSession('client');
require_once '../../Models/request.php';

$clientId = $_SESSION['user_id'];
$request = new Request();
$activeRequests = $request->getActiveRequestsByClientId($clientId);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>On-Road | Track Your Request</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../../root/css/main.css">
</head>
<body>
    <?php include_once '../includes/navbar.php'; ?>
    
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-4">Track Your Requests</h2>
            
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <?php if (empty($activeRequests)): ?>
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle me-2"></i>
                            You don't have any active requests at the moment.
                            <div class="mt-3">
                                <a href="makeRequest.php" class="btn btn-primary">Make a New Request</a>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="card shadow">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">Your Active Requests</h5>
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
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($activeRequests as $activeRequest): ?>
                                                <tr>
                                                    <td>#<?php echo $activeRequest['id']; ?></td>
                                                    <td><?php echo $activeRequest['description']; ?></td>
                                                    <td><?php echo $activeRequest['location']; ?></td>
                                                    <td>
                                                        <?php if (empty($activeRequest['mechanic_id'])): ?>
                                                            <span class="badge bg-warning text-dark">Pending Assignment</span>
                                                        <?php else: ?>
                                                            <span class="badge bg-info">Mechanic Assigned</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?php echo date('M d, Y H:i', strtotime($activeRequest['createdAt'])); ?></td>
                                                    <td>
                                                        <?php if (empty($activeRequest['mechanic_id'])): ?>
                                                            <span class="text-muted">Not assigned yet</span>
                                                        <?php else: ?>
                                                            <a href="mechanicProfile.php?id=<?php echo $activeRequest['mechanic_id']; ?>"><?php echo $activeRequest['mechanicName']; ?></a>
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