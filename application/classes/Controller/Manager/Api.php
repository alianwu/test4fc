<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Manager_Api extends Controller_Api {

  public $model_house;

  public function before()
  {
    parent::before();
    $this->user = $this->session->get($this->us_name_m);
    if ($this->user === NULL) {
      $this->redirect('api/error');
    }
  }

  public function action_delete()
  {
    $this->result(FALSE, NULL);
  }

  public function action_upload()
  {
    $id = $this->request->param('id');
    $post = Validation::factory( Arr::extract($_POST + $_FILES,  array('qquuid', 'qqfile', 'imgFile')));
    if ($id == 1) {
      $post->rules('qquuid', array(
            array('not_empty')
        ))
      ->rules('qqfile', array(
            array('not_empty'),
            array('Upload::check')
          ));
    }
    elseif ($id == 2) {
      $post->rules('imgFile', array(
            array('not_empty')
        ));
    }
    else {
      return $this->result(1);
    }
    if($post->check()) {
      $data = $post->data();
      switch ($id) {
        case 1:
          $path = Upload::save_get_path($data['qqfile']);
          $result = array(
            'success' => TRUE, 
            'uuid' => $data['qquuid'], 
            'uploadName' => $path[0],
            'fullpath' => $path[1]);
        break;
        case 2:
          $path = Upload::save_get_path($data['imgFile']);
          $result = array(
            'error' => 0, 
            'url' => $path[0]);
      }
      $this->result(TRUE, NULL, $result);
    }
    else {
      $error = $post->errors('manager/upload');
      $this->result(FALSE, NULL, $error);
    }
  }

  function action_rel()
  {
    $rel = Arr::get($_GET, 'rel');
    $rel_id = Arr::get($_GET, 'rel_id');
    $where['page'] = 1;
    $where['keyword'] = $rel_id;
    $data = array();
    switch($rel) {
      case 1:
        $ret = Model::factory('House')->get_list_front($this->city_id, $where);
        if ($ret) {
          foreach($ret->as_array() as $v) {
            $data[] = array($v->hid, $v->name);
          }
        }
        break;
      case 2:
        break;
      case 4:
        break;
      case 8:
        break;
    }
    if ($data) {
      $this->result(0, $data);
    }
  }
  
} // End API
