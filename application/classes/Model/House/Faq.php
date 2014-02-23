<?php defined('SYSPATH') OR die('No direct script access.');

class Model_House_Faq extends Model {

  protected $pagination;

  function __construct()   
  {
    $this->pagination = Kohana::$config->load('pagination');
  }

  public function get_list($city_id, $page = 0)
  {
    $pagination = Kohana::$config->load('pagination.manager');
    $query = DB::query(Database::SELECT, 'SELECT count(*) FROM house_faq WHERE city_id=:city_id')
              ->param(':city_id', $city_id)
              ->as_object()
              ->execute();
    $ret['total'] = $query->get('count', 0);
    $query = DB::query(Database::SELECT, 'SELECT * FROM house_faq
                WHERE city_id=:city_id ORDER BY weight DESC, fid DESC LIMIT :num OFFSET :start ')
              ->param(':city_id', $city_id)
              ->param(':num', $this->pagination->manager['items_per_page'])
              ->param(':start', $this->pagination->manager['items_per_page'] * ($page-1))
              ->as_object()
              ->execute();
    $ret['data'] = $query;
    return $ret;
  } 

  public function get_list_front($hid, $page = 1)
  {
    $query = DB::query(Database::SELECT, 'SELECT * FROM house_faq
                WHERE hid=:hid AND display=true ORDER BY weight DESC, fid DESC LIMIT :num OFFSET :start ')
              ->param(':hid', $hid)
              ->param(':num', $this->pagination->default['items_per_page'])
              ->param(':start', $this->pagination->default['items_per_page'] * ($page-1))
              ->as_object()
              ->execute();
    return $query->count() == 0 ? NULL : $query;
  } 
  
  public function get_one_front($fid)
  {
    $query = DB::query(Database::SELECT, 'SELECT * FROM house_faq
                WHERE fid=:fid and display=true LIMIT 1 ')
              ->param(':fid', $fid)
              ->as_object()
              ->execute();
    return $query->count() == 0 ? NULL: $query->current();

  }

  public function save_one($data)
  {
    $query = DB::query(Database::INSERT, 'INSERT INTO house_faq(hid, question, mid, username)
                VALUES (:hid, :question, :mid, :username) ')
              ->param(':hid', $data['hid'])
              ->param(':city_id', $data['city_id'])
              ->param(':question', $data['question'])
              ->param(':mid', $data['_id'])
              ->param(':username', $data['_name'])
              ->execute();
    return $query?TRUE:FALSE;
  }

  public function display_one($fid)
  {
    $query = DB::query(Database::UPDATE, 'UPDATE house_faq SET display= NOT display  WHERE fid=:fid')
              ->param(':fid', $fid)
              ->execute();
    return $query?TRUE:FALSE;
  }

  public function delete($fid)
  {
    $ret = array('error'=>TRUE, 'info'=>'');
    $query = DB::query(Database::DELETE, 'DELETE FROM house_faq  WHERE fid=:fid')
              ->param(':fid', $fid)
              ->execute();
    return $query?TRUE:FALSE;
  }
}
