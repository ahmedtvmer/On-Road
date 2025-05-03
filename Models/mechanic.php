<?php
require_once 'DbController.php';

class Mechanic
{
    private $id;
    private $fullName;
    private $email;
    private $username;
    private $password;
    private $location;
    private $specialization;
    private $experience;
    const ROLE = 'mechanic';
    
    public function __construct($id = "", $fullName = "", $email = "", $username = "", $password = "", $location = "", $specialization = "", $experience = "")
    {
        $this->id = $id;
        $this->fullName = $fullName;
        $this->email = $email;
        $this->username = $username;
        $this->password = $password;
        $this->location = $location;
        $this->specialization = $specialization;
        $this->experience = $experience;
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
    
    public function getLocation()
    {
        return $this->location;
    }
    
    public function getSpecialization()
    {
        return $this->specialization;
    }
    
    public function getExperience()
    {
        return $this->experience;
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
        $this->password = $password;
    }
    
    public function setLocation($location)
    {
        $this->location = $location;
    }
    
    public function setSpecialization($specialization)
    {
        $this->specialization = $specialization;
    }
    
    public function setExperience($experience)
    {
        $this->experience = $experience;
    }
    
    public function login($username, $password)
    {
        $dbController = new DBController();
        if($dbController->openConnection())
        {
            $query = "SELECT * FROM mechanic WHERE username = '$username' AND password = '$password'";
            $result = $dbController->executeQuery($query);
            
            if($result && count($result) > 0)
            {
                $this->id = $result[0]['id'];
                $this->fullName = $result[0]['fullName'];
                $this->email = $result[0]['email'];
                $this->username = $result[0]['username'];
                $this->password = $result[0]['password'];
                $this->location = $result[0]['location'];
                $this->specialization = $result[0]['specialization'];
                $this->experience = $result[0]['experience'];
                
                $dbController->closeConnection();
                return true;
            }
            
            $dbController->closeConnection();
        }
        
        return false;
    }
    
    public function register()
    {
        $dbController = new DBController();
        if($dbController->openConnection())
        {
            $query = "INSERT INTO mechanic (fullName, email, username, password, location, specialization, experience) 
                      VALUES ('$this->fullName', '$this->email', '$this->username', '$this->password', '$this->location', '$this->specialization', '$this->experience')";
            
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
    
    public function getMechanicById($id)
    {
        $dbController = new DBController();
        if($dbController->openConnection())
        {
            $query = "SELECT * FROM mechanic WHERE id = $id";
            $result = $dbController->executeQuery($query);
            
            if($result && count($result) > 0)
            {
                $this->id = $result[0]['id'];
                $this->fullName = $result[0]['fullName'];
                $this->email = $result[0]['email'];
                $this->username = $result[0]['username'];
                $this->password = $result[0]['password'];
                $this->location = $result[0]['location'];
                $this->specialization = $result[0]['specialization'];
                $this->experience = $result[0]['experience'];
                
                $dbController->closeConnection();
                return true;
            }
            
            $dbController->closeConnection();
        }
        
        return false;
    }
    
    public function updateMechanic()
    {
        $dbController = new DBController();
        if($dbController->openConnection())
        {
            $query = "UPDATE mechanic SET 
                      fullName = '$this->fullName', 
                      email = '$this->email', 
                      username = '$this->username', 
                      password = '$this->password',
                      location = '$this->location',
                      specialization = '$this->specialization',
                      experience = '$this->experience'
                      WHERE id = $this->id";
            
            $result = $dbController->connection->query($query);
            
            $dbController->closeConnection();
            return $result;
        }
        
        return false;
    }
    
    public function deleteMechanic($id)
    {
        $dbController = new DBController();
        if($dbController->openConnection())
        {
            $query = "DELETE FROM mechanic WHERE id = $id";
            $result = $dbController->connection->query($query);
            
            $dbController->closeConnection();
            return $result;
        }
        
        return false;
    }
    
    public function getAllMechanics()
    {
        $dbController = new DBController();
        if($dbController->openConnection())
        {
            $query = "SELECT * FROM mechanic";
            $result = $dbController->executeQuery($query);
            
            $dbController->closeConnection();
            return $result;
        }
        
        return false;
    }
    
    public function getMechanicsByLocation($location)
    {
        $dbController = new DBController();
        if($dbController->openConnection())
        {
            $query = "SELECT * FROM mechanic WHERE location LIKE '%$location%'";
            $result = $dbController->executeQuery($query);
            
            $dbController->closeConnection();
            return $result;
        }
        
        return false;
    }
    
    public function getMechanicsBySpecialization($specialization)
    {
        $dbController = new DBController();
        if($dbController->openConnection())
        {
            $query = "SELECT * FROM mechanic WHERE specialization LIKE '%$specialization%'";
            $result = $dbController->executeQuery($query);
            
            $dbController->closeConnection();
            return $result;
        }
        
        return false;
    }
    
    public function checkUsernameExists($username)
    {
        $dbController = new DBController();
        if($dbController->openConnection())
        {
            $query = "SELECT * FROM mechanic WHERE username = '$username'";
            $result = $dbController->executeQuery($query);
            
            $dbController->closeConnection();
            return ($result && count($result) > 0);
        }
        
        return false;
    }
    
    public function checkEmailExists($email)
    {
        $dbController = new DBController();
        if($dbController->openConnection())
        {
            $query = "SELECT * FROM mechanic WHERE email = '$email'";
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