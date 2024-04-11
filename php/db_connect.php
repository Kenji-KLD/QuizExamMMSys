<?php
class Connection{
    private $host;
    private $dbname;
    private $username;
    private $password;
    
    public function __construct(){
        // Database connection details
        $this->host = "localhost";
        $this->dbname = "quizsystem";
        $this->username = "root";
        $this->password = "";

        // Establishing database connection
        $conn = new mysqli($this->host, $this->username, $this->password, $this->dbname);
        if ($conn->connect_error){
            die("Connection Failed : " . $conn->connect_error);
        }
        else{
            return $conn;
        }
    }
}
?>