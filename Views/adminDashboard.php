<?php
require_once '../Controllers/ValidationController.php';
ValidationController::validateSession('admin');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AUTODOC BREAKDOWN ASSISTANCE | Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/main.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
</head>
<body>
    <div class="admin-section">
        <div class="admin-sidebar" id="adminSidebar">
            <div class="admin-profile">
                <div class="admin-avatar">
                    <img src="img/avatar.png" alt="Admin" onerror="this.src='https://ui-avatars.com/api/?name=Autodoc+Admin&background=ff6b6b&color=fff'">
                </div>
                <h5 class="admin-name">Admin</h5>
                <p class="admin-email">admin@gmail.com</p>  
            </div>
            
            <ul class="sidebar-menu">
                <li>
                    <a href="#" class="active">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="fas fa-user"></i> Driver
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="fas fa-file-alt"></i> Request
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="fas fa-reply"></i> Mechanic Response
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="fas fa-search"></i> Search
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="fas fa-chart-bar"></i> Report
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="fas fa-user-circle"></i> My Profile
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="fas fa-sign-out-alt"></i> Sign Out
                    </a>
                </li>
            </ul>
        </div>
        
        <div class="admin-header-container">
            <div class="admin-header">
                <button class="toggle-btn" id="toggleSidebar">
                    <i class="fas fa-bars"></i>
                </button>
                <h6 class="m-0">ON-ROAD BREAKDOWN ASSISTANCE</h6>
                <div class="user-actions">
                    <a href="#" class="text-white">
                        <i class="fas fa-user-circle fs-4"></i>
                    </a>
                </div>
            </div>
        </div>
        
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
                            <div class="stat-value">1</div>
                            <a href="#" class="stat-link">View Detail</a>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <div class="stat-label">Approved Requests</div>
                            <div class="stat-value">1</div>
                            <a href="#" class="stat-link">View Detail</a>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <div class="stat-label">Rejected Requests</div>
                            <div class="stat-value">0</div>
                            <a href="#" class="stat-link">View Detail</a>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <div class="stat-label">Driver on the way</div>
                            <div class="stat-value">0</div>
                            <a href="#" class="stat-link">View Detail</a>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-3">
                        <div class="stat-card">
                            <div class="stat-label">Completed Requests</div>
                            <div class="stat-value">1</div>
                            <a href="#" class="stat-link">View Detail</a>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <div class="stat-label">Total Drivers</div>
                            <div class="stat-value">6</div>
                            <a href="#" class="stat-link">View Detail</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/main.js"></script>
</body>
</html>