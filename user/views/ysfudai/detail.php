<?php
use yii\helpers\Html;
use yii\helpers\Url;
/**
 *
 * @var \yii\web\View $this
 * @var string $content
 */
$this->title = $detail->name;
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

    <!--index.wxml-->
    <div>
        <div class="goods-photo-swipe"><img src="<?=$detail->headimg;?>"><!--640*320--></div>
        <div class="detail-event-wrap">
            <div class="detail-event-price">
                <div class="price-current">免费</div>

                <div class="price-origin">
                    <span class="price-origin-data">¥<?=$detail->awardprize;?></span>
                    <span class="price-origin-label">剩<?=($detail->awardnum-$num);?>份</span>
                    <span class="price-origin-label">已换<?=$num;?>份</span>
                </div>
            </div>

            <div class="detail-event-timer">
                <div class="timer-desc" id="timer-d">离结束仅剩</div>
                <div class="timer-data">
                    <span class="data" id="days"></span><span class="data" id="hour"></span>:<span class="data" id="min"></span>:<span class="data" id="sec"></span>
                </div>
            </div>
        </div>
        <div id="app">

        </div>

        <template id="template">
            <div>
                <div class="section">
                    <div class="section-inner">
                        <div class="detail-goods text-center">
                            <div class="goods-name"><span><?=$detail->name;?></span></div>
                        </div>

                        <template v-if="u_item_data.is_sign == 0">
                            <div class="luckybag-box">
                                <template v-for="(item,index) in user_key_data">
                                    <div class="luckybag" v-if="item.num>0">
                                        <span>{{item.key}}</span>
                                        <span class="luckybag-badage">{{item.num}}</span>
                                    </div>

                                    <div class="luckybag disable" v-if="item.num<=0">
                                        <span>{{item.key}}</span>
                                    </div>
                                </template>
                            </div>
                        </template>
                        <!--可正常抽奖（展示福袋）-->
                        <template v-if="u_item_data.is_finished != 1">
                            <div class="flex">
                                <template v-if="user_num>0">
                                    <a class="flex-item btn btn-danger btn-streaker" @click="chou()">我要抽奖(剩{{user_num}}次)</a>
                                </template>

                                <template v-if="user_num <= 0">
                                    <div class="flex-item btn btn-disable" v-else>我要抽奖(剩0次)</div>
                                </template>

                                <div class="flex-item btn btn-danger-stroke gap-left-small" @click="showHelp()">求助好友</div>
                            </div>
                        </template>


                        <!--中奖登记信息（展示福袋）-->
                        <template v-if="u_item_data.is_finished == 1 &amp;&amp; u_item_data.is_sign == 0">
                            <div class="text-center">
                                <icon type="success" size="40" color="#3b3">
                                    <div>恭喜你成功集齐福袋</div>
                                    <div class="gap-bottom text-size-small text-color-slight">快去登记信息兑换奖品哦</div>
                                </icon></div>
                            <div class="btn-block btn btn-danger" @click="toSign()">立即登记信息</div>
                        </template>



                        <template v-if="u_item_data.is_finished == 1 &amp;&amp; u_item_data.is_sign == 1 &amp;&amp; u_item_data.buy_status == 1">

                            <!--中奖详情物流奖品（不展示福袋和活动规则）-->
                            <div class="list-item">
                                <div class="text-center">
                                    <span class="code">恭喜您已中奖</span>
                                    <div class="text-size-small text-color-gray">我们将尽快把奖品给您</div>
                                </div>
                            </div>
                            <div class="list-item">
                                <div class="flex text-size-small gap-bottom">
                                    <div class="text-color-gray">奖品名称</div>
                                    <div class="flex-item gap-left-small"><?=$detail->awardname;?></div>
                                </div>
                            </div>
                            <div class="list-item text-center">
                                <div class="btn btn-default" @click="show_info()">个人信息</div>
                            </div>

                        </template>


                    </div>
                </div>

                <?php if($detail->showrule){?>
                <div class="section">
                    <div class="section-title"><div class="section-title-text">活动规则<span class="section-title-desc">Event Rule</span></div></div>
                    <div class="section-inner">
                        <div class="list-item">
                            <div>✦您每天均有<?=$detail->daynum;?>次抽奖机会；</div>
                            <div>✦每天分享到微信群或朋友圈，好友点击您发的页面，均可获得1次抽奖机会(每天最多<?=$detail->sharenum;?>次)；</div>
                        </div>
                    </div>
                </div>
                <?php }?>

                <div class="section">
                    <div class="section-title"><div class="section-title-text">中奖名单<span class="section-title-desc">WINNER</span></div></div>
                    <div class="section-inner">
                        <template v-for="(item,index) in winner_list">
                            <div class="list-item list-small flex flex-center">
                                <div class="avatar">
                                    <img :src="item.headimgurl">
                                </div>
                                <div class="flex-item gap-left-small">
                                    <span><span class="text-color-danger">{{item.nickname}}</span> 集齐了福袋，获得奖品</span>
                                    <div class="text-color-slight text-size-small">{{item.finishtime}}</div>
                                </div>
                            </div>
                        </template>

                        <div class="list-item text-center" v-if="w_has_more">
                            <a class="btn btn-default btn-small" @click="loadWinner()">查看更多</a>
                        </div>
                    </div>
                </div>

                <div class="section">
                    <div class="section-title"><div class="section-title-text">活动详情<span class="section-title-desc">Prize Detail</span></div></div>
                    <div class="section-inner">
                        <div class="list-item">
                            <div>
                                <?=$detail->content;?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mask" :style="&quot;display:&quot;+(info_show || shop_show || goSign_show || chou_show || failed_show || sign_show?&quot;block;&quot;:&quot;none;&quot;)"></div>

                <template v-if="u_item_data.is_finished == 0">
                    <!-- 还未中奖结果 -->
                    <div class="pop" :style="&quot;display:&quot;+(chou_show == true?&quot;block;&quot;:&quot;none;&quot;)"><!-- 抽中展示结果 -->
                        <template v-if="user_num >=0 &amp;&amp; time_out == 0">
                            <template v-if="!chou_loading">
                                <div class="pop-header">恭喜你抽中了福袋</div>
                                <div class="pop-body">
                                    <div class="text-center luckybag-result">
                                        <div class="luckybag-scene"></div>
                                        <div class="luckybag">
                                            <span>{{chou_word}}</span>
                                        </div>
                                        <div class="luckybag-scene"></div>
                                        <div class="text-size-small text-color-slight">集到一个福袋【{{chou_word}}】</div>
                                    </div>
                                </div>
                            </template>

                            <template v-if="chou_loading &amp;&amp; time_out == 0">
                                <div class="pop-header">正在抽奖</div>
                                <div class="pop-body">
                                    <div class="text-center luckybag-result">
                                        <div class="luckybag-scene"></div>
                                        <div class="luckybag happy"></div>
                                        <div class="luckybag-scene"></div>
                                        <div class="text-size-small text-color-slight">请稍候</div>
                                    </div>
                                </div>
                            </template>

                            <div class="pop-footer">
                                <button class="btn btn-danger btn-streaker" @click="chou()">再次抽奖(剩{{user_num}}次)</button>
                            </div>
                        </template>

                        <template v-if="user_num <= 0 &amp;&amp; time_out == 1">
                            <div class="pop-header">抽奖次数用完了</div>
                            <div class="pop-body">
                                <div class="text-center luckybag-result">
                                    <div class="luckybag-scene"></div>
                                    <div class="luckybag sad"></div>
                                    <div class="luckybag-scene"></div>
                                    <div class="text-size-small text-color-slight">求助好友可获得更多抽奖机会哦</div>
                                </div>
                            </div>
                            <div class="pop-footer">
                                <div class="btn btn-danger" @click="showHelp()">求助好友</div>
                            </div>
                        </template>
                        <div class="pop-close" @click="closePop()"><div class="icon icon-close"></div></div>
                    </div>
                </template>

                <template v-if="u_item_data.is_finished == 1 &amp;&amp; u_item_data.is_sign == 0">
                    <div class="pop" :style="&quot;display:&quot;+(goSign_show == true?&quot;block;&quot;:&quot;none;&quot;)"><!-- 集齐福袋 -->
                        <div class="pop-header">恭喜你，成功集齐福袋</div>
                        <div class="pop-body">
                            <div class="text-center luckybag-result">
                                <div class="luckybag-scene"></div>
                                <div class="luckybag happy"></div>
                                <div class="luckybag-scene"></div>
                                <div class="text-size-small text-color-slight">快去登记信息兑换奖品哦</div>
                            </div>
                        </div>
                        <div class="pop-footer">
                            <div class="btn btn-danger" @click="toSign()">立即登记信息</div>
                        </div>
                        <div class="pop-close" @click="closePop()"><div class="icon icon-close"></div></div>
                    </div>
                </template>

                <div class="pop" style="display:"><!-- 求助好友 -->
                    <div class="pop-header">求助好友</div>
                    <div class="pop-body">
                        <div class="text-center luckybag-result">
                            <div class="luckybag-scene"></div>
                            <div class="luckybag happy">
                            </div>
                            <div class="luckybag-scene"></div>
                            <div class="text-size-small text-color-slight">求助好友可获得更多抽奖机会哦</div>
                        </div>
                    </div>
                    <div class="pop-footer">
                        <div class="btn btn-success">直接求助</div>
                        <div class="btn btn-danger">生成海报</div>
                    </div>
                    <div class="pop-close"><div class="icon icon-close"></div></div>
                </div>


                <div class="pop" :style="&quot;display:&quot;+(failed_show == true?&quot;block;&quot;:&quot;none;&quot;)"><!-- 抽奖次数用完了 -->
                    <div class="pop-header">失败啦</div>
                    <div class="pop-body">
                        <div class="text-center luckybag-result">
                            <div class="luckybag-scene"></div>
                            <div class="luckybag sad"></div>
                            <div class="luckybag-scene"></div>
                            <div class="text-size-small text-color-slight">{{err_msg}}</div>
                        </div>
                    </div>
                    <div class="pop-footer">
                        <div class="btn btn-danger" @click="closePop()">知道了</div>
                    </div>
                    <div class="pop-close" @click="closePop()"><div class="icon icon-close"></div></div>
                </div>


                <div class="pop" :style="&quot;display:&quot;+(sign_show?&quot;block;&quot;:&quot;none;&quot;)"><!-- 个人信息 -->
                    <div class="pop-header">登记信息查看兑奖码</div>
                    <div class="pop-body">
                        <div class="list-item flex flex-center">
                            <div class="form-label">姓名</div>
                            <input class="flex-item" type="text" id="name" value="" placeholder="请输入姓名" confirm-type="next">
                        </div>
                        <div class="list-item flex flex-center">
                            <div class="form-label">手机号</div>
                            <input class="flex-item" type="number" id="tel" value="" placeholder="请输入联系电话" confirm-type="next">
                        </div>

                        <div class="list-item flex flex-center">
                            <div class="form-label">地址</div>
                            <input class="flex-item" type="text" id="address" value="" placeholder="请填写地址" confirm-type="done">
                        </div>
                    </div>
                    <div class="pop-footer">
                        <div class="btn btn-danger" @click="signUp()">提交</div>
                    </div>
                    <div class="pop-close" @click="closePop()"><div class="icon icon-close"></div></div>
                </div>

                <div class="pop" :style="&quot;display:&quot;+(shop_show?&quot;block;&quot;:&quot;none;&quot;)"><!-- 可用门店 -->
                    <div class="pop-header">共{{shop_list.length}}个可用门店</div>
                    <div class="pop-body">
                        <template v-for="(item,index) in shop_list">
                            <div class="list-item">
                                {{item.name}}
                                <div class="flex text-size-small">
                                    <div class="text-color-gray">地址</div>
                                    <div class="flex-item gap-left-small">{{item.address}}</div>
                                </div>
                                <div class="flex text-size-small">
                                    <div class="text-color-gray">电话</div>
                                    <div class="flex-item gap-left-small">{{item.tel}}</div>
                                    <a class="btn btn-small btn-default" :href="'tel:'+item.tel">拨打电话</a>
                                </div>
                            </div>
                        </template>
                    </div>
                    <div class="pop-footer">
                        <div class="btn btn-danger btn-block" @click="closePop()">知道了</div>
                    </div>
                </div>
                <div class="pop" :style="&quot;display:&quot;+(info_show?&quot;block;&quot;:&quot;none;&quot;)"><!-- 个人信息 -->
                    <div class="pop-header">个人信息</div>
                    <div class="pop-body">
                        <div class="list-item flex flex-center">
                            <div class="form-label">姓名</div>
                            <span class="flex-item">{{u_item_data.name}}</span>
                        </div>
                        <div class="list-item flex flex-center">
                            <div class="form-label">手机号</div>
                            <span class="flex-item">{{u_item_data.tel}}</span>
                        </div>

                        <div class="list-item flex flex-center">
                            <div class="form-label">地址</div>
                            <span class="flex-item">{{u_item_data.address}}</span>
                        </div>
                    </div>
                    <div class="pop-footer">
                        <div class="btn btn-danger" @click="closePop()">知道了</div>
                    </div>
                </div>

                <div class="loader-wrap" v-if="u_data_key_loading || u_data_item_loading">
                    <div class="loader"></div>
                    <div class="loader-text">加载中…</div>
                </div>
            </div>

        </template>
    </div>


    <div class="copyright">
        <div class="gap-bottom">
            <div class="gap-bottom-xsmall"><?=$model->telpeople;?>：<?=$model->telphone;?></div>
            <a class="btn btn-default btn-small" href="tel:<?=$model->telphone;?>">点击拨号</a>
        </div>
        <span>虎虎生威技术支持</span>
    </div>
    <div class="fixmenu">
        <a class="fixmenu-item" href="/ysfudai?bid=<?=$bid;?>">
            <div class="fixmenu-item-text">我的首页</div>
        </a>
    </div>

    <?php if(!is_null($detail->focustype) && $detail->focustype==1){?>
        <div class="fixfoucs">
            <a class="fixfoucs-item" href="/ysfudai/fouces?bid=<?=$bid;?>&yid=<?=$yid;?>">
                <div class="fixfoucs-item-text">增加<?=$detail->focusnum;?>抽奖机会</div>
            </a>
        </div>
    <?php }?>


    <?=Html::jsFile('@web/js/zepto.min.js')?>
    <?=Html::jsFile('@web/js/touch.js')?>
    <?=Html::jsFile('@web/js/fx.js')?>
    <?=Html::jsFile('@web/js/fx_methods.js')?>
    <script type="text/javascript">
        var timer;
        function hideToast()
        {
            if($('.pop[hdtrole~="toast"]').length>0) $('.pop[hdtrole~="toast"]').fadeOut(function(){$(this).remove()});
            clearTimeout(timer);
        }
        function toggleToast(tip,option) //tip:string,||option:{autohide:boolean,||delay:number(s),||loading:boolean}
        {
            hideToast();
            option=$.extend({},{autohide:true,delay:3,loading:false},option);
            option.delay=option.delay*1000;
            if(option.loading) tip='<span class="icon icon-load" hdtrole="loading"></span><span class="text-slight">'+tip+'</span>';
            $('body').append('<div class="pop" hdtrole="toast"></div>');
            $('.pop[hdtrole~="toast"]').html(tip).fadeIn().tap(function(){hideToast();return false;})
            if(option.autohide) timer=setTimeout('hideToast()',option.delay);
        }
        function showTime($end_time)
        {
            $now = new Date();
            $now_timestamp = parseInt($now.getTime()/1000);
            $t = $end_time - $now_timestamp;
            if($t<=0){
                $('#timer-d').html('已结束');
                $('#days').html('0天');
                $('#hour').html('0');
                $('#min').html('0');
                $('#sec').html('0');
                return;
            }
            $d = parseInt($t / (24*3600));
            $t = $t % (24*3600);
            $H = parseInt($t / 3600);
            $t = $t % 3600;
            $i = parseInt($t / 60);
            $s = $t % 60;
            if($d>0){
                $('#days').html($d+"天");
            }else{
                $('#days').html();
                $('#days').hide();
            }
            $('#hour').html($H<10?('0'+$H):$H);
            $('#min').html($i<10?('0'+$i):$i);
            $('#sec').html($s<10?('0'+$s):$s);
        }
        window.setInterval(function(){showTime(<?=strtotime($detail->end);?>);}, 1000);

        function isTel(tel){
            var mobile = /^(((13[0-9]{1})|(14[0-9]{1})|(19[0-9]{1})|(16[0-9]{1})|(17[0-9]{1})|(15[0-9]{1})|(18[0-9]{1}))+\d{8})$/;
            if(tel.length==11 && mobile.test(tel)){
                return true;
            }else{
                return false;
            }
        }
    </script>
    <?=Html::jsFile('@web/js/vue2.2.6.min.js')?>
    <script>
        var app = new Vue({
            el: '#app',
            template: '#template',
            data: {
                u_item_data:{"left_num":"0","address":null,"buy_status":"0","code":null,"code_qr":null,"code_status":0,"finish_time":null,"fudai_id":"187","item_encrypt":"0bb63e54737a","is_finished":"0","name":null,"tel":null,"is_sign":"0","use_shop":null,"use_time":null},
                user_key_data:[],
                u_data_key_loading:false,
                u_data_item_loading:false,
                user_num:'*',
                chou_loading:false,
                chou_word:'',
                chou_show:false,
                time_out:0,
                sign_show:false,
                goSign_show:false,
                shop_show:false,
                info_show:false,
                failed_show:false,
                shop_list:[],
                winner_list:[],
                w_page:0,
                isEnd:false,
                w_has_more:false,
                yid:'<?=$yid?>',
                bid:'<?=$bid?>',
                err_msg:''
            },
            mounted:function(){
                this.loadUserKeyData();
                this.loadWinner();
                //this.loadUserItemData();
            },
            methods:{
                loadUserKeyData:function(){
                    var vm = this;
                    if(vm.u_data_key_loading){
                        return false;
                    }
                    vm.$set(vm,'u_data_key_loading',true);
                    $.ajax({
                        url: '/ysfudai/data',
                        type: 'get',
                        dataType: 'json',
                        data: {
                            key:'userdata',
                            yid:vm.yid,
                            bid:vm.bid
                        },
                        success: function(data) {
                            vm.$set(vm,'u_data_key_loading',false);
                            if(data.status == 1){
                                vm.$set(vm,'user_key_data',data.data);
                                vm.$set(vm,'user_num',data.userNum);
                            }
                        }
                    });
                },
                show_info:function(){
                    var vm = this;
                    vm.$set(vm,"info_show",true);
                },
                loadWinner:function(is_first){
                    var vm = this;
                    var _page = vm.w_page;
                    if(is_first){
                        _page = 1;
                    }else{
                        _page += 1;
                        vm.$set(vm,'u_data_key_loading',true);
                    }
                    $.ajax({
                        url: '/ysfudai/data',
                        type: 'get',
                        dataType: 'json',
                        data: {
                            page:_page,
                            key:'LoadWinner',
                            yid:vm.yid,
                            bid:vm.bid
                        },
                        success: function(data) {
                            var winner_list = vm.winner_list;
                            if(data.data){
                                for(var i=0;i<data.data.length;i++){
                                    winner_list.push(data.data[i]);
                                }
                            }
                            vm.$set(vm,'w_page',_page);
                            vm.$set(vm,'w_has_more',data.isMore);
                            vm.$set(vm,'winner_list',winner_list);
                            vm.$set(vm,'u_data_key_loading',false);
                        }
                    });
                },
                signUp:function(){
                    var vm = this;
                    var name = $("#name").val();
                    var address = $("#address").val();
                    var tel = $("#tel").val();
                    if(name == ''||!name){
                        toggleToast('姓名不能为空！');
                        return false;
                    }
                    if(!isTel(tel)){
                        toggleToast('电话号码格式有误！');
                        return false;
                    }

                    if(address == ''||!address){
                        toggleToast('地址不能为空！');
                        return false;
                    }

                    $.ajax({
                        url: '/ysfudai/data',
                        type: 'post',
                        dataType: 'json',
                        data: {
                            key:'signUp',
                            yid:vm.yid,
                            bid:vm.bid,
                            name:name,
                            tel:tel,
                            address:address
                        },
                        success: function(data) {
                            toggleToast(data.msg);
                            if(data.status){
                                location.href = location.href;
                                return false;
                            }
                        }
                    });
                },
                loadUserItemData:function(){
                    var vm = this;
                    if(vm.u_data_item_loading){
                        return false;
                    }
                    vm.$set(vm,'u_data_item_loading',true);
                    $.ajax({
                        url: '/ysfudai/data',
                        type: 'get',
                        dataType: 'json',
                        data: {
                            key:'GetUserItem',
                            yid:vm.yid,
                            bid:vm.bid,
                        },
                        success: function(data) {
                            vm.$set(vm,'u_data_item_loading',false);
                            if(data.code == 0){
                                vm.$set(vm,'u_item_data',data.u_item_data);
                            }
                        }
                    });
                },
                closePop:function(){
                    var vm = this;
                    vm.$set(vm,'chou_show',false);
                    vm.$set(vm,'sign_show',false);
                    vm.$set(vm,'shop_show',false);
                    vm.$set(vm,'goSign_show',false);
                    vm.$set(vm,'info_show',false);
                    vm.$set(vm,'failed_show',false)
                },
                showHelp:function() {
                    toggleToast('戳右上角，你懂的~');
                    return false;
                },
                toSign:function(){
                    var vm = this;
                    vm.$set(vm,'goSign_show',false);
                    vm.$set(vm,'sign_show',true);
                },
                chou:function(){
                    var vm = this;
                    if(vm.chou_loading){
                        return false;
                    }
                    vm.$set(vm,'chou_loading',true);
                    vm.$set(vm,'chou_show',true);

                    $.ajax({
                        url: '/ysfudai/data',
                        type: 'post',
                        dataType: 'json',
                        data: {
                            key:'chou',
                            yid:vm.yid,
                            bid:vm.bid
                        },
                        success: function(data) {
                            vm.$set(vm,'chou_loading',false);
                            if(data.status){
                                vm.$set(vm,'chou_word',data.key);
                                vm.$set(vm,'user_num',data.num);
                                vm.$set(vm,'user_key_data',data.data);
                                vm.$set(vm,'u_item_data',data.item_data);
                                if(vm.u_item_data.is_finished == 1){
                                    //vm.$set(vm,'goSign_show',true);
                                    if(vm.u_item_data.buy_status!=1){
                                        vm.$set(vm,'goSign_show',true);
                                    }
                                }
                                //info_show || shop_show || goSign_show || chou_show || failed_show || sign_show
                            }else{
                                vm.err_msg = data.msg;
                                vm.$set(vm,'time_out',data.time_out);
                                vm.$set(vm,'failed_show',true);
                            }
                            return false;
                        }
                    });
                    return false;
                }
            }
        });
    </script>

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
    <div style="display: none;">
        <?php if(!empty($detail->statcode)){ echo $detail->statcode;};?>
    </div>
    <?php $this->endBody()?>
</body>
</html>
<?php $this->endPage()?>