<?php
class Database {

  private $host = 'localhost';
  private $db = 'simple_login';
  private $username = 'root';
  private $password = '';
  public $conn = null;

  function __construct() {
    try {
      $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        PDO::ATTR_EMULATE_PREPARES   => false,
      ];
      $this->conn = new PDO("mysql:host=$this->host;dbname=$this->db", $this->username, $this->password, $options);
    } catch (PDOException $e) {
      echo $e->getMessage();
    }
  }

  public function destroy() {
    $this->conn = null; 
  }
}