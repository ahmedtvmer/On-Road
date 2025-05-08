<?php
$isLoggedIn = isset($_SESSION['user_id']);
$username = $isLoggedIn ? $_SESSION['username'] : '';
$userRole = $isLoggedIn ? $_SESSION['role'] : '';

// Determine the base path based on the current file location
$currentPath = $_SERVER['PHP_SELF'];
$rootPath = '';

// Check if we're in a subdirectory of Views
if (strpos($currentPath, '/Views/') !== false) {
    $subfolderCount = substr_count(substr($currentPath, strpos($currentPath, '/Views/') + 7), '/');
    $rootPath = str_repeat('../', $subfolderCount);
}

// Determine the home page based on user role
$homePage = '../index.php';
if ($isLoggedIn) {
    if ($userRole == 'admin') {
        $homePage = 'adminDashboard.php';
    } elseif ($userRole == 'mechanic') {
        $homePage = 'mechanicDashboard.php';
    } else {
        $homePage = 'home.php';
    }
}
?>

<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #ff6b6b;">
    <div class="container">
        <a class="navbar-brand" href="<?php echo $rootPath . $homePage; ?>">ON-ROAD BREAKDOWN ASSISTANCE</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <?php if ($isLoggedIn && $userRole == 'client'): ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $rootPath . 'requests/myRequests.php'; ?>">My Requests</a>
                </li>
                <?php endif; ?>
            </ul>
            <?php if ($isLoggedIn): ?>
            <div class="d-flex align-items-center">
                <div class="dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="avatar-circle me-2">
                            <img src="<?php echo $rootPath; ?>../root/img/Avatar.png" alt="Avatar" class="rounded-circle img-fluid border border-light" width="40" height="40">
                        </div>
                        <span class="text-white"><?php echo $username; ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="<?php echo $rootPath; ?>profile.php">Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?php echo $rootPath; ?>../Controllers/LogoutController.php">Sign Out</a></li>
                    </ul>
                </div>
            </div>
            <?php else: ?>
            <div class="d-flex">
                <a href="<?php echo $rootPath; ?>login.php" class="btn btn-outline-light me-2">Login</a>
                <a href="<?php echo $rootPath; ?>../index.php" class="btn btn-light">Register</a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</nav>