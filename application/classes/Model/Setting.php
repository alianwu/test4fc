<?php defined('SYSPATH') OR die('No direct script access.');

class Model_Setting {
  
  public $setting;

  public function get_list($setting)
  {
    $query = DB::query(Database::SELECT, 'SELECT * FROM setting WHERE setting=:setting')
              ->param(':setting', $setting)
              ->as_object()
              ->execute();
    return $query->count() == 0 ? NULL : $query;
  }

  public function get($name, $setting='search')
  {
    $query = DB::query(Database::SELECT, 'SELECT * FROM setting WHERE name=:name AND setting=:setting LIMIT 1')
              ->param(':setting', $setting)
              ->param(':name', $name)
              ->execute();
    return $query->count() == 0 ? NULL : $query->current();
  }


  public function update($data, $setting = 'search')
  {
    $query = DB::query(Database::SELECT, 'SELECT name FROM setting WHERE name=:name AND setting=:setting LIMIT 1')
              ->param(':setting', $setting)
              ->param(':name', $data['name'])
              ->execute();
    if ($query->count() == 0) {
      $query = DB::query(Database::INSERT, 'INSERT INTO setting (name, value, setting) 
                  VALUES(:name, :value, :setting)')
              ->param(':name', $data['name'])
              ->param(':value', $data['value'])
              ->param(':setting', $setting)
              ->execute();
    }
    else {
      $query = DB::query(Database::UPDATE, 'UPDATE setting  SET  value=:value
                  WHERE name=:name AND setting=:setting')
              ->param(':name', $data['name'])
              ->param(':value', $data['value'])
              ->param(':setting', $setting)
              ->execute();
    }
    return $query?TRUE:FALSE;
    
  }

  public function clear($name)
  {
    $query = DB::query(Database::DELETE, 'UPDATE setting SET value=:value WHERE name=:name')
              ->param(':name', $name)
              ->param(':value', '')
              ->execute();
    return $query?TRUE:FALSE;
  }

}


