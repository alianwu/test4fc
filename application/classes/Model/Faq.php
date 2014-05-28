<?php defined('SYSPATH') OR die('No direct script access.');

class Model_Faq extends Model {

  protected $table = 'faq';
  protected $primary_key = 'fid';

  public function get_list($city_id, $where = NULL)
  {
    $page = max((int) (isset($where['page'])? $where['page'] : 1), 1);

    $query = DB::query(Database::SELECT, 'SELECT count(*) FROM :table WHERE  city_id=:city_id')
              ->param(':city_id', $city_id)
              ->param_extra(':table', $this->table)
              ->as_object()
              ->execute();
    $ret['total'] = $query->get('count', 0);

    $query = DB::query(Database::SELECT, 'SELECT * FROM :table
                WHERE city_id=:city_id ORDER BY weight DESC, fid DESC LIMIT :num OFFSET :start ')
              ->param(':city_id', $city_id)
              ->param_extra(':table', $this->table)
              ->param(':num', $this->pagination->manager['items_per_page'])
              ->param(':start', $this->pagination->manager['items_per_page'] * ($page-1))
              ->as_object()
              ->execute();
    $ret['data'] = $query;
    return $ret;
  } 

  public function get_list_front($city_id, $where)
  {
    $query = DB::query(Database::SELECT, 'SELECT *
              FROM :table
                WHERE id=:id AND display=true 
                  ORDER BY created DESC, fid DESC LIMIT :num OFFSET :start ')
              ->param_extra(':table', $this->table)
              ->param(':id', $where['id'])
              ->param(':type', $where['type'])
              ->param(':city_id', $city_id)
              ->param(':num', $this->pagination->default['items_per_page'])
              ->param(':start', $this->pagination->default['items_per_page'] * ($where['page']-1))
              ->as_object()
              ->execute();
    return $query->count() == 0 ? NULL : $query;
  } 

  public function get_list_hot_front($hid, $page = 1)
  {
    $query = DB::query(Database::SELECT, 'SELECT * FROM house_faq
                WHERE hid=:hid AND display=true ORDER BY count DESC, fid DESC LIMIT :num OFFSET :start ')
              ->param(':hid', $hid)
              ->param(':num', $this->pagination->default['items_per_page'])
              ->param(':start', $this->pagination->default['items_per_page'] * ($page-1))
              ->as_object()
              ->execute();
    return $query->count() == 0 ? NULL : $query;
  } 
  
  public function get_one_front($fid)
  {
    $query = DB::query(Database::SELECT, 'SELECT * FROM faq
                WHERE fid=:fid and display=true LIMIT 1 ')
              ->param(':fid', $fid)
              ->as_object()
              ->execute();
    return $query->count() == 0 ? NULL: $query->current();

  }

  public function get_list_favorite(array $ids, $page=1)
  {
    $fid = implode(',', $ids);
    $query = DB::query(Database::SELECT, 'SELECT *  FROM house_faq
                WHERE fid in ('.$fid.') AND display=TRUE')
              ->as_object()
              ->execute();
    return $query->count() == 0 ? NULL : $query;
  }


  public function save_one($data)
  {
    $query = DB::query(Database::INSERT, 'INSERT INTO 
              faq (id, body, mid, uname)
                VALUES (:id, :body, :mid, :uname) ')
              ->param(':id', $data['id'])
              ->param(':city_id', $data['city_id'])
              ->param(':type', $data['type'])
              ->param(':body', $data['body'])
              ->param(':mid', $data['_id'])
              ->param(':mname', $data['_name'])
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
