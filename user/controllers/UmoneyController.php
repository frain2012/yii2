<?php
namespace user\controllers;

use Yii;
use yii\web\Controller;
use common\models\money\MoneyData;
use common\models\money\MoneyUser;
use common\models\wxpay\WxPay;
use common\wechat\WechatPay;


class UmoneyController extends Controller
{

	public function actionTest(){
		$id = Yii::$app->request->get('wid');
		return $id;
	}
	
    public function actionIndex()
    {
    	if (YII::$app->request->isPost){
    		$openid = Yii::$app->request->post('openid');
    		$id = Yii::$app->request->get('id');
    		
    		$response = Yii::$app->response;
    		$response->format = \yii\web\Response::FORMAT_JSON;
    		if ((date('H:i') <'08:00') && (date('H:i') >'00:00')){
    			return $response->data = ['info' => '0点~8点不在发红包时间内！','status'=> 0];
    		}
    		$user = MoneyUser::find(['openid'=>$openid,'mid'=>$id]);
    		switch ($user->status){
    			case 0:
    				return $response->data = ['info' => '您访问不正确','status'=> 0];
    			case 1:
    				$money = $this->draw();
    				if ($money > 0){
    					$user->money = $money;
    					$user->status = 1;
    					$user->sendtime = date('Ymd');
    					$user->update_at = time();
    					if ($user->save()) {
    						return $response->data = ['info' => '恭喜您获得'.$user->money.'元红包','url'=>'/lantern/pay','status'=> 1];
    					}
    					return $response->data = ['info' => '系统繁忙,请稍后重试~~。','status'=> 0];
    				}else{
    					return $response->data = ['info' => '今天红包已经领完，请明天再来。','status'=> 0];
    				}
    			case 1:
    				return $response->data = ['info' => '恭喜您获得'.$user->money.'元红包','url'=>'/lantern/pay','status'=> 1];
    			case 2:
    				return $response->data = ['info' => '您已兑过奖','status'=> 0];
    		}
    	}
    	/****活动开始*****/
    	$request = Yii::$app->request;
    	$id = $request->get('id');
    	$openid = $request->get('openid');
    	if (!is_numeric($id) || empty($openid)){
    		return $this->renderPartial('tip', ['msg' => '参数非法']);
    	}
    	$money = MoneyData::findOne(['id'=>$id]);
    	if($money === null){
    		return $this->renderPartial('tip', ['msg' => '活动不存在']);
    	}
    	$wxPay = WxPay::findOne(['aid'=>$money->aid]);
    	if ($wxPay === null){
    		return $this->renderPartial('tip', ['msg' => '微信支付未配置']);
    	}
    	$wechatPay = new WechatPay($wxPay);
    	$date = date('Y-m-d');
    	if (($date >= $money->sdate) && ($date <= $money->edate)){
    		$time = date('H:i');
    		if (($time >= $money->stime) && ($time <= $money->etime)){
    			if ($code = $request->get('code')){
    				$moneyUser = MoneyUser::find(['openid'=>$openid,'mid'=>$money->id]);
    				if ($moneyUser === null){
    					return $this->renderPartial('tip',['msg'=>'不存在用户。']);
    				}
    				$json = $wechatPay->wechatAuthorizeCode($code);
    				if (!$json){
    					return $this->renderPartial('tip',['msg'=>'授权繁忙,请稍后重试~~']);
    				}
    				$moneyUser->wechat = $json['openid'];
    				$moneyUser->status = 1;
    				if ($moneyUser->save()){
    					return $this->renderPartial('shake',['name'=>$money->name,'openid'=>$openid]);
    				}
    				return $this->renderPartial('tip',['msg'=>'系统繁忙,请稍后重试~~']);
    			}
    			return $this->redirect($wechatPay->wechatSnsapi_userinfo());
    		}
    		return $this->renderPartial('tip', ['msg' => '时间不在此范围内']);
    	}
    	return $this->renderPartial('tip', ['msg' => '活动不在此范围内']);
    }
    
    public function actionPay(){
    	
    }
}
