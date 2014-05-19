<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Model base class. All models should extend this class.
 *
 * @package    Kohana
 * @category   Models
 * @author     Kohana Team
 * @copyright  (c) 2008-2012 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class Model extends Kohana_Model {

  protected $table;
  protected $primary_key;
  protected $field;
  protected $pagination;

  public static $core = NULL;

  function __construct($table = NULL)
  {
    if ($table !== NULL) {
      $this->table = $table;
    }
    $this->pagination = Kohana::$config->load('pagination.default');
	}
  
  public function table($table) 
  {
    $this->table = $table;
    return $this;
  }

  public function get($id, $is_object = TRUE, $display = NULL) 
  {
    $extra_sql = '';
    $params = array();
    if ($display !== NULL) {
      $extra_sql = ' AND display=:display';
      if ($display === TRUE) {
        $params[':display'] = 'true';
      }
      elseif($display === FALSE) {
        $params[':display'] = 'false';
      }
      else {
        $params[':display'] = $display;
      }
    }
    $params[':id'] = $id;
    $query = DB::query(Database::SELECT, 
                  'SELECT * FROM "'. $this->table .'" 
                      WHERE '. $this->primary_key .'=:id '. $extra_sql .' LIMIT 1')
                ->parameters($params);
    if ($is_object === TRUE) {
      $query = $query->as_object();
    }
    $query = $query->execute();
    return $query->count() == 0 ?  NULL : $query->current();
  }

  public function get_more($where, $start = 0, $limit = 20, $is_object = TRUE, $display = NULL)
  {
    $where = '';
    $params = array();
    if ($display !== NULL) {
      $where = ' AND display=:display';
      if ($display === TRUE) {
        $params[':display'] = 'true';
      }
      elseif($display === FALSE) {
        $params[':display'] = 'false';
      }
      else {
        $params[':display'] = $display;
      }
    }
    list ($_where, $_params) = $where;
    $params += $_params;
    $where = $_where . $where;
    $query = DB::query(Database::SELECT, 
                  'SELECT * FROM "'. $this->table .'" 
                      WHERE '. $where .' LIMIT '.$limit.' OFFSET '.$start)
                ->parameters($params);
    if ($is_object === TRUE) {
      $query = $query->as_object();
    }
    return $query->execute();
  } 

  public function insert($data, $return = NULL)
  {
    $parms = array();
    foreach($data as $k=>$v) {
      $p_key = ':'.$k;
      $params[$p_key] = $v;
    }
    $return_sql = '';
    if ($return !== NULL) {
      $return_sql = 'RETURNING '.$return;
    }
    $keys = array_keys($data);
    $query = DB::query(Database::SELECT, 
                'INSERT INTO  "'. $this->table .'" ('. (implode(',', $keys)) .') 
                    VALUES (:'. (implode(', :', $keys)) .') '.$return_sql)
              ->parameters($params)
              ->execute();
    if ($return !== NULL) {
      return $query->get('c_id');
    }
    return  $query->count() ? TRUE : FALSE;
  } 

  public function update($id, array $data =NULL)
  {
    $sets = 
    $parms = array();
    foreach($data as $k=>$v) {
      $p_key = ':'.$k;
      $sets[] = $k.'='.$p_key;
      $params[$p_key] = $v;
    }
    $params[':id'] = $id;
    $query = DB::query(Database::UPDATE, 
                'UPDATE "'. $this->table .'" SET '. (implode(',', $sets)) 
                  .' WHERE '. $this->primary_key .'=:id')
              ->parameters($params)
              ->execute();
    return $query ? TRUE : FALSE;
  } 

  public function delete($id)
  {
    $query = DB::query(Database::DELETE, 
                'DELETE FROM "'. $this->table .'" WHERE '. $this->primary_key .'=:id')
              ->param(':id', $id)
              ->execute();
    print_r($query);
    return $query?  TRUE : FALSE;
  }

}
