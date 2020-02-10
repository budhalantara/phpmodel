<?php
session_start();
include_once 'Auth.php';

if(array_key_exists('loggedIn', $_SESSION) && $_SESSION['loggedIn'])
  header("Location: index.php");

$auth = new Auth;
if(isset($_POST['submit']) && $_POST['submit']) {
  if(isset($_POST['email']) && $_POST['email'] && isset($_POST['password']) && $_POST['password']) {
    $data = [
      'email' => $_POST['email'],
      'password' => $_POST['password']
    ];
    $login = $auth->login($data);
    if($login) {
      $_SESSION['userId'] = $login->id;
      $_SESSION['loggedIn'] = true;
      header("Location: index.php");
    } else {
      $_SESSION['user'] = null;
      $_SESSION['loggedIn'] = false;
      header("Location: login.php?gagal=1");
    }
  }
}
if(isset($_GET['gagal']) && $_GET['gagal'] == '1')
  echo "Login gagal";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Login</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body>
  <form method="POST">
  <div class="container mt-5">
    <div class="row">
      <div class="col-md-6 offset-3">
        <div class="form-group">
          <label>Email</label>
          <input type="email" class="form-control" name="email" aria-describedby="emailHelp">
        </div>
        <div class="form-group">
          <label>Password</label>
          <input type="password" class="form-control" name="password">
        </div>
        <button type="submit" name="submit" value="submit" class="btn btn-primary">Login</button>
        <span class="ml-3 mr-1">Belum punya akun ?</span><a href="register.php">Register</a>
      </div>
    </div>
  </div>
  </form>
</body>
</html>