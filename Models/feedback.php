<?php
require_once __DIR__ . "/../Controllers/DbController.php";
require_once 'mechanic.php';

class Feedback
{
    private $id;
    private $requestId;
    private $clientId;
    private $mechanicId;
    private $createdAt;
    private $costRating;
    private $serviceRating;
    private $dbController;
    
    public function __construct($id = "", $requestId = "", $clientId = "", $mechanicId = "", $createdAt = "", $costRating = 0, $serviceRating = 0, ?DBController $dbController = null)
    {
        $this->id = $id;
        $this->requestId = $requestId;
        $this->clientId = $clientId;
        $this->mechanicId = $mechanicId;
        $this->createdAt = $createdAt ? $createdAt : date('Y-m-d H:i:s');
        $this->costRating = $costRating;
        $this->serviceRating = $serviceRating;
        $this->dbController = $dbController ?? new DBController();
    }
    
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
    
    public function createFeedback()
    {
        if($this->dbController->openConnection())
        {
            $query = "INSERT INTO feedbacks (request_id, client_id, mechanic_id, createdAt, costRating, serviceRating) 
                      VALUES ('$this->requestId', '$this->clientId', '$this->mechanicId', '$this->createdAt', '$this->costRating', '$this->serviceRating')";
            
            $result = $this->dbController->connection->query($query);
            
            if($result)
            {
                $this->id = $this->dbController->connection->insert_id;
                $this->updateMechanicRating();
                
                return true;
            }
            
             
        }
        
        return false;
    }
    
    public function getFeedbackById($id)
    {
        if($this->dbController->openConnection())
        {
            $query = "SELECT * FROM feedbacks WHERE id = $id";
            $result = $this->dbController->executeQuery($query);
            
            if($result && count($result) > 0)
            {
                $this->id = $result[0]['id'];
                $this->requestId = $result[0]['request_id'];
                $this->clientId = $result[0]['client_id'];
                $this->mechanicId = $result[0]['mechanic_id'];
                $this->createdAt = $result[0]['created_at'];
                $this->costRating = $result[0]['cost_rating'];
                $this->serviceRating = $result[0]['service_rating'];
                
                 
                return true;
            }
            
             
        }
        
        return false;
    }
    
    public function updateFeedback()
    {
        if($this->dbController->openConnection())
        {
            $query = "UPDATE feedbacks SET 
                      request_id = '$this->requestId', 
                      client_id = '$this->clientId', 
                      mechanic_id = '$this->mechanicId',
                      cost_rating = '$this->costRating',
                      service_rating = '$this->serviceRating'
                      WHERE id = $this->id";
            
            $result = $this->dbController->connection->query($query);
            
             
            
            if($result) {
                $this->updateMechanicRating();
                return true;
            }
            
            return $result;
        }
        
        return false;
    }
    
    public function deleteFeedback($id)
    {
        if($this->dbController->openConnection())
        {
            $this->getFeedbackById($id);
            
            $query = "DELETE FROM feedbacks WHERE id = $id";
            $result = $this->dbController->connection->query($query);
            
             
            
            if($result) {
                $this->updateMechanicRatingAfterDeletion();
                return true;
            }
            
            return $result;
        }
        
        return false;
    }
    
    public function getFeedbackByRequestId($requestId)
    {
        if($this->dbController->openConnection())
        {
            $query = "SELECT * FROM feedbacks WHERE request_id = $requestId";
            $result = $this->dbController->executeQuery($query);
            
            if($result && count($result) > 0)
            {
                $this->id = $result[0]['id'];
                $this->requestId = $result[0]['request_id'];
                $this->clientId = $result[0]['client_id'];
                $this->mechanicId = $result[0]['mechanic_id'];
                $this->createdAt = $result[0]['createdAt'];
                $this->costRating = $result[0]['costRating'];
                $this->serviceRating = $result[0]['serviceRating'];
                
                 
                return true;
            }
            
             
        }
        
        return false;
    }
    
    public function getFeedbackByClientId($clientId)
    {
        if($this->dbController->openConnection())
        {
            $query = "SELECT * FROM feedbacks WHERE client_id = $clientId ORDER BY created_at DESC";
            $result = $this->dbController->executeQuery($query);
            
             
            return $result;
        }
        
        return false;
    }
    
    public function getFeedbackByMechanicId($mechanicId)
    {
        if($this->dbController->openConnection())
        {
            $query = "SELECT * FROM feedbacks WHERE mechanic_id = $mechanicId ORDER BY created_at DESC";
            $result = $this->dbController->executeQuery($query);
            
             
            return $result;
        }
        
        return false;
    }
    
    public function checkFeedbackExists($requestId)
    {
        if($this->dbController->openConnection())
        {
            $query = "SELECT * FROM feedbacks WHERE request_id = $requestId";
            $result = $this->dbController->executeQuery($query);
            
             
            return ($result && count($result) > 0);
        }
        
        return false;
    }
    
    private function updateMechanicRating()
    {
        if($this->mechanicId) {
            $mechanic = new Mechanic();
            if($mechanic->getMechanicById($this->mechanicId)) {
                $averageRating = ($this->costRating + $this->serviceRating) / 2;
                $mechanic->addReview($averageRating);
            }
        }
    }
    
    private function updateMechanicRatingAfterDeletion()
    {
        if($this->mechanicId) {
            if($this->dbController->openConnection())
            {
                $query = "SELECT AVG((cost_rating + service_rating) / 2) as avg_rating, COUNT(*) as count 
                          FROM feedbacks 
                          WHERE mechanic_id = $this->mechanicId";
                $result = $this->dbController->executeQuery($query);
                
                if($result && count($result) > 0) {
                    $mechanic = new Mechanic();
                    if($mechanic->getMechanicById($this->mechanicId)) {
                        $mechanic->setRating($result[0]['avg_rating'] ?? 0);
                        $mechanic->setTotalReviews($result[0]['count'] ?? 0);
                        $mechanic->updateUser();
                    }
                }
                
                 
            }
        }
    }
}
?>