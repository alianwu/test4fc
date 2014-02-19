<?php defined('SYSPATH') OR die('No direct script access.');

class Model_Favorite {

  function __construct()
  {
    $this->pagination = Kohana::$config->load('pagination.default');
  }

  public function get_list($mid, $type, $page)
  {
    $query = DB::query(Database::SELECT, 'SELECT * FROM favorite 
                WHERE mid=:mid and type=:type  ORDER BY created DESC LIMIT :num OFFSET :start ')
              ->param(':type', $type)
              ->param(':mid', $mid)
              ->param(':num', $this->pagination['items_per_page'])
              ->param(':start', $this->pagination['items_per_page'] * ($page-1))
              ->as_object()
              ->execute();
    return $query->count() == 0 ? NULL : $query;

  }

  public function save($data)
  {
    $query = DB::query(Database::INSERT, 'INSERT INTO favorite (uid, type, id, name) 
                  VALUES(:uid, :type, :id, :name)')
              ->param(':uid', $data['uid'])
              ->param(':type', $data['type'])
              ->param(':id', $data['id'])
              ->param(':name', $data['name'])
              ->execute();
    return $query;
    
  }

  public function delete($fid)
  {
    $query = DB::query(Database::DELETE, 'DELETE FROM favorite  WHERE fid=:fid')
              ->param(':fid', $fid)
              ->execute();
    return $query;
  }

}


