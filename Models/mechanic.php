<?php
require_once 'user.php';
require_once __DIR__ . "/../Controllers/DbController.php";

class Mechanic extends User
{
    private $location;
    private $specialization;
    private $experience;
    private $rating;
    private $totalReviews;
    const ROLE = 'mechanic';
    
    public function __construct($id = "", $fullName = "", $email = "", $username = "", $password = "", $location = "", $specialization = "", $experience = "", $rating = 0, $totalReviews = 0)
    {
        parent::__construct($id, $username, $password, $fullName, $email);
        $this->location = $location;
        $this->specialization = $specialization;
        $this->experience = $experience;
        $this->rating = $rating;
        $this->totalReviews = $totalReviews;
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
            $query = "SELECT * FROM mechanics WHERE username = '$username'";
            $result = $dbController->executeQuery($query);
            
            if($result && count($result) > 0)
            {
                if($this->verifyPassword($password, $result[0]['password'])) {
                    $this->setId($result[0]['id']);
                    $this->setFullName($result[0]['fullName']);
                    $this->setEmail($result[0]['email']);
                    $this->setUsername($result[0]['username']);
                    $this->setPassword($result[0]['password']);
                    $this->location = $result[0]['location'];
                    $this->specialization = $result[0]['specialization'];
                    $this->experience = $result[0]['experience'];
                    $this->rating = $result[0]['rating'] ?? 0;
                    $this->totalReviews = $result[0]['totalReviews'] ?? 0;
                    
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
            $query = "INSERT INTO mechanics (
                fullName, email, username, password, location, specialization, experience, rating, totalReviews, role
            ) VALUES (
                '".$this->getFullName()."', '".$this->getEmail()."', '".$this->getUsername()."', '{$hashedPassword}', '{$this->location}',
                '{$this->specialization}', '{$this->experience}', {$this->rating}, {$this->totalReviews}, '" . self::ROLE . "'
            )";
    
            
            $result = $dbController->connection->query($query);
            
            if($result)
            {
                $this->setId($dbController->connection->insert_id);
                return true;
            }
            
        }
        
        return false;
    }
    
    public function getMechanicById($id)
    {
        $dbController = new DBController();
        if($dbController->openConnection())
        {
            $query = "SELECT * FROM mechanics WHERE id = $id";
            $result = $dbController->executeQuery($query);
            
            if($result && count($result) > 0)
            {
                $this->setId($result[0]['id']);
                $this->setFullName($result[0]['fullName']);
                $this->setEmail($result[0]['email']);
                $this->setUsername($result[0]['username']);
                $this->setPassword($result[0]['password']);
                $this->location = $result[0]['location'];
                $this->specialization = $result[0]['specialization'];
                $this->experience = $result[0]['experience'];
                $this->rating = $result[0]['rating'] ?? 0;
                $this->totalReviews = $result[0]['totalReviews'] ?? 0;
                
                return true;
            }
            
        }
        
        return false;
    }
    
    public function updateMechanic()
    {
        $dbController = new DBController();
        if($dbController->openConnection())
        {
            $query = "UPDATE mechanics SET 
                      fullName = '".$this->getFullName()."', 
                      email = '".$this->getEmail()."', 
                      username = '".$this->getUsername()."', 
                      password = '".$this->getPassword()."',
                      location = '$this->location',
                      specialization = '$this->specialization',
                      experience = '$this->experience',
                      rating = $this->rating,
                      totalReviews = $this->totalReviews
                      WHERE id = ".$this->getId();    
            
            $result = $dbController->connection->query($query);
            
            return $result;
        }
        
        return false;
    }
    
    public function deleteMechanic($id)
    {
        $dbController = new DBController();
        if($dbController->openConnection())
        {
            $query = "DELETE FROM mechanics WHERE id = $id";
            $result = $dbController->connection->query($query);
            
            return $result;
        }
        
        return false;
    }
    
    public function getAllMechanics()
    {
        $dbController = new DBController();
        if($dbController->openConnection())
        {
            $query = "SELECT * FROM mechanics";
            $result = $dbController->executeQuery($query);
            
            return $result;
        }
        
        return false;
    }
    
    public function getMechanicsByLocation($location)
    {
        $dbController = new DBController();
        if($dbController->openConnection())
        {
            $query = "SELECT * FROM mechanics WHERE location LIKE '%$location%'";
            $result = $dbController->executeQuery($query);
            
            return $result;
        }
        
        return false;
    }
    
    public function getMechanicsBySpecialization($specialization)
    {
        $dbController = new DBController();
        if($dbController->openConnection())
        {
            $query = "SELECT * FROM mechanics WHERE specialization LIKE '%$specialization%'";
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
            $query = "SELECT * FROM mechanics WHERE username = '$username'";
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
            $query = "SELECT * FROM mechanics WHERE email = '$email'";
            $result = $dbController->executeQuery($query);
            
            return ($result && count($result) > 0);
        }
        
        return false;
    }
    
    public function getRole()
    {
        return self::ROLE;
    }
    
    public function getRating()
    {
        return $this->rating;
    }
    
    public function getTotalReviews()
    {
        return $this->totalReviews;
    }
    
    public function setRating($rating)
    {
        $this->rating = $rating;
    }
    
    public function setTotalReviews($totalReviews)
    {
        $this->totalReviews = $totalReviews;
    }
    
    public function addReview($reviewRating)
    {
        $currentTotalPoints = $this->rating * $this->totalReviews;
        $newTotalPoints = $currentTotalPoints + $reviewRating;
        $this->totalReviews++;
        $this->rating = $this->totalReviews > 0 ? $newTotalPoints / $this->totalReviews : 0;
        
        return $this->updateMechanic();
    }
    
    public function getTopRatedMechanics($limit = 5)
    {
        $dbController = new DBController();
        if($dbController->openConnection())
        {
            $query = "SELECT * FROM mechanics WHERE totalReviews > 0 ORDER BY rating DESC LIMIT $limit";
            $result = $dbController->executeQuery($query);
            
            return $result;
        }
        
        return false;
    }
}
?>