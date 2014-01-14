<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Sigin extends Controller_Common {

  protected $accounts_ip_fail_max_num = 2;

  public function before()
  {
    parent::before();

  }

  public function action_index()
  {
    $display_captcha = FALSE;

    if ($this->accounts_ip_num > $this->accounts_ip_fail_max_num) {
      $display_captcha = TRUE;
    }

    $sigin = View::factory('accounts/sigin')
        ->set('display_captcha', $display_captcha);

    $this->template->content = $sigin;
  }

  public function action_check()
  {
    $post = Validation::factory( Arr::extract($_POST, array('passport', 'password', 'expires')) );
    $post->rules('passport', array(
            array('trim'),
            array('not_empty'),
            array('max_length', array(':value', 15)),
            array('min_length', array(':value', 5))
          )
        )
        ->rules('password', array(
          array('trim'),
          array('not_empty')
        ))
        ->rules('captcha', array(
            array('trim'),
        ));
          
    if( $post->check() ) {
      $data_valid_post = $post->as_array();
    }
    else {
      Session::instance()->set($this->accounts_ip, $this->accounts_ip_num + 1);
      $this->template->set_global('account_check_message', '用户名或者密码不正确，请重新输入');
    }
    $this->action_index();
  }  
} // End Sigin
