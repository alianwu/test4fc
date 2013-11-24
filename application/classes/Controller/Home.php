<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Home extends Controller {

  public function action_index()
  {
    $output_html[] = 'Hello World!';
    $output_html[] = 'chinese language: '. __('username');
    $data_post = array(
        'username' =>'username', 
        'password' => 'password',
        'captcha'  => '',
        );
    $post = Validation::factory( Arr::extract($data_post, array('username', 'captcha')));
    
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
      $errors = $post->errors('common');
      $output_html[] = var_export($errors, TRUE);
    }
    
    $this->response->body(implode("<br />\n", $output_html));
  }

} // End Home
