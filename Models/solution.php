<?php
require_once __DIR__ . "/../Controllers/DbController.php";

class Solution
{
    private $id;
    private $requestId;
    private $description;
    private $dbController;
    
    public function __construct($id = "", $requestId = "", $description = "", DBController $dbController = null)
    {
        $this->id = $id;
        $this->requestId = $requestId;
        $this->description = $description;
        $this->dbController = $dbController?? new DBController();
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getRequestId()
    {
        return $this->requestId;
    }
    
    public function getDescription()
    {
        return $this->description;
    }
    
    public function setId($id)
    {
        $this->id = $id;
    }
    
    public function setRequestId($requestId)
    {
        $this->requestId = $requestId;
    }
    
    public function setDescription($description)
    {
        $this->description = $description;
    }
    
    public function createSolution()
    {
         
        if($this->dbController->openConnection())
        {
            $query = "INSERT INTO solutions (request_id, description) 
                      VALUES ('$this->requestId', '$this->description')";
            
            $result = $this->dbController->connection->query($query);
            
            if($result)
            {
                $this->id = $this->dbController->connection->insert_id;
                 
                return true;
            }
            
             
        }
        
        return false;
    }
    
    public function getSolutionById($id)
    {
         
        if($this->dbController->openConnection())
        {   
            $query = "SELECT * FROM solutions WHERE id = $id";
            $result = $this->dbController->executeQuery($query);
            
            if($result && count($result) > 0)
            {
                $this->id = $result[0]['id'];
                $this->requestId = $result[0]['request_id'];
                $this->description = $result[0]['description'];
                
                 
                return true;
            }
            
             
        }
        
        return false;
    }
    
    public function updateSolution()
    {
         
        if($this->dbController->openConnection())
        {
            $query = "UPDATE solutions SET 
                      request_id = '$this->requestId', 
                      description = '$this->description'
                      WHERE id = $this->id";
            
            $result = $this->dbController->connection->query($query);
            
             
            return $result;
        }
        
        return false;
    }
    
    public function getSolutionByRequestId($requestId)
    {
         
        if($this->dbController->openConnection())
        {
            $query = "SELECT * FROM solutions WHERE request_id = $requestId";
            $result = $this->dbController->executeQuery($query);
            
            if($result && count($result) > 0)
            {
                $this->id = $result[0]['id'];
                $this->requestId = $result[0]['request_id'];
                $this->description = $result[0]['description'];
                
                 
                return true;
            }
            
             
        }
        
        return false;
    }
    
    public function checkSolutionExists($requestId)
    {
         
        if($this->dbController->openConnection())
        {
            $query = "SELECT COUNT(*) as count FROM solutions WHERE request_id = $requestId";
            $result = $this->dbController->executeQuery($query);
            
            if($result && count($result) > 0) {
                return $result[0]['count'] > 0;
            }
        }
        
        return false;
    }
}
?>