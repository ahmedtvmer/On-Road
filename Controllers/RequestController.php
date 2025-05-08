<?php
require_once 'ValidationController.php';
require_once __DIR__ . '/../Models/request.php';
require_once __DIR__ . '/../Models/mechanic.php';

class RequestController {
    private $mechanicId;
    private $request;
    private $assignedRequests;
    private $completedRequests;
    private $successMessage = '';
    private $errorMessage = '';
    
    public function __construct(?Request $request = null) {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        ValidationController::validateSession('mechanic');
        $this->mechanicId = $_SESSION['user_id'];
        $this->request = $request ?? new Request();
        $this->loadAssignedRequests();
        $this->loadCompletedRequests();
    }
    
    private function loadAssignedRequests() {
        $this->assignedRequests = $this->request->getActiveRequestsByMechanicId($this->mechanicId);
    }
    
    private function loadCompletedRequests() {
        $this->completedRequests = $this->request->getCompletedRequestsByMechanicId($this->mechanicId);
    }
    
    public function getAssignedRequests() {
        return $this->assignedRequests;
    }
    
    public function getCompletedRequests() {
        return $this->completedRequests;
    }
    
    public function getSuccessMessage() {
        return $this->successMessage;
    }
    
    public function getErrorMessage() {
        return $this->errorMessage;
    }
    
    public function completeRequest($requestId) {
        $this->request->getRequestById($requestId);
        
        if ($this->request->completeService()) {
            $this->successMessage = "Request #$requestId has been marked as completed successfully!";
            $this->loadAssignedRequests();
            return true;
        } else {
            $this->errorMessage = "Failed to complete the request. Please try again.";
            return false;
        }
    }
    
    public function assignRandomRequest() {
        $mechanic = new Mechanic();
        $mechanic->getMechanicById($this->mechanicId);
        $mechanicLocation = $mechanic->getLocation();
        $pendingRequest = $this->request->getRandomPendingRequest($mechanicLocation);
        
        if ($pendingRequest) {
            $this->request->getRequestById($pendingRequest['id']);
            if ($this->request->assignMechanic($this->mechanicId)) {
                $this->successMessage = "You have been assigned to request #" . $pendingRequest['id'] . " successfully!";
                $this->loadAssignedRequests();
                return true;
            } else {
                $this->errorMessage = "Failed to assign the request. Please try again.";
                return false;
            }
        } else {
            $this->errorMessage = "No pending requests available in your location at the moment.";
            return false;
        }
    }
    
    public function processRequests() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['complete_request'])) {
                $requestId = $_POST['request_id'];
                $this->completeRequest($requestId);
            } elseif (isset($_POST['assign_random'])) {
                $this->assignRandomRequest();
            }
        }
    }
}
?>