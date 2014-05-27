<?php defined('SYSPATH') OR die('No direct script access.');

class Model_School extends Model {

  protected $table = 'school';
  protected $primary_key = 'sid';


  public function save_one($data) 
  {
    $id = $data['sid'];
    $data['display'] = (bool) $data['display']; 
    $data['gps'] = $data['lng'] . ',' . $data['lat']; 
    $data['created'] = $data['updated'] = 'now()';
    $data['geo'] = 'ST_GeomFromText(\'POINT('.$data['lng'].' '.$data['lat'].')\', 4326)';

    $image = array();
    if (empty($data['image_history']) === FALSE) {
      foreach($data['image_history'] as $k=>$v) {
        $image[] = array('src'=>$v, 'alt'=> $data['image_desc'][$k]);
      }
    }
    if ($image) {
      $data['image'] = json_encode($image);
      $data['image_default'] = $image[0]['src'];
    }
    $parameters = array();
    $parameters_extra = array();
    foreach($data as $k=>$v) {
      $pkey = ':' . $k;
      switch($k) {
        case 'updated':
        case 'created':
        case 'geo':
          $parameters_extra[$pkey] = $v;
        break;
        default:
          $parameters[$pkey] = $v;
      }
    }
    unset($data['csrf'], $data['lng'], $data['image_history'], $data['image_desc'], $data['lat']);
    if ($id == 0) {
      unset($data['sid']);
      $field =  implode(', ', array_keys($data));
      $value =  implode(', :', array_keys($data));
      $query = DB::query(Database::SELECT, 'INSERT INTO school ('. $field .') 
            VALUES (:'. $value .') RETURNING sid')
        ->parameters($parameters)
        ->parameters_extra($parameters_extra)
        ->execute(); 
      if ($query->count()) {
        $id =  $query->get('cid');
      }
      else {
        return FALSE;
      }
    }
    else {
      unset($data['created']);
      $field = array_map(function($v) {return $v.'=:'.$v;}, array_keys($data));
      $upset =  implode(', ', $field);
      $query = DB::query(Database::UPDATE, 'UPDATE :table SET '. $upset .' WHERE sid=:sid')
        ->parameters($parameters)
        ->param_extra(':table', $this->table)
        ->parameters_extra($parameters_extra)
        ->execute();
      if($query) {
        $id = $data['sid'];
      }
      else {
        return FALSE;
      }
    }
    return $id;
  }
  
  public function get_pretty($city_id, $where=NULL) 
  {
    $sql = '';
    $query = DB::query(Database::SELECT, 'SELECT * FROM :table 
                WHERE city_id=:city_id '.$sql
                  .' ORDER BY name ASC')
              ->param(':city_id', $city_id)
              ->param_extra(':table', $this->table)
              ->execute()
              ->as_array('sid', 'name');
    return $query;

  }

  public function get_list($city_id, $where)
  {
    $sql = '';
    $page = max((int)Arr::get($where, 'page', 1), 1);
    $ret = $parameters = array();
    if (isset($where['area']) && $where['area']) {
      $sql .= ' AND city_area=:city_area';
      $parameters[':city_area'] = $where['area'];
    }
    if (isset($where['display']) && $where['display']) {
      $sql .= ' AND display=:display';
      $parameters[':display'] = $where['display'];
    }
    if (isset($where['keyword']) && $where['keyword']) {
      if (is_int($where['keyword'])) {
        $sql .= ' OR sid = :sid ';
      }
      else {
       $sql .='  OR name like :keyword OR address like :keyword';
      }
      $parameters[':keyword'] = '%'.$where['keyword'].'%';
      $parameters[':sid'] = $where['keyword'];
    }
    $query = DB::query(Database::SELECT, 'SELECT count(*) AS count 
                FROM :table 
                  WHERE city_id=:city_id '. $sql)
              ->param(':city_id', $city_id)
              ->param_extra(':table', $this->table)
              ->parameters($parameters)
              ->execute();
    $ret['total'] = $query->get('count', 0);
    $query = DB::query(Database::SELECT, 'SELECT * FROM :table 
                WHERE city_id=:city_id '.$sql
                  .' ORDER BY sid DESC LIMIT :num OFFSET :start ')
              ->param(':city_id', $city_id)
              ->param_extra(':table', $this->table)
              ->parameters($parameters)
              ->param(':num', $this->pagination->manager['items_per_page'])
              ->param(':start', $this->pagination->manager['items_per_page'] * ($page-1))
              ->as_object()
              ->execute();
    $ret['data'] = $query;
    return $ret; 
  }

}


