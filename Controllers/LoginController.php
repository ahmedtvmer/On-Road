<?php
session_start();
require_once '../Models/admin.php';
require_once '../Models/client.php';
require_once '../Models/mechanic.php';

class LoginController {
    private $error = "";
    private $locked = false;
    
    public function __construct() {
        if (!isset($_SESSION['login_attempts'])) {
            $_SESSION['login_attempts'] = 0;
        }
        
        if (isset($_SESSION['login_attempts']) && $_SESSION['login_attempts'] >= 3) {
            $this->locked = true;
            $this->error = "Too many failed login attempts. Please try again later.";
        }
    }
    
    public function processLogin() {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && !$this->locked) {
            $username = $_POST['username'];
            $password = $_POST['password'];
            
            $admin = new Admin();
            if ($admin->login($username, $password)) {
                $_SESSION['user_id'] = $admin->getId();
                $_SESSION['username'] = $admin->getUsername();
                $_SESSION['fullname'] = $admin->getFullName();
                $_SESSION['email'] = $admin->getEmail();
                $_SESSION['role'] = Admin::ROLE;
                
                $_SESSION['login_attempts'] = 0;
                
                header("Location: adminDashboard.php");
                exit();
            }
            
            $mechanic = new Mechanic();
            if ($mechanic->login($username, $password)) {
                $_SESSION['user_id'] = $mechanic->getId();
                $_SESSION['username'] = $mechanic->getUsername();
                $_SESSION['fullname'] = $mechanic->getFullName();
                $_SESSION['email'] = $mechanic->getEmail();
                $_SESSION['role'] = Mechanic::ROLE;
                
                $_SESSION['login_attempts'] = 0;
                
                header("Location: mechanicDashboard.php");
                exit();
            }
            
            $client = new Client();
            if ($client->login($username, $password)) {
                $_SESSION['user_id'] = $client->getId();
                $_SESSION['username'] = $client->getUsername();
                $_SESSION['fullname'] = $client->getFullName();
                $_SESSION['email'] = $client->getEmail();
                $_SESSION['role'] = Client::ROLE;
                
                $_SESSION['login_attempts'] = 0;
                
                header("Location: home.php");
                exit();
            }
            
            $_SESSION['login_attempts']++;
            $this->error = "Invalid username or password. Attempts remaining: " . (3 - $_SESSION['login_attempts']);
            
            if ($_SESSION['login_attempts'] >= 3) {
                $this->error = "Too many failed login attempts. Please try again later.";
                $this->locked = true;
            }
        }
    }
    
    public function getError() {
        return $this->error;
    }
    
    public function isLocked() {
        return $this->locked;
    }
    
    public function getRole() {
        if (isset($_SESSION['role'])) {
            return $_SESSION['role'];
        }
        return null;
    }
}
?>