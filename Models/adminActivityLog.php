<?php
require_once 'DbController.php';

class AdminActivityLog
{
    private $id;
    private $adminId;
    private $clientId;
    private $action;
    private $createdAt;
    
    public function __construct($id = "", $adminId = "", $clientId = "", $action = "", $createdAt = "")
    {
        $this->id = $id;
        $this->adminId = $adminId;
        $this->clientId = $clientId;
        $this->action = $action;
        $this->createdAt = $createdAt ? $createdAt : date('Y-m-d H:i:s');
    }
    
    // Getters
    public function getId()
    {
        return $this->id;
    }
    
    public function getAdminId()
    {
        return $this->adminId;
    }
    
    public function getClientId()
    {
        return $this->clientId;
    }
    
    public function getAction()
    {
        return $this->action;
    }
    
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
    
    // Setters
    public function setId($id)
    {
        $this->id = $id;
    }
    
    public function setAdminId($adminId)
    {
        $this->adminId = $adminId;
    }
    
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;
    }
    
    public function setAction($action)
    {
        $this->action = $action;
    }
    
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }
    
    // Database Operations
    public function logActivity()
    {
        $dbController = new DBController();
        if($dbController->openConnection())
        {
            $query = "INSERT INTO admin_activity_log (admin_id, client_id, action, created_at) 
                      VALUES ('$this->adminId', '$this->clientId', '$this->action', '$this->createdAt')";
            
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
    
    public function getLogById($id)
    {
        $dbController = new DBController();
        if($dbController->openConnection())
        {
            $query = "SELECT * FROM admin_activity_log WHERE id = $id";
            $result = $dbController->executeQuery($query);
            
            if($result && count($result) > 0)
            {
                $this->id = $result[0]['id'];
                $this->adminId = $result[0]['admin_id'];
                $this->clientId = $result[0]['client_id'];
                $this->action = $result[0]['action'];
                $this->createdAt = $result[0]['created_at'];
                
                $dbController->closeConnection();
                return true;
            }
            
            $dbController->closeConnection();
        }
        
        return false;
    }
    
    public function getAllLogs()
    {
        $dbController = new DBController();
        if($dbController->openConnection())
        {
            $query = "SELECT * FROM admin_activity_log ORDER BY created_at DESC";
            $result = $dbController->executeQuery($query);
            
            $dbController->closeConnection();
            return $result;
        }
        
        return false;
    }
    
    public function getLogsByAdminId($adminId)
    {
        $dbController = new DBController();
        if($dbController->openConnection())
        {
            $query = "SELECT * FROM admin_activity_log WHERE admin_id = $adminId ORDER BY created_at DESC";
            $result = $dbController->executeQuery($query);
            
            $dbController->closeConnection();
            return $result;
        }
        
        return false;
    }
    
    public function getLogsByClientId($clientId)
    {
        $dbController = new DBController();
        if($dbController->openConnection())
        {
            $query = "SELECT * FROM admin_activity_log WHERE client_id = $clientId ORDER BY created_at DESC";
            $result = $dbController->executeQuery($query);
            
            $dbController->closeConnection();
            return $result;
        }
        
        return false;
    }
    
    public function getLogsByAction($action)
    {
        $dbController = new DBController();
        if($dbController->openConnection())
        {
            $query = "SELECT * FROM admin_activity_log WHERE action LIKE '%$action%' ORDER BY created_at DESC";
            $result = $dbController->executeQuery($query);
            
            $dbController->closeConnection();
            return $result;
        }
        
        return false;
    }
    
    public function getLogsByDateRange($startDate, $endDate)
    {
        $dbController = new DBController();
        if($dbController->openConnection())
        {
            $query = "SELECT * FROM admin_activity_log WHERE created_at BETWEEN '$startDate' AND '$endDate' ORDER BY created_at DESC";
            $result = $dbController->executeQuery($query);
            
            $dbController->closeConnection();
            return $result;
        }
        
        return false;
    }
    
    public function deleteLog($id)
    {
        $dbController = new DBController();
        if($dbController->openConnection())
        {
            $query = "DELETE FROM admin_activity_log WHERE id = $id";
            $result = $dbController->connection->query($query);
            
            $dbController->closeConnection();
            return $result;
        }
        
        return false;
    }
    
    public function clearLogs()
    {
        $dbController = new DBController();
        if($dbController->openConnection())
        {
            $query = "TRUNCATE TABLE admin_activity_log";
            $result = $dbController->connection->query($query);
            
            $dbController->closeConnection();
            return $result;
        }
        
        return false;
    }
}
?>