<?php defined('SYSPATH') OR die('No direct script access.');

class Model_House extends Model {

  protected $pagination;

  public $cache_keys = array('manager.house_new10_hot', 'manager.house_new10_stick'); 
  public $max_hot_stick = 3;

  protected $table = 'house';
  protected $primary_key = 'hid';

  public function clear_cache(){ }

  public function get_list($city_id, $page = 0, $extra = NULL)
  {
    $pagination = Kohana::$config->load('pagination.manager');

    $where = '';
    $parameters = array();
    if (isset($extra['area']) && $extra['area']) {
      $where .= ' AND city_area=:city_area';
      $parameters[':city_area'] = $extra['area'];
    }
    if (isset($extra['display']) && $extra['display']) {
      $where .= ' AND display=:display';
      $parameters[':display'] = $extra['display'];
    }
    if (isset($extra['keyword']) && $extra['keyword']) {
      $where .= ' AND (name like :keyword or address like :keyword)';
      $parameters[':keyword'] = '%'.$extra['keyword'].'%';
    }

    $query = DB::query(Database::SELECT, 'SELECT count(*) FROM house WHERE city_id=:city_id'.$where)
              ->param(':city_id', $city_id)
              ->parameters($parameters)
              ->as_object()
              ->execute();
    $ret['total'] = $query->get('count', 0);
    $query = DB::query(Database::SELECT, 'SELECT * FROM house 
                WHERE city_id=:city_id '.$where.' ORDER BY weight DESC, hid DESC LIMIT :num OFFSET :start ')
              ->param(':city_id', $city_id)
              ->parameters($parameters)
              ->param(':num', $this->pagination->manager['items_per_page'])
              ->param(':start', $this->pagination->manager['items_per_page'] * ($page-1))
              ->as_object()
              ->execute();
    $ret['data'] = $query;
    return $ret;
  } 
  
  public function get_hot_front($city_id, $page)
  {
    $query = DB::query(Database::SELECT, 'SELECT *, gps[0] AS lng, gps[1] AS lat
                FROM house 
                WHERE city_id=:city_id  AND display=TRUE ORDER BY hot DESC, weight DESC LIMIT :num OFFSET :start ')
              ->param(':city_id', $city_id)
              ->param(':num', $this->pagination->default['items_per_page'])
              ->param(':start', $this->pagination->default['items_per_page'] * ($page-1))
              ->as_object()
              ->execute();
    return $query->count() == 0 ? NULL : $query;
  }

  public function get_latest_front($city_id, $where)
  {
    $page = $where['page'];
    $query = DB::query(Database::SELECT, 'SELECT *, gps[0] AS lng, gps[1] AS lat 
                FROM house 
                WHERE city_id=:city_id AND display=TRUE AND house_date_sale > current_date AND house_date_sale-90 < current_date ORDER BY stick DESC,  house_date_sale ASC, weight DESC LIMIT :num OFFSET :start ')
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
        $sql .= ' AND (hid =:hid  OR name like :keyword OR address like :keyword)';
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
    if (isset($where['price']) && $where['price']) {
      $price = explode('-', $data['price']);
      $min_price = isset($price[0])? (int) $price[0]:0;
      if ($min_price) {
        $sql .= ' AND price >:min_price';
        $parameters[':min_price'] = $min_price;
      }
      $max_price = isset($price[1])? (int) $price[1]:0;
      if ( $max_price && $max_price > $min_price) {
        $sql .= ' AND price < :max_price';
        $parameters[':max_price'] = $max_price;
      }
    }
    if (isset($where['underground']) && $where['underground']) {
      $sql .= ' AND underground=:underground';
      $parameters[':underground'] = $where['underground'];
    }
    if (isset($where['underground_platform']) && $where['underground_platform']) {
      $sql .= ' AND underground_platform=:underground_platform';
      $parameters[':underground_platform'] =$where['underground_platform'];
    }

    $query = DB::query(Database::SELECT, 'SELECT *, gps[0] AS lng, gps[1] AS lat
              FROM house 
                WHERE '.$sql.' AND display=TRUE 
                  ORDER BY weight DESC, created DESC LIMIT :num OFFSET :start ')
              ->parameters($parameters)
              ->param(':num', $this->pagination->default['items_per_page'])
              ->param(':start', $this->pagination->default['items_per_page'] * ($page-1))
              ->as_object()
              ->execute();
    return $query->count() == 0 ? NULL : $query;
  }

  public function get_near_front($cid, $lat, $lng, $radius, $page=1)
  {
    $point = 'POINT('.$lng.' '.$lat.')';
    $sql = "SELECT t.*
              , ST_Distance(ST_Transform(ST_GeomFromText('".$point."',4326),26986), ST_Transform(geo, 26986)) as distance
              , t.gps[0] AS lng, t.gps[1] AS lat
              FROM house AS t
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
  
  public function get_list_favorite($city_id, array $ids, $page=1)
  {
    $ids = Arr::map('intval', $ids);
    $hid = implode(',', $ids);
    $query = DB::query(Database::SELECT, 'SELECT *, gps[0] AS lng, gps[1] AS lat  FROM house 
                WHERE hid in (:hid) AND display=TRUE')
              ->param_extra(':hid', $hid)
              ->execute();
    return $query->count() == 0 ? NULL : $query;
  }

  public function get_one($hid, $is_object = TRUE)
  {
    $query = DB::query(Database::SELECT, 'SELECT 
      *, gps[0] AS lng, gps[1] AS lat
                FROM house WHERE hid=:hid LIMIT 1')
              ->param(':hid', $hid);
    if ($is_object === TRUE) {
      $query->as_object();
    }
    $query = $query->execute();
    return $query->count() == 0? NULL: $query->current();
  }

  public function get_one_front($hid)
  {
    $query = DB::query(Database::SELECT, 'SELECT *, gps[0] AS lng, gps[1] AS lat FROM house WHERE hid=:hid AND display = TRUE LIMIT 1')
              ->param(':hid', $hid)
              ->as_object()
              ->execute();
    return $query->count() == 0? NULL : $query->current();
  }

  public function get_hot_stick($type='hot', $hid=0)
  {
    $cache_key = 'manager.house_new10_'.$type;
    $cache = (array)Cache::instance()->get($cache_key);
    if (empty($cache)) {
      $query =  DB::query(Database::SELECT, 'SELECT hid, updated FROM house WHERE '.$type.'=1 ORDER BY updated DESC')
        ->execute();
      if ($query->count()) {
        $cache = $query->as_array();
        Cache::instance()->set($cache_key, $cache);
      }
    }
    $count = 0; 

    foreach($cache as $v) {
      $v['updated'] = strtotime($v['updated']);
      if ($v['updated'] > ( time() - 3600*24 ) ) {
        $count += 1;
      }
    }
    if ($count < $this->max_hot_stick) {
      if ($hid) {
        array_unshift( $cache, array('hid'=>$hid, 'updated'=>date('Y-m-d H:i:s',time())));
        if (count($cache) > $this->max_hot_stick) {
          $last = array_pop($cache);
          DB::query(Database::UPDATE, 'UPDATE house SET '.$type.'=0 WHERE hid=:hid')
            ->param(':hid', $last['hid'])
            ->execute();
        }
        Cache::instance()->set($cache_key, $cache);
      }
      return TRUE;
    }
    else {
      return FALSE;
    }
  }
  // return house id
  public function save_one($data, $user=NULL)
  {
    $rcode = 0;
    $data['gps'] = $data['lng'] . ',' . $data['lat']; 
    $data['geo'] = 'ST_GeomFromText(\'POINT('.$data['lng'].' '.$data['lat'].')\', 4326)';
    $data['display'] = (bool) $data['display']; 
    $data['updated'] = 'now()';

    $phone = $data['phone'];
    $ptmp = array();
    if ($phone) {
      $phone_list = explode("\n", $phone);
      foreach($phone_list as $v) {
        $pone = explode(' ', $v);
        if (count($pone) > 1) {
          $ptmp[] = array('n'=>$pone[1], 'c'=>$pone[0]);
        }
      }
    }
    $data['phone'] = json_encode($ptmp);

    $schools = $image = array();
    if (empty($data['image_history']) === FALSE) {
      foreach($data['image_history'] as $k=>$v) {
        $group = (int)Arr::path($data, 'image_group.'.$k);
        $image[] = array('src'=>$v, 
          'group'=> $group,
          'alt'=> Arr::path($data, 'image_desc.'.$k)
        );
      }
    }
    if ($image) {
      $data['image'] = json_encode($image);
      $default = Upload::image_cache($image[0]['src']);
      $data['image_default'] = $default;
    }

    if (empty($data['school']) === FALSE) {
      $school = Model::factory('School')->get_pretty($data['city_id']);
      foreach($data['school'] as $k=>$v) {
        $schools[] = array(
          's'=> $si = Arr::path($data, 'school.'.$k),
          'sn'=> Arr::get($school, $si),
          'sb'=> Arr::path($data, 'school_building.'.$k)
        );
      }
    }
    if ($schools) {
      $data['schools'] = json_encode($schools);
    }

    unset($data['csrf'], 
      $data['lng'], 
      $data['lat'], 
      $phone,
      $image,
      $ptmp,
      $data['image_history'], 
      $data['image_desc'], 
      $data['image_group'], 
      $data['school_building'], 
      $data['school']);

    $parameters = array();
    $parameters_extra = array();
    $upset = '';
    foreach($data as $k=>$v)  {
      switch($k) {
      case 'updated':
      case 'geo':
        $parameters_extra[':'.$k] = $v;
        break;
      case 'display':
          $upset .= $k.'=:'.$k.',';
          $parameters[':'.$k] = $v?'true':'false';
          break;
      case 'hot':
          if ($this->get_hot_stick('hot') || !$v) {
            $upset .= $k.'=:'.$k.',';
            $parameters[':'.$k] = $v?1:0;
            Cache::instance()->delete('manager.house_new10_'.$k);
          }
          else {
            unset($data[$k]);
          }
        break;
      case 'stick':
          if ($this->get_hot_stick('stick') || !$v) {
            $upset .= $k.'=:'.$k.',';
            $parameters[':'.$k] = $v?1:0;
            Cache::instance()->delete('manager.house_new10_'.$k);
          }
          else {
            unset($data[$k]);
          }
        break;
        default:
          if ($v <> '') {
            $parameters[':'.$k] = $v;
            $upset .= $k.'=:'.$k.',';
          }
          else { 
            unset($data[$k]);
          }
      }
    }
    if ($data['hid'] == 0) {
      unset($data['hid']);

      $data['author'] = $user['name'];
      $parameters[':author'] = $user['name'];
      $field =  implode(',', array_keys($data));
      $value =  implode(', :', array_keys($data));
      $query = DB::query(Database::SELECT, 'INSERT INTO house ('. $field .', created) VALUES (:'. $value .', NOW()) RETURNING hid')
                ->parameters($parameters)
                ->parameters_extra($parameters_extra)
                ->as_object()
                ->execute();
      if ($query) {
        $hid =  $query->get('hid');
        if (isset($_POST['hot']) && $this->get_hot_stick('hot')) {
          $this->get_hot_stick('hot', $hid);
        }
        if (isset($_POST['stick']) && $this->get_hot_stick('stick')) {
          $this->get_hot_stick('stick', $hid);
        }
        $rcode = $hid;
      }
    }
    else {
      $upset = substr($upset, 0, -1);
      $query = DB::query(Database::UPDATE, 'UPDATE house SET '. $upset .' WHERE hid=:hid')
                ->parameters($parameters)
                ->parameters_extra($parameters_extra)
                ->execute();
      if($query) {
        $rcode = $data['hid'];
     }
    }

    if ($schools && $rcode) {
      $house = array((int)$rcode => $data['name']);
      foreach($schools as $v) {
        Model::factory('School')->house_update($v['s'], $house); 
      }
    }
    return $rcode;
  }

}
