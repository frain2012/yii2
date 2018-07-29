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
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no,email=no">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=0">
    <?=Html::cssFile('@web/css/ysfudai/style.css')?>
    <title><?= Html::encode($this->title) ?></title>
</head>
<body>
    <?php $this->beginBody()?>
    <div class="section-pop">
        <img class="section-pop-photo" src="<?=$qrcode->qrcode;?>">
    </div>
    <?php $this->endBody()?>
</body>
</html>
<?php $this->endPage()?>