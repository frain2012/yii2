<?php
namespace user\controllers;

use common\models\base\TBusinessDomain;
use common\models\base\TBusinessQrcode;
use common\models\base\TBusinessShare;
use common\models\fudai\TPlugFudaiList;
use common\models\fudai\TPlugFudaiUser;
use common\models\fudai\TPlugFudaiUserDetail;
use Yii;
use common\models\fudai\TPlugFudaiBase;
use common\models\fudai\TYsfudaiUser;
use common\models\fudai\TPlugFudaiDetail;
use yii\web\Response;
use common\util\WechatUtil;
use yii\web\Controller;
use yii\data\Pagination;

/**
 * Site controller
 */
class YsfudaiController extends Controller
{
    private $pref="haodian_";
	public function actionError() {
        $exception = Yii::$app->errorHandler->exception;
        if ($exception !== null) {
            return $this->renderPartial('error', ['exception' => $exception]);
        }
    }

    public  function checkSeesion($bid=0){
        $session = Yii::$app->session;
        $key = $this->pref.$bid;
        if(empty($session->get($key))){
            return WechatUtil::wechatSnsapi_userinfo();
        }
        return null;
    }

    public  function setSeesion($bid=0,$openid){
        $session = Yii::$app->session;
        $key = $this->pref.$bid;
        if(empty($session->get($key))){
            $session->set($key,$openid);
        }else{
            $str = $session->get($key);
            if($str != $openid){
                $session->remove($key);
                $session->set($key,$openid);
            }
        }
    }

    public function getSession($bid=0){
        $session = Yii::$app->session;
        $key = $this->pref.$bid;
        return $session->get($key);
    }

    public function actionClear(){
        $bid = Yii::$app->request->get("bid");
        $session = Yii::$app->session;
        $key = $this->pref.$bid;
        $session->remove($key);
    }

    public function actionIndex()
    {
        $biz_id = Yii::$app->request->get("bid");
        if(empty($biz_id)){
            return;
        }
        //授权
        $code = Yii::$app->request->get("code");
        if(empty($code)){
            $url = $this->checkSeesion($biz_id);
            if(!empty($url)){
                return $this->redirect($url);
            }
        }else{
            $domain = TBusinessDomain::findOne(['bid'=>$biz_id,'status'=>0]);
            if(empty($domain)){
                return 'domain unset';
            }
            $nowDomain = trim($_SERVER['HTTP_HOST']);
            if(trim($domain->domain)!=$nowDomain){
                return $this->redirect(WechatUtil::getUrl($domain->domain));
            }
            $json = WechatUtil::wechatAuthorizeCode($code);
            if($json){
                $num = TYsfudaiUser::find()->where(['openid'=>$json['openid']])->count();
                if($num<1){
                    $data=WechatUtil::userinfo($json['openid'],$json['access_token']);
                    $dto = new TYsfudaiUser();
                    $dto->openid=$data['openid'];
                    $dto->nickname=$data['nickname'];
                    $dto->sex=$data['sex'].'';
                    $dto->province=$data['province'];
                    $dto->city=$data['city'];
                    $dto->country=$data['country'];
                    $dto->headimgurl=$data['headimgurl'];
                    $dto->save();
                }else{
                    echo "user info fail";
                    return;
                }
                $this->setSeesion($biz_id,$json['openid']);
            }
        }
        //分享配置
        $model = TPlugFudaiBase::findOne(['bid'=>$biz_id]);
        if(empty($model)){
            return;
        }
        //分享签名
        $share = TBusinessShare::findOne(['bid'=>$biz_id]);
        $array['appid']=$share->appid;
        $array['appsecret']=$share->appsecret;
        //分享链接
        $sdomain = TBusinessDomain::findOne(['bid'=>$biz_id,'status'=>99]);
        $shareLink = '';
        if($sdomain){
            $shareLink=WechatUtil::getUrl($sdomain->domain);
        }
        $c_share = WechatUtil::getTicketSignature($array,$shareLink);
        $list = TPlugFudaiDetail::find()->select(['id','headimg','name','awardprize','awardnum'])->where(['bid'=>$biz_id])->andWhere(['stauts'=>1])->asArray()->all();
        foreach ($list as &$item){
            $num = TPlugFudaiUser::find()->where(['yid'=>$item['id'],'bid'=>$biz_id])->andWhere(['>','status',1])->count();
            $item['num']=number_format(($num/$item['awardnum'])*100,2);
        }
        return $this->renderPartial('index',['share'=>$share,'c_share'=>$c_share,'model'=>$model,'list'=>$list,'bid'=>$biz_id]);
    }

