<?php
require_once __DIR__ . "/../Controllers/DbController.php";

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
    private $rating;
    private $totalReviews;
    const ROLE = 'mechanic';
    
    public function __construct($id = "", $fullName = "", $email = "", $username = "", $password = "", $location = "", $specialization = "", $experience = "", $rating = 0, $totalReviews = 0)
    {
        $this->id = $id;
        $this->fullName = $fullName;
        $this->email = $email;
        $this->username = $username;
        $this->password = $password;
        $this->location = $location;
        $this->specialization = $specialization;
        $this->experience = $experience;
        $this->rating = $rating;
        $this->totalReviews = $totalReviews;
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
        $this->password = password_hash($password, PASSWORD_DEFAULT);
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
                if(password_verify($password, $result[0]['password'])) {
                    $this->id = $result[0]['id'];
                    $this->fullName = $result[0]['fullName'];
                    $this->email = $result[0]['email'];
                    $this->username = $result[0]['username'];
                    $this->password = $result[0]['password'];
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
    
    public function register()
    {
        $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);
        
        $dbController = new DBController();
        if($dbController->openConnection())
        {
            $query = "INSERT INTO mechanics (
                fullName, email, username, password, location, specialization, experience, rating, totalReviews, role
            ) VALUES (
                '{$this->fullName}', '{$this->email}', '{$this->username}', '{$hashedPassword}', '{$this->location}',
                '{$this->specialization}', '{$this->experience}', {$this->rating}, {$this->totalReviews}, '" . self::ROLE . "'
            )";
    
            
            $result = $dbController->connection->query($query);
            
            if($result)
            {
                $this->id = $dbController->connection->insert_id;
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
                $this->id = $result[0]['id'];
                $this->fullName = $result[0]['fullName'];
                $this->email = $result[0]['email'];
                $this->username = $result[0]['username'];
                $this->password = $result[0]['password'];
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
                      fullName = '$this->fullName', 
                      email = '$this->email', 
                      username = '$this->username', 
                      password = '$this->password',
                      location = '$this->location',
                      specialization = '$this->specialization',
                      experience = '$this->experience',
                      rating = $this->rating,
                      totalReviews = $this->totalReviews
                      WHERE id = $this->id";
            
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