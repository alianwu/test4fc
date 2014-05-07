<?php defined('SYSPATH') OR die('No direct script access.');

class Model_Config extends Model {

  public function get_all($group=NULL)
  {
    if ($group === NULL) {
      $query = DB::query(Database::SELECT, 'SELECT * FROM config');
    }
    else {
      $query = DB::query(Database::SELECT, 'SELECT * FROM config WHERE "group"=:group');
      $query->param(':group', $group);
    }
    $query = $query->execute()->as_array('name', 'value');
    return $query;
  } 

  public function update_one($name, $value, $group)
  {
    
    $query = DB::query(Database::SELECT, 'SELECT  name FROM config WHERE name=:name AND "group"=:group LIMIT 1')
              ->param(':name', $name)
              ->param(':group', $group)
              ->execute();
    if ($query->count()) {
      $query = DB::query(Database::UPDATE, ' UPDATE config SET value=:value WHERE name=:name AND "group"=:group')
        ->param(':name', $name)
        ->param(':value', $value)
        ->param(':group', $group)
        ->execute();
    }
    else {
      $query = DB::query(Database::UPDATE, 'INSERT INTO config (name, value, "group") VALUES (:name, :value, :group)')
        ->param(':name', $name)
        ->param(':value', $value)
        ->param(':group', $group)
        ->execute();
    }
    return $query;
  }

}
