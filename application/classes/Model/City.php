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

  public function get_city_group($city_id)
  {
    $ret = array('group'=>NULL, 'underground'=>'');
    $query = DB::query(Database::SELECT, 'SELECT cid, name, value FROM city WHERE parent_cid=:cid and type=1')
              ->param(':display', TRUE)
              ->param(':cid', $city_id)
              ->as_object()
              ->execute();
    $group = array();
    if ($query->count()) {
      foreach($query as $v) {
        $query_2 = DB::query(Database::SELECT, 'SELECT cid, name, value FROM city WHERE parent_cid=:cid and type=6')
              ->param(':display', TRUE)
              ->param(':cid', $v->cid)
              ->execute();
        $group[$v->cid] = $query_2->as_array('cid', 'name');
      }
    }
    $query = DB::query(Database::SELECT, 'SELECT cid, name, value FROM city WHERE parent_cid=:cid and type=2')
              ->param(':display', TRUE)
              ->param(':cid', $city_id)
              ->as_object()
              ->execute();
    $underground = array();
    if ($query->count()) {
      foreach($query as $v) {
        $query_2 = DB::query(Database::SELECT, 'SELECT cid, name, value FROM city WHERE parent_cid=:cid and type=3')
              ->param(':display', TRUE)
              ->param(':cid', $v->cid)
              ->execute();
        $underground[$v->cid] = $query_2->as_array('cid', 'name');
      }
    }
    $ret['shop'] = $group;
    $ret['underground'] = $underground;
    return $ret;

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

  public function get_city_pretty($parent = NULL, $type = NULL, $display= TRUE, $output = 1)
  {
    $where = 'true';
    $params = array();
    if ($parent !== NULL) {
      $where .= ' AND parent_cid=:parent_cid';
      $params[':parent_cid'] = $parent;
    }
    if ($type !== NULL) {
      $where .= ' AND type=:type';
      $params[':type'] = $type;
    }
    if ($display !== NULL) {
      $where .= ' AND display=:display';
      $params[':display'] = $display?'true':'false';
    }
    $query = DB::query(Database::SELECT, 'SELECT cid, name, value 
                FROM city 
                WHERE '.$where.' ORDER BY weight DESC, cid ASC')
              ->parameters($params)
              ->execute();
    if ( $query->count() == 0 ) {
      return array(); 
    }
    switch($output) {
      case 1:
        return $query->as_array('cid', 'name');
        break;
      case 2:
        return $query->as_array('cid', 'value');
        break;
      case 3:
        return $query->as_array('value', 'name');
        break;
      case 4:
        return $query->as_array('name', 'name');
        break;
      default:
        return $query->as_array();
    }
  } 
  
  public function get_city_one($cid)
  {
    $query = DB::query(Database::SELECT, 'SELECT * FROM city WHERE cid=:cid LIMIT 1')
              ->param(':cid', $cid)
              ->execute();
    return $query->count() == 0? NULL : $query->current();
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
