<?php
require_once '../../Controllers/ValidationController.php';
ValidationController::validateSession('mechanic');
require_once '../../Models/request.php';

// Get mechanic ID from session
$mechanicId = $_SESSION['user_id'];

// Create Request object
$request = new Request();

// Get active requests assigned to this mechanic
$assignedRequests = $request->getActiveRequestsByMechanicId($mechanicId);

// Handle request completion
$successMessage = '';
$errorMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['complete_request'])) {
        $requestId = $_POST['request_id'];
        
        $request->getRequestById($requestId);
        
        if ($request->completeService()) {
            $successMessage = "Request #$requestId has been marked as completed successfully!";
            $assignedRequests = $request->getActiveRequestsByMechanicId($mechanicId);
        } else {
            $errorMessage = "Failed to complete the request. Please try again.";
        }
    } elseif (isset($_POST['assign_random'])) {
        require_once '../../Models/mechanic.php';
        $mechanic = new Mechanic();
        $mechanic->getMechanicById($mechanicId);
        $mechanicLocation = $mechanic->getLocation();
        $pendingRequest = $request->getRandomPendingRequest($mechanicLocation);
        
        if ($pendingRequest) {
            $request->getRequestById($pendingRequest['id']);
            if ($request->assignMechanic($mechanicId)) {
                $successMessage = "You have been assigned to request #" . $pendingRequest['id'] . " successfully!";
                $assignedRequests = $request->getActiveRequestsByMechanicId($mechanicId);
            } else {
                $errorMessage = "Failed to assign the request. Please try again.";
            }
        } else {
            $errorMessage = "No pending requests available in your location at the moment.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>On-Road | Assign Request</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../../root/css/main.css">
</head>
<body>
    <div class="admin-section">
        <div class="admin-sidebar" id="adminSidebar">
            <div class="admin-profile">
                <div class="admin-avatar">
                    <img src="../../root/img/avatar.png" alt="Mechanic" onerror="this.src='https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['fullname']); ?>&background=ff6b6b&color=fff'">
                </div>
                <h5 class="admin-name"><?php echo $_SESSION['fullname']; ?></h5>
                <p class="admin-email"><?php echo $_SESSION['email']; ?></p>  
            </div>
            
            <ul class="sidebar-menu">
                <li>
                    <a href="../mechanicDashboard.php">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="#" class="active">
                        <i class="fas fa-file-alt"></i> Assign Request
                    </a>
                </li>
                <li>
                    <a href="../reports/mechanicReport.php">
                        <i class="fas fa-chart-bar"></i> Report
                    </a>
                </li>
                <li>
                    <a href="../profile.php">
                        <i class="fas fa-user-circle"></i> My Profile
                    </a>
                </li>
                <li>
                    <a href="../../Controllers/LogoutController.php">
                        <i class="fas fa-sign-out-alt"></i> Sign Out
                    </a>
                </li>
            </ul>
        </div>
        
        <?php include_once '../includes/navbar.php'; ?>
        
        <div class="admin-content-wrapper">
            <div class="admin-content">
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="../mechanicDashboard.php"><i class="fas fa-home"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Assign Request</li>
                    </ol>
                </nav>
                
                <h4 class="mb-4">Assign Request</h4>
                
                <?php if (!empty($successMessage)): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i> <?php echo $successMessage; ?>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($errorMessage)): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i> <?php echo $errorMessage; ?>
                    </div>
                <?php endif; ?>
                
                <?php if (empty($assignedRequests)): ?>
                    <div class="card mb-4">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-clipboard-list fa-4x mb-3 text-muted"></i>
                            <h5>You don't have any active requests assigned to you</h5>
                            <p class="text-muted">You can get assigned to a new request by clicking the button below.</p>
                            <form method="POST" action="">
                                <button type="submit" name="assign_random" class="btn btn-primary">
                                    <i class="fas fa-random me-2"></i> Get Assigned to a Request
                                </button>
                            </form>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Your Active Requests</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Request ID</th>
                                            <th>Client</th>
                                            <th>Description</th>
                                            <th>Location</th>
                                            <th>Status</th>
                                            <th>Created At</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($assignedRequests as $req): ?>
                                            <tr>
                                                <td>#<?php echo $req['id']; ?></td>
                                                <td><?php echo $req['clientName']; ?></td>
                                                <td><?php echo $req['description']; ?></td>
                                                <td><?php echo $req['location']; ?></td>
                                                <td>
                                                    <?php if ($req['status'] == 'assigned'): ?>
                                                        <span class="badge bg-info">Assigned</span>
                                                    <?php elseif ($req['status'] == 'in_progress'): ?>
                                                        <span class="badge bg-warning text-dark">In Progress</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?php echo date('M d, Y H:i', strtotime($req['createdAt'])); ?></td>
                                                <td>
                                                    <form method="POST" action="">
                                                        <input type="hidden" name="request_id" value="<?php echo $req['id']; ?>">
                                                        <button type="submit" name="complete_request" class="btn btn-sm btn-success">
                                                            <i class="fas fa-check me-1"></i> Mark as Completed
                                                        </button>
                                                    </form>
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
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../root/js/main.js"></script>
</body>
</html>