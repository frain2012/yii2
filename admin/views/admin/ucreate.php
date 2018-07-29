<?php
use yii\helpers\Html;
/**
 *
 * @var \yii\web\View $this
 * @var string $content
 */
$this->title = '添加用户帐号';
?>
<div class="container-fluid">
	<div class="row-fluid">
		<div class="box">
			<div class="box-title">
				<h3>
					<span class="glyphicon glyphicon-edit" aria-hidden="true"></span><?=$this->title?>
				</h3>
			</div>
			
			<div class="box-content">
				<form action="" method="post" class="form-horizontal form-validate">
					<div class="control-group">
						<label for="name" class="control-label"><span class="maroon">*</span>昵称：</label>
						<div class="controls">
							<input type="text" name="UserForm[nickname]" datatype="s2-16" errormsg="名称至少2个字符,最多16个字符！"/>
						</div>
					</div>
					<div class="control-group">
						<label for="name" class="control-label"><span class="maroon">*</span>帐号：</label>
						<div class="controls">
							<input type="text" name="UserForm[username]" ajaxurl="/admin/finduname" datatype="s4-16" errormsg="描述至少2个字符,最多16个字符！"/>
						</div>
					</div>
					<div class="control-group">
						<label for="name" class="control-label"><span class="maroon">*</span>密码：</label>
						<div class="controls">
							<input type="password" name="UserForm[password]" datatype="s2-16" errormsg="描述至少2个字符,最多16个字符！"/>
						</div>
					</div>
					<div class="control-group">
						<label for="name" class="control-label"><span class="maroon">*</span>确认密码：</label>
						<div class="controls">
							<input type="password"  recheck="UserForm[password]" datatype="s2-16" errormsg="密码不一致"/>
						</div>
					</div>
					<div class="form-actions">
						<button type="submit" class="btn btn-primary">保存</button>
						<a class="btn" href="Javascript:window.history.go(-1)">取消</a>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>