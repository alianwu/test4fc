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

  function __construct($table = NULL)
  {
    if ($table !== NULL) {
      $this->table = $table;
    }
    $this->pagination = Kohana::$config->load('pagination');
	}
  
  public function table($table) 
  {
    $this->table = $table;
    return $this;
  }

  public function get($id, $is_object = TRUE, $display = NULL) 
  {
    $sql = '';
    $params = array();
    if ($display !== NULL) {
      $sql = ' AND display=:display';
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
                  'SELECT * FROM :table 
                      WHERE :primary_key = :id '. $sql .' LIMIT 1')
                ->param_extra(':table', $this->table)
                ->param_extra(':primary_key', $this->primary_key)
                ->parameters($params);
    if ($is_object === TRUE) {
      $query = $query->as_object();
    }
    $query = $query->execute();
    return $query->count() == 0 ?  NULL : $query->current();
  }

  public function get_more($where =NULL, $start = 0, $limit = 20, $is_object = TRUE)
  {
    $sql = array('true');
    $params = array();
    if ($where !== NULL) {
      foreach($where as $k=>$v) {
        $pkey = ':'.$k;
        $params[$pkey] = $v;
        $sql[] = $k.'='.$pkey;
      }
    }
    $query = DB::query(Database::SELECT, 
                'SELECT * FROM :table 
                    WHERE '. (implode(' AND ', $sql)) .' LIMIT '.$limit.' OFFSET '.$start)
              ->param_extra(':table', $this->table)
              ->param(':start', $start)
              ->param(':limit', $limit)
              ->parameters($params);
    if ($is_object === TRUE) {
      $query = $query->as_object();
    }
    return $query->execute();
  } 

  public function save($data, $return = NULL)
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
                'INSERT INTO :table  ('. (implode(',', $keys)) .') 
                    VALUES (:'. (implode(', :', $keys)) .') '.$return_sql)
              ->param_extra(':table', $this->table)
              ->parameters($params)
              ->execute();
    if ($return !== NULL) {
      return $query->get('c_id');
    }
    return  $query->count() ? TRUE : FALSE;
  } 

  public function update($id, array $data =NULL, array $data_extra = NULL)
  {
    $upset = $parms = $params_extra = array();
    if($data !== NULL) {
      foreach($data as $k=>$v) {
        $pkey = ':'.$k;
        $upset[] = $k.'='.$pkey;
        $params[$pkey] = $v;
      }
    }
    if($data_extra !== NULL) {
      foreach($data_extra as $k=>$v) {
        $pkey = ':'.$k;
        $upset[] = $k.'='.$pkey;
        $params_extra[$pkey] = $v;
      }
    }
    $params[':id'] = $id;
    $query = DB::query(Database::UPDATE, 
                'UPDATE :table SET '.(implode(',', $upset)).' WHERE :pkey=:id')
              ->param_extra(':table', $this->table)
              ->param_extra(':pkey', $this->primary_key)
              ->parameters($params)
              ->parameters_extra($params_extra)
              ->execute();
    return $query;
  } 
  
  public function delete($id)
  {
    $query = DB::query(Database::DELETE, 'DELETE FROM ":table"  
                  WHERE :primary_key = :id')
              ->param_extra(':table', $this->table)
              ->param_extra(':primary_key', $this->primary_key);

    if (is_array($id)) {
      foreach($id as $v) {
        $query->param(':id', $v)->execute();
      }
      return 1;
    }
    else {
      return $query->param(':id', $id)->execute();
    }
  }

  public function update_hot($id, $field='hot')
  {
    switch($field) {
      case 'display':
        $sql = 'UPDATE :table SET display= NOT display  WHERE :primary_key :op :id';
        break;
      case 'hit':
        $sql = 'UPDATE :table SET hit = hit+1  WHERE :primary_key :op :id';
        break;
      case 'hot':
      default:
        $sql = 'UPDATE :table SET hot= NOT hot  WHERE :primary_key :op :id';
    }
    $query = DB::query(Database::UPDATE, $sql)
              ->param_extra(':table', $this->table)
              ->param_extra(':primary_key', $this->primary_key);
    if (is_array($id)) {
      $id = Arr::map(function($v){ return (int) $v; }, $id);
      $query
        ->param_extra(':op', ' in ')
        ->param_extra(':id', '('.(implode(', ', $id)).')');
    }
    else {
      $query
        ->param_extra(':op', '=')
        ->param(':id', $id);
    }
    return $query->execute();
  }


}
