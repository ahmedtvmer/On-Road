<?php
require_once __DIR__ . "/../Controllers/DbController.php";

class Request
{
    private $id;
    private $clientId;
    private $mechanicId;
    private $description;
    private $location;
    private $status;
    private $createdAt;
    private $completedAt;
    private $dbController;
    
    public function __construct($id = "", $clientId = "", $mechanicId = "", $description = "", $location = "", $status = "", $createdAt = "", $completedAt = "", ?DBController $dbController = null)
    {
        $this->id = $id;
        $this->clientId = $clientId;
        $this->mechanicId = $mechanicId;
        $this->description = $description;
        $this->location = $location;
        $this->status = $status;
        $this->createdAt = $createdAt ? $createdAt : date('Y-m-d H:i:s');
        $this->completedAt = $completedAt;
        $this->dbController = $dbController ?? DBController::getInstance();
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getClientId()
    {
        return $this->clientId;
    }
    
    public function getMechanicId()
    {
        return $this->mechanicId;
    }
    
    public function getDescription()
    {
        return $this->description;
    }
    
    public function getLocation()
    {
        return $this->location;
    }
    
    public function getStatus()
    {
        return $this->status;
    }
    
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
    
    public function getCompletedAt()
    {
        return $this->completedAt;
    }
    
    public function setId($id)
    {
        $this->id = $id;
    }
    
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;
    }
    
    public function setMechanicId($mechanicId)
    {
        $this->mechanicId = $mechanicId;
    }
    
    public function setDescription($description)
    {
        $this->description = $description;
    }
    
    public function setLocation($location)
    {
        $this->location = $location;
    }
    
    public function setStatus($status)
    {
        $this->status = $status;
    }
    
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }
    
    public function setCompletedAt($completedAt)
    {
        $this->completedAt = $completedAt;
    }
    
    public function createRequest()
    {
        if($this->dbController->openConnection())
        {
            $query = "INSERT INTO requests (client_id, description, location, status, createdAt) 
                      VALUES ('$this->clientId', '$this->description', '$this->location', '$this->status', '$this->createdAt')";
            
            $result = $this->dbController->connection->query($query);
            
            if($result)
            {
                $this->id = $this->dbController->connection->insert_id;
                 
                return true;
            }
            
             
        }
        
        return false;
    }
    
    public function getRequestById($id)
    {

        if($this->dbController->openConnection())
        {
            $query = "SELECT * FROM requests WHERE id = $id";
            $result = $this->dbController->executeQuery($query);
            
            if($result && count($result) > 0)
            {
                $this->id = $result[0]['id'];
                $this->clientId = $result[0]['client_id'];
                $this->mechanicId = $result[0]['mechanic_id'];
                $this->description = $result[0]['description'];
                $this->location = $result[0]['location'];
                $this->status = $result[0]['status'];
                $this->createdAt = $result[0]['createdAt'];
                $this->completedAt = $result[0]['completedAt'];
                
                 
                return true;
            }
            
             
        }
        
        return false;
    }
    
    public function updateRequest()
    {
        if($this->dbController->openConnection())
        {
            $query = "UPDATE requests SET 
                      client_id = '$this->clientId', 
                      mechanic_id = '$this->mechanicId', 
                      description = '$this->description',
                      location = '$this->location',
                      status = '$this->status',
                      completedAt = '$this->completedAt'
                      WHERE id = $this->id";
            
            $result = $this->dbController->connection->query($query);
            
             
            return $result;
        }
        
        return false;
    }
    
    public function deleteRequest($id)
    {
        if($this->dbController->openConnection())
        {
            $query = "DELETE FROM requests WHERE id = $id";
            $result = $this->dbController->connection->query($query);
            
             
            return $result;
        }
        
        return false;
    }
    
    public function getAllRequests()
    {
        if($this->dbController->openConnection())
        {
            $query = "SELECT * FROM requests ORDER BY createdAt DESC";
            $result = $this->dbController->executeQuery($query);
            
             
            return $result;
        }
        
        return false;
    }
    
    public function getRequestsByClientId($clientId)
    {
        if($this->dbController->openConnection())
        {
            $query = "SELECT * FROM requests WHERE client_id = $clientId ORDER BY createdAt DESC";
            $result = $this->dbController->executeQuery($query);
            
             
            return $result;
        }
        
        return false;
    }
    
    public function getActiveRequestsByMechanicId($mechanicId)
    {
        if($this->dbController->openConnection())
        {
            $query = "SELECT r.*, c.fullname as clientName 
                      FROM requests r 
                      LEFT JOIN clients c ON r.client_id = c.id
                      WHERE r.mechanic_id = $mechanicId AND r.completedAt = '0000-00-00 00:00:00'
                      ORDER BY r.createdAt DESC";
            
            $result = $this->dbController->executeQuery($query);
            
            return $result ? $result : array();
        }
        
        return array();
    }

    public function getRandomPendingRequest($mechanicLocation)
    {
        if($this->dbController->openConnection())
        {
            $query = "SELECT r.*, c.fullname as clientName 
                      FROM requests r 
                      LEFT JOIN clients c ON r.client_id = c.id
                      WHERE (r.mechanic_id IS NULL OR r.mechanic_id = 0)
                      AND r.location = '$mechanicLocation'
                      ORDER BY RAND() LIMIT 1";
            
            $result = $this->dbController->executeQuery($query);
            
            return $result && !empty($result) ? $result[0] : null;
        }
        
        return null;
    }
    
    public function getRequestsByMechanicId($mechanicId)
    {
        if($this->dbController->openConnection())
        {
            $query = "SELECT * FROM requests WHERE mechanic_id = $mechanicId ORDER BY createdAt DESC";
            $result = $this->dbController->executeQuery($query);
            
             
            return $result;
        }
        
        return false;
    }
    
    public function getRequestsByStatus($status)
    {
        if($this->dbController->openConnection())
        {
            $query = "SELECT * FROM requests WHERE status = '$status' ORDER BY createdAt DESC";
            $result = $this->dbController->executeQuery($query);
            
             
            return $result;
        }
        
        return false;
    }
    
    public function assignMechanic($mechanicId)
    {
        $this->mechanicId = $mechanicId;
        $this->status = "assigned";
        return $this->updateRequest();
    }
    
    public function startService()
    {
        $this->status = "in_progress";
        return $this->updateRequest();
    }
    
    public function completeService()
    {
        $this->status = "completed";
        $this->completedAt = date('Y-m-d H:i:s');
        return $this->updateRequest();
    }
    
    public function cancelRequest()
    {
        $this->status = "cancelled";
        return $this->updateRequest();
    }

    
    
    public function getRequestsCount($status = null)
    {
        if($this->dbController->openConnection())
        {
            $query = "SELECT COUNT(*) as count FROM requests";
            if($status) {
                $query .= " WHERE status = '$status'";
            }
            
            $result = $this->dbController->executeQuery($query);
            
             
            if($result && count($result) > 0) {
                return $result[0]['count'];
            }
        }
        
        return 0;
    }

    public function getActiveRequestsByClientId($clientId)
{
    if($this->dbController->openConnection())
    {
        $query = "SELECT r.*, m.fullName as mechanicName 
                  FROM requests r 
                  LEFT JOIN mechanics m ON r.mechanic_id = m.id
                  WHERE r.client_id = $clientId AND r.completedAt = '0000-00-00 00:00:00'
                  ORDER BY r.createdAt DESC";
        
        $result = $this->dbController->executeQuery($query);
        
        return $result;
    }
    
    return [];
}

