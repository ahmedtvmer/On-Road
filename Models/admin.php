<?php
require_once __DIR__ . "/../Controllers/DbController.php";


class Admin
{
    private $id;
    private $fullName;
    private $email;
    private $username;
    private $adminCode;
    private $password;
    const ROLE = 'admin';
    
    public function __construct($id = "", $fullName = "", $email = "", $username = "", $adminCode = "", $password = "")
    {
        $this->id = $id;
        $this->fullName = $fullName;
        $this->email = $email;
        $this->username = $username;
        $this->adminCode = $adminCode;
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
    
    public function getAdminCode()
    {
        return $this->adminCode;
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
    
    public function setAdminCode($adminCode)
    {
        $this->adminCode = $adminCode;
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
            $query = "SELECT * FROM admins WHERE username = '$username'";
            $result = $dbController->executeQuery($query);
            
            if($result && count($result) > 0)
            {
                    if(password_verify($password, $result[0]['password'])) {
                    $this->id = $result[0]['id'];
                    $this->fullName = $result[0]['fullName'];
                    $this->email = $result[0]['email'];
                    $this->username = $result[0]['username'];
                    $this->adminCode = $result[0]['adminCode'];
                    $this->password = $result[0]['password'];
                    
                    $dbController->closeConnection();
                    return true;
                }
            }
            
            $dbController->closeConnection();
        }
        
        return false;
    }
    
    public function register()
    {
        // Hash the password before storing
        $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);
        
        $dbController = new DBController();
        if($dbController->openConnection())
        {
            $query = "INSERT INTO admins (fullName, email, username, adminCode, password, role) 
                      VALUES ('$this->fullName', '$this->email', '$this->username', '$this->adminCode', '$hashedPassword', '" . self::ROLE . "')";
            
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
    
    public function getAdminById($id)
    {
        $dbController = new DBController();
        if($dbController->openConnection())
        {
            $query = "SELECT * FROM admins WHERE id = $id";
            $result = $dbController->executeQuery($query);
            
            if($result && count($result) > 0)
            {
                $this->id = $result[0]['id'];
                $this->fullName = $result[0]['fullName'];
                $this->email = $result[0]['email'];
                $this->username = $result[0]['username'];
                $this->adminCode = $result[0]['adminCode'];
                $this->password = $result[0]['password'];
                
                $dbController->closeConnection();
                return true;
            }
            
            $dbController->closeConnection();
        }
        
        return false;
    }
    
    public function updateAdmin()
    {
        $dbController = new DBController();
        if($dbController->openConnection())
        {
            // Check if password needs to be updated (if it's not already hashed)
            if(!password_get_info($this->password)['algo']) {
                $this->password = password_hash($this->password, PASSWORD_DEFAULT);
            }
            
            $query = "UPDATE admins SET 
                      fullName = '$this->fullName', 
                      email = '$this->email',
                      username = '$this->username', 
                      adminCode = '$this->adminCode', 
                      password = '$this->password' 
                      WHERE id = $this->id";
            
            $result = $dbController->connection->query($query);
            
            $dbController->closeConnection();
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
            
            $dbController->closeConnection();
            return $result;
        }
        
        return false;
    }
    
    public function getAllAdmins()
    {
        $dbController = new DBController();
        if($dbController->openConnection())
        {
            $query = "SELECT * FROM admins";
            $result = $dbController->executeQuery($query);
            
            $dbController->closeConnection();
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
            
            $dbController->closeConnection();
            return ($result && count($result) > 0);
        }
        
        return false;
    }
    
    public function checkUsernameExists($username)
    {
        $dbController = new DBController();
        if($dbController->openConnection())
        {
            $query = "SELECT * FROM admins WHERE username = '$username'";
            $result = $dbController->executeQuery($query);
            
            $dbController->closeConnection();
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