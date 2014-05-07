<?php defined('SYSPATH') OR die('No direct script access.');

class Model_Article_Core_Withtag extends Kohana_Model {

  public  $vew;
  public  $table = 'article_with_tag';

  public  function get_list(array $tag_list) 
  {
    $query = DB::query(Database::SELECT, 
                'SELECT tid, name FROM "'. $this->table .'" WHERE tid IN ('. implode(',', $tag_list) .')')
              ->execute();
    return $query->count() == 0 ?  array() : $query->as_array('tid', 'name');
  }

  public  function get_one($tid) 
  {
    $query = DB::query(Database::SELECT, 
                'SELECT name FROM "'. $this->table .'" WHERE tid=:tid LIMIT 1')
              ->param(':tid', $tid)
              ->execute();
    return $query->count() == 0 ?  NULL : $query->get('name');
  }

  public function delete_one($tid)
  {
    $query = DB::query(Database::SELECT, 
                'DELETE FROM "'. $this->table .'" WHERE tid=:tid')
              ->param(':cid', $cid)
              ->execute();
    return $query->count() == 0 ?  FALSE : TRUE;
  }

  public function delete_list(array $tid)
  {
    $query = DB::query(Database::SELECT, 
                'DELETE FROM "'. $this->table .'" WHERE tid in ('. implode(',',$tid) .')')
              ->execute();
    return $query->count() == 0 ?  FALSE : TRUE;
  }

  public function save_one($tag)
  {
    $ret = $this->check_one($tag);
    if ($ret) {
      return $ret;
    }
    $query = DB::query(Database::SELECT, 
                'INSERT INTO "'. $this->table .'" (name) VALUES (:name) RETURNING tid')
              ->param(':name', $tag)
              ->execute();
    return $query->count() == 0 ?  FALSE : $query->get('tid');
  } 

  public function save_list(array $tag)
  {
    $query = DB::query(Database::SELECT, 
                'INSERT INTO "'. $this->table .'" (name) VALUES (:name) RETURNING tid')
                ->param(':name', $tag);
    $ret = array();
    foreach($tag as $v) {
      if ($this->check_one($tag) == FALSE) {
        $r = $query->param(':name', $v)->execute();
        $ret[] = $r->get('tid');
      }
    }
    return $ret;
  } 

  public function check_one($tag)
  {
    $query = DB::query(Database::SELECT, 
                'SELECT tid FROM "'. $this->table .'" WHERE name=:name LIMIT 1')
              ->param(':name', $tag)
              ->execute();
    return $query->count() == 0 ?  FALSE : $query->get('tid');
  } 

}
