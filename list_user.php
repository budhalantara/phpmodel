<?php
session_start();
include_once 'model/users.php';
include_once 'Auth.php';

$auth = new Auth;
$currentUser = $auth->getCurrentUser();
if(array_key_exists('loggedIn', $_SESSION) && $_SESSION['loggedIn'] && $currentUser->type == 'admin') {

$res = Users::fetch();
?>
<ul>
<?php foreach ($res as $key => $value): ?>
  <li><?=$value->name?></li>
<?php endforeach ?>
</ul>

<?php } ?>

<a href="index.php">Dashboard</a>