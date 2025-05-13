<?php
require_once("DbController.php");

class AuthenticationController {
    private $dbController;
    
    public function __construct(?DBController $dbController = null) {
        $this->dbController = $dbController ?? DBController::getInstance();
    }
    
    public function login($email, $password) {
        if($this->dbController->openConnection()) {
            $query = "SELECT * FROM users WHERE email='$email' AND password='$password'";
            $result = $this->dbController->executeQuery($query);
            if($result && count($result) > 0) {
                return true;
            }
            else {
                return false;
            }
        }
        else {
            echo "Error in connection";
            return false;
        }
    }
}
?>