<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Manager_Api extends Controller_Api {


  public function before()
  {
    parent::before();
    $this->user = $this->get_user('manager.user');
    if ($this->user === NULL) {
      $this->redirect('api/error');
    }
    //  $this->response->headers('cache-control', 'max-age=5');
    $this->model_house = Model::factory('House_New');
  }

  public function action_attachment_delete()
  {
    $atype = (int )Arr::get($_GET, 'atype', 0);
    $hid = (int )Arr::get($_GET, 'hid', 0);
    $attachment = Arr::get($_GET, 'file', 0);
    $ret = $this->model_house->attachment_delete($hid, $atype, $attachment);
    $this->result($ret);
  }

  public function action_attachment_upload()
  {
    $atype = (int) Arr::get($_POST, 'atype', 0);
    $hid = (int) Arr::get($_POST, 'hid', 0);
    if ($hid && $atype && array_key_exists($atype, $this->setting->attachment)
        && isset($_FILES['Filedata']) ) {
      $dir = $this->core['upload_dir']. DIRECTORY_SEPARATOR .date("Y/m") . DIRECTORY_SEPARATOR;
      $real_dir = DOCROOT . $dir;
      $name = uniqid().'.'.strtolower(pathinfo($_FILES['Filedata']['name'], PATHINFO_EXTENSION));
      if (is_dir($real_dir) == FALSE) {
        file_put_contents('/tmp/name.log', $real_dir);
        $test = mkdir($real_dir, 0755, TRUE);
        if($test == FALSE) {
          $this->result(1, '文件夹权限错误');
          return 0;
        }
      }

      try {
        $ret = Upload::save($_FILES['Filedata'], $name, $real_dir);
        if ($ret) {
          $attachment  = $dir.$name;
          $body['data'] = $attachment;
          $ret = $this->model_house->attachment_save($hid, $atype, $attachment);
          return $this->result($ret, $attachment);
        }
        else {
          $this->result(1, '异常问题');
        }
      }
      catch (Kohana_Exception  $e) {
        $this->result(1, Kohana_Exception::text($e)); 
      }
    }
    else {
      $this->result(1, '非法类型'); 
    }
    $this->result(1, '非法请求'); 
  }


} // End API
