<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Accounts_Resetpassport extends Controller_Accounts_Template {

  public function action_index()
  {
    $this->template->content = View::factory($this->view . '/accounts/resetpassport');
  }
  
  public function action_check()
  {
    $post = Validation::factory( Arr::extract($_POST, array('email', 'captcha')) );
    $post->rules('email', array(
            array('trim'),
            array('not_empty'),
            array('email'),
            array('max_length', array(':value', 30)),
            array('min_length', array(':value', 5))
          )
        )
        ->rules('captcha', array(
          array('trim'),
        //  array('not_empty'),
        ));

    $cache_key = 'accounts-reset-num-' . Request::$client_ip;
    $arni = (int) Cache::instance()->get($cache_key, 0);

    if( $arni < 2 &&  $post->check() ) {
      $data = $post->as_array();
      $ret = $this->model->resetpw_member($data);
      if ($ret['error'] == FALSE) {
        Cache::instance()->set($cache_key, $arni++, 3600);
      }
      $message =  $ret['status'];
    }
    else {
      $error = $post->errors('accounts/member');
      $this->template->bind_global('error', $error);
      $message =  '';
    }
    
    $this->template->set_global('message', $message);
    $this->action_index();
  }
} // End Resetpasswd
