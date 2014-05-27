<?php defined('SYSPATH') OR die('No direct script access.');

class Model_Company extends Model {

  protected $table = 'company';
  protected $primary_key = 'cid';


  public function save_one($data) 
  {
    $cid = $data['cid'];
    $data['display'] = (bool) $data['display']; 
    $data['gps'] = $data['lng'] . ',' . $data['lat']; 
    $data['created'] = $data['updated'] = 'now()';
    $data['geo'] = 'ST_GeomFromText(\'POINT('.$data['lng'].' '.$data['lat'].')\', 4326)';

    $image = array();
    if (empty($data['image_history']) === FALSE) {
      foreach($data['image_history'] as $k=>$v) {
        $image[] = array(
          'src'=>$v, 
          'alt'=> Arr::path($data, 'image_desc.'.$k), 
          'group'=> Arr::path($data, 'image_group.'.$k),
        );
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
    unset($data['csrf'], 
      $data['image_history'], 
      $data['image_desc'], 
      $data['image_group'], 
      $data['lng'], 
      $data['lat']);
    if ($cid == 0) {
      unset($data['cid']);
      $field =  implode(', ', array_keys($data));
      $value =  implode(', :', array_keys($data));
      $query = DB::query(Database::SELECT, 'INSERT INTO company ('. $field .') 
            VALUES (:'. $value .') RETURNING cid')
        ->parameters($parameters)
        ->parameters_extra($parameters_extra)
        ->execute(); 
      if ($query->count()) {
        $cid =  $query->get('cid');
      }
      else {
        return FALSE;
      }
    }
    else {
      unset($data['created']);
      $field = array_map(function($v) {return $v.'=:'.$v;}, array_keys($data));
      $upset =  implode(', ', $field);
      $query = DB::query(Database::UPDATE, 'UPDATE company SET '. $upset .' WHERE cid=:cid')
        ->parameters($parameters)
        ->parameters_extra($parameters_extra)
        ->execute();
      if($query) {
        $cid = $data['cid'];
      }
      else {
        return FALSE;
      }
    }
    return $cid;
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
      $sql .= ' AND (cid = :cid or name like :keyword or address like :keyword)';
      $parameters[':keyword'] = '%'.$where['keyword'].'%';
      $parameters[':cid'] = $where['keyword'];
    }
    $query = DB::query(Database::SELECT, 'SELECT count(*) AS count 
                FROM company WHERE city_id=:city_id '. $sql)
              ->param(':city_id', $city_id)
              ->parameters($parameters)
              ->as_object()
              ->execute();
    $ret['total'] = $query->get('count', 0);
    $query = DB::query(Database::SELECT, 'SELECT *, gps[0] AS lng, gps[1] AS lat FROM company 
                WHERE city_id=:city_id '.$sql
                  .' ORDER BY cid DESC LIMIT :num OFFSET :start ')
              ->param(':city_id', $city_id)
              ->parameters($parameters)
              ->param(':num', $this->pagination->manager['items_per_page'])
              ->param(':start', $this->pagination->manager['items_per_page'] * ($page-1))
              ->as_object()
              ->execute();
    $ret['data'] = $query;
    return $ret; 
  }

}


