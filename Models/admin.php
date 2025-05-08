<?php
require_once 'user.php';
require_once __DIR__ . "/../Controllers/DbController.php";

class Admin extends User
{
    private $adminCode;
    const ROLE = 'admin';
    
    public function __construct($id = "", $fullName = "", $email = "", $username = "", $adminCode = "", $password = "")
    {
        parent::__construct($id, $username, $password, $fullName, $email);
        $this->adminCode = $adminCode;
    }
    
    public function getAdminCode()
    {
        return $this->adminCode;
    }
    
    public function setAdminCode($adminCode)
    {
        $this->adminCode = $adminCode;
    }
    
    public function login($username, $password)
    {
        $dbController = new DBController();
        if($dbController->openConnection())
        {
            $query = "SELECT * FROM admins WHERE username = '$username'";
            $result = $dbController->executeQuery($query);
            
            if($result && count($result) > 0)
            {
                if($this->verifyPassword($password, $result[0]['password'])) {
                    $this->setId($result[0]['id']);
                    $this->setFullName($result[0]['fullName']);
                    $this->setEmail($result[0]['email']);
                    $this->setUsername($result[0]['username']);
                    $this->adminCode = $result[0]['adminCode'];
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
        
        $dbController = new DBController();
        if($dbController->openConnection())
        {
            $query = "INSERT INTO admins (fullName, email, username, adminCode, password, role) 
                      VALUES ('".$this->getFullName()."', '".$this->getEmail()."', '".$this->getUsername()."', '$this->adminCode', '$hashedPassword', '" . self::ROLE . "')";
            
            $result = $dbController->connection->query($query);
            
            if($result)
            {
                $this->setId($dbController->connection->insert_id);
                return true;
            }
        }
        
        return false;
    }
    
    public function getAdminById($id)
    {
        $dbController = new DBController();
        if($dbController->openConnection())
        {
            $query = "SELECT * FROM admins WHERE id = $id";
            $result = $dbController->executeQuery($query);
            
            if($result && count($result) > 0)
            {
                $this->setId($result[0]['id']);
                $this->setFullName($result[0]['fullName']);
                $this->setEmail($result[0]['email']);
                $this->setUsername($result[0]['username']);
                $this->adminCode = $result[0]['adminCode'];
                $this->setPassword($result[0]['password']);
                
                return true;
            }
        }
        
        return false;
    }
    
    public function updateAdmin()
    {
        $dbController = new DBController();
        if($dbController->openConnection())
        {
            $query = "UPDATE admins SET 
                      fullName = '".$this->getFullName()."', 
                      email = '".$this->getEmail()."',
                      username = '".$this->getUsername()."', 
                      adminCode = '$this->adminCode', 
                      password = '".$this->getPassword()."' 
                      WHERE id = ".$this->getId();
            
            $result = $dbController->connection->query($query);
            
            return $result;
        }
        
        return false;
    }
    
    public function deleteAdmin($id)
    {
        $dbController = new DBController();
        if($dbController->openConnection())
        {
            $query = "DELETE FROM admins WHERE id = $id";
            $result = $dbController->connection->query($query);
            
            return $result;
        }
        
        return false;
    }
    
    public function verifyAdminCode($code)
    {
        $dbController = new DBController();
        if($dbController->openConnection())
        {
            $query = "SELECT * FROM admin_codes WHERE code = '$code' AND is_used = 0";
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