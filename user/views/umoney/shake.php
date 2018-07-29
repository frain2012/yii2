<?php
use yii\helpers\Html;
use yii\helpers\Url;
/**
 *
 * @var \yii\web\View $this
 * @var string $content
 */
$this->title = $name;
?>
<?php $this->beginPage()?>
<!DOCTYPE>
<html lang="<?= Yii::$app->language ?>">
<head>
<meta charset="<?= Yii::$app->charset ?>" />
<meta content="application/xhtml+xml;charset=UTF-8" http-equiv="Content-Type">
<meta content="no-cache,must-revalidate" http-equiv="Cache-Control">
<meta content="no-cache" http-equiv="pragma">
<meta content="0" http-equiv="expires">
<meta content="telephone=no, address=no" name="format-detection">
<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no">
<title><?= Html::encode($this->title) ?></title>
    <?php $this->head()?>
    <?=Html::cssFile('@web/css/shake.css')?>
    <style type="text/css">
    .tel{
    position: relative;
	  height: auto;
	  -webkit-box-sizing: border-box;
	     -moz-box-sizing: border-box;
	          box-sizing: border-box;
	  padding: 10px;
	  font-size: 16px;
	  width: 180px;
	  margin:10px auto;
	  border: 1px solid #CEC2C2;
	  border-radius: 4px;
	  outline: none;
	  }
    </style>
</head>
<body>
    <?php $this->beginBody()?>
    <!-- 主体部分 -->
    <div class="ss"></div>
    <audio id="autoPlay" controls="controls" style="display:none;" src="/audio/shake.wav"></audio>
    <?=Html::jsFile('@web/js/jquery.min.js')?>
    <?=Html::jsFile('@web/js/layer.js')?>
    <?=Html::jsFile('@web/js/jweixin-1.0.0.js')?>
    <script type="text/javascript">
    var tag =  true;
    var SHAKE_THRESHOLD = 1000;
    var last_update = 0;
    var x = y = z = last_x = last_y = last_z = 0;
    if (window.DeviceMotionEvent) {
    	window.addEventListener('devicemotion', deviceMotionHandler, false);
    } else {
    	layer.alert('手机不支持摇一摇');
    }
    function deviceMotionHandler(eventData) {
    	var acceleration = eventData.accelerationIncludingGravity;
    	var curTime = new Date().getTime();
    	if ((curTime - last_update) > 200) {
    		var diffTime = curTime - last_update;
    		last_update = curTime;
    		x = acceleration.x;
    		y = acceleration.y;
    		z = acceleration.z;
    		var speed = Math.abs(x + y + z - last_x - last_y - last_z) / diffTime * 10000;
    		if (speed > SHAKE_THRESHOLD) {
    			if(!tag) return;
    			$('#autoPlay')[0].play();
    			tag = !tag;
    			$.post("", function(data){
        			switch(data.status){
        			case 0:
        				layer.open({content: data.info,btn: ['确定'],yes:function(index){
    				    	layer.close(index);
    				    	tag = !tag;
    				    }});
            			break;
        			case 1:
        				layer.open({
        				    content: data.info+'<input class="tel" placeholder="请输入电话号码" id="tel"/>',
        				    btn: ['兑奖'],
        				    shadeClose: false,
        				    yes: function(index){
        				    	var tel = $('#tel').val();
        				    	if(!checkPhoneNum(tel)){
        				    		alert('电话号码不正确');
            				    	return;
            				    }
        				    	layer.close(index);
        				    	var tpye = layer.open({type : 2 });
        				    	setTimeout(function(){
        				    		$.post(data.url,{tel:tel},function(data){
            			    			switch(data.status){
            			    			case 0:
            			    			case 1:
            			    				layer.close(tpye);
            			    				layer.open({content: data.info,btn: ['确定']});
            			        			break;
            			    			default:
            			    				layer.open({content: '出现错误',time: 2});
            			    				break;
            			    			}
            			    			setTimeout(function(){tag = !tag;},1000);
            			 			 });
            				    },1500);
        				    }
        				});
            			break;
        			default:
        				layer.open({content: '出现错误',time: 2});
        				tag = !tag;
        				break;
        			}
     			 });
    		}
    		last_x = x;
    		last_y = y;
    		last_z = z;
    	}
    }
    function checkPhoneNum(obj){
	    if(/^(1[3|4|5|7|8][0-9]{9})$/.test(obj)){
	        return true;
	    }else{
	    	return false;
	    }
	}
</script>
<script type="text/javascript">
    /**微信分享**/
	var SHARE = {
		title : "<?=$share['title']?>",
		desc : "<?=$share['desc']?>",
		link : "<?=$share['link']?>",
		imgUrl : "<?=$share['imgUrl']?>",
	};
	wx.config({
		debug : false,
		appId : '<?=$share['appId']?>',
		timestamp : '<?=$share['timestamp']?>',
		nonceStr : '<?=$share['nonceStr']?>',
		signature : '<?=$share['signature']?>',
		jsApiList : [ 'onMenuShareTimeline', 'onMenuShareAppMessage',
				'onMenuShareQQ', 'onMenuShareWeibo','hideMenuItems']
	});
	wx.ready(function() {
		wx.hideMenuItems({
		      menuList: [
		        'menuItem:copyUrl'
		      ],
		      success: function (res) {
		      },
		      fail: function (res) {
		      }
		});
		wx.onMenuShareAppMessage({
			title : SHARE.title,
			desc : SHARE.desc,
			link : SHARE.link,
			imgUrl : SHARE.imgUrl,
			success : function(res) {
			},
			cancel : function(res) {
			},
			fail : function(res) {
			}
		});
		wx.onMenuShareTimeline({
			title : SHARE.desc,
			link : SHARE.link,
			imgUrl : SHARE.imgUrl,
			success : function(res) {
			},
			cancel : function(res) {
			},
			fail : function(res) {
			}
		});
		wx.onMenuShareQQ({
			title : SHARE.title,
			desc : SHARE.desc,
			link : SHARE.link,
			imgUrl : SHARE.imgUrl,
			success : function(res) {
			},
			cancel : function(res) {
			},
			fail : function(res) {
			}
		});
		wx.onMenuShareWeibo({
			title : SHARE.title,
			desc : SHARE.desc,
			link : SHARE.link,
			imgUrl : SHARE.imgUrl,
			success : function(res) {
			},
			cancel : function(res) {
			},
			fail : function(res) {
			}
		});
	});
	</script>
<div style="display:none;"><script src="http://s11.cnzz.com/stat.php?id=1257128320&web_id=1257128320" language="JavaScript"></script></div>
    <?php $this->endBody()?>
</body>
</html>
<?php $this->endPage()?>