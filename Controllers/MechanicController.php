<?php
require_once 'ValidationController.php';
require_once '../Models/mechanic.php';
require_once '../Models/request.php';

class MechanicController {
    private $mechanic;
    private $request;
    private $successMessage;
    private $errorMessage;
    
    public function __construct() {
        ValidationController::validateSession('admin');
        $this->mechanic = new Mechanic();
        $this->request = new Request();
        $this->successMessage = '';
        $this->errorMessage = '';
        
        $this->handleActions();
    }
    
    private function handleActions() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['action']) && $_POST['action'] == 'delete') {
                $this->deleteMechanic();
            }
        }
    }
    
    private function deleteMechanic() {
        if (isset($_POST['mechanic_id']) && !empty($_POST['mechanic_id'])) {
            $mechanicId = $_POST['mechanic_id'];
            
            // Check if mechanic has active requests
            $activeRequests = $this->request->getActiveRequestsByMechanicId($mechanicId);
            
            if (count($activeRequests) > 0) {
                $this->errorMessage = "Cannot delete mechanic with active requests. Please reassign or complete these requests first.";
                $_SESSION['error_message'] = $this->errorMessage;
                header("Location: ../Views/mechanics.php");
                exit();
            }
            
            // Delete mechanic
            if ($this->mechanic->deleteMechanic($mechanicId)) {
                $this->successMessage = "Mechanic deleted successfully.";
                $_SESSION['success_message'] = $this->successMessage;
            } else {
                $this->errorMessage = "Failed to delete mechanic.";
                $_SESSION['error_message'] = $this->errorMessage;
            }
            
            header("Location: ../Views/mechanics.php");
            exit();
        } else {
            $this->errorMessage = "Mechanic ID is required.";
            $_SESSION['error_message'] = $this->errorMessage;
            header("Location: ../Views/mechanics.php");
            exit();
        }
    }
    
    public function getSuccessMessage() {
        return $this->successMessage;
    }
    
    public function getErrorMessage() {
        return $this->errorMessage;
    }
}

// Initialize controller if this file is accessed directly
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    $controller = new MechanicController();
}
?>