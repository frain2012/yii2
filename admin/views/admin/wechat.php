<?php
use yii\helpers\Html;
use yii\helpers\Url;
/**
 *
 * @var \yii\web\View $this
 * @var string $content
 */
$this->title = '微信公众帐号管理';
?>
<div class="container-fluid">
		<div class="row-fluid">
			<div class="box">
				<div class="box-title">
					<h3>
						<span class="glyphicon glyphicon-th" aria-hidden="true"></span><?=$this->title?>
					</h3>
				</div>
                <div class="alert alert-warning" role="alert">
                  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                   	温馨提示:<?=$msg?>
                </div>
                
                 <div class="row-fluid" style="margin-bottom:10px;">
                        <div class="control-group" style="color:#929292;margin-top:10px;">
                           <img src="http://stc.weimob.com/img/wei_small.jpg" style="width:100px;" alt="">
                           <a class="btn btn-info btn-large" style="margin-right:20px;" href="<?=Url::to(['wechat/create'])?>">手动绑定</a>填写公众号的相关信息及id，并且填写接口地址、TOKEN，个性域名等信息完成绑定后使用
                    	</div>
                </div>
                
                
				<div class="box-content">
					<div class="panel panel-default">
						<table class="table table-bordered table-hover">
							<thead>
								<tr>
									<th>公众号名称</th>
									<th>会员套餐</th>
									<th>创建时间/到期时间</th>
									<th>操作</th>
								</tr>
							</thead>
							<tbody>
							<?php foreach($model as $item){?>
                            <tr>
                            	<td style="text-align:center;">
                                    <p>
                                    <a href="javascript:void(0)" onclick="parent.location.href='/wechat/index/aid/<?=$item->id?>'" title="点击进入功能管理">
                                    <img src="<?=$item->head?>" style="width:88px;height:88px;"></a>
                                    </p>
                                    <p><?=$item->name?></p>
									</td>
									<td style="vertical-align:middle;">
												<strong>演示版<a href="javascript:;" class="js_upgrade"><i class="icon-arrow-up" title="升级"></i>升级</a></strong>
									</td>
                                    <td style="vertical-align:middle;">
                                    	<p>创建时间:<?=date('Y-m-d', $item->created_at)?></p>
                                        <p>到期时间:2016-03-31</p>
                                    </td>
									
									<td style="vertical-align:middle;">
										<a class="btn btn-default" href="/wechat/update/aid/<?=$item->id?>"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>
                                        <a class="btn btn-default" href="wechat/delete/id/<?=$item->id?>"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>
										<a class="btn btn-default" href="javascript:void(0)" onclick="parent.location.href='/wechat/index/aid/<?=$item->id?>'">
											<span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
										</a>
                                    </td>
                            </tr>
                            <?php }?>
                           </tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>