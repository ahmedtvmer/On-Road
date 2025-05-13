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
        $this->dbController = $dbController ?? DBController::getInstance();
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
    
    
    public function updatePassword($newPassword, $table)
    {
        $hashedPassword = $this->hashPassword($newPassword);
        
        if($this->dbController->openConnection())
        {
            $query = "UPDATE $table SET 
                      password = '$hashedPassword'
                      WHERE id = '$this->id'";
            
            $result = $this->dbController->executeQuery($query);
            
            if($result)
            {
                $this->password = $hashedPassword;
                return true;
            }
        }
        
        return false;
    }
    abstract public function updateUser();
    abstract public function deleteUser($id);
    abstract public function login($username, $password);
    abstract public function register();
    abstract public function getRole();
}
?>