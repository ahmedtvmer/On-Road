<?php
require_once 'DbController.php';

class Feedback
{
    private $id;
    private $requestId;
    private $clientId;
    private $mechanicId;
    private $createdAt;
    private $costRating;
    private $serviceRating;
    
    public function __construct($id = "", $requestId = "", $clientId = "", $mechanicId = "", $createdAt = "", $costRating = 0, $serviceRating = 0)
    {
        $this->id = $id;
        $this->requestId = $requestId;
        $this->clientId = $clientId;
        $this->mechanicId = $mechanicId;
        $this->createdAt = $createdAt ? $createdAt : date('Y-m-d H:i:s');
        $this->costRating = $costRating;
        $this->serviceRating = $serviceRating;
    }
    
    // Getters
    public function getId()
    {
        return $this->id;
    }
    
    public function getRequestId()
    {
        return $this->requestId;
    }
    
    public function getClientId()
    {
        return $this->clientId;
    }
    
    public function getMechanicId()
    {
        return $this->mechanicId;
    }
    
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
    
    public function getCostRating()
    {
        return $this->costRating;
    }
    
    public function getServiceRating()
    {
        return $this->serviceRating;
    }
    
    // Setters
    public function setId($id)
    {
        $this->id = $id;
    }
    
    public function setRequestId($requestId)
    {
        $this->requestId = $requestId;
    }
    
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;
    }
    
    public function setMechanicId($mechanicId)
    {
        $this->mechanicId = $mechanicId;
    }
    
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }
    
    public function setCostRating($costRating)
    {
        $this->costRating = $costRating;
    }
    
    public function setServiceRating($serviceRating)
    {
        $this->serviceRating = $serviceRating;
    }
    
    // Database Operations
    public function createFeedback()
    {
        $dbController = new DBController();
        if($dbController->openConnection())
        {
            $query = "INSERT INTO feedback (request_id, client_id, mechanic_id, created_at, cost_rating, service_rating) 
                      VALUES ('$this->requestId', '$this->clientId', '$this->mechanicId', '$this->createdAt', '$this->costRating', '$this->serviceRating')";
            
            $result = $dbController->connection->query($query);
            
            if($result)
            {
                $this->id = $dbController->connection->insert_id;
                $dbController->closeConnection();
                
                // Update mechanic rating
                $this->updateMechanicRating();
                
                return true;
            }
            
            $dbController->closeConnection();
        }
        
        return false;
    }
    
    public function getFeedbackById($id)
    {
        $dbController = new DBController();
        if($dbController->openConnection())
        {
            $query = "SELECT * FROM feedback WHERE id = $id";
            $result = $dbController->executeQuery($query);
            
            if($result && count($result) > 0)
            {
                $this->id = $result[0]['id'];
                $this->requestId = $result[0]['request_id'];
                $this->clientId = $result[0]['client_id'];
                $this->mechanicId = $result[0]['mechanic_id'];
                $this->createdAt = $result[0]['created_at'];
                $this->costRating = $result[0]['cost_rating'];
                $this->serviceRating = $result[0]['service_rating'];
                
                $dbController->closeConnection();
                return true;
            }
            
            $dbController->closeConnection();
        }
        
        return false;
    }
    
    public function updateFeedback()
    {
        $dbController = new DBController();
        if($dbController->openConnection())
        {
            $query = "UPDATE feedback SET 
                      request_id = '$this->requestId', 
                      client_id = '$this->clientId', 
                      mechanic_id = '$this->mechanicId',
                      cost_rating = '$this->costRating',
                      service_rating = '$this->serviceRating'
                      WHERE id = $this->id";
            
            $result = $dbController->connection->query($query);
            
            $dbController->closeConnection();
            
            if($result) {
                // Update mechanic rating
                $this->updateMechanicRating();
                return true;
            }
            
            return $result;
        }
        
        return false;
    }
    
    public function deleteFeedback($id)
    {
        $dbController = new DBController();
        if($dbController->openConnection())
        {
            // First get the feedback to know which mechanic's rating to update
            $this->getFeedbackById($id);
            
            $query = "DELETE FROM feedback WHERE id = $id";
            $result = $dbController->connection->query($query);
            
            $dbController->closeConnection();
            
            if($result) {
                // Update mechanic rating after deletion
                $this->updateMechanicRatingAfterDeletion();
                return true;
            }
            
            return $result;
        }
        
        return false;
    }
    
    public function getAllFeedback()
    {
        $dbController = new DBController();
        if($dbController->openConnection())
        {
            $query = "SELECT * FROM feedback ORDER BY created_at DESC";
            $result = $dbController->executeQuery($query);
            
            $dbController->closeConnection();
            return $result;
        }
        
        return false;
    }
    
    public function getFeedbackByRequestId($requestId)
    {
        $dbController = new DBController();
        if($dbController->openConnection())
        {
            $query = "SELECT * FROM feedback WHERE request_id = $requestId";
            $result = $dbController->executeQuery($query);
            
            if($result && count($result) > 0)
            {
                $this->id = $result[0]['id'];
                $this->requestId = $result[0]['request_id'];
                $this->clientId = $result[0]['client_id'];
                $this->mechanicId = $result[0]['mechanic_id'];
                $this->createdAt = $result[0]['created_at'];
                $this->costRating = $result[0]['cost_rating'];
                $this->serviceRating = $result[0]['service_rating'];
                
                $dbController->closeConnection();
                return true;
            }
            
            $dbController->closeConnection();
        }
        
        return false;
    }
    
    public function getFeedbackByClientId($clientId)
    {
        $dbController = new DBController();
        if($dbController->openConnection())
        {
            $query = "SELECT * FROM feedback WHERE client_id = $clientId ORDER BY created_at DESC";
            $result = $dbController->executeQuery($query);
            
            $dbController->closeConnection();
            return $result;
        }
        
        return false;
    }
    
    public function getFeedbackByMechanicId($mechanicId)
    {
        $dbController = new DBController();
        if($dbController->openConnection())
        {
            $query = "SELECT * FROM feedback WHERE mechanic_id = $mechanicId ORDER BY created_at DESC";
            $result = $dbController->executeQuery($query);
            
            $dbController->closeConnection();
            return $result;
        }
        
        return false;
    }
    
    public function checkFeedbackExists($requestId)
    {
        $dbController = new DBController();
        if($dbController->openConnection())
        {
            $query = "SELECT * FROM feedback WHERE request_id = $requestId";
            $result = $dbController->executeQuery($query);
            
            $dbController->closeConnection();
            return ($result && count($result) > 0);
        }
        
        return false;
    }
    
    // Helper methods for mechanic rating
    private function updateMechanicRating()
    {
        if($this->mechanicId) {
            $mechanic = new Mechanic();
            if($mechanic->getMechanicById($this->mechanicId)) {
                // Calculate average of cost and service ratings
                $averageRating = ($this->costRating + $this->serviceRating) / 2;
                $mechanic->addReview($averageRating);
            }
        }
    }
    
    private function updateMechanicRatingAfterDeletion()
    {
        if($this->mechanicId) {
            // Recalculate mechanic rating from all remaining feedback
            $dbController = new DBController();
            if($dbController->openConnection())
            {
                $query = "SELECT AVG((cost_rating + service_rating) / 2) as avg_rating, COUNT(*) as count 
                          FROM feedback 
                          WHERE mechanic_id = $this->mechanicId";
                $result = $dbController->executeQuery($query);
                
                if($result && count($result) > 0) {
                    $mechanic = new Mechanic();
                    if($mechanic->getMechanicById($this->mechanicId)) {
                        $mechanic->setRating($result[0]['avg_rating'] ?? 0);
                        $mechanic->setTotalReviews($result[0]['count'] ?? 0);
                        $mechanic->updateMechanic();
                    }
                }
                
                $dbController->closeConnection();
            }
        }
    }
}
?>