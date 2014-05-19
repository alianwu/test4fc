<?php defined('SYSPATH') OR die('No direct script access.');

class Model_Company extends Model {

  protected $table = 'company';
  protected $primary_key = 'cid';

  function __construct()   
  {
    $this->pagination = Kohana::$config->load('pagination');
  }

  public function save_one($data) 
  {
    $cid = $data['cid'];
    $data['display'] = (bool) $data['display']; 
    $data['updated'] = '__&__now()';
    $data['gps'] = $data['lng'] . ',' . $data['lat']; 
    $data['geo'] = '__&__ST_GeomFromText(\'POINT('.$data['lng'].' '.$data['lat'].')\', 4326)';

    unset($data['csrf'], $data['lng'], $data['lat']);
    foreach($data['image']['name'] as $k => $v) {
      if ($v) {

      } 
    }

    if ($cid == 0) {
      unset($data['cid']);
    }
    else {
    }
  }

}


