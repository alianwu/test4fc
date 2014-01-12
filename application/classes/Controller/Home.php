<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Home extends CTemplate {

  public function action_index()
  {
    $this->response->headers('cache-control', 'max-age=3600');
    $this->template->content = View::factory('index');
  }
  
  public function action_code()
  {
    $output_html = array();
    
    $f=fopen(__FILE__, 'r');
    while(!feof($f))
        $output_html[] = htmlspecialchars(fread($f, 10240));
    fclose($f); 
    
    $this->template->content = implode("\n", $output_html);
  }

} // End Home
