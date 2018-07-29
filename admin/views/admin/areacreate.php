<?php
use yii\helpers\Html;
/**
 *
 * @var \yii\web\View $this
 * @var string $content
 */
$this->title = '添加区域';
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
				<form action="" method="post" class="form-horizontal form-validate"  enctype="multipart/form-data">
					<div class="control-group">
						<label for="name" class="control-label"><span class="maroon">*</span>昵称：</label>
						<div class="controls">
							<input type="text" name="City[name]" datatype="*2-100" errormsg="昵称至少2个字符,最多100个字符！"/>
						</div>
					</div>
					<div class="control-group">
						<label for="name" class="control-label"><span class="maroon">*</span>二维码：</label>
						<div class="controls">
							<input type="file" name="City[qrcode]" datatype="*"/>
						</div>
					</div>
					<div class="control-group">
						<label for="name" class="control-label"><span class="maroon">*</span>所在省：</label>
						<div class="controls">
							<select name="City[province]" datatype="*2-3">
								<option value="北京">北京</option>
								<option value="浙江">浙江</option>
								<option value="天津">天津</option>
								<option value="安徽">安徽</option>
								<option value="上海">上海</option>
								<option value="福建">福建</option>
								<option value="重庆">重庆</option>
								<option value="江西">江西</option>
								<option value="山东">山东</option>
								<option value="河南">河南</option>
								<option value="湖北">湖北</option>
								<option value="湖南">湖南</option>
								<option value="广东">广东</option>
								<option value="海南">海南</option>
								<option value="山西">山西</option>
								<option value="青海">青海</option>
								<option value="江苏">江苏</option>
								<option value="辽宁">辽宁</option>
								<option value="吉林">吉林</option>
								<option value="台湾">台湾</option>
								<option value="河北">河北</option>
								<option value="贵州">贵州</option>
								<option value="四川">四川</option>
								<option value="云南">云南</option>
								<option value="陕西">陕西</option>
								<option value="甘肃">甘肃</option>
								<option value="黑龙江">黑龙江</option>
								<option value="香港">香港</option>
								<option value="澳门">澳门</option>
								<option value="广西">广西</option>
								<option value="宁夏">宁夏</option>
								<option value="新疆">新疆</option>
								<option value="内蒙古">内蒙古</option>
								<option value="西藏">西藏</option>
							</select>
						</div>
					</div>
					<div class="control-group">
						<label for="name" class="control-label"><span class="maroon">*</span>排序：</label>
						<div class="controls">
							<input type="text" name="City[order]" datatype="n" value="0"/>
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