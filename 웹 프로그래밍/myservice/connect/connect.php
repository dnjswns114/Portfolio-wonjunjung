<?php
  $host = "localhost";
  $user = "root";
  $pw = "root";
  $dbName = "myservice";

  if($this->dbConnection == null){
    $this->dbConnection = new mysqli($host, $user, $pw, $dbName);
    $this->dbConnection->set_charset("utf8");
  }
?>