public function getAllRequestsByClientId($clientId) {
    if ($this->dbController->openConnection()) {
        $query = "SELECT r.*, m.fullname as mechanicName 
                  FROM requests r 
                  LEFT JOIN mechanics m ON r.mechanic_id = m.id 
                  WHERE r.client_id = '$clientId' 
                  ORDER BY r.createdAt DESC";
        
        $result = $this->dbController->executeQuery($query);
        
        return $result ? $result : array();
    } else {
        echo "Error in database connection";
        return array();
    }
}

public function getAllRequestsByMechanicId($mechanicId) {
    if ($this->dbController->openConnection()) {
        $query = "SELECT r.*, c.fullname as clientName 
                  FROM requests r 
                  LEFT JOIN clients c ON r.client_id = c.id 
                  WHERE r.mechanic_id = '$mechanicId' 
                  ORDER BY r.createdAt DESC";
        
        $result = $this->dbController->executeQuery($query);
        
        return $result ? $result : array();
    } else {
        echo "Error in database connection";
        return array();
    }
}

public function getRequestsCountByMechanicAndStatus($mechanicId, $status)
{
    if($this->dbController->openConnection())
    {
        $query = "SELECT COUNT(*) as count FROM requests 
                  WHERE mechanic_id = $mechanicId AND status = '$status'";
        
        $result = $this->dbController->executeQuery($query);
        
        if($result && count($result) > 0) {
            return $result[0]['count'];
        }
    }
    
    return 0;
}

public function getCompletedRequestsCountByMechanic($mechanicId)
{
    if($this->dbController->openConnection())
    {
        $query = "SELECT COUNT(*) as count FROM requests 
                  WHERE mechanic_id = $mechanicId AND status = 'completed'";
        
        $result = $this->dbController->executeQuery($query);
        
        if($result && count($result) > 0) {
            return $result[0]['count'];
        }
    }
    
    return 0;
}
    
    public function getRequestCountByStatus($status)
    {
        if($this->dbController->openConnection())
        {
            $query = "SELECT COUNT(*) as count FROM requests WHERE status = ?";
            $stmt = $this->dbController->connection->prepare($query);
            $stmt->bind_param("s", $status);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = $result->fetch_assoc();
            return $data['count'];
        }
        
        return 0;
    }
    
    public function getAssignedOrdersCount($mechanicId) {
        if($this->dbController->openConnection()) {
            $query = "SELECT COUNT(*) as count FROM requests WHERE mechanic_id = $mechanicId";
            $result = $this->dbController->executeQuery($query);
            
            if($result && count($result) > 0) {
                return $result[0]['count'];
            }
        }
        
        return 0;
    }
    
    public function getMechanicName()
    {
        if ($this->mechanicId) {
            if ($this->dbController->openConnection()) {
                $query = "SELECT fullname FROM mechanics WHERE id = $this->mechanicId";
                $result = $this->dbController->executeQuery($query);
                
                if ($result && count($result) > 0) {
                    return $result[0]['fullname'];
                }
            }
        }
        return "Not assigned";
    }

    public function getClientName()
    {
        if ($this->clientId) {
            if ($this->dbController->openConnection()) {
                $query = "SELECT fullname FROM clients WHERE id = $this->clientId";
                $result = $this->dbController->executeQuery($query);
                
                if ($result && count($result) > 0) {
                    return $result[0]['fullname'];
                }
            }
        }
        return "Not assigned";
    }
    
    public function getCompletedRequestsByMechanicId($mechanicId)
    {
        if($this->dbController->openConnection())
        {
            $query = "SELECT r.*, c.fullname as clientName 
                      FROM requests r 
                      LEFT JOIN clients c ON r.client_id = c.id
                      WHERE r.mechanic_id = $mechanicId AND r.status = 'completed'
                      ORDER BY r.createdAt DESC";
            
            $result = $this->dbController->executeQuery($query);
            
            return $result ? $result : array();
        }
        
        return array();
    }
}
?>

