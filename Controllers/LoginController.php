<?php
require_once '../Models/admin.php';
require_once '../Models/client.php';
require_once '../Models/mechanic.php';

class LoginController {
    private $error = "";
    private $admin;
    private $client;
    private $mechanic;
    
    public function __construct(
        ?Admin $admin = null,
        ?Client $client = null,
        ?Mechanic $mechanic = null
    ) {
        session_start();
        
        $this->admin = $admin ?? new Admin();
        $this->client = $client ?? new Client();
        $this->mechanic = $mechanic ?? new Mechanic();
        
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
            
            if ($this->admin->login($username, $password)) {
                $_SESSION['user_id'] = $this->admin->getId();
                $_SESSION['username'] = $this->admin->getUsername();
                $_SESSION['fullname'] = $this->admin->getFullName();
                $_SESSION['email'] = $this->admin->getEmail();
                $_SESSION['role'] = Admin::ROLE;
                
                
                header("Location: ../Views/adminDashboard.php");
                exit();
            }
            
            if ($this->mechanic->login($username, $password)) {
                $_SESSION['user_id'] = $this->mechanic->getId();
                $_SESSION['username'] = $this->mechanic->getUsername();
                $_SESSION['fullname'] = $this->mechanic->getFullName();
                $_SESSION['email'] = $this->mechanic->getEmail();
                $_SESSION['role'] = Mechanic::ROLE;
                
                
                header("Location: ../Views/mechanicDashboard.php");
                exit();
            }
            
            if ($this->client->login($username, $password)) {
                $_SESSION['user_id'] = $this->client->getId();
                $_SESSION['username'] = $this->client->getUsername();
                $_SESSION['fullname'] = $this->client->getFullName();
                $_SESSION['email'] = $this->client->getEmail();
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