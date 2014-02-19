<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Api_Favorite extends Controller_Api {

  public function before()
  {
    parent::before();
    
    if ($this->user == NULL)
    {
      $this->redirect('api/error');
    }

    $this->model = Model::factory('Favorite');
  }

  public function action_list()
  {
    $page = (int) Arr::get($_GET, 'page', 1);
    $type = (int) Arr::get($_GET, 'type', 0);
    if ($page && $type) {
      $data = $this->model->get_list($this->user['id'], $type, $page);
      if ($data) {
        $this->body['data'] = $data->as_array();
      }
    }
    $this->body = $this->model_city->get_city_pretty($cid, $type);
  }

  public function action_delete()
  {
    $id = (int) $this->request->param('id');
    if($id) {
      $ret = $this->model->delete($fid);
      if($ret) {
        $this->body['error'] = 0;
      }
    }
  }

} // End API
