<?php
require_once __DIR__ . "/../Controllers/DbController.php";

abstract class User
{
    protected $id;
    protected $username;
    protected $password;
    protected $fullName;
    protected $email;
    protected $dbController;
    
    public function __construct($id = "", $username = "", $password = "", $fullName = "", $email = "", ?DBController $dbController = null)
    {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->fullName = $fullName;
        $this->email = $email;
        $this->dbController = $dbController ?? new DBController();
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getUsername()
    {
        return $this->username;
    }
    
    public function getPassword()
    {
        return $this->password;
    }
    
    public function getFullName()
    {
        return $this->fullName;
    }
    
    public function getEmail()
    {
        return $this->email;
    }
    
    public function setId($id)
    {
        $this->id = $id;
    }
    
    public function setUsername($username)
    {
        $this->username = $username;
    }
    
    public function setPassword($password)
    {
        $this->password = $password;
    }
    
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;
    }
    
    public function setEmail($email)
    {
        $this->email = $email;
    }
    
    protected function hashPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }
    
    protected function verifyPassword($inputPassword, $hashedPassword)
    {
        return password_verify($inputPassword, $hashedPassword);
    }
    
    protected function updateUser($table)
    {
        $dbController = new DBController();
        if($dbController->openConnection())
        {
            $query = "UPDATE $table SET 
                      username = '$this->username',
                      full_name = '$this->fullName',
                      email = '$this->email',
                      WHERE id = '$this->id'";
            
            $result = $dbController->executeQuery($query);
            
            if($result)
            {
                return true;
            }
        }
        
        return false;
    }
    
    public function updatePassword($newPassword, $table)
    {
        $hashedPassword = $this->hashPassword($newPassword);
        
        $dbController = new DBController();
        if($dbController->openConnection())
        {
            $query = "UPDATE $table SET 
                      password = '$hashedPassword'
                      WHERE id = '$this->id'";
            
            $result = $dbController->executeQuery($query);
            
            if($result)
            {
                $this->password = $hashedPassword;
                return true;
            }
        }
        
        return false;
    }
    abstract public function login($username, $password);
    abstract public function register();
    abstract public function getRole();
}
?>