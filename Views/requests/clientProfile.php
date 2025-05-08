<?php
require_once '../../Controllers/ValidationController.php';
if($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'mechanic')
    ValidationController::validateSession($_SESSION['role']);
else
    ValidationController::validateSession('admin');
require_once '../../Models/client.php';
require_once '../../Models/request.php';

$errorMessage = '';
$client = null;
$requestCount = 0;

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $clientId = $_GET['id'];
    $client = new Client();
    
    if (!$client->getClientById($clientId)) {
        $errorMessage = "Client not found.";
    } else {
        $request = new Request();
        $clientRequests = $request->getAllRequestsByClientId($clientId);
        $requestCount = count($clientRequests);
    }
} else {
    $errorMessage = "Client ID is required.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>On-Road | Client Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../../root/css/main.css">
</head>
<body>
    <?php include_once '../includes/navbar.php'; ?>
    
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <?php if (!empty($errorMessage)): ?>
                        <div class="alert alert-danger text-center">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <?php echo $errorMessage; ?>
                            <div class="mt-3">
                                <a href="../reports/adminReports.php" class="btn btn-primary">Back to Reports</a>
                            </div>
                        </div>
                    <?php elseif ($client): ?>
                        <div class="card shadow">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">Client Profile</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 text-center mb-4 mb-md-0">
                                        <img src="../../root/img/Avatar.png" alt="Client Avatar" class="rounded-circle img-fluid" style="width: 150px;">
                                    </div>
                                    <div class="col-md-8">
                                        <h4 class="mb-3"><?php echo $client->getFullName(); ?></h4>
                                        <div class="mb-3">
                                            <h5 class="mb-2">Contact Information</h5>
                                            <p class="mb-1"><i class="fas fa-envelope me-2"></i> <?php echo $client->getEmail(); ?></p>
                                            <p class="mb-1"><i class="fas fa-user me-2"></i> @<?php echo $client->getUsername(); ?></p>
                                        </div>
                                    </div>
                                </div>
                                
                                <hr>
                                
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <h5 class="mb-3">Client Statistics</h5>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="card bg-light">
                                                    <div class="card-body text-center">
                                                        <p class="mb-0">Total Requests</p>
                                                        <h1 class="display-4 text-primary"><?php echo $requestCount; ?></h1>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="card bg-light">
                                                    <div class="card-body text-center">
                                                    <h6 class="mb-2">Client ID</h6>
                                                    <h1 class="display-4 text-primary">#<?php echo $client->getId(); ?></h1>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <?php if ($_SESSION['role'] == 'admin'): ?>
                                <hr>
                                
                                <div class="text-center mt-3">
                                    <a href="../reports/adminReports.php" class="btn btn-primary">
                                        <i class="fas fa-arrow-left me-2"></i> Back to Reports
                                    </a>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../root/js/main.js"></script>
</body>
</html>