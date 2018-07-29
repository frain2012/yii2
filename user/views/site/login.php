<?php

use yii\helpers\Html;

$this->title = '用户登录';
?>
<div class="jumbotron" style="margin-top:50px;">
		<div class="container">
    		<div class="row">
                  <div class="col-md-4 col-md-offset-4">
                  	<form method="post">
                      <div class="form-group">
                        <label>帐号</label>
                        <input type="text" class="form-control" name="uname" placeholder="用户名">
                      </div>
                      <div class="form-group">
                        <label>密码</label>
                        <input type="password" class="form-control" name="passwd" placeholder="密码">
                      </div>
                      <div class="checkbox">
                        <label><input type="checkbox" name="rememberMe">记住</label>
                      </div>
                      <button type="submit" class="btn btn-default">提交</button>
                    </form>
                  </div>
                </div>
        </div>
	</div>