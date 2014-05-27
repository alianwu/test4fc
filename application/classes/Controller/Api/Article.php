<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Api_Article extends Controller_Api {

  public function before()
  {
    parent::before();

    $this->model_article = Model::factory('Article');
    $this->model_faq = Model::factory('Article_Faq');
    $this->model_category = Model::factory('Article_Category');
  }

  private  function support($num)
  {
    $aid = (int) Arr::get($_POST, 'aid', 0);
    if ($aid) {
      $ret = $this->model_article->support_one($aid, $num); 
      if ($ret) {
        $this->result(0);
      }
    }
  }

  public function action_up()
  {
    $this->support(1);
  }

  public function action_down()
  {
    $this->support(-1);
  }
  
  public function action_search()
  {
    $data = Arr::extract($_GET, array('keyword', 'category', 'day',  'page'));
    $data = $this->model_article->get_search_front($this->city_id, $data);
    if ($data) {
      $data = $data->as_array();
      $category = $this->model_category->get_list_pretty();
      $this->result(0, $data, array('category'=>$category));
    }
  }
  
  public function action_list()
  {
    $id = (int) Arr::get($_GET, 'id', 0);
    $page = max((int) Arr::get($_GET, 'page', 1), 1);
    if ($id 
      && $article = $this->model_article->get_list_front($this->city_id, $id, $page)) {
        $category = $this->model_category->get_list_pretty();
        $this->result(0, $article, array('category'=>$category));
    }
  }

  public function action_faq_save()
  {
    if ($this->user == NULL) {
      return $this->error_user();
    }
    $post = Validation::factory( Arr::extract($_POST, 
                                              array('body', 'aid')) );
    $post->rules('body', array(
            array('not_empty'),
            array('min_length', array(':value', 5)),
            array('max_length', array(':value', 200))
        ))
        ->rules('aid', array(
          array('not_empty'),
          array('digit'),
        ));
    if ($post->check()) {
      $data = $post->data();
      $ret = $this->model_faq->save_one($data);
      $this->result((bool)$ret);
    }
    else {
      $error = $post->errors('article');
      print_r($error);
      $this->result(1, $error);
    }
  }

} // End API
