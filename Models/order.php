<?php
require_once 'DbController.php';

class Order
{
    private $id;
    private $clientId;
    private $mechanicId;
    private $description;
    private $location;
    private $status;
    private $createdAt;
    private $completedAt;
    
    public function __construct($id = "", $clientId = "", $mechanicId = "", $description = "", $location = "", $status = "pending", $createdAt = "", $completedAt = "")
    {
        $this->id = $id;
        $this->clientId = $clientId;
        $this->mechanicId = $mechanicId;
        $this->description = $description;
        $this->location = $location;
        $this->status = $status;
        $this->createdAt = $createdAt ? $createdAt : date('Y-m-d H:i:s');
        $this->completedAt = $completedAt;
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
    
    public function createOrder()
    {
        $dbController = new DBController();
        if($dbController->openConnection())
        {
            $query = "INSERT INTO orders (client_id, mechanic_id, description, location, status, created_at) 
                      VALUES ('$this->clientId', '$this->mechanicId', '$this->description', '$this->location', '$this->status', '$this->createdAt')";
            
            $result = $dbController->connection->query($query);
            
            if($result)
            {
                $this->id = $dbController->connection->insert_id;
                $dbController->closeConnection();
                return true;
            }
            
            $dbController->closeConnection();
        }
        
        return false;
    }
    
    public function getOrderById($id)
    {

        $dbController = new DBController();
        if($dbController->openConnection())
        {
            $query = "SELECT * FROM orders WHERE id = $id";
            $result = $dbController->executeQuery($query);
            
            if($result && count($result) > 0)
            {
                $this->id = $result[0]['id'];
                $this->clientId = $result[0]['client_id'];
                $this->mechanicId = $result[0]['mechanic_id'];
                $this->description = $result[0]['description'];
                $this->location = $result[0]['location'];
                $this->status = $result[0]['status'];
                $this->createdAt = $result[0]['created_at'];
                $this->completedAt = $result[0]['completed_at'];
                
                $dbController->closeConnection();
                return true;
            }
            
            $dbController->closeConnection();
        }
        
        return false;
    }
    
    public function updateOrder()
    {
        $dbController = new DBController();
        if($dbController->openConnection())
        {
            $query = "UPDATE orders SET 
                      client_id = '$this->clientId', 
                      mechanic_id = '$this->mechanicId', 
                      description = '$this->description',
                      location = '$this->location',
                      status = '$this->status',
                      completed_at = '$this->completedAt'
                      WHERE id = $this->id";
            
            $result = $dbController->connection->query($query);
            
            $dbController->closeConnection();
            return $result;
        }
        
        return false;
    }
    
    public function deleteOrder($id)
    {
        $dbController = new DBController();
        if($dbController->openConnection())
        {
            $query = "DELETE FROM orders WHERE id = $id";
            $result = $dbController->connection->query($query);
            
            $dbController->closeConnection();
            return $result;
        }
        
        return false;
    }
    
    public function getAllOrders()
    {
        $dbController = new DBController();
        if($dbController->openConnection())
        {
            $query = "SELECT * FROM orders ORDER BY created_at DESC";
            $result = $dbController->executeQuery($query);
            
            $dbController->closeConnection();
            return $result;
        }
        
        return false;
    }
    
    public function getOrdersByClientId($clientId)
    {
        $dbController = new DBController();
        if($dbController->openConnection())
        {
            $query = "SELECT * FROM orders WHERE client_id = $clientId ORDER BY created_at DESC";
            $result = $dbController->executeQuery($query);
            
            $dbController->closeConnection();
            return $result;
        }
        
        return false;
    }
    
    public function getOrdersByMechanicId($mechanicId)
    {
        $dbController = new DBController();
        if($dbController->openConnection())
        {
            $query = "SELECT * FROM orders WHERE mechanic_id = $mechanicId ORDER BY created_at DESC";
            $result = $dbController->executeQuery($query);
            
            $dbController->closeConnection();
            return $result;
        }
        
        return false;
    }
    
    public function getOrdersByStatus($status)
    {
        $dbController = new DBController();
        if($dbController->openConnection())
        {
            $query = "SELECT * FROM orders WHERE status = '$status' ORDER BY created_at DESC";
            $result = $dbController->executeQuery($query);
            
            $dbController->closeConnection();
            return $result;
        }
        
        return false;
    }
    
    public function assignMechanic($mechanicId)
    {
        $this->mechanicId = $mechanicId;
        $this->status = "assigned";
        return $this->updateOrder();
    }
    
    public function startService()
    {
        $this->status = "in_progress";
        return $this->updateOrder();
    }
    
    public function completeService()
    {
        $this->status = "completed";
        $this->completedAt = date('Y-m-d H:i:s');
        return $this->updateOrder();
    }
    
    public function cancelOrder()
    {
        $this->status = "cancelled";
        return $this->updateOrder();
    }
    
    public function getOrdersCount($status = null)
    {
        $dbController = new DBController();
        if($dbController->openConnection())
        {
            $query = "SELECT COUNT(*) as count FROM orders";
            if($status) {
                $query .= " WHERE status = '$status'";
            }
            
            $result = $dbController->executeQuery($query);
            
            $dbController->closeConnection();
            if($result && count($result) > 0) {
                return $result[0]['count'];
            }
        }
        
        return 0;
    }
}
?>