    public function  actionDetail(){
        $bid = Yii::$app->request->get("bid");
        $yid = Yii::$app->request->get("yid");
        if (empty($bid) || empty($yid)){
            return;
        }
        //授权
        $code = Yii::$app->request->get("code");
        if(empty($code)){
            $url = $this->checkSeesion($bid);
            if(!empty($url)){
                return $this->redirect($url);
            }
        }else{
            $domain = TBusinessDomain::findOne(['bid'=>$bid,'status'=>0]);
            if(empty($domain)){
                return 'domain unset';
            }
            $nowDomain = trim($_SERVER['HTTP_HOST']);
            if(trim($domain->domain)!=$nowDomain){
                return $this->redirect(WechatUtil::getUrl($domain->domain));
            }
            $json = WechatUtil::wechatAuthorizeCode($code);
            if($json){
                $num = TYsfudaiUser::find()->where(['openid'=>$json['openid']])->count();
                if($num<1){
                    $data=WechatUtil::userinfo($json['openid'],$json['access_token']);
                    if($data){
                        $dto = new TYsfudaiUser();
                        $dto->openid=$data['openid'];
                        $dto->nickname=$data['nickname'];
                        $dto->sex=$data['sex'].'';
                        $dto->province=$data['province'];
                        $dto->city=$data['city'];
                        $dto->country=$data['country'];
                        $dto->headimgurl=$data['headimgurl'];
                        $dto->save();
                    }else{
                        echo "user info fail";
                        return;
                    }
                }
                $this->setSeesion($bid,$json['openid']);
            }
        }

//        $this->setSeesion($bid,'121212');

        $share = TBusinessShare::findOne(['bid'=>$bid]);
        $array['appid']=$share->appid;
        $array['appsecret']=$share->appsecret;

        //分享链接
        $sdomain = TBusinessDomain::findOne(['bid'=>$bid,'status'=>99]);
        $shareLink='';
        if($sdomain){
            $shareLink=WechatUtil::getUrl($sdomain->domain);
        }
        $c_share = WechatUtil::getTicketSignature($array,$shareLink);
        $model = TPlugFudaiBase::findOne(['bid'=>$bid]);
        if(empty($model)){
            return;
        }
        $detail = TPlugFudaiDetail::find()->select(['name','awardprize','awardnum','end','headimg','content','awardname','showrule','daynum','sharenum','statcode','focustype','focusnum'])->where(['bid'=>$bid,'id'=>$yid])->one();
        $openid = $this->getSession($bid);
        $user =TPlugFudaiUser::find()->select(['day','id'])->where(['bid'=>$bid,'openid'=>$openid,'yid'=>$yid])->one();
        if($user){
            if($user->day !=date("Ymd")){
                TPlugFudaiUser::updateAll(['num'=>$detail->daynum,'day'=>date("Ymd")],['id'=>$user->id]);
            }
        }else{
            $dto = new TPlugFudaiUser();
            $duser = TYsfudaiUser::find()->select(['headimgurl','nickname'])->where(['openid'=>$openid])->one();
            $dto->scenario='add';
            $dto->bid=$bid;
            $dto->yid=$yid;
            $dto->openid=$openid;
            $dto->nickname=$duser->nickname;
            $dto->headimgurl=$duser->headimgurl;
            $dto->createtime=date('Y-m-d H:i:s');
            $dto->num=$detail->daynum;
            $dto->day=date("Ymd");
            $dto->status=1;
            if(!$dto->save()) {
                echo 'save info fail';
                return;
            }
        }
        $num = TPlugFudaiUser::find()->where(['bid'=>$bid,'yid'=>$yid])->andWhere(['>','status',1])->count();
        return $this->renderPartial('detail',['model'=>$model,'c_share'=>$c_share,'detail'=>$detail,'bid'=>$bid,'yid'=>$yid,'num'=>$num]);
    }

