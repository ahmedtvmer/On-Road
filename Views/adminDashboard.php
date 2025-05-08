<?php
require_once '../Controllers/ValidationController.php';
ValidationController::validateSession('admin');

require_once '../Models/request.php';
require_once '../Models/mechanic.php';
require_once '../Models/client.php';

$request = new Request();
$mechanic = new Mechanic();
$client = new Client();

$newRequests = $request->getRequestCountByStatus('pending');
$inProgressRequests = $request->getRequestCountByStatus('assigned');
$completedRequests = $request->getRequestCountByStatus('completed');
$totalMechanics = $mechanic->getMechanicCount();
$totalClients = $client->getClientCount();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> ON-ROAD BREAKDOWN ASSISTANCE | Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../root/css/main.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
</head>
<body>
    <div class="admin-section">
        <?php include_once 'includes/adminsidebar.php'; ?>
        
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
                    <div class="col-md-3">
                        <div class="stat-card">
                            <div class="stat-label">New Requests</div>
                            <div class="stat-value"><?php echo $newRequests; ?></div>
                            <a href="requests/adminRequests.php?status=pending" class="stat-link">View Detail</a>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <div class="stat-label">Driver on the way</div>
                            <div class="stat-value"><?php echo $inProgressRequests; ?></div>
                            <a href="requests/adminRequests.php?status=assigned" class="stat-link">View Detail</a>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <div class="stat-label">Completed Requests</div>
                            <div class="stat-value"><?php echo $completedRequests; ?></div>
                            <a href="requests/adminRequests.php?status=completed" class="stat-link">View Detail</a>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <div class="stat-label">Total Mechanics</div>
                            <div class="stat-value"><?php echo $totalMechanics; ?></div>
                            <a href="mechanics.php" class="stat-link">View Detail</a>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-4">
                    <div class="col-md-3">
                        <div class="stat-card">
                            <div class="stat-label">Total Clients</div>
                            <div class="stat-value"><?php echo $totalClients; ?></div>
                            <a href="clients.php" class="stat-link">View Detail</a>
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