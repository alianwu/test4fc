<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Api_Faq extends Controller_Api {

  public function before()
  {
    parent::before();

    $this->model_faq = Model::factory('House_Faq');
    $this->model_faq_detail = Model::factory('House_Faq_Detail');
  }

  public function action_list()
  {
    $page = (int) Arr::get($_GET, 'page', 1);
    $hid  = (int) Arr::get($_GET, 'hid', 1);
    if ($hid <> 0 && $page <> 0) {
      $data = $this->model_faq->get_faq_front($hid, $page);
      if($data) {
        $this->body['data'] = $data->as_array();
      }
    }
  }

  public function action_list_detail()
  {
    $page = (int) Arr::get($_GET, 'page', 1);
    $fid  = (int) Arr::get($_GET, 'fid', 1);
    if ($hid <> 0 && $page <> 0) {
      $data = $this->model_faq_detail->get_faq_front($fid, $page);
      if($data) {
        $this->body['data'] = $data->as_array();
      }
    }
  }

  public function action_save()
  {
    if ($this->user == NULL) {
      $this->body['data'] = 'require login!';
      return 0;
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
      $data = $post->as_array() + $this->user + array('city_id'=>$this->city_id);
      $ret = Model::factory('House_Faq')->save($data);
      if ($ret) {
        $this->body['error'] = 0;
      }
      else {
        $this->body['data'] = 'Database error!';
      }
    }
    else {
      $this->body['data'] = $post->errors('house/faq');
    }
  }

  public function action_detail_save()
  {
    if ($this->user == NULL) {
      $this->body['data'] = 'require login!';
      return 0;
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
      $data = $post->as_array() + $this->user;
      $ret = Model::factory('House_Faq_Detail')->save($data);
      if ($ret) {
        $this->body['error'] = 0;
      }
      else {
        $this->body['data'] = 'Database error!';
      }
    }
    else {
      $this->body['data'] = $post->errors('house/faq');
    }
  }

} // End API
