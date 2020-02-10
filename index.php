<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Home</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body>
<?php
session_start();
include_once 'Auth.php';
$auth = new Auth;

if(array_key_exists('loggedIn', $_SESSION) && $_SESSION['loggedIn']) {
  $currentUser = $auth->getCurrentUser();
  ?>
  Welcome, <?=$currentUser->name?>
  <br>
  Level kamu : <?=$currentUser->type?>
  <br>
  <br>
  <?php if($currentUser->type == 'admin'): ?>
    <div>
      <div>Menu Admin</div>
      <div><a href="index.php">Dashboard</a></div>
      <div><a href="list_user.php">Daftar Pengguna</a></div>
      <div><a href="barang.php">Barang</a></div>
      <div><a href="supplier.php">Supplier</a></div>
    </div>

  <?php elseif($currentUser->type == 'user'): ?>
    <div>
      <div>Menu User</div>
      <div><a href="index.php">Dashboard</a></div>
    </div>

  <?php endif ?>

  <br>
  <a href="logout.php">Logout</a>
  <?php
} else {
  header("Location: login.php");
}
?>
</body>
</html>