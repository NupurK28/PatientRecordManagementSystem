<?php 

class Database{
   private $host = "localhost";
   private $username = "root";
   private $password = "";
   private $dbname = "nba_health";
   public $conn; 

   public function connect(){
    $this->conn = new mysqli($this->host, $this->username, $this->password, $this->dbname);
    if($this->conn->connect_error){
        die("Database Connection Failed: ".$this->conn->connect_error);
    }
    return $this->conn;
   }
}

?>