<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Test_Language extends Controller_Test_Template {

  public function action_index()
  {
    $output  = '<pre>';
    $output .= var_export($_SERVER, TRUE);

    $output .= '</pre>';
    $output .= 'Language: '. I18n::lang(). ' etc. username：'.__('username');

    if ($this->auto_render) {
      $this->template->content = View::factory('test/index');
      $this->template->detail = $output;
    }
    else {
      $this->response->body($output);
    }
  }

}
