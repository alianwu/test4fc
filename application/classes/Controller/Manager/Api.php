<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Manager_Api extends Controller_Manager_Template {

  public $auto_render = FALSE; 

  public function before()
  {
    parent::before();
    //  $this->response->headers('cache-control', 'max-age=5');
    $this->model_house = Model::factory('House_New');
  }

  public function action_attachment_delete()
  {
    $body = array('error'=>1,'data'=>'');
    $atype = (int )Arr::get($_GET, 'atype', 0);
    $hid = (int )Arr::get($_GET, 'hid', 0);
    $attachment = Arr::get($_GET, 'file', 0);
    $ret = $this->model_house->attachment_delete($hid, $atype, $attachment);
    if ($ret) {
      $body['error'] = 0;
    }
    $this->response->body(json_encode($body))->headers('Content-Type', 'application/json');
  }

  public function action_attachment_upload()
  {
    $body = array('error'=>1,'data'=>'');
    $atype = (int )Arr::get($_POST, 'atype', 0);
    $hid = (int )Arr::get($_POST, 'hid', 0);
    if ($hid && $atype && array_key_exists($atype, $this->setting->attachment)
        && isset($_FILES['Filedata']) ) {
      $dir = $this->core['upload_dir']. DIRECTORY_SEPARATOR .date("Y/m") . DIRECTORY_SEPARATOR;
      $real_dir = DOCROOT . $dir;
      $name = uniqid().'.'.strtolower(pathinfo($_FILES['Filedata']['name'], PATHINFO_EXTENSION));
      if (is_dir($real_dir) == FALSE) {
        file_put_contents('/tmp/name.log', $real_dir);
        $test = mkdir($real_dir, 0755, TRUE);
        if($test == FALSE) {
          $body['data'] = '似乎没有创建文件夹的权限';
          $this->response->body(json_encode($body))->headers('Content-Type', 'application/json');
          return 0;
        }
      }

      try {
        $ret = Upload::save($_FILES['Filedata'], $name, $real_dir);
        if ($ret) {
          $attachment  = $dir.$name;
          $body['data'] = $attachment;
          $ret = $this->model_house->attachment_save($hid, $atype, $attachment);
          if ($ret) {
            $body['error'] = 0;
          }
        }
        else {
          $body['data'] = '保存失败';
        }
      }
      catch (Kohana_Exception  $e) {
        $body['data'] = Kohana_Exception::text($e); 
      }
    }
    else {
      $body['data'] = '非法请求'.$atype; 
    }
    $this->response->body(json_encode($body))->headers('Content-Type', 'application/json');
  }


} // End API
