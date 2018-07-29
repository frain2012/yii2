<?php
use yii\helpers\Html;
use yii\helpers\Url;
/**
 *
 * @var \yii\web\View $this
 * @var string $content
 */
$this->title = $model->name;
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
        <img class="section-pop-photo" src="<?=$model->headimg;?>">
    </div>

    <div class="section">
        <div class="tab-multi">
            <div class="tab" style="position: static; text-align: center;">
                <a class="tab-item tab-item-active" href="javascript:;"><span>福利</span></a>
                <!--<a class="tab-item tab-item-active" href="/t/ysfudai/subject?biz_id=>&sub_id=17"><span>福利</span></a>-->
            </div>
        </div>
    </div>


    <?php if(!is_null($list)){ foreach($list as $item){?>
        <div class="section">
            <a class="list-item list-goods-groupbuy" href="/ysfudai/detail?bid=<?=$bid;?>&yid=<?=$item['id'];?>">
                <div class="list-photo">
                    <img src="<?=$item['headimg'];?>">
                    <span class="list-photo-label">马上抢</span>
                </div>
                <div class="list-detail">
                    <div class="goods-name"><span><?=$item['name'];?></span></div>
                    <div class="flex">
                        <div class="goods-price flex-item">
                            <span class="goods-price-current">免费</span>
                            <span class="goods-price-origin">¥<?=number_format($item['awardprize'],2);?></span>
                        </div>
                        <div class="text-right">
                            <span class="btn btn-danger btn-small">马上抢</span>
                            <div>
                                <span class="text-size-xsmall text-color-slight">已抢<?=$item['num'];?>%</span>
                                <div class="goods-process"><div class="goods-process-move" style="width:<?=$item['num'];?>%"></div></div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    <?php }}?>

    <div class="copyright">
        <div class="gap-bottom">
            <div class="gap-bottom-xsmall"><?=$model->telpeople;?>：<?=$model->telphone;?></div>
            <a class="btn btn-default btn-small" href="tel:<?=$model->telphone;?>">点击拨号</a>
        </div>
        <span>虎虎生威技术支持</span>
    </div>
    <div class="fixmenu">
        <a class="fixmenu-item" href="javascript:void(0);">
            <div class="fixmenu-item-text">我的首页</div>
        </a>
    </div>



    <?=Html::jsFile('@web/js/zepto.min.js')?>
    <?=Html::jsFile('@web/js/touch.js')?>
    <?=Html::jsFile('@web/js/fx.js')?>
    <?=Html::jsFile('@web/js/fx_methods.js')?>

    <script src="http://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>
    <script type="text/javascript">
        /**微信分享**/
        var SHARE = {
            title : "<?=$model->sharetitle;?>",
            desc : "<?=$model->sharedesc;?>",
            link : "<?=$c_share['link'];?>",
            imgUrl : "<?=$model->shareimg;?>",
        };
        wx.config({
            debug : false,
            appId : '<?=$c_share['appId'];?>',
            timestamp : '<?=$c_share['timestamp'];?>',
            nonceStr : '<?=$c_share['nonceStr']?>',
            signature : '<?=$c_share['signature']?>',
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
    <script type="text/javascript">
        $(function(){
            horizontalMove($('.tab-multi>.tab'));
        });
        /**
         * 横向拖拽滚动
         *
         * @param {$} ele:触发对象
         */
        function horizontalMove(el)
        {
            el.each(function(){
                var el_new=$(this);
                var start,
                    move,
                    max,
                    min=parseInt(el_new.css('left')),
                    inertia=10;//inertia step
                var gomin=false,//min flag
                    gomax=false,//max flag
                    moving=false,//prevent tap moving
                    valid;
                max=el_new.width()-$(window).width();
                /**/
                if(max<0)
                {
                    el_new.css({'position':'static','text-align':'center'});
                    return false
                }
                else
                {
                    el_new.css({'position':'absolute'});
                }

                el_new.on('touchstart',function()
                {
                    //start=event.touches[0].pageX-parseInt($(this).css('left'));
                    moving=false;
                    valid=undefined;
                    var touches=event.touches[0];
                    // measure start values
                    start={
                        // get initial touch coords
                        x:touches.pageX-parseInt($(this).css('left')),
                        xx:touches.pageX,
                        y:touches.pageY,
                        // store time to determine touch duration
                        time:+new Date
                    };
                }).on('touchmove',function()
                {
                    if(max<0) return false;
                    //move=event.touches[0].pageX-start.x;
                    var touches=event.touches[0];
                    move={
                        // get initial touch coords
                        x:touches.pageX-start.x,
                        xx:touches.pageX-start.xx,
                        y:touches.pageY-start.y,
                        // store time to determine touch duration
                        time:+new Date
                    };
                    if(typeof valid=='undefined')//prevent invalid moving
                    {
                        valid=!!(valid||Math.abs(move.xx)<Math.abs(move.y));
                    }
                    //if(Math.abs(move.xx)>40&&Math.abs(move.y)<20&&!!(Math.abs(move.xx)>Math.abs(move.y)))
                    if(!valid)
                    {
                        event.preventDefault();
                        moving=true;
                        if((move.x<parseInt($(this).css('left'))))
                        {
                            inertia=-Math.abs(inertia);//dir="left";
                            if(Math.abs(move.x)>max)//go max & autostop
                            {
                                move.x=-(max+(Math.abs(move.x)-max)/4);
                                gomax=true;
                            }
                        }
                        else
                        {
                            inertia=Math.abs(inertia);//dir="right";
                            if(move.x>min)//go min & autostop
                            {
                                move.x=move.x/4;
                                gomin=true;
                            }
                        }
                        $(this).animate({left:move.x},0,'ease');
                    }
                }).on('touchend',function()
                {
                    if(moving) $(this).animate({left:move.x+inertia},400,'ease');
                    if(gomin) {$(this).animate({left:min},400,'ease');gomin=false;}
                    if(gomax) {$(this).animate({left:-max},400,'ease');gomax=false;}
                });
            });
        }
    </script>
    <?php $this->endBody()?>
</body>
</html>
<?php $this->endPage()?>