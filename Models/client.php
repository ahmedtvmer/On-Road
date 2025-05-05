<?php
require_once __DIR__ . "/../Controllers/DbController.php";

class Client
{
    private $id;
    private $fullName;
    private $email;
    private $username;
    private $password;
    const ROLE = 'client';
    
    public function __construct($id = "", $fullName = "", $email = "", $username = "", $password = "")
    {
        $this->id = $id;
        $this->fullName = $fullName;
        $this->email = $email;
        $this->username = $username;
        $this->password = $password;
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getFullName()
    {
        return $this->fullName;
    }
    
    public function getEmail()
    {
        return $this->email;
    }
    
    public function getUsername()
    {
        return $this->username;
    }
    
    public function getPassword()
    {
        return $this->password;
    }
    
    public function setId($id)
    {
        $this->id = $id;
    }
    
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;
    }
    
    public function setEmail($email)
    {
        $this->email = $email;
    }
    
    public function setUsername($username)
    {
        $this->username = $username;
    }
    
    public function setPassword($password)
    {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }
    
    public function login($username, $password)
    {
        $dbController = new DBController();
        if($dbController->openConnection())
        {
            $query = "SELECT * FROM clients WHERE username = '$username'";
            $result = $dbController->executeQuery($query);
            
            if($result && count($result) > 0)
            {
                if(password_verify($password, $result[0]['password'])) {
                    $this->id = $result[0]['id'];
                    $this->fullName = $result[0]['fullName'];
                    $this->email = $result[0]['email'];
                    $this->username = $result[0]['username'];
                    $this->password = $result[0]['password']; 
                    
                    return true;
                }
            }
            
        }
        
        return false;
    }
    
    public function register()
    {
        $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);
        
        $dbController = new DBController();
        if($dbController->openConnection())
        {
            $query = "INSERT INTO clients (fullName, email, username, password, role) 
                      VALUES ('$this->fullName', '$this->email', '$this->username', '$hashedPassword', '" . self::ROLE . "')";
            
            $result = $dbController->connection->query($query);
            
            if($result)
            {
                $this->id = $dbController->connection->insert_id;
                return true;
            }
            else
            {
                echo "Database Error: " . $dbController->connection->error;
            }
            
        }
        
        return false;
    }
    
    public function getClientById($id)
    {
        $dbController = new DBController();
        if($dbController->openConnection())
        {
            $query = "SELECT * FROM clients WHERE id = $id";
            $result = $dbController->executeQuery($query);
            
            if($result && count($result) > 0)
            {
                $this->id = $result[0]['id'];
                $this->fullName = $result[0]['fullName'];
                $this->email = $result[0]['email'];
                $this->username = $result[0]['username'];
                $this->password = $result[0]['password'];
                
                return true;
            }
            
        }
        
        return false;
    }
    
    public function updateClient()
    {
        $dbController = new DBController();
        if($dbController->openConnection())
        {
            if(!password_get_info($this->password)['algo']) {
                $this->password = password_hash($this->password, PASSWORD_DEFAULT);
            }
            
            $query = "UPDATE clients SET 
                      fullName = '$this->fullName', 
                      email = '$this->email', 
                      username = '$this->username', 
                      password = '$this->password' 
                      WHERE id = $this->id";
            
            $result = $dbController->connection->query($query);
            
            return $result;
        }
        
        return false;
    }
    
    public function deleteClient($id)
    {
        $dbController = new DBController();
        if($dbController->openConnection())
        {
            $query = "DELETE FROM clients WHERE id = $id";
            $result = $dbController->connection->query($query);
            
            return $result;
        }
        
        return false;
    }
    
    public function getAllClients()
    {
        $dbController = new DBController();
        if($dbController->openConnection())
        {
            $query = "SELECT * FROM clients";
            $result = $dbController->executeQuery($query);
            
            return $result;
        }
        
        return false;
    }
    
    public function checkUsernameExists($username)
    {
        $dbController = new DBController();
        if($dbController->openConnection())
        {
            $query = "SELECT * FROM clients WHERE username = '$username'";
            $result = $dbController->executeQuery($query);
            
            return ($result && count($result) > 0);
        }
        
        return false;
    }
    
    public function checkEmailExists($email)
    {
        $dbController = new DBController();
        if($dbController->openConnection())
        {
            $query = "SELECT * FROM clients WHERE email = '$email'";
            $result = $dbController->executeQuery($query);
            
            return ($result && count($result) > 0);
        }
        
        return false;
    }
    
    public function getRole()
    {
        return self::ROLE;
    }
}
?>