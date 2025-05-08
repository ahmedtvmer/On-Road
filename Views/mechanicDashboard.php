<?php
require_once '../Controllers/ValidationController.php';
ValidationController::validateSession('mechanic');
require_once '../Models/request.php';

$mechanicId = $_SESSION['user_id'];

$request = new Request();
$newRequestsCount = $request->getRequestsCountByMechanicAndStatus($mechanicId, 'assigned');
$inProgressRequestsCount = $request->getRequestsCountByMechanicAndStatus($mechanicId, 'in_progress');
$completedRequestsCount = $request->getCompletedRequestsCountByMechanic($mechanicId);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> ON-ROAD BREAKDOWN ASSISTANCE | Mechanic Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../root/css/main.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
</head>
<body>
    <div class="admin-section">
        <?php include_once 'includes/mechanicSidebar.php'; ?>
        
        <?php include_once 'includes/navbar.php'; ?>
        
        
        <div class="admin-content-wrapper">
            <div class="admin-content">
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                    </ol>
                </nav>
                
                <h4 class="mb-4">Dashboard</h4>
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="stat-card">
                            <div class="stat-label">Total New Request</div>
                            <div class="stat-value"><?php echo $newRequestsCount; ?></div>
                            <a href="requests/assignRequest.php" class="stat-link">View Detail</a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card">
                            <div class="stat-label">Inprogress Request</div>
                            <div class="stat-value"><?php echo $inProgressRequestsCount; ?></div>
                            <a href="requests/assignRequest.php" class="stat-link">View Detail</a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card">
                            <div class="stat-label">Completed Request</div>
                            <div class="stat-value"><?php echo $completedRequestsCount; ?></div>
                            <a href="reports/mechanicReport.php" class="stat-link">View Detail</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../root/js/main.js"></script>
</body>
</html>