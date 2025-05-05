<?php
require_once __DIR__ . '/../Models/client.php';
require_once __DIR__ . '/../Models/mechanic.php';
require_once __DIR__ . '/../Models/admin.php';
require_once __DIR__ . '/../Controllers/DbController.php';

class RegisterController
{
    private $error;
    private $dbController;

    
    public function __construct($dbController = null)
    {
        $this->error = "";
        $this->dbController = $dbController ?? new DBController();
    }

    private function isUsernameExist($username){
        $selectedRole = $_POST['selected_role']?? 'client';

        switch($selectedRole){
            case 'client':
                        $query = "SELECT * FROM clients WHERE username = '$username'";
                        $result = $this->dbController->executeQuery($query);
                        
                        if ($result && count($result) > 0) {
                            $this->error = "Username already exists";
                            $this->dbController->closeConnection();
                            return false;
                        }
            case 'mechanic':
                        $query = "SELECT * FROM mechanics WHERE username = '$username'";
                        $result = $this->dbController->executeQuery($query);

                        if ($result && count($result) > 0) {
                            $this->error = "Username already exists";
                            $this->dbController->closeConnection();
                            return false;
                        }
            case 'admin':
                        $query = "SELECT * FROM admins WHERE username = '$username'";
                        $result = $this->dbController->executeQuery($query);

                        if ($result && count($result) > 0) {
                            $this->error = "Username already exists";
                            $this->dbController->closeConnection();
                            return false;
                        }
                }
                $this->dbController->closeConnection();
                return true;
    }

    private function isEmailExist($email){
        $selectedRole = $_POST['selected_role']?? 'client';

        switch($selectedRole){
            case 'client':
                    $query = "SELECT * FROM clients WHERE email = '$email'";
                    $result = $this->dbController->executeQuery($query);
                    
                    if ($result && count($result) > 0) {
                        $this->error = "Email already exists";
                        $this->dbController->closeConnection();
                        return false;
                    }
            case'mechanic':
                    $query = "SELECT * FROM mechanics WHERE email = '$email'";
                    $result = $this->dbController->executeQuery($query);

                    if ($result && count($result) > 0) {
                        $this->error = "Email already exists";
                        $this->dbController->closeConnection();
                        return false;
                    }
            case 'admin':
                    $query = "SELECT * FROM admins WHERE email = '$email'";
                    $result = $this->dbController->executeQuery($query);

                    if ($result && count($result) > 0) {
                        $this->error = "Email already exists";
                        $this->dbController->closeConnection();
                        return false;
                    }
                }
                $this->dbController->closeConnection();
                return true;
    }

    private function isAdminCodeExist($adminCode)
    {
        $query = "SELECT * FROM admins WHERE adminCode = '$adminCode'";
        $result = $this->dbController->executeQuery($query);
        
        if ($result && count($result) > 0) {
            $this->error = "Admin code already exists";
            $this->dbController->closeConnection();
            return false;
        }
        $this->dbController->closeConnection();
        return true;
    }
    
    public function processRegistration()
    {

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $fullName = $_POST['fullname'] ?? '';
            $email = $_POST['email'] ?? '';
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            $selectedRole = $_POST['selected_role'] ?? 'client';
            
            if (empty($fullName) || empty($email) || empty($username) || empty($password)) {
                $this->error = "All fields are required";
                return false;
            }
            
            if ($password !== $confirmPassword) {
                $this->error = "Passwords do not match";
                return false;
            }
            
            switch ($selectedRole) {
                case 'client':
                    if(!$this->isUsernameExist($username))
                    {
                      return false;  
                    }

                    if(!$this->isEmailExist($email))
                    {
                        return false;
                    }
                    return $this->registerClient($fullName, $email, $username, $password);
                    
                case 'mechanic':
                    $specialization = $_POST['specialization'] ?? '';
                    $location = $_POST['location'] ?? '';
                    $experience = $_POST['experience'] ?? '';
                    $rating = $_POST['rating']?? '';
                    $totalReviews = $_POST['total_reviews']?? '';
                    
                    if (empty($specialization) || empty($location) || empty($experience)) {
                        $this->error = "All mechanic fields are required";
                        return false;
                    }

                    if(!$this->isUsernameExist($username))
                    {
                      return false;  
                    }

                    if(!$this->isEmailExist($email))
                    {
                        return false;
                    }
                    
                    return $this->registerMechanic($fullName, $email, $username, $password, $location, $specialization, $experience, $rating, $totalReviews);
                    
                case 'admin':
                    $adminCode = $_POST['admin_code'] ?? '';
                    
                    if (empty($adminCode)) {
                        $this->error = "Admin code is required";
                        return false;
                    }

                    if(!$this->isUsernameExist($username))
                    {
                      return false;  
                    }

                    if(!$this->isEmailExist($email))
                    {
                        return false;
                    }

                    if(!$this->isAdminCodeExist($adminCode))
                    {
                        return false;
                    }  
                    return $this->registerAdmin($fullName, $email, $username, $adminCode, $password);
                    
                default:
                    $this->error = "Invalid role selected";
                    return false;
            }
        }
        
        return false;
    }
    
    private function registerClient($fullName, $email, $username, $password)
    {
        $client = new Client("", $fullName, $email, $username, $password);
        
        if ($client->register()) {
            header("Location: Views/login.php?registered=true&role=client");
            exit();
        } else {
            $this->error = "Registration failed. Please try again.";
            return false;
        }
    }
    
    private function registerMechanic($fullName, $email, $username, $password, $location, $specialization, $experience, $rating, $totalReviews)
{
    $mechanic = new Mechanic("", $fullName, $email, $username, $password, $location, $specialization, $experience, $rating, $totalReviews);
    
    if ($mechanic->register()) {
        header("Location: Views/login.php?registered=true&role=mechanic");
        exit();
    } else {
        $this->error = "Registration failed. Please try again.";
        return false;
    }
}
    
    private function registerAdmin($fullName, $email, $username, $adminCode, $password)
    {
        $admin = new Admin("", $fullName, $email, $username, $adminCode, $password);
        
        if ($admin->register()) {
            header("Location: Views/login.php?registered=true&role=admin");
            exit();
        } else {
            $this->error = "Registration failed. Please try again.";
            return false;
        }
    }
    
    public function getError()
    {
        return $this->error;
    }
}
?>