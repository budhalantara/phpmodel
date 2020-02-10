<?php
include_once "model.php";

class Barang extends Model
{
  protected static $column = [ '*' ];
  protected static $insertCol = [ 'kode_barang', 'nama', 'satuan', 'harga' ];
}