<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Supplier</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body>
<?php
$dataPerPage = 2;
session_start();
include_once 'Auth.php';
include_once 'model/supplier.php';
$auth = new Auth;

if(array_key_exists('loggedIn', $_SESSION) && $_SESSION['loggedIn']) {
  $currentUser = $auth->getCurrentUser();
  if($currentUser->type == 'admin') {
    $action = '';
    if(isset($_GET['id']) && $_GET['id'] && isset($_GET['action']) && $_GET['action']) {
      $action = $_GET['action'];
      if ($action == 'delete') {
        $delete = Supplier::delete(['id' => $_GET['id']]);
        header("Location: ".$_SERVER['PHP_SELF']);
      }
    }

    if(isset($_POST['tambah']) && $_POST['tambah'] == 'tambah') {
      $tambah = Supplier::insert([
        'kode_supp' => $_POST['kode_supp'],
        'nama' => $_POST['nama'],
        'alamat' => $_POST['alamat'],
        'no_hp' => $_POST['no_hp']
      ]);
      header("Location: ".$_SERVER['PHP_SELF']);
    } else if(isset($_POST['simpan']) && $_POST['simpan'] == 'simpan') {
      $update = Supplier::update(
        [
          'kode_supp' => $_POST['kode_supp'],
          'nama' => $_POST['nama'],
          'alamat' => $_POST['alamat'],
          'no_hp' => $_POST['no_hp']
        ],
        [
          'id' => $_GET['id']
        ]
      );
      header("Location: ".$_SERVER['PHP_SELF']);
    }

    if(isset($_GET['nama'])) {
      if(!$_GET['nama'])
        header("Location: ".$_SERVER['PHP_SELF']);
    }
    
    $currentPage = isset($_GET['page']) && $_GET['page'] ? $_GET['page'] : 1;
    
    $totalData = isset($_GET['nama']) && $_GET['nama']
      ? Supplier::search(['nama' => $_GET['nama']])
      : Supplier::fetch();
    $supplier = isset($_GET['nama']) && $_GET['nama']
      ? Supplier::search(['nama' => $_GET['nama']], 'LIMIT '.($currentPage-1) * $dataPerPage.', '. $dataPerPage) 
      : Supplier::fetch('LIMIT '.($currentPage-1) * $dataPerPage.', '. $dataPerPage);
  ?>
  <div class="container mt-5">
    <div class="row mb-2">
      <div class="col-md-4">
        <form>
          <div class="input-group mb-3">
            <input type="text" class="form-control" name="nama" placeholder="Cari nama" value="<?=isset($_GET['nama']) && $_GET['nama'] ? $_GET['nama'] : ''?>">
            <div class="input-group-append">
              <button type="submit" class="btn btn-primary">Cari</button>
            </div>
          </div>
        </form>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th scope="col">ID</th>
              <th scope="col">Kode Supplier</th>
              <th scope="col">Nama</th>
              <th scope="col">Alamat</th>
              <th scope="col">No Hp</th>
              <th scope="col">Aksi</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach($supplier as $row): ?>
          <tr>
            <th scope="row"><?=$row->id?></th>
            <td><?=$row->kode_supp?></td>
            <td><?=$row->nama?></td>
            <td><?=$row->alamat?></td>
            <td><?=$row->no_hp?></td>
            <td class="bd-example">
              <a style="margin-top: .25rem; margin-bottom: .25rem;" class="btn btn-warning" href="?id=<?= $row->id ?>&action=update">Update</a>
              <a style="margin-top: .25rem; margin-bottom: .25rem;" class="btn btn-danger" href="?id=<?= $row->id ?>&action=delete">Delete</a>
            </td>
          </tr>
          <?php endforeach ?>
          </tbody>
        </table>
        <?php
          $pageTotal = ceil(count($totalData) / $dataPerPage);
          $prev = isset($_GET['page']) ? $currentPage-1 : 1;
          $next = isset($_GET['page']) ? $currentPage+1 : 2;
          $search = isset($_GET['nama']) && $_GET['nama'] ? "&nama=".$_GET['nama'] : '';
        ?>
        <?php if($pageTotal > 1): ?>
        <nav class="mt-4" aria-label="pagination">
          <ul class="pagination">
            <li class="page-item <?=$prev == 0 ? 'disabled' : ''?>">
              <a class="page-link" href="?page=<?= $prev.$search ?>" tabindex="-1">Previous</a>
            </li>
            <?php for($i=1; $i<=$pageTotal; $i++): ?>
            <li class="page-item <?=$i == $currentPage ? 'active' : ''?>"><a class="page-link" href="?page=<?= $i.$search?>"><?= $i?></a></li>
            <?php endfor ?>
            <li class="page-item <?=$next == $pageTotal+1 ? 'disabled' : ''?>">
              <a class="page-link" href="?page=<?= $next.$search ?>">Next</a>
            </li>
          </ul>
        </nav>
        <?php endif ?>
      </div>
    </div>
    <?php
    $kode = ''; $nama = ''; $alamat = ''; $no_hp = ''; $btnName = 'tambah';
    if($action == 'update') {
      $btnName = $action == 'update' ? 'simpan' : 'tambah';
      $getDetail = Supplier::get(['*'], ['id' => $_GET['id']]);
      $kode = $getDetail->kode_supp;
      $nama = $getDetail->nama;
      $alamat = $getDetail->alamat;
      $no_hp = $getDetail->no_hp;
    } ?>
    <div class="row mt-5">
      <div class="col-md-6">
      <form method="POST">
        <div class="form-group">
          <label>Kode Supplier</label>
          <input type="text" class="form-control" name="kode_supp" value="<?=$kode?>">
        </div>
        <div class="form-group">
          <label>Nama Supllier</label>
          <input type="text" class="form-control" name="nama" value="<?=$nama?>">
        </div>
        <div class="form-group">
          <label>Alamat Supplier</label>
          <input type="text" class="form-control" name="alamat" value="<?=$alamat?>">
        </div>
        <div class="form-group">
          <label>No HP Supllier</label>
          <input type="text" class="form-control" name="no_hp" value="<?=$no_hp?>">
        </div>
        <button type="submit" class="btn btn-primary" name="<?=$btnName?>" value="<?=$btnName?>"><?=ucwords($btnName)?></button>
      </form>
      </div>
    </div>
  </div>
  <?php }
} else {
  header("Location: login.php");
}
?>
</body>
</html>