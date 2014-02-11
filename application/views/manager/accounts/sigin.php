<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="pure-u-2-5">
  <br />
  <br />
  <br />
  <br />
  <p>没有账户，请 <?php echo HTML::anchor('manager_sigup', '注册', array('class'=>'pure-button pure-button-success')); ?></p>
</div><div class="pure-u-3-5">
  <?php echo Form::open('manager_sigin/check', array('class'=>'pure-form pure-form-aligned')); ?>
    <fieldset>
        <legend>用户登陆</legend>
        <?php if (isset($Account_Check_Message)) :?>
        <div class="pure-control-group info info-error"><?php  echo $Account_Check_Message; ?></div>
        <?php endif;?>
        <div class="pure-control-group">
            <label for="name">邮件/账户</label>
            <?php echo Form::input('passport', Arr::get($_POST, 'passport'), array('id'=>'passport', 'placeholder'=>'请输入你的邮件或者账户名称')); ?>
        </div>

        <div class="pure-control-group">
            <label for="password">密码</label>
            <?php echo Form::password('password', '', array('id'=>'Password', 'placeholder'=>'请输入你的密码')); ?>
        </div>
        <?php if ($display_captcha): ?>
        <div class="pure-control-group">
            <label for="password">验证码</label>
            <?php echo Form::input('captcha', '', array('id'=>'captcha', 'placeholder'=>'请输入验证码')); ?>
            <?php echo Captcha::instance('default')->html_render(); ?>
        </div>
        <?php endif; ?>
        <div class="pure-controls">
            <label for="cb" class="pure-checkbox">
            <input id="cb" type="checkbox" name="expires"> 登陆时效为 一星期 <?php echo HTML::anchor('', '?'); ?>
            </label>
            <?php echo Form::hidden('redirect', Arr::get($_POST, 'redirect', '')); ?>
            <button type="submit" class="pure-button pure-button-primary">登陆</button>
        </div>
    </fieldset>
  </form>
</div>
