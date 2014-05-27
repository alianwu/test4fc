<?php defined('SYSPATH') OR die('No direct script access.');

class Model_Config extends Model {

  public function get_all($group=NULL)
  {
    if ($group === NULL) {
      $query = DB::query(Database::SELECT, 'SELECT * FROM config ORDER BY name ASC')->execute()->as_array();
      return Arr::config($query);
    }
    else {
      $query = DB::query(Database::SELECT, 'SELECT * FROM config WHERE "group"=:group ORDER BY name ASC');
      $query->param(':group', $group);
      return $query->execute()->as_array('name', 'value');
    }
  } 

  public function update_one($data)
  {
    $query = DB::query(Database::INSERT, 'INSERT INTO config (name, value, "group") 
                VALUES (:name, :value, :group)');
    foreach($data as $group=>$v) {  
      DB::query(Database::DELETE, 'DELETE FROM config  WHERE "group"=:group')
        ->param(':group', $group)
        ->execute();
      foreach($v as $name => $value)
        $query->param(':name', $name)
          ->param(':value', $value)
          ->param(':group', $group)
          ->execute();
    }
    return $query;
  }

}
