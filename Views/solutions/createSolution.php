<?php
require_once '../../Controllers/ValidationController.php';
ValidationController::validateSession('mechanic');
require_once '../../Models/request.php';
require_once '../../Models/solution.php';

$errorMessage = '';
$successMessage = '';
$requestData = null;

if (isset($_GET['request_id']) && !empty($_GET['request_id'])) {
    $requestId = $_GET['request_id'];
    $request = new Request();
    
    if ($request->getRequestById($requestId)) {
        if ($request->getMechanicId() == $_SESSION['user_id']) {
            $requestData = [
                'id' => $request->getId(),
                'description' => $request->getDescription(),
                'location' => $request->getLocation(),
                'status' => $request->getStatus(),
                'clientName' => $request->getClientName()
            ];
            
            $solution = new Solution();
            if ($solution->getSolutionByRequestId($requestId)) {
                header("Location: editMechanicSolution.php?request_id=$requestId");
                exit;
            }
        } else {
            $errorMessage = "You don't have permission to add a solution to this request.";
        }
    } else {
        $errorMessage = "Request not found.";
    }
} else {
    $errorMessage = "Request ID is required.";
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create_solution'])) {
    $description = trim($_POST['description']);
    $requestId = $_POST['request_id'];
    
    if (empty($description)) {
        $errorMessage = "Solution description is required.";
    } else {
        $solution = new Solution();
        $solution->setRequestId($requestId);
        $solution->setDescription($description);
        
        if ($solution->createSolution()) {
            $successMessage = "Solution created successfully!";
            header("Refresh: 2; URL=assignRequest.php");
        } else {
            $errorMessage = "Failed to create solution. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ON-ROAD BREAKDOWN ASSISTANCE | Create Solution</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../../root/css/main.css">
</head>
<body>
    <div class="admin-section">
        <?php include_once '../includes/mechanicSidebar.php';?>
        
        <?php include_once '../includes/navbar.php'; ?>
        
        <div class="admin-content-wrapper">
            <div class="admin-content">
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="../mechanicDashboard.php"><i class="fas fa-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="assignRequest.php">Assign Request</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Create Solution</li>
                    </ol>
                </nav>
                
                <h4 class="mb-4">Create Solution</h4>
                
                <?php if (!empty($errorMessage)): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i> <?php echo $errorMessage; ?>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($successMessage)): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i> <?php echo $successMessage; ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($requestData): ?>
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Request Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Request ID:</strong> #<?php echo $requestData['id']; ?></p>
                                    <p><strong>Client:</strong> <?php echo $requestData['clientName']; ?></p>
                                    <p><strong>Location:</strong> <?php echo $requestData['location']; ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Status:</strong> 
                                        <?php if ($requestData['status'] == 'assigned'): ?>
                                            <span class="badge bg-info">Assigned</span>
                                        <?php elseif ($requestData['status'] == 'in_progress'): ?>
                                            <span class="badge bg-warning text-dark">In Progress</span>
                                        <?php endif; ?>
                                    </p>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <p><strong>Description:</strong></p>
                                    <div class="p-3 bg-light rounded">
                                        <?php echo nl2br($requestData['description']); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">Create Solution</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="">
                                <input type="hidden" name="request_id" value="<?php echo $requestData['id']; ?>">
                                
                                <div class="mb-3">
                                    <label for="description" class="form-label">Solution Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="6" required></textarea>
                                    <div class="form-text">Provide a detailed description of the solution for this breakdown request.</div>
                                </div>
                                
                                <div class="d-flex gap-2">
                                    <button type="submit" name="create_solution" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i> Save Solution
                                    </button>
                                    <a href="assignRequest.php" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-2"></i> Cancel
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-exclamation-triangle fa-4x text-warning mb-3"></i>
                        <h5>Request not found</h5>
                        <p class="text-muted">The request you're trying to add a solution for doesn't exist or you don't have permission to access it.</p>
                        <a href="assignRequest.php" class="btn btn-primary mt-3">
                            <i class="fas fa-arrow-left me-2"></i> Back to Requests
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../root/js/main.js"></script>
</body>
</html>