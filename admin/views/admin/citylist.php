<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
/**
 *
 * @var \yii\web\View $this
 * @var string $content
 */
$this->title = '区域列表';
?>
<div class="container-fluid">
		<div class="row-fluid">
			<div class="box">
				<div class="box-title">
					<h3>
						<span class="glyphicon glyphicon-th" aria-hidden="true"></span><?=$this->title?>
					</h3>
				</div>
                <!--  div class="alert alert-warning" role="alert">
                  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                   	温馨提示:帐号管理
                </div -->
                 <div class="row-fluid form-inline" style="margin-bottom:10px;">
	                 <div class="control-group" style="color:#929292;margin-top:10px;">
	                 	<a class="btn btn-info btn-large" style="margin-right:10px;" href="/admin/areacreate">添加</a>
	                 	<!--  form action="" method="get" style="display: inline-block;">
	                 		<input  type="text" name="keys" class="form-control" placeholder="帐号昵称"/>
	                 		<select name="match_type" class="input-small form-control">
                                 <option value="" selected="">全部</option>
                                 <option value="1">分公司</option>
                                 <option value="2">模糊匹配</option>
                           </select>
	                 	   <button class="btn" type="submit">查询</button>
	                 	</form -->
	                 </div>
                </div>
                
				<div class="box-content">
					<div class="panel panel-default">
						<table class="table table-bordered table-hover">
							<thead>
								<tr>
									<th>昵称</th>
									<th>所在省</th>
									<th>二维码</th>
									<th>操作</th>
								</tr>
							</thead>
							<tbody>
							<?php foreach ($model as $item){?>
							<tr>
                            	<td>
                                    <p><?=$item->name?></p>
								</td>
								<td>
									<?=$item->province?>
								</td>
								<td>
                                    <p><?=$item->qrcode?></p>
								</td>
								<td style="vertical-align:middle;">
										<a class="btn btn-default" href="/admin/areaedit/<?=$item->id?>"><span class="glyphicon glyphicon-edit" aria-hidden="true">编辑</span></a>
                                        <a class="btn btn-default" href="/admin/areadelete/<?=$item->id?>"><span class="glyphicon glyphicon-trash" aria-hidden="true">删除</span></a>
                                  </td>
                            </tr>
                            <?php }?>
                            <tr>
                            	<td colspan="6" class="text-right">
                            		<?= LinkPager::widget(['pagination' => $page]); ?>
                            	</td>
							</tr>
                           </tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>