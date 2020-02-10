<?php
include_once 'config/Database.php';

class Auth extends Database
{
  public function login($data) {
    $stmt = $this->conn->prepare("SELECT id FROM users WHERE email = :email AND password = :password LIMIT 1");
    $stmt->execute($data);
    $res = $stmt->fetch();
    $this->destroy();
    return $res;
  }

  public function register($data) {
    $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
    $stmt->execute(['email' => $data['email']]);
    $res = $stmt->fetch();

    if($res && count($res) > 0) {
      $res = false;
      $message = "Username sudah terdaftar";
    } else {
      $stmt = $conn->prepare("INSERT INTO users (name, email, password, level) VALUES (:name, :email, :password, '2')");
      $res = $stmt->execute($data);
      $message = "";
    }
    
    $this->destroy();
    return ['res' => $res, 'message' => $message];
  }

  public function getCurrentUser() {
    $stmt = $this->conn->prepare("SELECT name, email, type FROM users INNER JOIN levels ON users.level = levels.id WHERE users.id = :id");
    $stmt->execute(['id' => $_SESSION['userId']]);
    $res = $stmt->fetch();
    $this->destroy();
    return $res;
  }
}