<?php

class DBController
{
    private $dbHost;
    private $dbUser;
    private $dbPassword;
    private $dbName;
    public $connection;

    public function __construct($dbHost = "localhost", $dbUser = "root", $dbPassword = "", $dbName = "on-road")
    {
        $this->dbHost = $dbHost;
        $this->dbUser = $dbUser;
        $this->dbPassword = $dbPassword;
        $this->dbName = $dbName;
    }

    public function openConnection()
    {
        $this->connection = new mysqli($this->dbHost, $this->dbUser, $this->dbPassword, $this->dbName);
        if($this->connection->connect_error)
        {
            echo " Error in Connection : ".$this->connection->connect_error;
            return false;
        }
        else
        {
            return true;
        }
    }
    
    public function closeConnection()
    {
        if($this->connection)
        {
            $this->connection->close();
        }
        else
            echo "Error in Closing Connection";
    }

    public function executeQuery($query)
    {
        if (!$this->connection) {
            if (!$this->openConnection()) {
                return false;
            }
        }
        $result = $this->connection->query($query);
        if($result)
        {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        else
        {
            return false;
        }
    }

    public function __destruct() {
        $this->closeConnection();
    }
}
?>