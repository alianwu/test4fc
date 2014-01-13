<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Test_Form extends Controller {

  public function action_post()
  {
    $test_data_post = array(
        'username' =>'username', 
        'password' => 'password',
        'captcha'  => '',
      );
    $post = Validation::factory( Arr::extract($test_data_post, array('username', 'captcha')));
    $post->rules('username', array(
            array('trim'),
            array('not_empty'),
            array('max_length', array(':value', 2)),
            array('min_length', array(':value', 1))
          )
        )
        ->rules('captcha', array(
            array('trim'),
            array('not_empty'),
          )
        )
        ->labels(array(
            'username' => __('username'),
            'password' => __('password'),
            'captcha'  => __('captcha')
          )
        );
          
    if( $post->check() ) {
      $data_valid_post = $post->as_array();
    }
    else {
      $errors = $post->errors('user');
      print_r($errors);
    }
  }

}