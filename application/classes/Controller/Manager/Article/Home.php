<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Manager_Article_Home extends Controller_Manager_Template {

  public function before()
  {
    parent::before();
    $this->model = Model::factory('Article_Core');
    $this->model_category = Model::factory('Article_Core_Category');
    if ($this->auto_render == TRUE) { 
      $this->template->container = View::factory('manager/article/article');
    }
  }

  public function action_index()
  {
    $page = Arr::get($_GET, 'page', 1);
    $article = $this->model->get_list($page);

    $view = View::factory('manager/article/article_index');
    $view->bind('article', $article);

    $this->template->container->detail = $view;
  }
  

  public function action_add()
  { 
    $category = $this->model_category->get_list_pretty();
    $this->template->container->detail = View::factory('manager/article/article_add')
                                              ->set('category', $category);
  }
  
  public function action_editor()
  {
    $data = $this->model->get_article_one($this->hid);
    if($data === FALSE) {
      throw new Kohana_HTTP_Exception_404();
    }

    $_POST = $data;
    $this->action_add();
  }

  public function action_update()
  {
    $fields = array(
      'hid' => array(
            array('not_empty'),
            array('digit'),
          ),
      'city_id' => array(
            array('digit'),
            array('not_empty'),
          ),
      'name' => array(
            array('not_empty'),
          ),
      'status' => array(
            array('max_length', array(':value', 30)),
          ),
      'hot' => array(
            array('digit'),
          ),
      'price' => array(
            array('digit'),
          ),
      'price_history' => array(
            array('max_length', array(':value', 100)),
          ),
      'article_date' => array(
            array('date'),
          ),
      'article_date_sale' => array(
            array('date'),
          ),
      'article_corp' => array(
            array('max_length', array(':value', 30)),
          ),
      'article_speculator' => array(
            array('max_length', array(':value', 30)),
          ),
      'article_service' => array(
            array('max_length', array(':value', 30)),
          ),
      'article_service_price' => array(
            array('numeric'),
          ),
      'acreage' => array(
            array('digit'),
          ),
      'acreage_building' => array(
            array('digit'),
          ),
      'copy' => array(
            array('digit'),
          ),
      'car' => array(
            array('digit'),
          ),
      'rate_1' => array(
            array('numeric'),
          ),
      'rate_2' => array(
            array('numeric'),
          ),
      'phone' => array(
            array('max_length', array(':value', 300)),
          ),
      'school' => array(
            array('max_length', array(':value', 30)),
          ),
      'school_near' => array(
            array('max_length', array(':value', 30)),
          ),
      'city_area' => array(
            array('digit'),
          ),
      'city_area_shop' => array(
            array('digit'),
          ),
      'underground' => array(
            array('digit'),
          ),
      'underground_platform' => array(
            array('digit'),
          ),
      'building' => array(
            array('digit'),
          ),
      'decorate' => array(
            array('digit'),
          ),
      'address' => array(
            array('not_empty'),
            array('max_length', array(':value', 30)),
          ),
      'lat' => array(
            array('numeric'),
          ),
      'lng' => array(
            array('numeric'),
          ),
      'description' => array(
            array('max_length', array(':value', 500)),
          ),
      'weight' => array(
            array('digit'),
            array('not_empty'),
          ),
      'display' => array(
            array('digit'),
          ),
      'csrf' => array(
            array('not_empty'),
            array('Security::check'),
          ),
    );
    $post = Validation::factory( Arr::extract($_POST,  array_keys($fields)) );
    foreach ($fields as $k => $v) {
      $post->rules($k, $v);
    }
    if( $post->check() ) {
      $data = $post->as_array();
      $ret = $this->model->save($data);
      $this->template->set_global('message', $ret);
      if ($ret['error'] == FALSE) {
        $this->action_update_success($ret['data']);
        return 0;
      }
    }
    else {
      $error = $post->errors('city/add');
      $this->template->bind_global('error', $error);
    }
    
    $this->action_add();
  }
  

  public function action_update_success($hid = NULL)
  {
    $this->template->container->detail = View::factory('manager/article/article_add_success')
                                              ->set('hid', $hid);
  }

  public function action_display()
  {
    $data = $this->model->update_display($this->hid);
    $this->template->bind_global('message', $data);
    $this->action_index();
  }

  public function action_delete()
  {
    $data = $this->model->delete($this->hid);
    $this->template->bind_global('message', $data);
    $this->action_index();
  }

} 
