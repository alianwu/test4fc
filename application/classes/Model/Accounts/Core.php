<?php defined('SYSPATH') OR die('No direct script access.');

class Model_Accounts_Core extends Kohana_Model {

  public  $vew;
  public  $table = 'user';

  public  function check_email($email) 
  {
    $query = DB::query(Database::SELECT, 
                'SELECT id FROM "'. $this->table .'" WHERE email=:email LIMIT 1')
              ->param(':email', $email);
    $ret = $query->execute();
    return $ret->count() == 0 ?  FALSE : TRUE;
  }
  
  public  function check_passport($passport) {
    $ret = array(
        'error'=> TRUE, 
        'info' => '');
    $auth = Auth::instance();
    $query = DB::query(Database::SELECT, 
        'SELECT id, username, password, actived  
          FROM "'. $this->table .'" WHERE email=:email LIMIT 1')
              ->param(':email', $passport['passport'])
              ->execute();
    if ($query->count() == 0) {
      $ret['info'] = '该账户不存在';
    }
    elseif ((bool)$query->get('actived', FALSE) == FALSE){
      $ret['info'] = '该账户没有激活';
    }
    elseif ($query->get('password', FALSE) <> $auth->hash_password($passport['password'])){
      $ret['info'] = '帐号或者密码正确';
    }
    else {
      $ret['error'] = FALSE;
      Session::instance()->set('accounts.'.$this->view, array('id'    => $query->get('id'), 
                                              'email' => $query->get('email', $passport['passport']),
                                              'ip' => Request::$client_ip,
                                              'name'  => $query->get('username')
                                       ), (int)$passport['expires']*604800);
    }
    return $ret;
  }
  
  public function set_view($view) 
  {
    $this->view = $view;
  }

  public  function logout()
  {
    Session::instance()->destroy();
  }
  
  public  function save_passport($passport)
  {
    $ret = array(
        'error'=> TRUE, 
        'info' => '');
    $query = DB::query(Database::SELECT, 
                'SELECT id FROM "'. $this->table .'" WHERE email=:email LIMIT 1')
              ->param(':email', $passport['email'])
              ->execute();
    if ($query->count() > 0) {
      $ret['info'] = '该账户已存在';
    }
    else {
      $auth = Auth::instance();
      $query = DB::query(Database::INSERT, 
                  'INSERT INTO "'. $this->table .'" (email, password, actived, created) 
                    VALUES (:email, :password, :actived, NOW())')
                ->param(':email', $passport['email'])
                ->param(':password', $auth->hash_password($passport['password']))
                ->param(':actived', FALSE)
                ->execute();
      if ($query) {
        $ret['error'] = FALSE;
      }
    }
    return $ret;
  }

  public  function update_passport($passport)
  {
    $query = DB::query(Database::UPDATE, 'UPDATE "'. $this->table .'" SET 
                username=:username '.(isset($passport['photo'])? ', photo=:photo':'').' WHERE id=:id')
                ->param(':id', $passport['id'])
                ->param(':username', $passport['username'])
                ->param(':photo', $passport['photo'])
                ->param(':actived', FALSE)
                ->execute();
    return $query? TRUE: FALSE;
  }
  public  function resetpw_passport($passport)
  {
    $ret = array(
        'error'=> TRUE, 
        'info' => '');
    $is_exist_email = $this->check_email($passport['email']);
    if ($is_exist_email) {
      $ret['error'] = FALSE;
      $ret['info'] = '已发送重置密码邮件，请根据提示操作';
    }
    else {
      $ret['info'] = '此账户不存在';
    }
    return $ret;
  }
  
  public function get_one($id)
  {
    $query = DB::query(Database::SELECT, 
                  'SELECT * FROM "'. $this->table .'" WHERE id=:id LIMIT 1')
                ->param(':id', $id)
                ->execute();
    return $query->count() == 0 ? NULL : $query->current();
  }

  public function get_list($data)
  {
    $where = 'WHERE true ';
    $params = array();
    if ($data['actived']) {
      $where .= 'AND actived=:actived';
      $params[':actived'] = $data['actived'];
    }
    if ($keyword = trim($data['keyword'])) {
      $where .= ' AND (id=:id OR username like :keyword OR email like :keyword)';
      $params[':id'] = (int)$keyword;
      $params[':keyword'] = '%'.$keyword.'%';
    }
    $query = DB::query(Database::SELECT, 
      'SELECT * FROM "'. $this->table .'"'. $where)
                ->parameters($params)
                ->as_object()
                ->execute();
    return $query;
  }

  public function actived($id)
  {
    $query = DB::query(Database::UPDATE, 
                'UPDATE  "'. $this->table .'" SET actived = not actived WHERE id=:id ')
                ->param(':id', $id)
                ->execute();
    return $query;
  }
   public function delete($id)
  {
    $query = DB::query(Database::DELETE, 
                'DELETE "'. $this->table .'"  WHERE id=:id ')
                ->param(':id', $id)
                ->execute();
    return $query;
  }

  public function auth_update($data)
  {
    $id = $data['id'];
    $auth = json_encode($data['auth']); 
    $query = DB::query(Database::UPDATE, 
                  'UPDATE  "'. $this->table .'" SET auth = :auth WHERE id=:id')
                ->param(':id', $id)
                ->param(':auth', $auth)
                ->execute();
    return $query?true:false;
  }

}
