<?php defined('SYSPATH') OR die('No direct script access.');

class Model_Article_Core_Category extends Model {

  public  $vew;
  public  $table = 'article_category';
  public  $primary_key = 'acid';

  public  function get_list() 
  {
    $query = DB::query(Database::SELECT, 
                'SELECT * FROM "'. $this->table .'" ORDER BY weight DESC, acid DESC')
              ->as_object()
              ->execute();
    return $query->count() == 0 ?  array() : $query;
  }

  public  function get_list_pretty() 
  {
    $query = DB::query(Database::SELECT, 
                'SELECT * FROM "'. $this->table .'" ORDER BY weight DESC, acid DESC')
              ->execute();
    return $query->count() == 0 ?  array() : $query->as_array('acid', 'name');
  }

  public  function get_one_pretty($acid) 
  {
    $query = DB::query(Database::SELECT, 
                'SELECT name FROM "'. $this->table .'" WHERE acid=:acid LIMIT 1')
              ->param(':acid', $acid)
              ->execute();
    return $query->count() == 0 ?  NULL : $query->get('name');
  }

  public  function get_one($acid) 
  {
    $query = DB::query(Database::SELECT, 
                'SELECT * FROM "'. $this->table .'" WHERE acid=:acid LIMIT 1')
              ->param(':acid', $acid)
              ->execute();
    return $query->count() == 0 ?  NULL : $query->current();
  }

  public function delete_one($acid)
  {
    $query = DB::query(Database::DELETE, 
                'DELETE FROM "'. $this->table .'" WHERE acid=:acid')
              ->param(':acid', $acid)
              ->execute();
    return $query ?  TRUE : FALSE;
  }

  public function delete_list(array $acid)
  {
    $query = DB::query(Database::SELECT, 
                'DELETE FROM "'. $this->table .'" WHERE acid in ('. implode(',',$acid) .')')
              ->execute();
    return $query->count() == 0 ?  FALSE : TRUE;
  }

  public function save_one($data)
  {
    $name = $data['name'];
    $acid = $data['acid'];
    if ($acid == 0) {
      if ($this->check_one($name) == TRUE) {
        return FALSE;
      }
      $query = DB::query(Database::SELECT, 
                'INSERT INTO "'. $this->table .'" (name, weight) VALUES (:name, :weight) RETURNING acid')
              ->param(':name', $name)
              ->param(':weight', $data['weight'])
              ->execute();
    }
    else {
      $query = DB::query(Database::SELECT, 
                'UPDATE "'. $this->table .'" SET name=:name, weight=:weight WHERE acid=:acid RETURNING acid')
              ->param(':name', $name)
              ->param(':acid', $acid)
              ->param(':weight', $data['weight'])
              ->execute();
    }
    return $query->count() == 0 ?  FALSE : TRUE;
  } 

  public function check_one($name)
  {
    $query = DB::query(Database::SELECT, 
                'SELECT * FROM "'. $this->table .'" WHERE name=:name LIMIT 1')
              ->param(':name', $name)
              ->execute();
    return $query->count() == 0 ?  FALSE : TRUE;
  } 

}
