<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Tool extends Controller {

  public function action_qrcode()
  {
    $url = Arr::get($_GET, 'url', '');
    // Force content type
    $this->response->headers('Content-Type','image/png');

    // Show QRcode         
    $this->response->body(QRCode::instance()->png($url));  
  }
} // End Home