    public function  actionFouces(){
        $bid = Yii::$app->request->get("bid");
        $yid = Yii::$app->request->get("yid");
        $en = Yii::$app->request->get("en");
        if (empty($bid) || empty($yid)){
            return;
        }
        $num = TPlugFudaiList::find()->where(['bid'=>$bid,'yid'=>$yid])->count();
        if($num<1){
            echo 'uset';
            return;
        }
        $tdaya = mt_rand(0,$num-1);
        $fdata = TPlugFudaiList::find()->where(['bid'=>$bid,'yid'=>$yid])->offset($tdaya)->limit(1)->all();
        $qrcode = TBusinessQrcode::find()->select(['qrcode'])->where(['id'=>$fdata[0]->fid])->one();
        /*if($qrcode){
            $detail = TPlugFudaiDetail::find()->select(['focustype','focusnum'])->where(['bid'=>$bid,'id'=>$yid])->one();
            if($detail->focustype==1){
                $openid = $this->getSession($bid);
                $user =TPlugFudaiUser::find()->select(['foucetime','id','num'])->where(['bid'=>$bid,'openid'=>$openid,'yid'=>$yid])->one();
                if($user){
                    if($user->foucetime!=date("Ymd")){
                        TPlugFudaiUser::updateAll(['num'=>$detail->focusnum+$user->num,'foucetime'=>date("Ymd")],['id'=>$user->id]);
                    }
                }
            }
        }*/
        return $this->renderPartial('fouces',['qrcode'=>$qrcode,'name'=>'增加机会']);
    }

    public function  actionChance(){
        $bid = Yii::$app->request->get("bid");
        $fid = Yii::$app->request->get("fid");
        $en = Yii::$app->request->get("en");
        if (empty($bid) || empty($yid) || empty($en)){
            return;
        }
        /*$fdata = TPlugFudaiList::find()->where(['bid'=>$bid,'yid'=>$yid])->offset($tdaya)->limit(1)->all();
        $qrcode = TBusinessQrcode::find()->select(['qrcode'])->where(['id'=>$fdata[0]->fid])->one();
        if($qrcode){
            $detail = TPlugFudaiDetail::find()->select(['focustype','focusnum'])->where(['bid'=>$bid,'id'=>$yid])->one();
            if($detail->focustype==1){
                $openid = $this->getSession($bid);
                $user =TPlugFudaiUser::find()->select(['foucetime','id','num'])->where(['bid'=>$bid,'openid'=>$openid,'yid'=>$yid])->one();
                if($user){
                    if($user->foucetime!=date("Ymd")){
                        TPlugFudaiUser::updateAll(['num'=>$detail->focusnum+$user->num,'foucetime'=>date("Ymd")],['id'=>$user->id]);
                    }
                }
            }
        }*/
        echo '增加机会成功！';
        return;
    }

