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
  
  public function get_near_front($cid, $lat, $lng, $radius, $page=1)
  {
    $point = 'POINT('.$lng.' '.$lat.')';
    $sql = "SELECT t.*
              , ST_Distance(ST_Transform(ST_GeomFromText('".$point."',4326),26986), ST_Transform(geo, 26986)) as distance
              , t.gps[0] AS lng, t.gps[1] AS lat
              FROM school AS t
                WHERE city_id=:city_id AND ST_DWithin(
                  ST_Transform(ST_GeomFromText('".$point."',4326),26986), 
                  ST_Transform(t.geo, 26986), 
                  ".$radius.") AND display=TRUE
                ORDER BY ST_Distance(ST_GeomFromText('".$point."',4326), t.geo) LIMIT :num OFFSET :start";
    $query = DB::query(Database::SELECT, $sql)
              ->param(':city_id', $cid)
              ->param(':num', $this->pagination->default['items_per_page'])
              ->param(':start', $this->pagination->default['items_per_page'] * ($page-1))
              ->execute();
    return $query->count() == 0 ? NULL : $query;
  }

  public function get_hot_front($city_id, $page)
  {
    $query = DB::query(Database::SELECT, 'SELECT *, gps[0] AS lng, gps[1] AS lat
                FROM school 
                  WHERE city_id=:city_id AND hot=1 AND display=TRUE 
                    ORDER BY hit DESC, weight DESC LIMIT :num OFFSET :start ')
              ->param(':city_id', $city_id)
              ->param(':num', $this->pagination->default['items_per_page'])
              ->param(':start', $this->pagination->default['items_per_page'] * ($page-1))
              ->as_object()
              ->execute();
    return $query->count() == 0 ? NULL : $query;
  }

  public function get_latest_front($city_id, $page)
  {
    $query = DB::query(Database::SELECT, 'SELECT *, gps[0] AS lng, gps[1] AS lat 
                FROM school 
                WHERE city_id=:city_id AND display=TRUE ORDER BY created DESC, weight DESC LIMIT :num OFFSET :start ')
              ->param(':city_id', $city_id)
              ->param(':num', $this->pagination->default['items_per_page'])
              ->param(':start', $this->pagination->default['items_per_page'] * ($page-1))
              ->as_object()
              ->execute();
    return $query->count() == 0 ? NULL : $query;
  }

  public function get_list_front($city_id, $where)
  {
    $page = max((int)$where['page'], 1);
    $parameters = array(':city_id'=>$city_id);
    $sql = 'city_id=:city_id';
    if (isset($where['keyword']) && $where['keyword']) {
        $sql .= ' AND (sid =:hid  OR name like :keyword OR address like :keyword)';
        $parameters[':keyword'] = '%'.$where['keyword'].'%';
        $parameters[':hid'] = (int )$where['keyword'];
    }
    if (isset($where['area']) && $where['area']) {
      $sql .= ' AND city_area=:city_area';
      $parameters[':city_area'] =  $where['area'];
    }
    if (isset($where['shop']) && $where['shop']) {
      $sql .= ' AND city_area_shop=:city_area_shop';
      $parameters[':city_area_shop'] = $where['shop'];
    }

    $query = DB::query(Database::SELECT, 'SELECT *, gps[0] AS lng, gps[1] AS lat
              FROM school 
                WHERE '.$sql.' AND display=TRUE 
                  ORDER BY weight DESC, created DESC LIMIT :num OFFSET :start ')
              ->parameters($parameters)
              ->param(':num', $this->pagination->default['items_per_page'])
              ->param(':start', $this->pagination->default['items_per_page'] * ($page-1))
              ->as_object()
              ->execute();
    return $query->count() == 0 ? NULL : $query;
  }

  
  public function get_list_favorite($city_id, array $ids, $page=1)
  {
    $ids = Arr::map('intval', $ids);
    $sid = implode(',', $ids);
    $query = DB::query(Database::SELECT, 'SELECT *, gps[0] AS lng, gps[1] AS lat  FROM school 
                WHERE sid in (:sid) AND display=TRUE')
              ->param_extra(':sid', $sid)
              ->execute();
    return $query->count() == 0 ? NULL : $query;
  }

  public function get_one($id)
  {
    $query = DB::query(Database::SELECT, 'SELECT 
      *, gps[0] AS lng, gps[1] AS lat
                FROM school WHERE sid=:sid')
              ->param(':sid', $id)
              ->as_object()
              ->execute();
    return $query->count() == 0? NULL: $query->current();
  }

  public function get_one_front($id)
  {
    $query = DB::query(Database::SELECT, 'SELECT *, gps[0] AS lng, gps[1] AS lat FROM school WHERE sid=:sid AND display = TRUE LIMIT 1')
              ->param(':sid', $id)
              ->as_object()
              ->execute();
    return $query->count() == 0? NULL : $query->current();
  }

  public function house_update($id, $house)
  {
    $school  = $this->get($id);
    if ($school) {
      $school_house_old = json_decode($school->house, TRUE);
      if ($school_house_old) {
        $school_house = array_merge($school_house_old, $house);
      }
      else {
        $school_house = $house;
      }
      $this->update($id, array('house'=> json_encode($school_house) ));
    }
  }

}


