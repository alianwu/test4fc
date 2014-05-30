<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Api_Faq extends Controller_Api {

  protected $type;
  protected $id;
  protected $sort;

  public function before()
  {
    parent::before();
    $this->id = (int) Arr::get($_GET, 'id', 0);
    $type = Arr::get($_GET, 'type', FALSE);
    $this->type = array_search($type, $this->setting['module']);
    $this->sort = Arr::get($_GET, 'sort', FALSE);

    $this->model_faq = Model::factory('Faq');
    $this->model_detail = Model::factory('Faq_Detail');
  }

  public function action_list()
  {
    $page = max((int) Arr::get($_GET, 'page', 1), 1);
    $where = array(
      'id' => $this->id,
      'type' => $this->type,
      'sort' => $this->sort,
      'page' => $page,
    );
    if ($this->id <> 0 
      && $this->type
      && $data = $this->model_faq->get_list_front($this->city_id, $where)) {
        $this->result(0, $data->as_array());
    }
  }

  public function action_detail()
  {
    $page = (int) Arr::get($_GET, 'page', 1);
    $fid = (int) Arr::get($_GET, 'fid', 0);
    if ($fid <> 0 
      && $data = $this->model_detail->get_list_front($fid, $page)) {
        $this->result(0, $data->as_array());
    }
  }

  public function action_save()
  {
    if ($this->user == NULL) {
      return $this->error_user();
    }
    $post = Validation::factory( Arr::extract($_POST, array(
                'body', 
                'type', 
                'id')));
    $post->rules('body', array(
          array('trim'),
          array('not_empty'),
          array('min_length', array(':value', 5)),
          array('max_length', array(':value', 100))))
        ->rules('id', array(
          array('not_empty'),
          array('digit')))
        ->rules('type', array(
          array('not_empty')))
          ;
    if ($post->check()) {
      $data = $post->data();
      $data['type'] = array_search($data['type'], $this->setting['module']);
      $data += $this->user + array('city_id'=>$this->city_id);
      $ret = $this->model_faq->save_one($data);
      $this->result(0);
    }
    else {
      $this->result(1);
    }
  }

  public function action_detail_save()
  {
    if ($this->user == NULL) {
      return $this->error_user();
    }
    $post = Validation::factory( Arr::extract($_POST, array(
                'body', 
                'fid')));
    $post->rules('body', array(
          array('not_empty'),
          array('min_length', array(':value', 5)),
          array('max_length', array(':value', 100))))
        ->rules('fid', array(
          array('not_empty'),
          array('digit')))
          ;
    if ($post->check()) {
      $data = $post->data();
      $data += $this->user + array('city_id'=>$this->city_id);
      $ret = $this->model_detail->save_one($data);
      $this->result(0);
    }
    else {
      $this->result(9);
    }
  }

} // End API
