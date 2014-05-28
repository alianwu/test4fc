<?php defined('SYSPATH') or die('No direct script access.');

class Controller_User_Denglu extends Controller_Template {
  
  public $api; 
  public $model_member; 

  public function before()
  {
    parent::before();
    $config = Kohana::$config->load('denglu');;
    $this->api = new Denglu($config->app_id,$config->app_key,'UTF-8');
    $this->model_member = Model::factory('Member');
  }
  
  public function action_login()
  {
    $token = Arr::get($_GET, 'token', '');
    if ($token) {
      try {
        $data = $this->api->getUserInfoByToken($token); 
        $ret = $this->model_member->denglu_sigin_or_sigup($data, $this->us_name);
        if ($ret) {
          $this->redirect('user_favorite');
        }
      }
      catch (DengluException $e) {
      
      }
    }
    $this->template->view = '登陆失败！';
  }

} // End Home
