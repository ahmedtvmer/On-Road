<?php
require_once '../Models/admin.php';
require_once '../Models/client.php';
require_once '../Models/mechanic.php';

class LoginController {
    private $error = "";
    
    public function __construct()
    {
        session_start();
        
        
        if (isset($_GET['registered']) && $_GET['registered'] === 'true') {
            $role = isset($_GET['role']) ? $_GET['role'] : '';
            $this->error = "Registration successful! Please login with your new credentials.";
            return;
        }
    }
    
    public function processLogin()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $username = $_POST['username'];
            $password = $_POST['password'];
            
            $admin = new Admin();
            if ($admin->login($username, $password)) {
                $_SESSION['user_id'] = $admin->getId();
                $_SESSION['username'] = $admin->getUsername();
                $_SESSION['fullname'] = $admin->getFullName();
                $_SESSION['email'] = $admin->getEmail();
                $_SESSION['role'] = Admin::ROLE;
                
                
                header("Location: ../Views/adminDashboard.php");
                exit();
            }
            
            $mechanic = new Mechanic();
            if ($mechanic->login($username, $password)) {
                $_SESSION['user_id'] = $mechanic->getId();
                $_SESSION['username'] = $mechanic->getUsername();
                $_SESSION['fullname'] = $mechanic->getFullName();
                $_SESSION['email'] = $mechanic->getEmail();
                $_SESSION['role'] = Mechanic::ROLE;
                
                
                header("Location: ../Views/mechanicDashboard.php");
                exit();
            }
            
            $client = new Client();
            if ($client->login($username, $password)) {
                $_SESSION['user_id'] = $client->getId();
                $_SESSION['username'] = $client->getUsername();
                $_SESSION['fullname'] = $client->getFullName();
                $_SESSION['email'] = $client->getEmail();
                $_SESSION['role'] = Client::ROLE;
                
                
                header("Location: ../Views/home.php");
                exit();
            }
            
            $this->error = "Invalid username or password.";
            
        }
    }
    
    public function getError() {
        return $this->error;
    }
    
    public function getRole() {
        if (isset($_SESSION['role'])) {
            return $_SESSION['role'];
        }
        return null;
    }
}
?>