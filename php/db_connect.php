<?php
class Connection{
    private $host;
    private $dbname;
    private $username;
    private $password;
    public $conn;
    
    public function __construct(){
        // Database connection details
        $this->host = "localhost";
        $this->dbname = "quizsystem";
        $this->username = "root";
        $this->password = "";

        // Establishing database connection
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->dbname);
        if ($this->conn->connect_error){
            die("Connection Failed : " . $this->conn->connect_error);
        }
    }
}
?>