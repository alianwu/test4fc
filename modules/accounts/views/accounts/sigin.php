<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="pure-u-2-5">
  这里是帮助信息
</div><div class="pure-u-3-5">
  <?php echo Form::open('sigin/check', array('class'=>'pure-form pure-form-aligned')); ?>
    <fieldset>
        <legend>用户登陆</legend>
        <?php if (isset($account_check_message)) :?>
        <div class="pure-control-group info info-error"><?php  echo $account_check_message; ?></div>
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

            <button type="submit" class="pure-button pure-button-primary">登陆</button>
        </div>
    </fieldset>
  </form>
</div>
