<?php defined('SYSPATH') OR die('No direct script access.');

class Model_House_Faq_Detail extends Model {

  protected $pagination;

  function __construct()   
  {
    $this->pagination = Kohana::$config->load('pagination');
  }

  public function get_list($fid, $page = 0)
  {
    $pagination = Kohana::$config->load('pagination.manager');
    $query = DB::query(Database::SELECT, 'SELECT count(*) FROM house_faq_detail WHERE fid=:fid')
              ->param(':fid', $fid)
              ->as_object()
              ->execute();
    $ret['total'] = $query->get('count', 0);
    $query = DB::query(Database::SELECT, 'SELECT * FROM house_faq_detail
                WHERE fid=:fid ORDER BY weight DESC, fdid DESC LIMIT :num OFFSET :start ')
              ->param(':fid', $fid)
              ->param(':num', $this->pagination->manager['items_per_page'])
              ->param(':start', $this->pagination->manager['items_per_page'] * ($page-1))
              ->as_object()
              ->execute();
    $ret['data'] = $query;
    return $ret;
  } 
  
  public function get_list_front($fid, $page = 1)
  {
    $query = DB::query(Database::SELECT, 'SELECT * FROM house_faq_detail
                WHERE fid=:fid and display=true ORDER BY weight DESC, created DESC LIMIT :num OFFSET :start ')
              ->param(':fid', $fid)
              ->param(':num', $this->pagination->default['items_per_page'])
              ->param(':start', $this->pagination->default['items_per_page'] * ($page-1))
              ->as_object()
              ->execute();
    return $query->count() == 0 ? NULL : $query;

  }

  public function save_one($data)
  {
    $query = DB::query(Database::INSERT, 'INSERT INTO house_faq_detail_detail(fid, body, mid, username)
                VALUES (:fid, :body, :mid, :username) ')
              ->param(':fid', $data['fid'])
              ->param(':body', $data['body'])
              ->param(':mid', $data['_id'])
              ->param(':username', $data['_name'])
              ->execute();
    return $query?TRUE:FALSE;
  }

  public function display_one($fid)
  {
    $query = DB::query(Database::UPDATE, 'UPDATE house_faq_detail SET display= NOT display  WHERE fdid=:fid')
              ->param(':fid', $fid)
              ->execute();
    return $query?TRUE:FALSE;
  }

  public function delete_one($fid)
  {
    $query = DB::query(Database::DELETE, 'DELETE FROM house_faq_detail  WHERE fdid=:fid')
              ->param(':fid', $fid)
              ->execute();
    return $query?TRUE:FALSE;
  }
}
