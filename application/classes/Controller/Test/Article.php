<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Test_Article extends Controller_Test_Template {
  
  public $auto_render = FALSE;

  public function before()
  {
    $this->model = Model::factory('Article_Core');
    $this->model_tag = Model::factory('Article_Core_Tag');
  }

  public function action_index()
  {
    echo '<pre />';
    // $query = $this->model->get_one(1);
    // $query = $this->model->delete_one(2);
    // echo $this->model_tag->save_one('测试');
    // echo $this->model_tag->check_one('测试');
    // print_r($this->model_tag->delete_list(array(1,2,3)));
    // print_r($this->model_tag->get_list(array(1,2,3)));
    
    // echo View::factory('test/geo')->render();
    //~ Redis_Client::instance()->getDB(8)->flushDB();
  }
  
} // End Cache
