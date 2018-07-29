<?php
use yii\helpers\Html;


$this->title =Yii::$app->user->identity->brand;;
?>
<div class="breadcrumb">
    <a href="javascript:;"><?=$this->title;?></a>
    <span class="text-muted">/</span>
    <span class="text-muted">首页</span>
</div>


<div class="app">
    <div class="app-inner">

        <div class="app-port clearfix">
            <?php if(!is_null($model)){foreach($model as $item){?>
                <a href="<?=$item['url'];?>" class="app-port-item ">
                    <h3><?=$item['name'];?></h3>
                    <p>点击进入管理</p>
                </a>
            <?php } }?>
            <!--<a href="/v2/plug/wuzk" class="app-port-item app-port-cool">
                <h3>五折卡</h3>
                <p>点击进入管理</p>
            </a>
            <a href="/v2/plug/bweshop" class="app-port-item app-port-danger">
                <h3>好店商城</h3>
                <p>点击进入管理</p>
            </a>
            <a href="/v2/plug/bargain" class="app-port-item app-port-success">
                <h3>砍价</h3>
                <p>点击进入管理</p>
            </a>
            <a href="javascript:;" class="app-port-item app-port-warn">
                <h3>黑卡</h3>
                <p>点击进入管理</p>
            </a>
            <a href="/v2/plug/fudai" class="app-port-item ">
                <h3>集福袋</h3>
                <p>点击进入管理</p>
            </a>-->
        </div>
        <div class="para-title">
            <h3>近期通知</h3>
            <p class="para-title-side text-muted"></a></p>
        </div>

        <ul class="article-list">
            <li class="article-list-item">
                <a href="https://pan.baidu.com/s/1eR1HN7k" target="_blank">
                    <h4>秒杀抢购活动运营方案（含思维转换、商户洽谈、活动玩法）</h4>
                    <span class="article-list-info">2017年10月17日 11:03:21</span>
                </a>
            </li>
            <li class="article-list-item">
                <a href="https://tcquan.kf5.com/hc/kb/article/1077862/" target="_blank">
                    <h4>同城圈收款升级通告</h4>
                    <span class="article-list-info">2017年08月24日 17:20:41</span>
                </a>
            </li>

        </ul>
    </div><!--end .app-inner-->
</div><!--end .app-->
