<?php
include_once "config/Database.php";

class Model
{
  protected static $column = [];
  protected static $insertCol = ''; 

  public static function insert($params) {
    $db = new Database;
    $select_list = join(',', static::$column);
    $table = strtolower(static::class);
    $i = 0;
    $insertCol = '';
    $insertVal = '';
    foreach($params as $key => $value) {
      $insertCol .= $key;
      $insertVal .= ":".$key;
      if($i < count($params) - 1) {
        $insertCol .= ', ';
        $insertVal .= ', ';
      }
      $i++;
    }
    $stmt = $db->conn->prepare("INSERT INTO $table ($insertCol) VALUES ($insertVal)");
    $res = $stmt->execute($params);
    $db->destroy();
    return $res;
  }
  
  public static function fetch($add = '') {
    $db = new Database;
    $select_list = join(',', static::$column);
    $table = strtolower(static::class);
    $stmt = $db->conn->query("SELECT $select_list FROM $table $add");
    $res = $stmt->fetchAll();
    $db->destroy();
    return $res;
  }

  public static function get($column, $where) {
    $db = new Database;
    $select_list = join(',', $column);
    $table = strtolower(static::class);
    $where2 = '';
    $fParam = []; 
    $i = 0;
    if(count($where) > 0) {
      $where2 .= 'WHERE ';
      foreach($where as $key => $value) {
        $paramVarname = "var".$i;
        $fParam[$paramVarname] = $value;
        $where2 .= $key."=:".$paramVarname;
        if($i < count($where) - 1) {
          $where2 .= ' AND ';
        }
        $i++;
      }
    }
    // var_dump($where2);
    $stmt = $db->conn->prepare("SELECT $select_list FROM $table $where2");
    $res = $stmt->execute($fParam);
    $db->destroy();
    return $res ? $stmt->fetch() : $res;
  }

  public static function update($params, $where) {
    $db = new Database;
    $select_list = join(',', static::$column);
    $table = strtolower(static::class);
    $set = '';
    $where2 = '';
    $fParam = []; 
    $i = 0;
    foreach($params as $key => $value) {
      $paramVarname = "var".$i;
      $fParam[$paramVarname] = $value;
      $set .= $key."=:".$paramVarname;
      if($i < count($params) - 1) {
        $set .= ', ';
      }
      $i++;
    }
    foreach($where as $key => $value) {
      $paramVarname = "var".$i;
      $fParam[$paramVarname] = $value;
      $where2 .= $key."=:".$paramVarname;
      if($i < count($where) - 1) {
        $where2 .= ' AND ';
      }
      $i++;
    }
    // var_dump($set);
    $stmt = $db->conn->prepare("UPDATE $table SET $set WHERE $where2");
    $res = $stmt->execute($fParam);
    $db->destroy();
    return $res;
  }

  public static function delete($where) {
    $db = new Database;
    $table = strtolower(static::class);
    $where2 = '';
    $fParam = []; 
    $i = 0;
    $where2 .= 'WHERE ';
    foreach($where as $key => $value) {
      $paramVarname = "var".$i;
      $fParam[$paramVarname] = $value;
      $where2 .= $key."=:".$paramVarname;
      if($i < count($where) - 1) {
        $where2 .= ' AND ';
      }
      $i++;
    }
    $stmt = $db->conn->prepare("DELETE FROM $table $where2");
    $res = $stmt->execute($fParam);
    $db->destroy();
    return $res;
  }

  public static function search($where, $add = '') {
    $where2 = '';
    $i = 0;
    foreach($where as $key => $value) {
      $where2 .= $key.' LIKE '.'"%'.$value.'%"';
      if($i < count($where) - 1) {
        $where2 .= ' AND ';
      }
      $i++;
    }
    $db = new Database;
    $select_list = join(',', static::$column);
    $table = strtolower(static::class);
    $stmt = $db->conn->query("SELECT $select_list FROM $table WHERE $where2 $add");
    $res = $stmt->fetchAll();
    $db->destroy();
    return $res;
  }
}