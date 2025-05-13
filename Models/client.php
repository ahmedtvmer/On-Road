<?php
require_once 'user.php';
require_once __DIR__ . "/../Controllers/DbController.php";

class Client extends User
{
    const ROLE = 'client';
    
    public function __construct($id = "", $fullName = "", $email = "", $username = "", $password = "", ?DBController $dbController = null)
    {
        parent::__construct($id, $username, $password, $fullName, $email, $dbController);
    }
    
    public function login($username, $password)
    {
        if($this->dbController->openConnection())
        {
            $query = "SELECT * FROM clients WHERE username = '$username'";
            $result = $this->dbController->executeQuery($query);
            
            if($result && count($result) > 0)
            {
                if($this->verifyPassword($password, $result[0]['password'])) {
                    $this->setId($result[0]['id']);
                    $this->setFullName($result[0]['fullName']);
                    $this->setEmail($result[0]['email']);
                    $this->setUsername($result[0]['username']);
                    $this->setPassword($result[0]['password']);
                    
                    return true;
                }
            }
        }
        
        return false;
    }
    
    public function register()
    {
        $hashedPassword = $this->hashPassword($this->getPassword());
        
        if($this->dbController->openConnection())
        {
            $query = "INSERT INTO clients (fullName, email, username, password, role) 
                      VALUES ('".$this->getFullName()."', '".$this->getEmail()."', '".$this->getUsername()."', '$hashedPassword', '" . self::ROLE . "')";
            
            $result = $this->dbController->connection->query($query);
            
            if($result)
            {
                $this->setId($this->dbController->connection->insert_id);
                return true;
            }
            else
            {
                echo "Database Error: " . $this->dbController->connection->error;
            }
        }
        
        return false;
    }
    
    public function getClientById($id)
    {
        if($this->dbController->openConnection())
        {
            $query = "SELECT * FROM clients WHERE id = $id";
            $result = $this->dbController->executeQuery($query);
            
            if($result && count($result) > 0)
            {
                $this->setId($result[0]['id']);
                $this->setFullName($result[0]['fullName']);
                $this->setEmail($result[0]['email']);
                $this->setUsername($result[0]['username']);
                $this->setPassword($result[0]['password']);
                
                return true;
            }
        }
        
        return false;
    }
    
    public function updateUser()
    {
        return $this->updateUser('clients');
    }
    
    public function deleteUser($id)
    {
        if($this->dbController->openConnection())
        {
            $query = "DELETE FROM clients WHERE id = $id";
            $result = $this->dbController->connection->query($query);
            
            return $result;
        }
        
        return false;
    }
    
    public function getRole()
    {
        return self::ROLE;
    }
    
    public function getClientCount() 
    {
        if($this->dbController->openConnection())
        {
            $query = "SELECT COUNT(*) as count FROM clients";
            $stmt = $this->dbController->connection->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = $result->fetch_assoc();
            return $data['count'];
        }
        
        return 0;
    }
}
?>