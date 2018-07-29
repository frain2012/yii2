<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var \common\models\LoginForm $model
 */
$this->title = '管理员登录';
?>
<div class="container">
      <form class="form-signin form-validate" method="post">
        <h2 class="form-signin-heading"><?= Html::encode($this->title) ?></h2>
        <label class="err_tips"></label>
        <label class="sr-only">帐号</label>
        <input type="text" class="form-control" name="AdminForm[username]" placeholder="帐号" datatype="s4-18">
        <label class="sr-only">密码</label>
        <input type="password" class="form-control" name="AdminForm[password]" placeholder="密码" datatype="s4-18">
        <div class="checkbox">
          <label>
            <input type="checkbox" name="AdminForm[rememberMe]" value="1">记住
          </label>
        </div>
        <button class="btn btn-lg btn-primary btn-block" type="submit">登陆</button>
      </form>
</div>