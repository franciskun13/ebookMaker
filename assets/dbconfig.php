<?php
class Database
{   
    private $host = "localhost";
    private $db_name = "ebook-maker";
    private $username = "root";
    private $password = "";

    public $conn;
    public function dbConnection()
	{
	    $this->conn = null;    
        try
		{
            // localhost MySQL port is 3307, otherwise remove 
            //inside the single quote '. ";port=3307"'  
            $this->conn = new PDO("mysql:host=" . ";port=3306" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	
        }

		catch(PDOException $exception)
		{
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
?>