<?php
require_once 'ValidationController.php';
require_once '../Models/mechanic.php';
require_once '../Models/request.php';

class MechanicController {
    private $mechanic;
    private $request;
    private $successMessage;
    private $errorMessage;
    
    public function __construct(?Mechanic $mechanic = null, ?Request $request = null) {
        ValidationController::validateSession('admin');
        $this->mechanic = $mechanic ?? new Mechanic();
        $this->request = $request ?? new Request();
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
            
            $activeRequests = $this->request->getActiveRequestsByMechanicId($mechanicId);
            
            if (count($activeRequests) > 0) {
                $this->errorMessage = "Cannot delete mechanic with active requests. Please reassign or complete these requests first.";
                $_SESSION['error_message'] = $this->errorMessage;
                header("Location: ../Views/mechanics.php");
                exit();
            }
            
            if ($this->mechanic->deleteUser($mechanicId)) {
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

if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    $controller = new MechanicController();
}
?>