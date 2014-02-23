<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Api_Faq extends Controller_Api {

  public function before()
  {
    parent::before();

    $this->model = Model::factory('House_Faq');
    $this->model_detail = Model::factory('House_Faq_Detail');
  }

  public function action_list()
  {
    $page = (int) Arr::get($_GET, 'page', 1);
    $hid  = (int) Arr::get($_GET, 'hid', 1);
    if ($hid <> 0 && $page <> 0) {
      $data = $this->model->get_list_front($hid, $page);
      if($data) {
        $this->result(0, $data->as_array());
      }
    }
  }

  public function action_list_detail()
  {
    $page = (int) Arr::get($_GET, 'page', 1);
    $fid  = (int) Arr::get($_GET, 'fid', 1);
    if ($hid <> 0 && $page <> 0) {
      $data = $this->model_detail->get_list_front($fid, $page);
      if($data) {
        $this->result(0, $data->as_array());
      }
    }
  }

  public function action_save()
  {
    if ($this->user == NULL) {
      $this->redirect('api/error');
    }

    $post = Validation::factory( Arr::extract($_POST, 
                                              array('body', 'hid')) );
    $post->rules('question', array(
            array('trim'),
            array('not_empty'),
            array('min_length', array(':value', 5)),
            array('max_length', array(':value', 100))
        ))
        ->rules('hid', array(
          array('not_empty'),
          array('digit'),
        ));
    if ($post->check()) {
      $data = $post->data() + $this->user + array('city_id'=>$this->city_id);
      $ret = $this->model->save_one($data);
      $this->result($ret);
    }
    else {
      $this->result(NULL, $post->errors('house/faq'));
    }
  }

  public function action_detail_save()
  {
    if ($this->user == NULL) {
      return $this->error_user();
    }

    $post = Validation::factory( Arr::extract($_POST, 
                                              array('body', 'fid')) );
    $post->rules('body', array(
            array('trim'),
            array('not_empty'),
            array('min_length', array(':value', 5)),
            array('max_length', array(':value', 100))
        ))
        ->rules('fid', array(
          array('not_empty'),
          array('digit'),
        ));
    if ($post->check()) {
      $data = $post->data() + $this->user;
      $ret = $this->model_detail->save_one($data);
      $this->result($ret);
    }
    else {
      $this->result(NULL, $post->errors('house/faq'));
    }
  }

} // End API
