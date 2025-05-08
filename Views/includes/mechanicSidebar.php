<?php
// Determine the base path based on the current file location
$basePath = '';
// Check if we're in a subdirectory of Views
if (strpos($_SERVER['PHP_SELF'], '/Views/') !== false) {
    $pathParts = explode('/Views/', $_SERVER['PHP_SELF']);
    if (isset($pathParts[1])) {
        $subfolderDepth = substr_count($pathParts[1], '/');
        $basePath = str_repeat('../', $subfolderDepth);
    }
}

// Root path for assets
$rootPath = $basePath . '../';
?>

<div class="admin-sidebar" id="adminSidebar">
    <div class="admin-profile">
        <div class="admin-avatar">
            <img src="<?php echo $rootPath; ?>root/img/avatar.png" alt="Mechanic" onerror="this.src='https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['fullname']); ?>&background=ff6b6b&color=fff'">
        </div>
        <h5 class="admin-name"><?php echo $_SESSION['fullname']; ?></h5>
        <p class="admin-email"><?php echo $_SESSION['email']; ?></p>  
    </div>
    
    <ul class="sidebar-menu">
        <li>
            <a href="<?php echo $basePath; ?>mechanicDashboard.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'mechanicDashboard.php') ? 'class="active"' : ''; ?>>
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="<?php echo $basePath; ?>requests/assignRequest.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'assignRequest.php') ? 'class="active"' : ''; ?>>
                <i class="fas fa-file-alt"></i> Assign Request
            </a>
        </li>
        <li>
            <a href="<?php echo $basePath; ?>reports/mechanicReport.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'mechanicReport.php') ? 'class="active"' : ''; ?>>
                <i class="fas fa-chart-bar"></i> Report
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