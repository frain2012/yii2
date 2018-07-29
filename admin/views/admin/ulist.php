<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
/**
 *
 * @var \yii\web\View $this
 * @var string $content
 */
$this->title = '用户列表';
?>
<div class="container-fluid">
		<div class="row-fluid">
			<div class="box">
				<div class="box-title">
					<h3>
						<span class="glyphicon glyphicon-th" aria-hidden="true"></span><?=$this->title?>
					</h3>
				</div>
                 <div class="row-fluid form-inline" style="margin-bottom:10px;">
	                 <div class="control-group" style="color:#929292;margin-top:10px;">
	                 	<a class="btn btn-info btn-large" style="margin-right:10px;" href="/admin/ucreate">添加</a>
	                 	<form action="" method="get" style="display: inline-block;">
	                 		<input  type="text" name="keys" class="form-control" placeholder="帐号昵称"/>
	                 		<select name="match_type" class="input-small form-control">
                                 <option value="" selected="">全部</option>
                                 <option value="1">启用</option>
                                 <option value="2">禁用</option>
                           </select>
	                 	   <button class="btn" type="submit">查询</button>
	                 	</form>
	                 </div>
                </div>
                
				<div class="box-content">
					<div class="panel panel-default">
						<table class="table table-bordered table-hover">
							<thead>
								<tr>
									<th>昵称</th>
									<th>帐号</th>
									<th>角色</th>
									<th>状态</th>
									<th>创建时间</th>
									<th>操作</th>
								</tr>
							</thead>
							<tbody>
							<?php foreach ($model as $item){?>
							<tr>
                            	<td>
                                    <p><?=$item->nickname?></p>
								</td>
								<td>
									<?=$item->username?>
								</td>
                               	<td>
                               		<?php
                               		   switch ($item->role){
                               		       case 20:
                               		           echo '用户';
                               		           break;
                               		       default:
                               		           echo '未知';
                               		           break;
                               		}?>
                                 </td>
                                 <td>
                                    <?php
                               		   switch ($item->status){
                               		       case 10:
                               		           echo '启用';
                               		           break;
                               		       default:
                               		           echo '禁用';
                               		           break;
                               		}?>
                                 </td>
                                 <td>
                                    <?= date('Y-m-d H:i:s', $item->created_at)?>
                                 </td>
								<td style="vertical-align:middle;">
										<a class="btn btn-default" href="/admin/uedit/<?=$item->id?>"><span class="glyphicon glyphicon-edit" aria-hidden="true">编辑</span></a>
										<a class="btn btn-default" href="/admin/ustatus/<?=$item->id?>">
										<?php 
										switch ($item->status){
										    case 10:
										        echo '<span class="glyphicon glyphicon-play" aria-hidden="true">禁用</span>';
										        break;
										    default:
										        echo '<span class="glyphicon glyphicon-pause" aria-hidden="true">启用</span>';
										        break;
										}?>
										</a>
                                        <a class="btn btn-default" href="/admin/udelete/<?=$item->id?>"><span class="glyphicon glyphicon-trash" aria-hidden="true">删除</span></a>
                                        <a class="btn btn-default" href="javascript:void(0)" target="_top" onclick="javascript:window.open('/admin/auth/<?=$item->id?>?type=2','_blank');"><span class="glyphicon glyphicon-user" aria-hidden="true">越权登录</span></a>
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