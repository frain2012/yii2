<?php
use yii\helpers\Html;
?>
<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">公众号海报链接 <small>用户从公众号入口进活动页面即可参与</small></h4>
        </div>
        <div class="modal-body">
            <div class="alert alert-danger">
                <span class="glyphicon glyphicon-info-sign"></span> 自动回复和自定义菜单是用户关注公众号后参与活动的唯一入口，请在您的公众号至少配置一项
            </div>

            <!--<div>
                <h4 class="text-success"><span class="glyphicon glyphicon-link"></span> 活动推广链接 <small class="text-muted">如需在群发文章等地方推广活动，请使用以下网页地址：</small></h4>
                <div class="well">
                    <code class="text-muted">http://abc.meiyuhouse.com/t/ysfudai?biz_id=9481&en=bee5a6a620</code>
                    <div class="btn-group btn-group-xs well-action" role="group">
                        <button type="button" class="btn btn-default copy-me" data-clip="http://abc.meiyuhouse.com/t/ysfudai?biz_id=9481&en=bee5a6a620"><span class="glyphicon glyphicon-copy"></span> 复制推广链接</button>
                    </div>
                </div>
            </div>-->

            <?php if(!is_null($data)){ foreach ($data as $item){ ?>
            <h4 class="text-success"><span class="glyphicon glyphicon-link"></span><?=$item['name'];?> <small class="text-muted">复制以下内容到微信公众号后台 -&gt; 自定义菜单(菜单内容为跳转网页)的页面地址：</small></h4>
            <div class="well">
                <div style="display: none;">
                    <p>复制以下内容到微信公众号后台 -&gt; 自动回复 -&gt; 被添加自动回复：<small class="text-danger">(请勿修改&lt;a href=""&gt;中的内容)</small></p>
                    <code class="text-muted reply-code" id="reply-code">欢迎关注，&lt;a href="http://abc.meiyuhouse.com/t/ysfudai?biz_id=9481&en=bee5a6a620&mp_en=89ed349bee"&gt;戳我砍价&lt;/a&gt;</code>
                    <div class="btn-group btn-group-xs well-action" role="group">
                        <button type="button" class="btn btn-default copy-me" data-target="#reply-code"><span class="glyphicon glyphicon-copy"></span> 复制回复内容</button>
                    </div>
                </div>

                <code class="text-muted">http://<?=$domain->domain?>/ysfudai/chance?bid=<?=$item['bid'];?>&fid=<?=$item['fid'];?>&en=<?=substr(md5($item['bid'].$item['fid']),0,6);?></code>
                <div class="btn-group btn-group-xs well-action" role="group">
                    <button type="button" class="btn btn-default copy-me" data-clip="http://abc.meiyuhouse.com/t/ysfudai?biz_id=9481&en=bee5a6a620&mp_en=89ed349bee"><span class="glyphicon glyphicon-copy"></span> 复制菜单链接</button>
                </div>
            </div>
            <?php } }?>
        </div>
    </div>
</div><!--end .modal-content-->

<script src="//cdn.bootcss.com/zeroclipboard/2.2.0/ZeroClipboard.min.js"></script>
<script type="text/javascript">
    var client = new ZeroClipboard( $('.copy-me') );
    client.on( 'ready', function(event) {
        client.on( 'copy', function(event) {
            var target = $(event.target).data('target');
            if(target) {
                var text = $(target).text();
            } else {
                var text = $(event.target).data('clip');
            }
            event.clipboardData.setData('text/plain', text);
        } );

        client.on( 'aftercopy', function(event) {
            alert('已复制到剪贴板')
        } );
    } );

    client.on( 'error', function(event) {
        ZeroClipboard.destroy();
    } );

    function downloadPoster(fudai_id,mp_id){
        $.ajax({
            url: '/v2/plug/fudai',
            type: 'post',
            dataType: 'json',
            data: {
                fudai_id:fudai_id,
                mp_id:mp_id
            },
            success: function(data) {
                console.log(data);
                if(data.status){
                    //location.href = data.mp_poster;
                    window.open(data.mp_poster);
                }else{
                    alert(data.msg);
                }
                return false;
            }
        });
    }
</script>
