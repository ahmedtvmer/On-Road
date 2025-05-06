<?php
require_once 'ValidationController.php';
require_once '../Models/request.php';
require_once '../Models/feedback.php';

class FeedbackController {
    private $clientId;
    private $errorMessage = '';
    private $successMessage = '';
    private $requestData = null;
    
    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        ValidationController::validateSession('client');
        $this->clientId = $_SESSION['user_id'];
    }
    
    public function getErrorMessage() {
        return $this->errorMessage;
    }
    
    public function getSuccessMessage() {
        return $this->successMessage;
    }
    
    public function getRequestData() {
        return $this->requestData;
    }
    
    public function validateRequest() {
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $requestId = $_GET['id'];
            
            $request = new Request();
            
            if ($request->getRequestById($requestId)) {
                if ($request->getClientId() == $this->clientId) {
                    if (!empty($request->getCompletedAt()) && $request->getCompletedAt() != '0000-00-00 00:00:00') {
                        $feedback = new Feedback();
                        if (!$feedback->checkFeedbackExists($requestId)) {
                            $this->requestData = [
                                'id' => $request->getId(),
                                'description' => $request->getDescription(),
                                'location' => $request->getLocation(),
                                'mechanicId' => $request->getMechanicId(),
                                'mechanicName' => $request->getMechanicName()
                            ];
                            return true;
                        } else {
                            $this->errorMessage = "You have already submitted feedback for this request.";
                        }
                    } else {
                        $this->errorMessage = "You can only provide feedback for completed requests.";
                    }
                } else {
                    $this->errorMessage = "You don't have permission to access this request.";
                }
            } else {
                $this->errorMessage = "Request not found.";
            }
        } else {
            $this->errorMessage = "Request ID is required.";
        }
        
        return false;
    }
    
    public function submitFeedback() {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_feedback'])) {
            $requestId = $_POST['request_id'];
            $mechanicId = $_POST['mechanic_id'];
            $costRating = $_POST['cost_rating'];
            $serviceRating = $_POST['service_rating'];
            
            $feedback = new Feedback();
            $feedback->setRequestId($requestId);
            $feedback->setClientId($this->clientId);
            $feedback->setMechanicId($mechanicId);
            $feedback->setCostRating($costRating);
            $feedback->setServiceRating($serviceRating);
            
            if ($feedback->createFeedback()) {
                $this->successMessage = "Thank you for your feedback!";
                return true;
            } else {
                $this->errorMessage = "Failed to submit feedback. Please try again.";
            }
        }
        
        return false;
    }
    
    public function processFeedback() {
        if (!$this->validateRequest()) {
            return false;
        }
        
        $this->submitFeedback();
        return true;
    }
}
?>