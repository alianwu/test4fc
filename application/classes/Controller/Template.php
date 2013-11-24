<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Template extends CTemplate {
  
  public $template = 'template';
  
  public function action_index()
  {
    $this->template->content = 'Hello World!';
  }

} // End Template
