<?php
session_start();

class ValidationController {
    public static function validateSession($requiredRole) {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== $requiredRole) {
            header("Location: login.php");
            exit();
        }
        return true;
    }
}
?>