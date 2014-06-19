<?php defined('SYSPATH') or die('No direct script access.');

class Controller_User_Qq extends Controller_Template {
  
  public $api; 
  public $model_member; 
  
  public $auto_render = FALSE;

  public function before()
  {
    parent::before();

    Kohana::load(Kohana::find_file('classes', 'QQsdk/Api', 'php'));
    
    $this->model_member = Model::factory('Member');
  }

  public function action_index()
  {
    $view = View::factory('user/qq_index');
    $this->response->body($view);
  } 
  
  public function action_login()
  {
    $qc = new QC();
    $openid = $qc->get_openid();
    $user = $qc->get_user_info();
    if ($openid) {
      $user = Security::xss_clean($user);
      $this->model_member->qq_login($openid, $user, $this->us_name);
      $this->redirect('user_favorite');
    }
    else {
      $view = View::factory('user/qq_fail');
      $this->view($view);
    }
  }

  public function action_auth()
  {   
    $qc = new QC();
    $qc->qq_login();
  }

} // End Home
