<?php defined('SYSPATH') OR die('No direct script access.');

class Model_City extends Model {

  public function get_city($parent = 0, $type = 1)
  {
    $query = DB::query(Database::SELECT, 'SELECT * FROM city WHERE parent_cid=:parent_cid AND type=:type ORDER BY weight DESC, cid ASC')
              ->param(':parent_cid', $parent)
              ->param(':type', $type)
              ->as_object()
              ->execute();
    return $query->count() == 0? NULL : $query;
  } 

  public function get_city_cache($key = 'name')
  {
    $query = DB::query(Database::SELECT, 'SELECT cid, name, value FROM city')
              ->param(':display', TRUE)
              ->execute();
    return $query->as_array('cid', $key);
  }

  public function get_city_from_value($value)
  {
    $query = DB::query(Database::SELECT, 'SELECT cid, name, value FROM city 
                  WHERE value=:value AND type=1 AND display=:display LIMIT 1')
              ->param(':display', TRUE)
              ->param(':value', $value)
              ->param(':type', 1)
              ->execute();
    return $query->count() == 0? NULL:$query->current();
  }

  public function get_city_pretty($parent = 0, $type = 1, $output = 1)
  {
    $query = DB::query(Database::SELECT, 'SELECT cid, name, value FROM city WHERE parent_cid=:parent_cid AND type=:type')
              ->param(':parent_cid', $parent)
              ->param(':type', $type)
              ->execute();
    if ( $query->count() == 0 ) {
      return array(); 
    }
    switch($output) {
      case 1:
        return $query->as_array('cid', 'name');
        break;
      case 2:
        return $query->as_array('value', 'name');
        break;
      case 3:
        return $query->as_array('name', 'name');
        break;
      default:
        return $query->as_array();
    }
  } 
  
  public function get_city_one($cid)
  {
    $query = DB::query(Database::SELECT, 'SELECT * FROM city WHERE cid=:cid')
              ->param(':cid', $cid)
              ->execute();
    return $query->count() == 0? FALSE : Arr::get($query->as_array(), 0);
  }
  
  public function save_one($data)
  {
    $rcode = 1;
    if (isset($data['cid']) && $data['cid'] == 0) {
      $name = explode("\n", $data['name']);
      $value = explode("\n", $data['value']);
      $query = DB::query(Database::SELECT, 'INSERT INTO city (name, value, parent_cid, type, display, weight) 
                  VALUES (:name, :value, :parent_cid, :type, :display, :weight) RETURNING cid')
                ->param(':parent_cid', $data['parent_cid'])
                ->param(':type', $data['type'])
                ->param(':display', (bool) $data['display'])
                ->param(':weight', $data['weight']);
      $index = 0;
      foreach($name as $k => $v) {
        if(($n = trim($v)) <> '' ) {
          $v = '';
          if (isset($value[$k])) {
            $v = trim($value[$k]);
          }
          ($test = $query->param(':name', $n)->param(':value', $v)->as_object()->execute() ) ? $index++ : NULL;
        }
      }
      $rcode = 0;
    }
    else {
      $data['name'] = trim($data['name']);
      $query = DB::query(Database::UPDATE, 'UPDATE city SET name=:name, value=:value, display=:display, weight=:weight WHERE cid=:cid')
                ->param(':cid', $data['cid'])
                ->param(':parent_cid', $data['parent_cid'])
                ->param(':name', $data['name'])
                ->param(':value', $data['value'])
                ->param(':type', $data['type'])
                ->param(':display', (bool)$data['display'])
                ->param(':weight', $data['weight'])
                ->execute();
      if($query) {
        $rcode = 0;
      }

    }
    return $rcode;
  }

  public function display_one($cid)
  {
    $query = DB::query(Database::UPDATE, 'UPDATE city SET display= NOT display  WHERE cid=:cid')
              ->param(':cid', $cid)
              ->execute();
    return $query?TRUE:FALSE;
  }

  public function delete_one($cid)
  {
    $query = DB::query(Database::DELETE, 'DELETE FROM city  WHERE cid=:cid')
              ->param(':cid', $cid)
              ->execute();
    return $query?TRUE:FALSE;
  }
}
