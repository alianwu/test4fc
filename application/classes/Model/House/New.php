<?php defined('SYSPATH') OR die('No direct script access.');

class Model_House_New extends Model {

  protected $pagination;

  function __construct()   
  {
    $this->pagination = Kohana::$config->load('pagination');
  }

  public function get_list($city_id, $page = 0)
  {
    $pagination = Kohana::$config->load('pagination.manager');
    $query = DB::query(Database::SELECT, 'SELECT count(*) FROM house WHERE city_id=:city_id')
              ->param(':city_id', $city_id)
              ->as_object()
              ->execute();
    $ret['total'] = $query->get('count', 0);
    $query = DB::query(Database::SELECT, 'SELECT * FROM house 
                WHERE city_id=:city_id ORDER BY weight DESC, hid DESC LIMIT :num OFFSET :start ')
              ->param(':city_id', $city_id)
              ->param(':num', $this->pagination->manager['items_per_page'])
              ->param(':start', $this->pagination->manager['items_per_page'] * ($page-1))
              ->as_object()
              ->execute();
    $ret['data'] = $query;
    return $ret;
  } 
  
  public function get_list_front($city_id, $page = 1)
  {
    $query = DB::query(Database::SELECT, 'SELECT *, phone[1] AS phone_1, geo[0] AS lng, geo[1] AS lat, attachment_9[0] AS image FROM house 
                WHERE city_id=:city_id and display=TRUE ORDER BY weight DESC, hid DESC LIMIT :num OFFSET :start ')
              ->param(':city_id', $city_id)
              ->param(':num', $this->pagination->default['items_per_page'])
              ->param(':start', $this->pagination->default['items_per_page'] * ($page-1))
              ->as_object()
              ->execute();
    return $query->count() == 0 ? NULL : $query;
  }
  
  public function get_hot_front($city_id, $page)
  {
    $query = DB::query(Database::SELECT, 'SELECT *, phone[1] AS phone_1, geo[0] AS lng, geo[1] AS lat, attachment_9[1] AS image  FROM house 
                WHERE city_id=:city_id AND hot=1 AND display=TRUE ORDER BY hit DESC, weight DESC LIMIT :num OFFSET :start ')
              ->param(':city_id', $city_id)
              ->param(':num', $this->pagination->default['items_per_page'])
              ->param(':start', $this->pagination->default['items_per_page'] * ($page-1))
              ->as_object()
              ->execute();
    return $query->count() == 0 ? NULL : $query;
  }

  public function get_search_front($city_id, $data)
  {
    $page = max((int)$data['page'], 1);
    $parameters = array(':city_id'=>$city_id);
    $where = 'city_id=:city_id';
    $name = trim($data['keyword']);
    if ($name) {
        $where .= ' AND (name like :keyword OR address like :keyword)';
        $parameters[':keyword'] = '%'.$name.'%';
    }
    $area = (int) $data['area']; 
    if ($area) {
      $where .= ' AND city_area=:city_area';
      $parameters[':city_area'] = $area;
    }
    $shop = (int) $data['shop']; 
    if ($shop) {
      $where .= ' AND city_area_shop=:city_area_shop';
      $parameters[':city_area_shop'] = $shop;
    }
    $price = explode('-', $data['price']);
    if ($price) {
      $min_price = isset($price[0])? (int) $price[0]:0;
      if ($min_price) {
        $where .= ' AND price >:min_price';
        $parameters[':min_price'] = $min_price;
      }
      $max_price = isset($price[1])? (int) $price[1]:0;

      if ( $max_price && $max_price > $min_price) {
        $where .= ' AND price < :max_price';
        $parameters[':max_price'] = $max_price;
      }
    }
    $underground = (int) $data['underground'];
    if ($underground) {
      $where .= ' AND underground=:underground';
      $parameters[':underground'] = $underground;
    }
    $underground_platform = (int) $data['underground_platform'];
    if ($underground_platform) {
      $where .= ' AND underground_platform=:underground_platform';
      $parameters[':underground_platform'] = $underground_platform;
    }

    $query = DB::query(Database::SELECT, 'SELECT *, phone[1] AS phone_1, geo[0] AS lng, geo[1] AS lat, attachment_9[1] AS image  FROM house 
                WHERE '.$where.' AND display=TRUE ORDER BY weight DESC, created DESC LIMIT :num OFFSET :start ')
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
    $sql = "SELECT t.*, t.attachment_9[1] AS image, t.phone[1] AS phone_1, t.phone[2] AS phone_2, t.phone[3] AS phone_3, t.phone[4] AS phone_4, t.geo[0] AS lng, t.geo[1] AS lat FROM house AS t
                WHERE city_id=:city_id AND ST_DWithin(
                  ST_Transform(ST_GeomFromText('".$point."',4326),26986), 
                  ST_Transform(t.geo2, 26986), "
                  .$radius.") AND display=TRUE
                  ORDER BY ST_Distance(ST_GeomFromText('".$point."',4326), t.geo2) LIMIT :num OFFSET :start";
    $query = DB::query(Database::SELECT, $sql)
              ->param(':city_id', $cid)
              ->param(':num', $this->pagination->default['items_per_page'])
              ->param(':start', $this->pagination->default['items_per_page'] * ($page-1))
              ->as_object()
              ->execute();
    return $query->count() == 0 ? NULL : $query;
  }
  
  public function get_list_favorite(array $ids, $page=1)
  {
    $hid = implode(',', $ids);
    $query = DB::query(Database::SELECT, 'SELECT *, phone[1] AS phone_1, geo[0] AS lng, geo[1] AS lat, attachment_9[1] AS image  FROM house 
                WHERE hid in ('.$hid.') AND display=TRUE')
              ->as_object()
              ->execute();
    return $query->count() == 0 ? NULL : $query;
  }

  public function get_one($hid)
  {
    $query = DB::query(Database::SELECT, 'SELECT *, geo[0] AS lng, geo[1] AS lat FROM house WHERE hid=:hid')
              ->param(':hid', $hid)
              ->execute();
    return $query->count() == 0? NULL: $query->current();
  }

  public function get_one_front($hid)
  {
    $query = DB::query(Database::SELECT, 'SELECT *, geo[0] AS lng, geo[1] AS lat FROM house WHERE hid=:hid AND display = TRUE LIMIT 1')
              ->param(':hid', $hid)
              ->as_object()
              ->execute();
    return $query->count() == 0? NULL : $query->current();
  }

  public function attachment_save($hid, $type, $attachment)
  {
    $field = 'attachment_'.$type;
    $query = DB::query(Database::UPDATE, 'UPDATE house SET '.$field.'=array_append('.$field.',:attachment) WHERE hid=:hid')
              ->param(':hid', $hid)
              ->param(':attachment', urldecode($attachment))
              ->execute();
    return $query? TRUE : FALSE;
  }

  public function attachment_delete($hid, $type, $attachment)
  {
    $field = 'attachment_'.$type;
    $query = DB::query(Database::UPDATE, 'UPDATE house SET '.$field.'=array_remove('.$field.',:attachment) WHERE hid=:hid')
              ->param(':hid', $hid)
              ->param(':attachment', urldecode($attachment))
              ->execute();
    return $query? TRUE : FALSE;
  }

  public function update_hit($hid)
  {
    $query = DB::query(Database::UPDATE, 'UPDATE house SET hit=hit+1 WHERE hid=:hid')
              ->param(':hid', $hid)
              ->execute();
    return $query? TRUE : FALSE;
  }

  // return house id
  public function save_one($data)
  {
    $rcode = 0;
    $data['geo'] = $data['lng'] . ',' . $data['lat']; 
    $data['display'] = (bool) $data['display']; 
    $data['phone'] = '{'. $data['phone'] .'}'; 

    unset($data['csrf'], $data['lng'], $data['lat']);
    $parameters = array();
    $upset = '';
    foreach($data as $k=>$v)  {
      switch($k) {
      case 'display':
          $upset .= 'display='.($v?'true':'false').', ';
          unset($data[$k]);
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
    $geo_update_sql = 'update house set geo2 = ST_GeomFromText(concat(\'POINT(\',geo[0], \' \', geo[1], \')\'), 4326) 
                              WHERE hid=:hid';
    if ($data['hid'] == 0) {
      unset($data['hid']);
      $tmp_attachment = (array) Session::instance()->get('manager.house.add.attachment');
      if ($tmp_attachment) {
        unset($tmp_attachment['hid']);
        foreach($tmp_attachment as $k => $v) {
          $parameters[':'.$k] = 
          $data[$k] = '{'.($v?implode(',', $v):'').'}';
        }
      }
      $field =  implode(',', array_keys($data));
      $value =  implode(', :', array_keys($data));
      $query = DB::query(Database::SELECT, 'INSERT INTO house ('. $field .', created) VALUES (:'. $value .', NOW()) RETURNING hid')
                ->parameters($parameters)
                ->as_object()
                ->execute();
      if ($query) {
        $hid =  $query->get('hid');
        Session::instance()->delete('manager.house.add.attachment');
        DB::query(Database::UPDATE, $geo_update_sql)->param(':hid', $hid)->execute();
        $rcode = $hid;
      }
    }
    else {
      $upset = substr($upset, 0, -1);
      $field =  implode(',', array_keys($data));
      $value =  implode(', :', array_keys($data));
      $query = DB::query(Database::UPDATE, 'UPDATE house SET '. $upset .' WHERE hid=:hid')
                ->parameters($parameters)
                ->execute();
      if($query) {
        DB::query(Database::UPDATE, $geo_update_sql)->param(':hid', $data['hid'])->execute();
        $rcode = $data['hid'];
     }

    }
    return $rcode;
  }

  public function display_one($hid)
  {
    $query = DB::query(Database::UPDATE, 'UPDATE house SET display= NOT display  WHERE hid=:hid')
              ->param(':hid', $hid)
              ->execute();
    return $query? TRUE:FALSE;
  }

  public function hot_many($hid, $hot)
  {
    $query = DB::query(Database::UPDATE, 'UPDATE house SET hot=:hot WHERE hid in ('.implode(',', $hid).')')
              ->param(':hot', $hot)
              ->execute();
    return $query? TRUE:FALSE;
  }

  public function delete_one($hid)
  {
    $query = DB::query(Database::DELETE, 'DELETE FROM house  WHERE hid=:hid')
              ->param(':hid', $hid)
              ->execute();
    return $query? TRUE:FALSE;
  }

  public function delete_many($hid)
  {
    $query = DB::query(Database::DELETE, 'DELETE FROM house  WHERE hid in ('.implode(',', $hid).')')
              ->execute();
    return $query? TRUE:FALSE;
  }


}
