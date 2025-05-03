<?php
require_once("DbController.php");
class AuthenticationController{
    
    public function login($email,$password){
        $db=new DBController();
        if($db->openConnection()){
            $query="SELECT * FROM users WHERE email='$email' AND password='$password'";
            $result = $db->executeQuery($query);
            if($result->num_rows>0){
                return true;
            }
            else{
                return false;
            }
        }
        else{
            echo "Error in connection";
            return false;
        }
    }
}





?>