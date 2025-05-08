<?php
require_once '../../Controllers/ValidationController.php';
ValidationController::validateSession('admin');
require_once '../../Models/request.php';
require_once '../../Models/solution.php';
require_once '../../Models/feedback.php';
require_once '../../Models/client.php';
require_once '../../Models/mechanic.php';

// Check if request ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: adminReports.php");
    exit;
}

$requestId = $_GET['id'];

// Initialize models
$request = new Request();
$solution = new Solution();
$feedback = new Feedback();
$client = new Client();
$mechanic = new Mechanic();

// Get request details
$requestResult = $request->getRequestById($requestId);

// If request not found, redirect back
if (!$requestResult) {
    header("Location: adminReports.php");
    exit;
}

// Get client details
$clientResult = $client->getClientById($request->getClientId());

// Get mechanic details if assigned
$mechanicResult = null;
if (!empty($request->getMechanicId())) {
    $mechanicResult = $mechanic->getMechanicById($request->getMechanicId());
}

// Get solution if exists
$solutionResult = $solution->getSolutionByRequestId($requestId);

// Get feedback if exists
$feedbackResult = $feedback->getFeedbackByRequestId($requestId);

// Generate current date for the report
$reportDate = date('F d, Y');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Report #<?php echo $requestId; ?> - ON-ROAD BREAKDOWN ASSISTANCE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }
        .report-container {
            max-width: 800px;
            margin: 20px auto;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 30px;
        }
        .report-header {
            text-align: center;
            border-bottom: 2px solid #ff6b6b;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .report-title {
            font-size: 24px;
            font-weight: bold;
            color: #ff6b6b;
            margin-bottom: 5px;
        }
        .report-subtitle {
            font-size: 16px;
            color: #666;
        }
        .report-date {
            font-size: 14px;
            color: #888;
            margin-top: 10px;
        }
        .section-title {
            font-size: 18px;
            font-weight: bold;
            color: #ff6b6b;
            margin: 25px 0 15px 0;
            padding-bottom: 5px;
            border-bottom: 1px solid #eee;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .info-table td {
            padding: 8px 12px;
            border: 1px solid #ddd;
        }
        .info-table td:first-child {
            width: 30%;
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .badge {
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: normal;
        }
        .badge-pending {
            background-color: #ffc107;
            color: #212529;
        }
        .badge-assigned {
            background-color: #17a2b8;
            color: #fff;
        }
        .badge-completed {
            background-color: #28a745;
            color: #fff;
        }
        .badge-cancelled {
            background-color: #dc3545;
            color: #fff;
        }
        .rating {
            color: #ffc107;
        }
        .print-button {
            background-color: #ff6b6b;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            margin-top: 20px;
        }
        .print-button:hover {
            background-color: #ff5252;
        }
        @media print {
            body {
                background-color: #fff;
            }
            .report-container {
                box-shadow: none;
                margin: 0;
                padding: 15px;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="report-container">
        <div class="report-header">
            <div class="report-title">ON-ROAD BREAKDOWN ASSISTANCE</div>
            <div class="report-subtitle">Request Report #<?php echo $requestId; ?></div>
            <div class="report-date">Generated on: <?php echo $reportDate; ?></div>
        </div>
        
        <div class="section-title">Request Information</div>
        <table class="info-table">
            <tr>
                <td>Request ID:</td>
                <td><?php echo $request->getId(); ?></td>
            </tr>
            <tr>
                <td>Status:</td>
                <td>
                    <?php 
                    $status = $request->getStatus();
                    $statusClass = '';
                    switch($status) {
                        case 'pending': $statusClass = 'badge-pending'; break;
                        case 'assigned': $statusClass = 'badge-assigned'; break;
                        case 'in_progress': $statusClass = 'badge-assigned'; break;
                        case 'completed': $statusClass = 'badge-completed'; break;
                        case 'cancelled': $statusClass = 'badge-cancelled'; break;
                    }
                    ?>
                    <span class="badge <?php echo $statusClass; ?>"><?php echo ucfirst($status); ?></span>
                </td>
            </tr>
            <tr>
                <td>Created At:</td>
                <td><?php echo date('F d, Y h:i A', strtotime($request->getCreatedAt())); ?></td>
            </tr>
            <?php if ($request->getCompletedAt() && $request->getCompletedAt() != '0000-00-00 00:00:00'): ?>
            <tr>
                <td>Completed At:</td>
                <td><?php echo date('F d, Y h:i A', strtotime($request->getCompletedAt())); ?></td>
            </tr>
            <?php endif; ?>
            <tr>
                <td>Location:</td>
                <td><?php echo $request->getLocation(); ?></td>
            </tr>
            <tr>
                <td>Description:</td>
                <td><?php echo nl2br($request->getDescription()); ?></td>
            </tr>
        </table>
        
        <div class="section-title">Client Information</div>
        <table class="info-table">
            <tr>
                <td>Client ID:</td>
                <td><?php echo $client->getId(); ?></td>
            </tr>
            <tr>
                <td>Name:</td>
                <td><?php echo $client->getFullName(); ?></td>
            </tr>
            <tr>
                <td>Email:</td>
                <td><?php echo $client->getEmail(); ?></td>
            </tr>
        </table>
        
        <?php if ($mechanicResult && $mechanic->getId()): ?>
        <div class="section-title">Mechanic Information</div>
        <table class="info-table">
            <tr>
                <td>Mechanic ID:</td>
                <td><?php echo $mechanic->getId(); ?></td>
            </tr>
            <tr>
                <td>Name:</td>
                <td><?php echo $mechanic->getFullName(); ?></td>
            </tr>
            <tr>
                <td>Email:</td>
                <td><?php echo $mechanic->getEmail(); ?></td>
            </tr>
            <tr>
                <td>Rating:</td>
                <td>
                    <?php 
                    $rating = $mechanic->getRating();
                    echo number_format($rating, 1) . ' / 5.0';
                    ?>
                    <div class="rating">
                        <?php 
                        for ($i = 1; $i <= 5; $i++) {
                            if ($i <= $rating) {
                                echo '★';
                            } elseif ($i - 0.5 <= $rating) {
                                echo '★';
                            } else {
                                echo '☆';
                            }
                        }
                        ?>
                    </div>
                </td>
            </tr>
        </table>
        <?php endif; ?>
        
        <?php if ($solutionResult): ?>
        <div class="section-title">Solution Information</div>
        <table class="info-table">
            <tr>
                <td>Solution:</td>
                <td><?php echo nl2br($solution->getDescription() ?? 'N/A'); ?></td>
            </tr>
        </table>
        <?php endif; ?>
        
        <?php if ($feedbackResult): ?>
        <div class="section-title">Client Feedback</div>
        <table class="info-table">
            <tr>
                <td>Cost Rating:</td>
                <td>
                    <?php echo $feedback->getCostRating(); ?> / 5
                    <div class="rating">
                        <?php 
                        for ($i = 1; $i <= 5; $i++) {
                            echo ($i <= $feedback->getCostRating()) ? '★' : '☆';
                        }
                        ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td>Service Rating:</td>
                <td>
                    <?php echo $feedback->getServiceRating(); ?> / 5
                    <div class="rating">
                        <?php 
                        for ($i = 1; $i <= 5; $i++) {
                            echo ($i <= $feedback->getServiceRating()) ? '★' : '☆';
                        }
                        ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td>Average Rating:</td>
                <td>
                    <?php 
                    $avgRating = ($feedback->getCostRating() + $feedback->getServiceRating()) / 2;
                    echo number_format($avgRating, 1); ?> / 5
                    <div class="rating">
                        <?php 
                        for ($i = 1; $i <= 5; $i++) {
                            if ($i <= $avgRating) {
                                echo '★';
                            } elseif ($i - 0.5 <= $avgRating) {
                                echo '★';
                            } else {
                                echo '☆';
                            }
                        }
                        ?>
                    </div>
                </td>
            </tr>
        </table>
        <?php endif; ?>
        
        <div class="text-center no-print">
            <button class="print-button" onclick="window.print()">
                <i class="fas fa-print"></i> Print Report
            </button>
            <a href="adminReports.php" class="btn btn-secondary mt-3">
                <i class="fas fa-arrow-left"></i> Back to Reports
            </a>
        </div>
    </div>
</body>
</html>