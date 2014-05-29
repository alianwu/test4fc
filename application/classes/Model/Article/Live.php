<?php defined('SYSPATH') OR die('No direct script access.');

class Model_Article_Live extends Model {

  public  $table = 'article_live';
  protected $primary_key = 'lid';


  public function get_list_front($city_id, $where)
  {
    $type = $where['type'];
    $sql = '';
    if ($where['lastid']) {
      if ($type == 'new') {
        $sql = ' AND lid > :lastid'; 
      }
      elseif ($type == 'old') {
        $sql = ' AND lid < :lastid'; 
      }
      else {
        return FALSE;
      }
    }
    $query = DB::query(Database::SELECT, 'SELECT * FROM :table 
                    WHERE aid=:aid '. $sql.' ORDER BY lid DESC LIMIT :limit')
      ->param_extra(':table', $this->table)
      ->param(':lastid', $where['lastid'])
      ->param(':limit', $this->pagination->default['items_per_page'])
      ->param(':aid', $where['aid'])
      ->as_object()
      ->execute();
    return $query->count() == 0 ? NULL: $query;
  }

}
