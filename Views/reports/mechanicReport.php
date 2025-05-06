<?php
require_once '../../Controllers/ValidationController.php';
ValidationController::validateSession('mechanic');
require_once '../../Models/request.php';
require_once '../../Models/mechanic.php';

// Get mechanic ID from session
$mechanicId = $_SESSION['user_id'];

// Create Mechanic object
$mechanic = new Mechanic();
$mechanic->getMechanicById($mechanicId);

// Create Request object
$request = new Request();

// Get all requests assigned to this mechanic (both active and completed)
$allRequests = $request->getAllRequestsByMechanicId($mechanicId);

// Get statistics
$totalRequests = count($allRequests);
$completedRequests = $request->getCompletedRequestsCountByMechanic($mechanicId);
$pendingRequests = $request->getRequestsCountByMechanicAndStatus($mechanicId, 'assigned');
$inProgressRequests = $request->getRequestsCountByMechanicAndStatus($mechanicId, 'in_progress');

// Calculate completion rate
$completionRate = ($totalRequests > 0) ? round(($completedRequests / $totalRequests) * 100) : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AUTODOC BREAKDOWN ASSISTANCE | Mechanic Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../../root/css/main.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
                    <a href="../requests/assignRequest.php">
                        <i class="fas fa-file-alt"></i> Assign Request
                    </a>
                </li>
                <li>
                    <a href="#" class="active">
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
                        <li class="breadcrumb-item active" aria-current="page">Mechanic Report</li>
                    </ol>
                </nav>
                
                <h4 class="mb-4">Mechanic Performance Report</h4>
                
                <!-- Mechanic Profile Card -->
                <div class="card shadow mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Mechanic Profile</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2 text-center">
                                <img src="../../root/img/avatar.png" alt="Mechanic" class="img-fluid rounded-circle mb-3" style="width: 120px; height: 120px;" onerror="this.src='https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['fullname']); ?>&background=ff6b6b&color=fff'">
                            </div>
                            <div class="col-md-5">
                                <h5><?php echo $mechanic->getFullName(); ?></h5>
                                <p><i class="fas fa-envelope me-2"></i> <?php echo $mechanic->getEmail(); ?></p>
                            </div>
                            <div class="col-md-5">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">Performance Metrics</h6>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Total Requests:</span>
                                            <strong><?php echo $totalRequests; ?></strong>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Completed Requests:</span>
                                            <strong><?php echo $completedRequests; ?></strong>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Completion Rate:</span>
                                            <strong><?php echo $completionRate; ?>%</strong>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Rating:</span>
                                            <strong><?php echo number_format($mechanic->getRating(), 1); ?> / 5</strong>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Total Reviews:</span>
                                            <strong><?php echo $mechanic->getTotalReviews(); ?></strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="stat-card">
                            <div class="stat-label">Pending Requests</div>
                            <div class="stat-value"><?php echo $pendingRequests; ?></div>
                            <div class="stat-icon bg-warning"><i class="fas fa-clock"></i></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card">
                            <div class="stat-label">In Progress</div>
                            <div class="stat-value"><?php echo $inProgressRequests; ?></div>
                            <div class="stat-icon bg-info"><i class="fas fa-tools"></i></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card">
                            <div class="stat-label">Completed</div>
                            <div class="stat-value"><?php echo $completedRequests; ?></div>
                            <div class="stat-icon bg-success"><i class="fas fa-check-circle"></i></div>
                        </div>
                    </div>
                </div>
                
                <!-- Request History Table -->
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Request History</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($allRequests)): ?>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i> You don't have any requests assigned to you yet.
                            </div>
                        <?php else: ?>
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
                                            <th>Completed At</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($allRequests as $req): ?>
                                            <tr>
                                                <td>#<?php echo $req['id']; ?></td>
                                                <td><?php echo $req['clientName'] ?? 'Unknown'; ?></td>
                                                <td><?php echo $req['description']; ?></td>
                                                <td><?php echo $req['location']; ?></td>
                                                <td>
                                                    <?php if ($req['status'] == 'assigned'): ?>
                                                        <span class="badge bg-warning text-dark">Pending</span>
                                                    <?php elseif ($req['status'] == 'in_progress'): ?>
                                                        <span class="badge bg-info">In Progress</span>
                                                    <?php elseif ($req['status'] == 'completed'): ?>
                                                        <span class="badge bg-success">Completed</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?php echo date('M d, Y H:i', strtotime($req['createdAt'])); ?></td>
                                                <td>
                                                    <?php if (!empty($req['completedAt']) && $req['completedAt'] != '0000-00-00 00:00:00'): ?>
                                                        <?php echo date('M d, Y H:i', strtotime($req['completedAt'])); ?>
                                                    <?php else: ?>
                                                        <span class="text-muted">Not completed</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <a href="../requests/requestDetails.php?id=<?php echo $req['id']; ?>" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye"></i> View
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../root/js/main.js"></script>
</body>
</html>