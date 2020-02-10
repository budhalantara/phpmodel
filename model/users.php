<?php
include_once "model.php";

class Users extends Model
{
  protected static $column = [ 'name' ];
  protected static $insertCol = [ 'kode_barang', 'nama', 'satuan', 'harga' ];
}