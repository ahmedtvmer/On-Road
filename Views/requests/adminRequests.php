<?php
require_once '../../Controllers/ValidationController.php';
ValidationController::validateSession('admin');
require_once '../../Models/request.php';
require_once '../../Models/solution.php';

$request = new Request();
$solution = new Solution();
$allRequests = $request->getAllRequests();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ON-ROAD BREAKDOWN ASSISTANCE | Requests</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../../root/css/main.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="admin-section">
        <?php include_once '../includes/adminsidebar.php'; ?>
        
        <?php include_once '../includes/navbar.php'; ?>
        
        <div class="admin-content-wrapper">
            <div class="admin-content">
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="../adminDashboard.php"><i class="fas fa-home"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Requests</li>
                    </ol>
                </nav>
                
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4>All Requests</h4>
                </div>
                
                <?php if (empty($allRequests)): ?>
                    <div class="alert alert-info">No requests found.</div>
                <?php else: ?>
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
                                    <th>Date</th>
                                    <th>Solution</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($allRequests as $req): ?>
                                    <tr>
                                        <td><?php echo $req['id']; ?></td>
                                        <td><?php echo $req['client_id']; ?></td>
                                        <td><?php echo $req['mechanic_id'] ? $req['mechanic_id'] : 'Not Assigned'; ?></td>
                                        <td><?php echo $req['description']; ?></td>
                                        <td><?php echo $req['location']; ?></td>
                                        <td>
                                            <span class="badge <?php 
                                                switch($req['status']) {
                                                    case 'pending': echo 'bg-warning'; break;
                                                    case 'approved': echo 'bg-success'; break;
                                                    case 'rejected': echo 'bg-danger'; break;
                                                    case 'completed': echo 'bg-info'; break;
                                                    default: echo 'bg-secondary';
                                                }
                                            ?>">
                                                <?php echo ucfirst($req['status']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('M d, Y', strtotime($req['createdAt'])); ?></td>
                                        <td>
                                            <?php if ($solution->checkSolutionExists($req['id'])): ?>
                                                <span class="badge bg-success">Provided</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Not Provided</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if (!$solution->checkSolutionExists($req['id'])): ?>
                                                <a href="../solutions/addSolution.php?request_id=<?php echo $req['id']; ?>" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-plus"></i> Add Solution
                                                </a>
                                            <?php else: ?>
                                                <a href="../solutions/viewSolution.php?request_id=<?php echo $req['id']; ?>" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i> View Solution
                                                </a>
                                            <?php endif; ?>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../root/js/main.js"></script>
</body>
</html>