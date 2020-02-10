<?php
include_once "model/barang.php";

$dataPerPage = 1;

if(isset($_GET['id']) && $_GET['id'] && isset($_GET['action']) && $_GET['action']) {
  $action = $_GET['action'];
  if ($action == 'delete') {
    $delete = Barang::delete(['id' => $_GET['id']]);
    header("Location: ".$_SERVER['PHP_SELF']);
  }
}

if(isset($_POST['tambah']) && $_POST['tambah'] == 'tambah') {
  $tambah = Barang::insert([
    'kode_barang' => $_POST['kode_barang'],
    'nama' => $_POST['nama'],
    'satuan' => $_POST['satuan'],
    'harga' => $_POST['harga']
  ]);
  header("Location: ".$_SERVER['PHP_SELF']);
} else if(isset($_POST['simpan']) && $_POST['simpan'] == 'simpan') {
  $update = Barang::update(
    [
      'kode_barang' => $_POST['kode_barang'],
      'nama' => $_POST['nama'],
      'satuan' => $_POST['satuan'],
      'harga' => $_POST['harga']
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

$barang = isset($_GET['nama']) && $_GET['nama']
  ? Barang::search(['nama' => $_GET['nama']])
  : Barang::fetch();
$barang2 = isset($_GET['nama']) && $_GET['nama']
  ? Barang::search(['nama' => $_GET['nama']], 'LIMIT '.($currentPage-1) * $dataPerPage.', '. $dataPerPage) 
  : Barang::fetch('LIMIT '.($currentPage-1) * $dataPerPage.', '. $dataPerPage);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Barang</title>
  <style>
  .disabled {
    pointer-events: none;
    cursor: default;   
  }
  </style>
</head>
<body>
  <form>
    <!-- <input type="hidden" name="page" value="<?=$currentPage?>"> -->
    <input type="text" name="nama">
    <input type="submit" value="cari">
  </form>
  <table>
    <thead>
      <tr>
        <td>ID</td>
        <td>Kode Barang</td>
        <td>Nama</td>
        <td>Satuan</td>
        <td>Harga</td>
        <td>Aksi</td>
      </tr>
    </thead>
    <tbody>
    <?php foreach($barang2 as $row): ?>
      <tr>
        <td><?= $row->id ?></td>
        <td><?= $row->kode_barang ?></td>
        <td><?= $row->nama ?></td>
        <td><?= $row->satuan ?></td>
        <td><?= $row->harga ?></td>
        <td>
          <a href="?id=<?= $row->id ?>&action=update">Update</a>
          <a href="?id=<?= $row->id ?>&action=delete">Delete</a>
        </td>
      </tr>
    <?php endforeach?>
    </tbody>
  </table>
  
  <br/><br/>
  <form method="POST">
    <?php $kode = ''; $nama = ''; $satuan = ''; $harga = ''; $btnName = 'tambah';?>
    <?php if(isset($_GET['action'])): $action = $_GET['action'];?>
    <?php if($action == 'update') {
      $btnName = $action == 'update' ? 'simpan' : 'tambah';
      $getDetail = Barang::get(['*'], ['id' => $_GET['id']]);
      $kode = $getDetail->kode_barang;
      $nama = $getDetail->nama;
      $satuan = $getDetail->satuan;
      $harga = $getDetail->harga;
    } ?>
    <?php else: $btnName = "tambah"?>
    <?php endif?>
    <input type="text" name="kode_barang" placeholder="kode barang" value="<?=$kode?>"><br/>
    <input type="text" name="nama" placeholder="nama" value="<?=$nama?>"><br/>
    <input type="text" name="satuan" placeholder="satuan" value="<?=$satuan?>"><br/>
    <input type="text" name="harga" placeholder="harga" value="<?=$harga?>"><br/>
    <input type="submit" name="<?=$btnName?>" value="<?=$btnName?>">
  </form>
  <br>
  <?php $prev = isset($_GET['page']) ? $_GET['page']-1 : 1; $next = isset($_GET['page']) ? $_GET['page']+1 : 2; $search = isset($_GET['nama']) && $_GET['nama'] ? "&nama=".$_GET['nama'] : ''; ?>
  <a href="?page=<?= $prev ?>" <?=$prev == '0' ? 'class="disabled"' : '' ?>>Sebelumnya</a>
    <?php
      $pageTotal = ceil(count($barang) / $dataPerPage);
    ?>
    <?php for($i=1; $i<=$pageTotal; $i++): ?>
      <a href="?page=<?= $i.$search?>"><?= $i?></a>
    <?php endfor ?>
  <a href="?page=<?= $next.$search ?>" <?=$next == $pageTotal+1 ? 'class="disabled"' : '' ?>>Selanjutnya</a>
</body>
</html>
