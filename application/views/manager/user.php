<?php defined('SYSPATH') or die('No direct script access.');?>
<h4>用户资料</h4>
<div class="pure-u-1">
  <?php echo Form::open('manager_user/update', array('class'=>'pure-form pure-form-aligned')); ?>
    <fieldset>
        <legend>更新</legend>
        <?php if (isset($Account_Check_Message)) :?>
        <div class="pure-control-group info info-error"><?php  echo $Account_Check_Message; ?></div>
        <?php endif;?>
        <div class="pure-control-group">
            <label for="email">邮件</label>
            <?php echo $user['email']; ?>
        </div>
        <div class="pure-control-group">
            <label for="username">名称</label>
            <?php echo Form::input('username', Arr::get($_POST, 'username', $user['name']), array('id'=>'username', 'placeholder'=>'请输入你的账户名称')); ?>
        </div>
        <div class="pure-control-group">
            <label for="password">旧密码</label>
            <?php echo Form::password('oldpassword', '', array('id'=>'oldPassword', 'placeholder'=>'请输入你的旧密码')); ?>
            <?php if(isset($error['oldpassword'])): ?><span class="info-error"><?php echo $error['oldpassword']; ?></span><?php endif; ?>
        </div>
        <div class="pure-control-group">
            <label for="password">新密码</label>
            <?php echo Form::password('password', '', array('id'=>'Password', 'placeholder'=>'请输入你的密码')); ?>
            <?php if(isset($error['password'])): ?><span class="info-error"><?php echo $error['password']; ?></span><?php endif; ?>
        </div>
        <div class="pure-control-group">
            <label for="password">确认密码</label>
            <?php echo Form::password('repassword', '', array('id'=>'rePassword', 'placeholder'=>'请再次输入你的密码')); ?>
            <?php if(isset($error['repassword'])): ?><span class="info-error"><?php echo $error['repassword']; ?></span><?php endif; ?>
        </div>
        <div class="pure-controls">
            <?php echo Form::hidden('id', Arr::get($user, 'id', 0)); ?>
            <?php echo Form::hidden('csrf', $token); ?>
            <button type="submit" class="pure-button pure-button-primary">确认</button>
        </div>
    </fieldset>
  </form>

</div>