    public function actionData(){
        if(Yii::$app->request->isGet) {
            $bid = Yii::$app->request->get("bid");
            $yid = Yii::$app->request->get("yid");
            $key = Yii::$app->request->get("key",'');
            Yii::$app->response->format = Response::FORMAT_JSON;
            $openid = $this->getSession($bid);
            if(empty($openid)){
                return ['status'=>0,'msg'=>'用户不存在'];
            }
            switch ($key){
                case 'userdata':
                    $user =TPlugFudaiUser::find()->select(['id','bid','day','num','status'])->where(['bid'=>$bid,'openid'=>$openid,'yid'=>$yid])->one();
                    if(empty($user)){
                        return ['status'=>0,'msg'=>'查询用户失败'];
                    }
                    $data = TPlugFudaiDetail::find()->select(['fudaitype','key1','key2','key3','key4','key5','key6','key7','key8','daynum'])->where(['bid'=>$bid,'id'=>$yid])->one();
                    $userNum = 0;
                    if($user->day==date("Ymd")){
                        $userNum =$user->num;
                    }else{
                        $userNum =$data->daynum;
                    }
                    $array = Array();
                    $array[]=Array('key'=>$data->key1,'num'=>0);
                    $array[]=Array('key'=>$data->key2,'num'=>0);
                    $array[]=Array('key'=>$data->key3,'num'=>0);
                    $array[]=Array('key'=>$data->key4,'num'=>0);
                    if ($data['fudaitype']==2){
                        $array[]=Array('key'=>$data->key5,'num'=>0);
                        $array[]=Array('key'=>$data->key6,'num'=>0);
                        $array[]=Array('key'=>$data->key7,'num'=>0);
                        $array[]=Array('key'=>$data->key8,'num'=>0);
                    }
                    $sql = "SELECT `key`,COUNT(*) as num FROM t_plug_ysfudai_user_detail WHERE tpid=".$user->id." GROUP BY `key`";
                    $command = Yii::$app->db->createCommand($sql);
                    $result = $command->queryAll();
                    if(empty($result)){
                        return ['status'=>1,'msg'=>'查询成功','data'=>$array,'userNum'=>$userNum];
                    }
                    foreach($result as $item){
                        foreach ($array as &$value){
                            if($item['key']==$value['key']){
                                $value['num']=$item['num'];
                                break;
                            }
                        }
                    }
                    return ['status'=>1,'msg'=>'查询成功','data'=>$array,'userNum'=>$userNum];
                case 'LoadWinner':
                    $page = Yii::$app->request->get("page",0);
                    $model = TPlugFudaiUser::find()->select(['nickname','headimgurl','finishtime'])->where(['bid'=>$bid,'yid'=>$yid])->andWhere(['>','status',1]);
                    $pages = new Pagination(['totalCount' =>$model->count(), 'pageSize' =>10]);
                    $data = $model->offset($pages->offset)->limit($pages->limit)->all();
                    $isMore = $pages->getPageCount()>$page;
                    return ['status'=>1,'msg'=>'查询成功','data'=>$data,'isMore'=>$isMore];
                default:
                    return ['status'=>0,'msg'=>'查询失败'];
            }
        }else{
            $bid = Yii::$app->request->post("bid");
            $yid = Yii::$app->request->post("yid");
            $key = Yii::$app->request->post("key",'');
            Yii::$app->response->format = Response::FORMAT_JSON;
            $openid = $this->getSession($bid);
            if(empty($openid)){
                return ['status'=>0,'msg'=>'用户不存在'];
            }
            switch ($key){
                case 'chou':
                    $user =TPlugFudaiUser::find()->select(['id','bid','day','num','status','realname','realtel','realadd'])->where(['bid'=>$bid,'yid'=>$yid,'openid'=>$openid])->one();
                    if(empty($user)){
                        return ['status'=>0,'msg'=>'查询用户失败'];
                    }
                    if($user->num<=0){
                        return ['status'=>0,'msg'=>'用户没有抽奖机会'];
                    }
                    if($user->status>1){
                        $user_data['is_finished']=1;
                        $user_data['is_sign']=$user->status==3?1:0;
                        $user_data['buy_status']=$user->status>2?1:0;
                        $user_data['name']=$user->realname;
                        $user_data['tel']=$user->realtel;
                        $user_data['address']=$user->realadd;
                        return ['status'=>1,'msg'=>'抽奖成功','item_data'=>$user_data];
                    }
                    //查询活动
                    $detail = TPlugFudaiDetail::find()->select(['fudaitype','key1','key2','key3','key4','key5','key6','key7','key8','sendnum','end'])->where(['bid'=>$bid,'id'=>$yid])->one();
                    if(empty($detail)){
                        return ['status'=>0,'msg'=>'查询活动失败'];
                    }
                    if(time()>=strtotime($detail->end)){
                        return ['status'=>0,'msg'=>'查询已结束'];
                    }
                    $nowNum = TPlugFudaiUser::find()->where(['bid'=>$bid,'yid'=>$yid])->andWhere(['>','status',1])->count();
                    if($nowNum>=$detail->sendnum){
                        return ['status'=>0,'msg'=>'亲，您来晚了。已没有奖品了'];
                    }
                    $array = Array();
                    $array[]=Array('key'=>$detail->key1,'num'=>0);
                    $array[]=Array('key'=>$detail->key2,'num'=>0);
                    $array[]=Array('key'=>$detail->key3,'num'=>0);
                    $array[]=Array('key'=>$detail->key4,'num'=>0);
                    if ($detail->fudaitype==2){
                        $array[]=Array('key'=>$detail->key5,'num'=>0);
                        $array[]=Array('key'=>$detail->key6,'num'=>0);
                        $array[]=Array('key'=>$detail->key7,'num'=>0);
                        $array[]=Array('key'=>$detail->key8,'num'=>0);
                    }
                    //查询用户抽奖
                    $sql = "SELECT `key`,COUNT(*) as num FROM t_plug_ysfudai_user_detail WHERE tpid=".$user->id." GROUP BY `key`";
                    $command = Yii::$app->db->createCommand($sql);
                    $result = $command->queryAll();
                    if(!empty($result)){
                        foreach($result as $item){
                            foreach ($array as &$value){
                                if($item['key']==$value['key']){
                                    $value['num']=$item['num'];
                                    break;
                                }
                            }
                        }
                    }
                    $unAward = Array();
                    $Award = Array();
                    $i=0;
                    foreach ($array as $item){
                        if($item['num']==0){
                            $unAward[]=$i;
                        }else{
                            $Award[]=$i;
                        }
                        ++$i;
                    }
                    $index=null;
                    $is_finished=null;
                    $usize  = count($unAward);
                    if($usize>1){
                        $tindex=mt_rand(0,count($unAward)-1);
                        $index =$unAward[$tindex];
                        $is_finished=0;
                    }else if($usize==1){
                        //总抽奖数
                        $total = TPlugFudaiUserDetail::find()->where(['bid'=>$bid,'yid'=>$yid])->count();
                        //奖品数   $detail->sendnum
                        if($total>0 && ($total < ($detail->sendnum*$nowNum) ) ){
                            $tindex=mt_rand(0,count($unAward)-1);
                            $index =$unAward[$tindex];
                            $is_finished=1;
                        }else{
                            $tindex=mt_rand(0,count($Award)-1);
                            $index =$Award[$tindex];
                            $is_finished=0;
                        }
                    }else{
                        $is_finished=1;
                    }
                    if(is_null($index)){
                        return ['status'=>0,'msg'=>'抽奖失败'];
                    }
                    $array[$index]['num']=$array[$index]['num']+1;
                    $addData = new  TPlugFudaiUserDetail();
                    $addData->bid=$bid;
                    $addData->yid=$yid;
                    $addData->tpid=$user->id;
                    $addData->key=$array[$index]['key'];
                    $addData->createtime=date('Y-m-d H:i:s');
                    $addData->day=date('Ymd');
                    if($addData->save()){
                        if($is_finished==1){
                            TPlugFudaiUser::updateAll(['num'=>$user->num-1,'status'=>2,'finishtime'=>date('Y-m-d H:i:s')],['id'=>$user->id]);
                        }else{
                            TPlugFudaiUser::updateAll(['num'=>$user->num-1],['id'=>$user->id]);
                        }
                        $user_data['is_finished']=$is_finished;
                        $user_data['is_sign']=0;
                        $user_data['buy_status']=0;
                        return ['status'=>1,'msg'=>'抽奖成功','data'=>$array,'key'=>$array[$index]['key'],'num'=>$user->num-1,'item_data'=>$user_data];
                    }
                    return ['status'=>0,'msg'=>'抽奖失败'];
                case 'signUp':
                    $user =TPlugFudaiUser::find()->select(['id','bid','status'])->where(['bid'=>$bid,'openid'=>'121212'])->one();
                    if(empty($user)){
                        return ['status'=>0,'msg'=>'查询用户失败'];
                    }
                    if($user->status<2){
                        return ['status'=>0,'msg'=>'您还未集齐'];
                    }
                    if($user->status>2){
                        return ['status'=>0,'msg'=>'您已提交成功'];
                    }
                    $name = Yii::$app->request->post("name");
                    $tel = Yii::$app->request->post("tel");
                    $address = Yii::$app->request->post("address");
                    $user->realname=$name;
                    $user->realtel=$tel;
                    $user->realadd=$address;
                    $user->status=3;
                    $user->scenario='addr';
                    if($user->save()){
                        return ['status'=>1,'msg'=>'您已登记成功，请耐心等待'];
                    }
                    return ['status'=>0,'msg'=>'您登记失败'];
                default:
                    return ['status'=>0,'msg'=>'查询失败'];
            }
        }
    }

}
