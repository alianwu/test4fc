<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Api_Article extends Controller_Api {

  public function before()
  {
    parent::before();

    $this->model_article = Model::factory('Article');
    $this->model_category = Model::factory('Article_Category');
    $this->model_faq = Model::factory('Article_Faq');
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
  
  public function action_list()
  {
    $tag = Arr::get($_GET, 'tag', NULL);
    $cat = Arr::get($_GET, 'category', NULL);
    $page = max((int) Arr::get($_GET, 'page', 1), 1);

    $where = array(
      'tag' => $tag,
      'cat' => $cat,
      'page' => $page
    );
    if ($article = $this->model_article->get_list_front($this->city_id, $where)) {
        $category = $this->model_category->get_list_pretty();
        $this->result(0, $article->as_array(), array('category'=>$category));
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


  function action_live()
  {
    $where = array();
    $lastid =  (int) Arr::get($_GET, 'lastid', 0);
    $aid =  (int) Arr::get($_GET, 'liveid', 0);
    $type = Arr::get($_GET, 'type', 'new');
    $where = array(
      'lastid' => $lastid,
      'aid' => $aid,
      'type' => $type
    );
    $data = Model::factory('Article_Live')->get_list_front($this->city_id, $where);
    if ($data) {
      $lastid = $data->get('lid');
      $this->result(0, $data->as_array(), array('lastid'=>$lastid));
    }
  }

  function action_live_save()
  {
    if ($this->manager == NULL) {
      return $this->error_user();
    }
    $message = Arr::get($_POST, 'message');
    $aid = Arr::get($_POST, 'aid');
    $vphoto = Arr::get($_POST, 'vphoto');
    if ($message || $vphoto) {
      if ($vphoto) {
        $ipath = Upload::base64_save_get_path($vphoto);
        if ($ipath) {
          $message .= '<br /> <img src="'.$ipath.'" />';
        }
      }
      $data = array(
        'message' => $message,
        'aid' => $aid,
        'uid' => $this->manager['id'],
        'uphoto' => $this->manager['photo'],
        'uname' => $this->manager['name'],
      );
      Model::factory('Article_Live')->save($data);
      $this->result(0);
    }
  }

} // End API
