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

}


