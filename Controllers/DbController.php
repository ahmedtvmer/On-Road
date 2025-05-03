<?php

class DBController
{
    public $dbHost="localhost:8080";
    public $dbUser="root";
    public $dbPassword="";
    public $dbName="on-road";
    public $connection;

    public function openConnection()
    {
        $this->connection=new mysqli($this->dbHost,$this->dbUser,$this->dbPassword,$this->dbName);
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
        $result=$this->connection->query($query);
        if($result)
        {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        else
        {
            return false;
        }
    }
}
?>