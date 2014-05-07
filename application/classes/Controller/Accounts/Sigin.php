<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Accounts_Sigin extends Controller_Accounts_Template {
  
  public $redirect_url = 'manager_home';
  public $success = 'sigin/success';

  public function action_index()
  {
    $display_captcha = FALSE;

    if ($this->accounts_ip_num > $this->accounts_ip_num_maxfail) {
      $display_captcha = TRUE;
    }

    $redirect = Arr::get($_GET, 'redirect', $this->redirect_url);
    $sigin = View::factory($this->view . '/accounts/sigin')
        ->set('display_captcha', $display_captcha)
        ->set('redirect', $redirect);

    $this->template->content = $sigin;
  }

  public function action_check()
  {
    $post = Validation::factory( Arr::extract($_POST, 
                                              array('passport', 'password', 'expires', 'redirect', 'captcha')) );
    $post->rules('passport', array(
            array('trim'),
            array('not_empty'),
            array('max_length', array(':value', 30))
        ))
        ->rules('password', array(
          array('trim'),
          array('not_empty')
        ))
        ->rules('expires', array(
          array('trim'),
          array('is_numeric')
        ))
        ->rules('redirect', array(
          array('regex', array(':value', '/^'. $this->redirect_url .'/')),
        ));

    // check captcha
    if ($this->accounts_ip_num > $this->accounts_ip_num_maxfail) {
      $post->rules('captcha', array(
          array('not_empty'),
          array('Captcha::valid', array(':value')),
        ));
    }

    if( $post->check() ) {
      Session::instance()->delete('captcha_response');
      $data = $post->as_array();
      $ret = $this->model->check_passport($data);
      if ($ret['error'] == FALSE) {
        $this->redirect( urldecode($data['redirect']) );
      }
      $this->template->set_global('message', $ret['info']);
    }
    else {
      Cache::instance()->set($this->accounts_ip, $this->accounts_ip_num + 1);
      $error = $post->errors('sigin');
      if (isset($error['captcha'])) {
        $this->template->set_global('message', '用户名、密码或验证码不正确，请重新输入');
      }
      else {
        $this->template->set_global('message', '用户名、密码不正确，请重新输入');
      }
    }
  
    $this->action_index();
  }  
} // End Sigin
