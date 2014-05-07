<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Accounts_Sigup extends Controller_Accounts_Template {
  
  public $success = 'sigup/success';

  public function action_index()
  {
    $redirect = Arr::get($_GET, 'redirect', $this->redirect_url);
    $sigup = View::factory($this->view . '/accounts/sigup')
        ->set('redirect', $redirect);

    $this->template->content = $sigup;
  }

  public function action_check()
  {
    $post = Validation::factory( Arr::extract($_POST, 
                                  array('email', 'password', 'repassword', 'agree', 'captcha')) );
    $post->rules('email', array(
            array('trim'),
            array('not_empty'),
            array('email'),
            array('max_length', array(':value', 30)),
            array('min_length', array(':value', 5))
        ))
        ->rules('password', array(
          array('trim'),
          array('not_empty'),
          array('max_length', array(':value', 32)),
          array('min_length', array(':value', 6))
        ))
        ->rules('repassword', array(
          array('matches', array(':validation', 'repassword', 'password'))
        ))
        ->rules('agree', array(
          array('not_empty'),
          array('equals', array(':value', 'on'))
        ))
        ->rules('captcha', array(
          array('trim'),
          array('not_empty'),
        ))
        ->labels(array(
          'email' => __('email'),
          'password' => __('password'),
          'agree' => __('agree'),
          'captcha' => __('captcha'),
        ));

    if( $post->check() ) {
      Session::instance()->delete('captcha_response');
      $data = $post->as_array();
      $ret = $this->model->save_passport($data);
      if ($ret['error'] == FALSE) {
        $this->redirect($this->success);
      }
      $this->template->set_global('message', $ret['status']);
    }
    else {
      $error = $post->errors('accounts/passport');
      $this->template->bind_global('error', $error);
      $this->template->set_global('message', '表单出现错误，请检查');
    }
    
    $this->action_index();
  }  

  public function action_success()
  {
    $this->template->content = View::factory($this->view . '/accounts/sigup-success');
  }
} // End Sigup
