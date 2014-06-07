<?php defined('SYSPATH') OR die('No direct script access.');

class Model_Faq_Detail extends Model {

  protected $table = 'faq_detail';
  protected $primary_key = 'did';

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

  public function get_list_front($fid, $page = 1)
  {
    $query = DB::query(Database::SELECT, 'SELECT * FROM :table
                WHERE fid=:fid AND display=true ORDER BY created DESC, did DESC LIMIT :num OFFSET :start ')
              ->param_extra(':table', $this->table)
              ->param(':fid', $fid)
              ->param(':num', $this->pagination->default['items_per_page'])
              ->param(':start', $this->pagination->default['items_per_page'] * ($page-1))
              ->as_object()
              ->execute();
    return $query->count() == 0 ? NULL : $query;
  } 

  
  public function get_one_front($fid)
  {
    $query = DB::query(Database::SELECT, 'SELECT * FROM faq_detail
                WHERE did=:id and display=true LIMIT 1 ')
              ->param(':id', $fid)
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
    $query = DB::query(Database::INSERT, 'INSERT INTO faq_detail (fid, city_id, body, mid, mname, created)
                VALUES (:fid, :city_id, :body, :mid, :mname, now()) ')
              ->param(':fid', $data['fid'])
              ->param(':city_id', $data['city_id'])
              ->param(':body', $data['body'])
              ->param(':city_id', $data['city_id'])
              ->param(':mid', $data['_id'])
              ->param(':mname', $data['_name'])
              ->execute();
    Model::factory('Faq')->update_count($data['fid']);
    return $query?TRUE:FALSE;
  }

}
