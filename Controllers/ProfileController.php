<?php
require_once 'ValidationController.php';
require_once '../Models/client.php';
require_once '../Models/mechanic.php';
require_once '../Models/admin.php';

class ProfileController {
    private $user;
    private $userRole;
    private $userId;
    private $successMessage = '';
    private $errorMessage = '';
    
    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        $this->userRole = $_SESSION['role'] ?? '';
        $this->userId = $_SESSION['user_id'] ?? '';
        
        ValidationController::validateSession($this->userRole);
        
        $this->loadUserData();
    }
    
    private function loadUserData() {
        if ($this->userRole == 'client') {
            $this->user = new Client();
            $this->user->getClientById($this->userId);
        } elseif ($this->userRole == 'mechanic') {
            $this->user = new Mechanic();
            $this->user->getMechanicById($this->userId);
        } elseif ($this->userRole == 'admin') {
            $this->user = new Admin();
            $this->user->getAdminById($this->userId);
        }
    }
    
    public function getUser() {
        return $this->user;
    }
    
    public function getUserRole() {
        return $this->userRole;
    }
    
    public function getSuccessMessage() {
        return $this->successMessage;
    }
    
    public function getErrorMessage() {
        return $this->errorMessage;
    }
    
    public function handleDeleteAccount() {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_account'])) {
            $deleteSuccess = false;
            
            if ($this->userRole == 'client') {
                $deleteSuccess = $this->user->deleteClient($this->userId);
            } elseif ($this->userRole == 'mechanic') {
                $deleteSuccess = $this->user->deleteMechanic($this->userId);
            } elseif ($this->userRole == 'admin') {
                $deleteSuccess = $this->user->deleteAdmin($this->userId);
            }
            
            if ($deleteSuccess) {
                header("Location: ../Controllers/LogoutController.php?deleted=1");
                exit();
            } else {
                $this->errorMessage = "Failed to delete account. Please try again.";
            }
        }
    }
    
    public function handleUpdateProfile() {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_profile'])) {
            $fullName = $_POST['fullName'];
            $email = $_POST['email'];
            $username = $_POST['username'];
            $password = $_POST['password'];
            
            $this->user->setFullName($fullName);
            $this->user->setEmail($email);
            $this->user->setUsername($username);
            
            if (!empty($password)) {
                $this->user->setPassword($password);
            }
            
            if ($this->userRole == 'mechanic') {
                $location = $_POST['location'];
                $specialization = $_POST['specialization'];
                $experience = $_POST['experience'];
                
                $this->user->setLocation($location);
                $this->user->setSpecialization($specialization);
                $this->user->setExperience($experience);
            } elseif ($this->userRole == 'admin') {
                $adminCode = $_POST['adminCode'];
                $this->user->setAdminCode($adminCode);
            }
            
            $updateSuccess = false;
            if ($this->userRole == 'client') {
                $updateSuccess = $this->user->updateClient();
            } elseif ($this->userRole == 'mechanic') {
                $updateSuccess = $this->user->updateMechanic();
            } elseif ($this->userRole == 'admin') {
                $updateSuccess = $this->user->updateAdmin();
            }
            
            if ($updateSuccess) {
                $this->successMessage = "Profile updated successfully!";
                
                $_SESSION['username'] = $username;
                $_SESSION['fullname'] = $fullName;
                $_SESSION['email'] = $email;
            } else {
                $this->errorMessage = "Failed to update profile. Please try again.";
            }
        }
    }
    
    public function processProfileActions() {
        $this->handleDeleteAccount();
        $this->handleUpdateProfile();
    }
}
?>