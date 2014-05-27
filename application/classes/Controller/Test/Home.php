<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Test_Home extends Controller_Test_Template {

  public function before()
  {
    parent::before();
    //$this->response->headers('cache-control', 'max-age=5');
  }
  public function action_index()
  {
    $a = array('a','b', 'c');
    print_r($a);
    exit;
    $geo['lat'] = '39.853101';
    $geo['lng'] = '116.423321';
    $geo['city_id'] = 1;
    Session::instance()->set('geo', $geo);
    echo $test = json_encode(array('abc','中文'));
    print_r(json_decode($test));
    $this->template->content = View::factory('test/index');
  }


  public function action_pgdb()
  {
    $data = array(
      'city_id' => 1,
      'city_area' => 3
      );
    //Model::factory('Company')->insert($data); 
    Model::factory('Company')->delete(5); 
    //Model::factory('Company')->update(5, $data); 
    
  }

} // End Home
