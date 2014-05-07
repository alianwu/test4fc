<?php defined('SYSPATH') OR die('No direct script access.');

class Model_Article_Core_Faq extends Kohana_Model {

  public  $vew;
  public  $table = 'article_faq';

  function __construct()   
  {
    $this->pagination = Kohana::$config->load('pagination');
  }

  public  function get_list($aid, $page = 1) 
  {
    $ret = array('total'=>0, 'data' => array());
    $query = DB::query(Database::SELECT, 'SELECT count(*) FROM "'.$this->table.'" WHERE aid=:aid')
            ->param(':aid', $aid)
            ->execute();
    $ret['total'] = $query->get('count');
    if ($ret['total']) {
      $query = DB::query(Database::SELECT, 
                'SELECT * FROM "'. $this->table .'" WHERE aid=:aid ORDER BY created DESC LIMIT :num OFFSET :start')
              ->param(':aid', $aid)
              ->param(':num', $this->pagination->manager['items_per_page'])
              ->param(':start', ($page-1) * $this->pagination->manager['items_per_page'])
              ->as_object()
              ->execute();
      $ret['data'] = $query;
    }
    return $ret;
  }


  public function delete_one($fid)
  {
    $query = DB::query(Database::SELECT, 
                'DELETE FROM "'. $this->table .'" WHERE fid=:fid')
              ->param(':fid', $fid)
              ->execute();
    return $query?  TRUE : FALSE;
  }

  public function delete_list(array $fid)
  {
    $query = DB::query(Database::SELECT, 
                'DELETE FROM "'. $this->table .'" WHERE fid in ('. implode(',',$fid) .')')
              ->execute();
    return $query?  TRUE : FALSE;
  }

  public function save_one($data)
  {
    $data['body'] = Security::xss_clean($data['body']);
    $query = DB::query(Database::SELECT, 
                'INSERT INTO "'. $this->table .'" (aid, body) VALUES (:aid, :body) RETURNING fid')
              ->param(':aid', $data['aid'])
              ->param(':body', $data['body'])
              ->execute();
    Model::factory('Article')->update_faq($data['aid']);
    return $query->count() == 0 ?  FALSE : $query->get('fid');
  } 

}
