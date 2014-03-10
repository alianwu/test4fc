<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Manager_Api extends Controller_Api {

  public $tmpid;

  public function before()
  {
    parent::before();
    $this->user = $this->get_user('manager.user');
    if ($this->user === NULL) {
      $this->redirect('api/error');
    }
    $this->tmpid = Cookie::get('manager_house_add_tmpid', FALSE);
    //  $this->response->headers('cache-control', 'max-age=5');
    $this->model_house = Model::factory('House_New');
  }

  public function action_house()
  {
    $action = Arr::get($_POST, 'action');
    $hid    = Arr::get($_POST, 'hid');
    if ($hid) {
      $hid = array_map(function($v){ return  (int)$v; } , $hid);
      switch($action) {
        case 'delete':
          $this->model_house->delete_many($hid);
          break;
        case 'hot':
          $this->model_house->hot_many($hid, 1);
          break;
        case 'unhot':
          $this->model_house->hot_many($hid, 0);
          break;
      }
    }
  }

  public function action_attachment_delete()
  {
    $atype = (int )Arr::get($_GET, 'atype', 0);
    $hid = (int )Arr::get($_GET, 'hid', 0);
    $attachment = Arr::get($_GET, 'file', 0);
    if ($this->tmpid) {
      $tmp_attachment = (array) Session::get('manager.house.add.attachment');
      if ( isset($tmp_attachment[$atype]) &&  ($key = array_search($attachment, $tmp_attachment[$atype])) !== false) {
          unset($tmp_attachment[$atype][$key]);
      }
      Session::instance()->set('manager.house.add.attachment', $tmp_attachment);
    }
    else {
      $ret = $this->model_house->attachment_delete($hid, $atype, $attachment);
    }
    $this->result($ret);
  }

  public function action_upload()
  {
    if ( isset($_FILES['imgFile']) ) {
      $dir = $this->core['upload_dir']. DIRECTORY_SEPARATOR .date("Y/m") . DIRECTORY_SEPARATOR;
      $real_dir = DOCROOT . $dir;
      $name = uniqid().'.'.strtolower(pathinfo($_FILES['imgFile']['name'], PATHINFO_EXTENSION));
      if (is_dir($real_dir) == FALSE) {
        $test = mkdir($real_dir, 0755, TRUE);
        
        if($test == FALSE) {
          $this->result(1, '', array('error'=>1, 'message'=>'文件夹权限错误'));
          return 0;
        }
      }

      try {
        $ret = Upload::save($_FILES['imgFile'], $name, $real_dir);
        if ($ret) {
          $attachment  = $dir.$name;
          $attachment = URL::base().$attachment;
          $this->result(1, '', array('error'=>0, 'url'=>$attachment));
          return $this->result($ret, $attachment);
        }
        else {
          $this->result(1, '', array('error'=>1, 'message'=>'空间已满?'));
        }
      }
      catch (Kohana_Exception  $e) {
        $this->result(1, '', array('error'=>1, 'message'=> Kohana_Exception::text($e)));
      }
    }
    else {
      $this->result(1, '非法类型'); 
    }

  }
  public function action_attachment_upload()
  {
    $atype = (int) Arr::get($_POST, 'atype', 0);
    $hid = (int) Arr::get($_POST, 'hid', 0);
    if ($atype && array_key_exists($atype, $this->setting->attachment)
        && isset($_FILES['Filedata']) ) {
      $dir = $this->core['upload_dir']. DIRECTORY_SEPARATOR .date("Y/m") . DIRECTORY_SEPARATOR;
      $real_dir = DOCROOT . $dir;
      $name = uniqid().'.'.strtolower(pathinfo($_FILES['Filedata']['name'], PATHINFO_EXTENSION));
      if (is_dir($real_dir) == FALSE) {
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
          if ($this->tmpid) {
            $tmp_attachment = (array) Session::instance()->get('manager.house.add.attachment');
            $tmp_attachment['attachment_'.$atype][] = $attachment;
            $tmp_attachment['hid'] = $this->tmpid;
            Session::instance()->set('manager.house.add.attachment', $tmp_attachment);
          }
          else {
            $ret = $this->model_house->attachment_save($hid, $atype, $attachment);
          }
          return $this->result(0, $attachment);
        }
        else {
          return $this->result(1, '异常问题');
        }
      }
      catch (Kohana_Exception  $e) {
        return  $this->result(1, Kohana_Exception::text($e)); 
      }
    }
    else {
      return $this->result(1, '非法类型'); 
    }
    $this->result(1, '非法请求'); 
  }


} // End API
