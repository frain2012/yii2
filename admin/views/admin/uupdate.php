<?php
use yii\helpers\Html;
/**
 *
 * @var \yii\web\View $this
 * @var string $content
 */
$this->title = '修改用户帐号';
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
							<input type="text" name="nickname" datatype="s2-16" value="<?=$model->nickname?>" errormsg="名称至少2个字符,最多16个字符！"/>
						</div>
					</div>
					<div class="control-group">
						<label for="name" class="control-label"><span class="maroon">*</span>帐号：</label>
						<div class="controls">
							<input type="text" name="username" disabled="disabled" datatype="s2-16" value="<?=$model->username?>" errormsg="描述至少2个字符,最多16个字符！"/>
						</div>
					</div>
					<div class="control-group">
						<label for="name" class="control-label"><span class="maroon">*</span>密码：</label>
						<div class="controls">
							<input type="password" name="password" datatype="s2-16" errormsg="描述至少2个字符,最多16个字符！"/>
						</div>
					</div>
					<div class="control-group">
						<label for="name" class="control-label"><span class="maroon">*</span>确认密码：</label>
						<div class="controls">
							<input type="password"  recheck="password" datatype="s2-16" errormsg="密码不一致"/>
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