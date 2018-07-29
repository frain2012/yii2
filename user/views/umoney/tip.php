<?php
use yii\helpers\Html;
use yii\helpers\Url;
/**
 *
 * @var \yii\web\View $this
 * @var string $content
 */
$this->title = '提示';
?>
<?php $this->beginPage()?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
<meta charset="<?= Yii::$app->charset ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= Html::encode($this->title) ?></title>
    <?php $this->head()?>
    <link href="//cdn.bootcss.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
</head>
<script type="text/javascript">
	function onBridgeReady(){
	 	WeixinJSBridge.call('hideOptionMenu');
	}
	if (typeof WeixinJSBridge == "undefined"){
	    if( document.addEventListener ){
	        document.addEventListener('WeixinJSBridgeReady', onBridgeReady, false);
	    }else if (document.attachEvent){
	        document.attachEvent('WeixinJSBridgeReady', onBridgeReady); 
	        document.attachEvent('onWeixinJSBridgeReady', onBridgeReady);
	    }
	}else{
	    onBridgeReady();
	}
</script>
<body style="padding-top:40px;padding-bottom:40px;background-color:#eee;">
    <?php $this->beginBody()?>
    <!-- 主体部分-->
    <div class="jumbotron">
      <div class="container">
      <div class="alert alert-danger" role="alert"><?=$msg?></div>
      </div>
    </div>
    <?php $this->endBody()?>
</body>
</html>
<?php $this->endPage()?>