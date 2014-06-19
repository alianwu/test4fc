<?php defined('SYSPATH') OR die('No direct script access.');

class Model_Member extends Model_Accounts_Core {

  public $table = 'member';

  public function denglu_sigin_or_sigup($data, $session)
  {

    $query = DB::query(Database::SELECT, 'SELECT * FROM member 
                    WHERE mediaid=:mediaid and mediauserid=:mediauserid LIMIT 1')
                  ->param(':mediaid', $data['mediaID'])
                  ->param(':mediauserid', $data['mediaUserID'])
                  ->as_object()
                  ->execute();
    if($query->count() == 0) {
      $data['screenName'] = htmlspecialchars($data['screenName']);
      $query = DB::query(Database::SELECT, 'INSERT INTO member(name, mediaid, mediauserid, created) 
                    VALUES (:name, :mediaid, :mediauserid, :created) RETURNING mid')
                  ->param(':name', $data['screenName'])
                  ->param(':mediaid', $data['mediaID'])
                  ->param(':mediauserid', $data['mediaUserID'])
                  ->param(':created', $data['createTime'])
                  ->as_object()
                  ->execute();
      if ($query->count()) {
        $mid = $query->get('mid');
        $mname = $query->get('name');
      }
    }
    else {
      $mid = $query->get('mid');
      $mname = $query->get('name');
    }

    Session::instance()->set($session, array('_id'=>$mid, '_name'=>$mname));
    return TRUE;
  }

  public function qq_login($openid, $user, $session)
  {
    $query = DB::query(Database::SELECT, 'SELECT * FROM member 
                    WHERE openid=:openid and source=:source LIMIT 1')
                  ->param(':source', 'qq')
                  ->param(':openid', $openid)
                  ->as_object()
                  ->execute();
    if($query->count() == 0) {
      $query = DB::query(Database::SELECT, 'INSERT INTO member(name, openid, nickname, photo, source, created) 
                    VALUES (:name, :openid, :nickname, :photo, :source, now()) RETURNING mid')
                  ->param(':name', $user['nickname'])
                  ->param(':openid', $openid)
                  ->param(':nickname', $user['nickname'])
                  ->param(':photo', $user['figureurl_qq_1'])
                  ->param(':source', 'qq')
                  ->as_object()
                  ->execute();
      if ($query->count()) {
        $mid = $query->get('mid');
        $mname = $query->get('name');
      }
    }
    else {
      $mid = $query->get('mid');
      $mname = $query->get('name');
    }

    Session::instance()->set($session, array('_id'=>$mid, '_name'=>$mname));
    return TRUE;
  }

}


