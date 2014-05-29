<?php defined('SYSPATH') OR die('No direct script access.');

class Model_Article_Core extends Model {

  public  $vew;
  public  $table = 'article';
  public  $primary_key = 'aid';
  protected $model_tag = 'Article_Core_Tag';
  protected $model_withtag = 'Article_Core_Withtag';

  function __construct()   
  {
    parent::__construct();
    $this->model_tag = Model::factory($this->model_tag);
    $this->model_withtag = Model::factory($this->model_withtag);
  }

  public  function get_one_front($aid) 
  {
    $query = DB::query(Database::SELECT, 
                'SELECT * FROM "'. $this->table .'" WHERE aid=:aid AND display=true LIMIT 1')
              ->param(':aid', $aid)
              ->as_object()
              ->execute();
    return $query->count() == 0 ?  NULL : $query->current();
  }

  public  function get_one($aid) 
  {
    $query = DB::query(Database::SELECT, 
                'SELECT * FROM "'. $this->table .'" WHERE aid=:aid LIMIT 1')
              ->param(':aid', (int) $aid)
              ->execute();
    return $query->count() == 0 ?  NULL : $query->current();
  }
  
  public  function get_pre_one($aid, $category) 
  {
    $query = DB::query(Database::SELECT, 
      'SELECT * FROM "'. $this->table .'" 
          WHERE category=:category AND aid < :aid AND display=true ORDER BY aid DESC LIMIT 1')
              ->param(':aid', $aid)
              ->param(':category', $category)
              ->execute();
    return $query->count() == 0 ?  NULL : $query->current();
  }

  public  function get_next_one($aid, $category) 
  {
    $query = DB::query(Database::SELECT, 
      'SELECT * FROM "'. $this->table .'" 
          WHERE category=:category AND aid > :aid and display=true ORDER BY aid ASC LIMIT 1')
              ->param(':aid', $aid)
              ->param(':category', $category)
              ->execute();
    return $query->count() == 0 ?  NULL : $query->current();
  }

  public function get_list_favorite(array $ids, $page=1)
  {
    $aid = implode(',', $ids);
    $query = DB::query(Database::SELECT, 'SELECT * FROM "'.$this->table.'" 
                WHERE aid in ('.$aid.') AND display=TRUE')
              ->as_object()
              ->execute();
    return $query->count() == 0 ? NULL : $query;
  }

  public function get_list_front($city_id, $where)
  {
    $sql = 'display= true';
    $parameters = $parameters_extra = array();
    if (isset($where['keyword']) && ($keyword = trim($where['keyword']))) {
        $sql .= ' AND subject like :keyword ';
        $parameters[':keyword'] = '%'.$keyword.'%';
    }
    if (isset($where['cat']) && $where['cat']) {
        $sql .= ' AND category=:cat ';
        $parameters[':cat'] = $where['cat'];
    } 
    if (isset($where['tag']) && $where['tag']) {
        $sql .= ' AND tag @> {:tag} ';
        $parameters_extra[':tag'] = $where['tag'];
    } 
    if (isset($where['day']) && $where['day']) {
        $sql .= ' AND created > :created ';
        $start = date('Y-m-d H:i:s', time()-$where['day']*86400);
        $parameters[':created'] = $start;
    }
    $page = $where['page']; 
    $query = DB::query(Database::SELECT, 'SELECT * FROM :table
                WHERE '.$sql.' ORDER BY weight DESC, created DESC LIMIT :num OFFSET :start ')
              ->parameters($parameters)
              ->parameters_extra($parameters_extra)
              ->param_extra(':table', $this->table)
              ->param(':num', $this->pagination->default['items_per_page'])
              ->param(':start', $this->pagination->default['items_per_page'] * ($page-1))
              ->as_object()
              ->execute();
    return $query->count() == 0 ? NULL : $query;
  }



  public function get_list(array $where = NULL, $page = 1)
  {
    $ret = array('total'=>0, 'data' => '');
    $sql = 'SELECT * FROM "'. $this->table .'"';
    $parameters = array();
    if($where !== NULL) 
    {
      $sql .= ' WHERE ';
      $w = array();
      foreach ($where as $k=>$v) {
        $w[] = $k.'=:'.$k;
        $parameters[':'.$k] = $v;
      }
      $sql .= implode(' AND ', $w);
    }
    $sql .= '';
    $page = max($page, 1);
    $query = DB::query(Database::SELECT, 'SELECT count(*) FROM "'.$this->table.'" ' .$where)
            ->parameters($parameters)
            ->execute();
    $ret['total'] = $query->get('count');
    if ($ret['total']) {
      $query = DB::query(Database::SELECT, $sql . 
                   'ORDER BY weight DESC, aid DESC LIMIT :num OFFSET :start')
              ->param(':num', $this->pagination->manager['items_per_page'])
              ->param(':start', ($page-1) * $this->pagination->manager['items_per_page'])
              ->as_object()
              ->execute();
      $ret['data'] = $query;
    }
    return $ret;
  }


  public function save_one($data)
  {
    unset($data['csrf']);

    $aid = $data['aid'];
    $tag = '{';
    if (empty($data['tag']) == FALSE) {
      $tmp = array();
      $tags = explode(' ', $data['tag']);
      foreach($tags as $v) {
        $v = Security::xss_clean($v);
        $ret = $this->model_tag->save_one($v);
        if ($ret === FALSE) {
          return FALSE;
        }
        if (in_array($ret, $tmp) == FALSE) {
          $tmp[] = $ret;
        }
      }
      $tag .= implode(',', $tmp);
    }
    $tag .= '}';

    $relation = '{';
    if (empty($data['relation']) == FALSE) {
      $tmp = explode("\n", $data['relation']);
      $tmp_2 = array();
      foreach($tmp as $v) {
        if (trim($v) <> '') {
          $tmp_2[] =  $v;
        }
      }
      $relation .= implode(',', $tmp_2);
    }
    $relation .= '}';


    $parameters = array();
    $upset = '';
    foreach($data as $k=>$v)  {
      if ($v <> '') {
        switch($k) {
          case 'relation':
            $parameters[':'.$k] = $relation;
            break;
          case 'tag':
            $parameters[':'.$k] = $tag;
            break;
          default:
          if (is_string($v)) {
            $v = Security::xss_clean($v);
          }
          $parameters[':'.$k] = $v;
        }
        $upset .= $k.'=:'.$k.',';
      }
      else { 
        unset($data[$k]);
      }
    }
    Cache::instance()->delete('article_tag');
    if ($aid == 0) {
      unset($data['aid']);
      $field =  implode(',', array_keys($data));
      $value =  implode(', :', array_keys($data));
      $query = DB::query(Database::SELECT, 
                  'INSERT INTO "'.$this->table.'" ('. $field .', created) VALUES (:'. $value .', NOW()) RETURNING aid')
                ->parameters($parameters)
                ->execute();
      return $query->count() == 0 ?  FALSE : $query->get('aid');
    }
    else {
      $field =  implode(',', array_keys($data));
      $value =  implode(', :', array_keys($data));
      $upset = UTF8::substr($upset, 0, -1);
      $query = DB::query(Database::UPDATE, 'UPDATE "'.$this->table.'" SET '. $upset .' WHERE aid=:aid')
                ->parameters($parameters)
                ->execute();
      return $query ?  $data['aid']: FALSE;
    }
  } 
  
  public function support_one($aid, $num)
  {
    if ($num > 0) {
      $set = 'up=up+1';
    }
    else {
      $set = 'down=down+1';
    }
    $query = DB::query(Database::UPDATE, 'UPDATE "'.$this->table.'" SET '.$set.'  WHERE aid=:aid')
              ->param(':aid', $aid)
              ->execute();
    return $query ?  TRUE : FALSE;
  }

  public function update_faq($aid)
  {
    $query = DB::query(Database::UPDATE, 'UPDATE "'.$this->table.'" SET faq=faq+1  WHERE aid=:aid')
              ->param(':aid', $aid)
              ->execute();
    return $query ?  TRUE : FALSE;
  }

  public function check_one($aid)
  {
    $query = DB::query(Database::SELECT, 'SELECT aid FROM "'. $this->table .'"  WHERE aid=:aid LIMIT 1')
              ->param(':aid', $aid)
              ->execute();
    return $query->count() == 0 ? FALSE : TRUE;
  }


}
