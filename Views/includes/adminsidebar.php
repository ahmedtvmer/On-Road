<?php
$basePath = '';
if (strpos($_SERVER['PHP_SELF'], '/Views/requests/') !== false || 
    strpos($_SERVER['PHP_SELF'], '/Views/reports/') !== false) {
    $basePath = '../';
} 
?>

<div class="admin-sidebar" id="adminSidebar">
    <div class="admin-profile">
        <div class="admin-avatar">
            <img src="<?php echo $basePath; ?>../root/img/avatar.png" alt="Admin" onerror="this.src='https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['fullname']); ?>&background=ff6b6b&color=fff'">
        </div>
        <h5 class="admin-name"><?php echo $_SESSION['fullname']; ?></h5>
        <p class="admin-email"><?php echo $_SESSION['email']; ?></p>  
    </div>
    
    <ul class="sidebar-menu">
        <li>
            <a href="<?php echo $basePath; ?>adminDashboard.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'adminDashboard.php') ? 'class="active"' : ''; ?>>
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="<?php echo $basePath; ?>mechanics.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'mechanics.php') ? 'class="active"' : ''; ?>>
                <i class="fas fa-user"></i> Mechanics
            </a>
        </li>
        <li>
            <a href="<?php echo $basePath; ?>requests/adminRequests.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'adminRequests.php') ? 'class="active"' : ''; ?>>   
                <i class="fas fa-file-alt"></i> Request
            </a>
        </li>
        <li>
            <a href="<?php echo $basePath; ?>reports/adminReports.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'adminReports.php') ? 'class="active"' : ''; ?>>
                <i class="fas fa-chart-bar"></i> Reports
            </a>
        </li>
        <li>
            <a href="<?php echo $basePath; ?>profile.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'profile.php') ? 'class="active"' : ''; ?>>
                <i class="fas fa-user-circle"></i> My Profile
            </a>
        </li>
        <li>
            <a href="<?php echo $basePath; ?>../Controllers/LogoutController.php">
                <i class="fas fa-sign-out-alt"></i> Sign Out
            </a>
        </li>
    </ul>
</div>