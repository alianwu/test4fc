<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Api_Favorite extends Controller_Api {

  public function before()
  {
    parent::before();
    // $this->model = Model::factory('Favorite');
  }

  public function action_list()
  {
    $page = max((int) Arr::get($_POST, 'page', 1), 1);
    $type = Arr::get($_POST, 'type', '');
    $value = Arr::get($_POST, 'value', '');
    $ids = explode('|', $value);
    if (empty($ids)) {
      return false;
    }

    $ids = array_map(function($v){ return (int)$v; }, $ids);
    switch($type) {
      case 'house_new':
        $data = Model::factory('House_New')->get_list_favorite($ids, $page) ;
        break;
      case 'faq':
        $data = Model::factory('House_Faq')->get_list_favorite($ids, $page) ;
        break;
      case 'article':
        $data = Model::factory('Article')->get_list_favorite($ids, $page) ;
        break;
      default:
        return false;
    }
    if ($data) {
      $this->result(0, $data->as_array());
    }
  }

  public function action_delete()
  {
    $id = (int) $this->request->param('id');
    if($id) {
      $ret = $this->model->delete_one($fid);
      $this->result($ret);
    }
  }

} // End API
