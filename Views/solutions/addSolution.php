<?php
require_once '../../Controllers/ValidationController.php';
ValidationController::validateSession('admin');
require_once '../../Models/request.php';
require_once '../../Models/solution.php';

$errorMessage = '';
$successMessage = '';
$requestData = null;

if (isset($_GET['request_id']) && !empty($_GET['request_id'])) {
    $requestId = $_GET['request_id'];
    $request = new Request();
    
    if ($request->getRequestById($requestId)) {
        $requestData = [
            'id' => $request->getId(),
            'client_id' => $request->getClientId(),
            'mechanic_id' => $request->getMechanicId(),
            'description' => $request->getDescription(),
            'location' => $request->getLocation(),
            'status' => $request->getStatus()
        ];
        
        $solution = new Solution("", $requestId, "");
        if ($solution->checkSolutionExists($requestId)) {
            header("Location: viewSolution.php?request_id=" . $requestId);
            exit();
        }
    } else {
        $errorMessage = "Request not found.";
    }
} else {
    $errorMessage = "Request ID is required.";
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $description = $_POST['description'];
    $requestId = $_POST['request_id'];
    
    $solution = new Solution("", $requestId, $description);
    
    if ($solution->createSolution()) {
        $successMessage = "Solution has been added successfully.";
    } else {
        $errorMessage = "Failed to add solution. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ON-ROAD BREAKDOWN ASSISTANCE | Add Solution</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../../root/css/main.css">
</head>
<body>
    <div class="admin-section">
        <?php include_once '../includes/adminSidebar.php';?>
        
        <?php include_once '../includes/navbar.php'; ?>
        
        <div class="admin-content-wrapper">
            <div class="admin-content">
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="../adminDashboard.php"><i class="fas fa-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="adminRequests.php">Requests</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add Solution</li>
                    </ol>
                </nav>
                
                <h4 class="mb-4">Add Solution for Request #<?php echo isset($requestData['id']) ? $requestData['id'] : ''; ?></h4>
                
                <?php if (!empty($errorMessage)): ?>
                    <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
                <?php endif; ?>
                
                <?php if (!empty($successMessage)): ?>
                    <div class="alert alert-success">
                        <?php echo $successMessage; ?>
                        <div class="mt-3">
                            <a href="adminRequests.php" class="btn btn-primary">Back to Requests</a>
                        </div>
                    </div>
                <?php else: ?>
                    <?php if ($requestData): ?>
                        <div class="card mb-4">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">Request Details</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Request ID:</strong> <?php echo $requestData['id']; ?></p>
                                        <p><strong>Client ID:</strong> <?php echo $requestData['client_id']; ?></p>
                                        <p><strong>Mechanic ID:</strong> <?php echo $requestData['mechanic_id'] ? $requestData['mechanic_id'] : 'Not Assigned'; ?></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Status:</strong> <?php echo ucfirst($requestData['status']); ?></p>
                                        <p><strong>Location:</strong> <?php echo $requestData['location']; ?></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <p><strong>Description:</strong></p>
                                        <p><?php echo $requestData['description']; ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">Add Solution</h5>
                            </div>
                            <div class="card-body">
                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                    <input type="hidden" name="request_id" value="<?php echo $requestData['id']; ?>">
                                    
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Solution Description</label>
                                        <textarea class="form-control" id="description" name="description" rows="5" required></textarea>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between">
                                        <a href="adminRequests.php" class="btn btn-secondary">Cancel</a>
                                        <button type="submit" name="submit" class="btn btn-primary">Submit Solution</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../root/js/main.js"></script>
</body>
</html>