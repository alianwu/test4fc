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
    $query = DB::query(Database::SELECT, 'SELECT *, phone[1] AS phone_1, geo[0] AS lng, geo[1] AS lat FROM house 
                WHERE city_id=:city_id and display=TRUE ORDER BY weight DESC, hid DESC LIMIT :num OFFSET :start ')
              ->param(':city_id', $city_id)
              ->param(':num', $this->pagination->default['items_per_page'])
              ->param(':start', $this->pagination->default['items_per_page'] * ($page-1))
              ->as_object()
              ->execute();
    return $query->count() == 0 ? NULL : $query;

  }

  public function get_search_front($data, $page)
  {
    $ret = array('total' => 0, 'data' => NULL);

    return $ret;
  }

  public function get_near_front($cid, $lat, $lng, $radius)
  {
    $ret = array('total' => 0, 'data' => NULL);
    $point = 'POINT('.$lng.' '.$lat.')';
    $sql = "SELECT t.*, t.geo[0] AS lng, t.geo[1] As lat FROM house As t
                WHERE city_id=:city_id AND ST_DWithin(
                  ST_Transform(ST_GeomFromText('".$point."',4326),26986), 
                  ST_Transform(t.geo2, 26986), "
                  .$radius.") 
                  ORDER BY ST_Distance(ST_GeomFromText('".$point."',4326), t.geo2) LIMIT :num";
    $query = DB::query(Database::SELECT, $sql)
              ->param(':city_id', $cid)
              ->param(':num', 30)
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
      if ($v <> '') {
        $parameters[':'.$k] = $v;
        $upset .= $k.'=:'.$k.',';
      }
      else { 
        unset($data[$k]);
      }
    }
    $geo_update_sql = 'update house set geo2 = ST_GeomFromText(concat(\'POINT(\',geo[0], \' \', geo[1], \')\'), 4326) 
                              WHERE hid=:hid';
    if ($data['hid'] == 0) {
      unset($data['hid']);
      $field =  implode(',', array_keys($data));
      $value =  implode(', :', array_keys($data));
      $query = DB::query(Database::SELECT, 'INSERT INTO house ('. $field .', created) VALUES (:'. $value .', NOW()) RETURNING hid')
                ->parameters($parameters)
                ->as_object()
                ->execute();
      if ($query) {
        $hid =  $query->get('hid');
        DB::query(Database::UPDATE, $geo_update_sql)->param(':hid', $hid)->execute();
        $rcode = 1;
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
        $rcode = 1;
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

  public function delete_one($hid)
  {
    $query = DB::query(Database::DELETE, 'DELETE FROM house  WHERE hid=:hid')
              ->param(':hid', $hid)
              ->execute();
    return $query? TRUE:FALSE;
  }
}
