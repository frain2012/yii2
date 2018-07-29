<?php
use yii\helpers\Html;


$this->title = '虎虎生威';
?>
<div class="container">
	<div class="title" id="solution">
		<h2>解决方案</h2>
		<p>精耕细作·深度挖掘，提供一站式的行业解决方案服务</p>
	</div>
	<div class="industry">
		<div class="industry-item wxapp">
			<h3>小程序电商</h3>
			<p>新小店</p>
			<p><a href="http://www.xinxiaodian.com" class="btn btn-stroke" target="_blank">了解详情</a></p>
		</div>
		<div class="industry-item city">
			<h3>同城生活消费</h3>
			<p>好店商城·同城圈</p>
			<p><a href="http://e.haodiantong.cn" class="btn btn-stroke" target="_blank">立即开通</a></p>
		</div>
		<div class="industry-item fans">
			<h3>粉丝特权服务</h3>
			<p>黑卡·五折卡·微信会员卡</p>
			<p><a href="http://e.haodiantong.cn" class="btn btn-stroke" target="_blank">立即开通</a></p>
		</div>
		<div class="industry-item event">
			<h3>营销推广服务</h3>
			<p>砍价·裂变海报·博饼</p>
			<p><a href="http://e.haodiantong.cn" class="btn btn-stroke" target="_blank">立即开通</a></p>
		</div>
	</div>
	<div class="title" id="case">
		<h2>客户案例</h2>
		<p>服务数百家自媒体，连接10000家同城本地商户</p>
	</div>
	<div class="case">
		<div class="case-item"><img src="./img/case.fook.png" alt="见福便利店"></div>
		<div class="case-item"><img src="./img/case.aolian.png" alt="奥联西饼"></div>
		<div class="case-item"><img src="./img/case.baheli.png" alt="八合里海记"></div>
		<div class="case-item"><img src="./img/case.chinaums.png" alt="银联商务"></div>
		<div class="case-item"><img src="./img/case.crepcrep.png" alt="可丽可丽"></div>
		<div class="case-item"><img src="./img/case.fantasy.png" alt="观音山梦幻海岸"></div>
		<div class="case-item"><img src="./img/case.fantawild.png" alt="方特梦幻王国"></div>
		<div class="case-item"><img src="./img/case.gulong.png" alt="古龙食品"></div>
		<div class="case-item"><img src="./img/case.happiness.png" alt="幸福西饼"></div>
		<div class="case-item"><img src="./img/case.huantour.png" alt="欢兔旅行"></div>
		<div class="case-item"><img src="./img/case.juanfu.png" alt="卷福"></div>
		<div class="case-item"><img src="./img/case.lajia.png" alt="辣家私厨"></div>
		<div class="case-item"><img src="./img/case.mingsheng.png" alt="民生银行"></div>
		<div class="case-item"><img src="./img/case.xiaoyu.png" alt="厦门小鱼网"></div>
		<div class="case-item"><img src="./img/case.tomato.png" alt="泉州番茄"></div>
		<div class="case-item"><img src="./img/case.puning.png" alt="掌上普宁"></div>
		<div class="case-item"><img src="./img/case.foodfuzhou.png" alt="吃喝玩乐福州"></div>
		<div class="case-item"><img src="./img/case.momom.png" alt="妈妈报到"></div>
		<div class="case-item"><img src="./img/case.narea.png" alt="南宁圈"></div>
		<div class="case-item"><img src="./img/case.riyuegu.png" alt="日月谷"></div>
		<div class="case-item"><img src="./img/case.sungiven.png" alt="元初食品"></div>
		<div class="case-item"><img src="./img/case.sweetone.png" alt="食汇堂"></div>
		<div class="case-item"><img src="./img/case.thankumom.png" alt="Thank U MOM"></div>
		<div class="case-item"><img src="./img/case.tongan.png" alt="同安旅游"></div>
		<div class="case-item"><img src="./img/case.xiaoye.png" alt="小爷酸汤鱼"></div>
		<div class="case-item"><img src="./img/case.xingkejia.png" alt="鑫客家"></div>
		<div class="case-item"><img src="./img/case.xmeyes.png" alt="厦门眼科中心"></div>
		<div class="case-item"><img src="./img/case.yanyu.png" alt="宴遇"></div>
		<div class="case-item"><img src="./img/case.foreveryoung.png" alt="老知青"></div>
		<div class="case-item"><img src="./img/case.zhenfu.png" alt="臻府海鲜"></div>
	</div>
</div>
<?=Html::jsFile('@web/js/jquery.2.2.0.min.js')?>
<script type="text/javascript">
	$(document).ready(function(){

		$('.product-nav-item').click(function()
		{
			if(!$(this).hasClass('active'))
			{
				$(this).addClass('active').siblings('.active').removeClass('active').parent('ul').siblings('ul').children('li').hide().eq($(this).index()).fadeIn(200);
				$(this).parents('.product').attr({'class':$(this).attr('class').split(' ')[1]+' product'})
			}
		});

	});
</script>