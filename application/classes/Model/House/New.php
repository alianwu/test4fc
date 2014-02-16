<?php defined('SYSPATH') OR die('No direct script access.');

class Model_House_New extends Model {

  protected $pagination;

  function __construct()   
  {
    $this->pagination = Kohana::$config->load('pagination.manager');
  }

  public function get_house($city_id, $page = 0)
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
              ->param(':num', $this->pagination['items_per_page'])
              ->param(':start', $this->pagination['items_per_page'] * ($page-1))
              ->as_object()
              ->execute();
    $ret['data'] = $query;
    return $ret;
  } 
  
  public function get_house_front($city_id, $page = 1)
  {
    $query = DB::query(Database::SELECT, 'SELECT *, phone[1] as phone_1 FROM house 
                WHERE city_id=:city_id and display=TRUE ORDER BY weight DESC, hid DESC LIMIT :num OFFSET :start ')
              ->param(':city_id', $city_id)
              ->param(':num', $this->pagination['items_per_page'])
              ->param(':start', $this->pagination['items_per_page'] * ($page-1))
              ->as_object()
              ->execute();
    return $query->count() == 0 ? NULL : $query;

  }

  public function get_house_search()
  {
    $ret = array('total' => 0, 'data' => NULL);
    return $ret;
  }

  public function get_house_one($hid)
  {
    $query = DB::query(Database::SELECT, 'SELECT * FROM house WHERE hid=:hid')
              ->param(':hid', $hid)
              ->execute();
    return $query->count() == 0? FALSE : Arr::get($query->as_array(), 0);
  }
  
  public function save($data)
  {
    $ret = array('error'=>TRUE, 'info'=>'');

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
    if ($data['hid'] == 0) {
      unset($data['hid']);
      $field =  implode(',', array_keys($data));
      $value =  implode(', :', array_keys($data));
      $query = DB::query(Database::INSERT, 'INSERT INTO house ('. $field .', created) VALUES (:'. $value .', NOW())')
                ->parameters($parameters)
                ->execute();
      if ($query) {
        $ret['info'] = '执行成功';
        $ret['error'] = FALSE;
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
        $ret['error'] = FALSE;
      }

    }
    return $ret;
  }

  public function update_display($hid)
  {
    $ret = array('error'=>TRUE, 'info'=>'');
    $query = DB::query(Database::UPDATE, 'UPDATE house SET display= NOT display  WHERE hid=:hid')
              ->param(':hid', $hid)
              ->execute();
    if ($query) {
      $ret['error'] = FALSE;
    }
    return $ret;
  }

  public function delete($hid)
  {
    $ret = array('error'=>TRUE, 'info'=>'');
    $query = DB::query(Database::DELETE, 'DELETE FROM house  WHERE hid=:hid')
              ->param(':hid', $hid)
              ->execute();
    if ($query) {
      $ret['error'] = FALSE;
    }
    return $ret;
  }
}
