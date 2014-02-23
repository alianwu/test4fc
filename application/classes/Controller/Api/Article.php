<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Api_Article extends Controller_Api {

  public function before()
  {
    parent::before();

    $this->model_faq = Model::factory('Article_Core_Faq');
  }

  public function action_faq_save()
  {

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
      $this->result($ret);
    }
    else {
      $this->result(1, $post->errors('article'));
    }
  }

} // End API
