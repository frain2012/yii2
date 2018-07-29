<?php
use yii\helpers\Html;

/**
 * 主要的应用
 * @var \yii\web\View $this
 * @var string $content
 */
?>
<?php $this->beginPage()?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
<meta charset="<?= Yii::$app->charset ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= Html::encode($this->title) ?></title>
    <?php $this->head()?>
    <link href="/css/bootstrap.css" rel="stylesheet">
    <!--[if lt IE 9]>
      <script src="//cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
    <?php $this->beginBody()?>
    <!-- 导航 -->
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button class="navbar-toggle collapsed" type="button" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand hidden-sm" href="javascript:void(0);">虎虎生威</a>
        </div>
        <div class="navbar-collapse collapse" role="navigation">
          <ul class="nav navbar-nav">
            <li class="hidden-sm hidden-md"><a href="javascipt:void(0);">首页</a></li>
            <li><a href="javascipt:void(0);">案例</a></li>
            <li><a href="javascipt:void(0);">功能简介</a></li>
            <li><a href="javascipt:void(0);">帮助</a></li>
            <li><a href="javascipt:void(0);">关于</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right hidden-sm">
          	<?php if (Yii::$app->user->isGuest) {
                echo '<li><a href="/site/login">登录</a></li><li><a href="javascipt:void(0);">注册</a></li>';
            } else {
                echo '<li><a href="/account/main">'.Yii::$app->user->identity->username.'</a></li><li><a href="/site/logout">退出</a></li>';
            }?>
          </ul>
        </div>
      </div>
    </nav>
    <!-- banner -->
    <!-- body -->
    <?= $content ?>
    <!-- footer -->
    <footer class="footer">
      <div class="container">
        <div class="row footer-top">
          <div class="col-sm-6 col-lg-6">
            <h4>
              <!--  img src="" -->
            </h4>
            <p>本网站由<a href="/">虎虎生威</a>发布。</p>
          </div>
          <div class="col-sm-6  col-lg-5 col-lg-offset-1">
            <div class="row about">
              <div class="col-xs-3">
                <h4>关于</h4>
                <ul class="list-unstyled">
                  <li><a href="javascript:void(0);">关于我们</a></li>
                  <li><a href="javascript:void(0);">广告合作</a></li>
                  <li><a href="javascript:void(0);">友情链接</a></li>
                  <li><a href="javascript:void(0);">招聘</a></li>
                </ul>
              </div>
              <div class="col-xs-3">
                <h4>联系方式</h4>
                <ul class="list-unstyled">
                  <li><a href="javascript:void(0);" title="">新浪微博</a></li>
                  <li><a href="javascript:void(0);" title="">微信</a></li>
                  <li><a href="mailto:admin@huoz.cn">电子邮件</a></li>
                </ul>
              </div>
              <div class="col-xs-3">
                <h4>案例</h4>
                <ul class="list-unstyled">
                  <li><a href="javascript:void(0);" target="_blank">案例</a></li>
                </ul>
              </div>
            </div>
    
          </div>
        </div>
        <hr>
        <div class="row footer-bottom">
          <ul class="list-inline text-center">
            <li><a href="http://www.miibeian.gov.cn/" target="_blank">皖ICP备11008151号</a></li><li>皖公网安备11010802014853</li>
          </ul>
        </div>
      </div>
    </footer>
    <?php $this->endBody()?>
    <?=Html::jsFile('@web/js/jquery.js')?>
	<?=Html::jsFile('@web/js/bootstrap.js')?>
</body>
</html>
<?php $this->endPage()?>