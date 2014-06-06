<?php defined('SYSPATH') OR die('No direct script access.');

class Model_Article_Core_Faq extends Model {

  public  $vew;
  public  $table = 'article_faq';


  public  function get_detail($aid, $page = 1) 
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

  public function get_more($where=NULL, $start=0, $limit=30, $is_object = TRUE) 
  {
    $sql = array('true');
    $params = array();
    if ($where !== NULL) {
      foreach($where as $k=>$v) {
        $pkey = ':'.$k;
        $params[$pkey] = $v;
        $sql[] = 'T1.'.$k.'='.$pkey;
      }
    }
    $query = DB::query(Database::SELECT, 
                'SELECT T1.*, T2.subject FROM :table AS T1 LEFT JOIN :table_article AS T2 ON T1.aid=T2.aid 
                    WHERE '. (implode(' AND ', $sql)) .' LIMIT '.$limit.' OFFSET '.$start)
              ->param_extra(':table', $this->table)
              ->param_extra(':table_article', 'article')
              ->param(':start', $start)
              ->param(':limit', $limit)
              ->parameters($params);
    if ($is_object === TRUE) {
      $query = $query->as_object();
    }
    return $query->execute();
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
    $query = DB::query(Database::SELECT, 
                'INSERT INTO "'. $this->table .'" (aid, mid, body, city_id) VALUES (:aid, :mid, :body, :city_id) RETURNING fid')
              ->param(':aid', $data['aid'])
              ->param(':mid', $data['mid'])
              ->param(':city_id', $data['city_id'])
              ->param(':body', $data['body'])
              ->execute();
    Model::factory('Article')->update_faq($data['aid']);
    return $query->count() == 0 ?  FALSE : $query->get('fid');
  } 

}
