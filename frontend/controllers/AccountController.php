<?php
namespace frontend\controllers;

use Yii;
use yii\data\Pagination;
use common\models\User;
use common\models\user\Wechat;
use common\models\UserLog;
use crazyfd\qiniu\Qiniu;
use common\util\WechatUtil;
use yii\web\UploadedFile;
use common\models\wxpay\WxPay;

/**
 * 帐号管理
 * @author Frain
 *
 */
class AccountController extends BackendController
{
	public $layout = 'layout';

	/**
	 * 微信管理后台入口
	 * @return string
	 */
	public function actionMain(){
		return $this->renderPartial('/index');
	}
	
   /*  public function actionIndex()
    {
    	$model = User::findOne(YII::$app->user->identity->id);
    	$request = YII::$app->request;
    	if($request->isPost){
    		$parames = $request->post('userForm');
    		$model->nickname = $parames['nickname'];
    		$model->telephone = $parames['telephone'];
    		$model->qq = $parames['qq'];
    		$model->email = $parames['email'];
    		$model->save();
    		return $this->refresh();
    	}else{
    		return $this->render('index',['model'=>$model]);
    	}
    } */
    
	/**
	 * 修改密码
	 */
	public function actionPasswd(){
		$request = YII::$app->request;
		if($request->isPost){
			$model = User::findOne(YII::$app->user->identity->id);
			$param = $request->post('userForm');
			if (($param['newpasswd']==$param['repasswd']) && ($model->validatePassword($param['oldpasswd']))){
				$model->setPassword($param['repasswd']);
				if ($model->save()){
					return $this->redirect(['wechat']);
				}
				Yii::$app->session->setFlash('alerts', '密码修改失败');
				return $this->refresh();
			}
			Yii::$app->session->setFlash('alerts', '旧密码不对或两次密码不一样');
			return $this->refresh();
		}else{
			return $this->render('passwd');
		}
	}
	
	/**
	 * 查看微信绑定列表
	 * @return Ambigous <string, string>
	 */
    public function actionWechat(){
    	$model = Wechat::findAll(['uid'=>YII::$app->user->identity->id]);
    	return $this->render('wechat',['model'=>$model,'msg'=>'你还可以添加两个帐号！']);
    }
    
    /**
     * 绑定微信号
     */
    public function actionCreate(){
        if ($num = Wechat::findOne(['uid'=>YII::$app->user->identity->id])){
            return $this->redirect(['account/wechat']);
        }
        $model = new Wechat;
        $model->setScenario('create');
        if ($model->load(Yii::$app->request->post())) {
        	
            $image = UploadedFile::getInstance($model, 'head');
            $ext = $image->getExtension();
            $imageName = time().rand(1000,9999).'.'.$ext;
            $baseurl =  dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'web'.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR;
            $imgFile = $baseurl.$imageName;
            
            $image->saveAs($imgFile);
            
            $qiniu = new Qiniu(YII::$app->params['qiuniu']['accesskey'], YII::$app->params['qiuniu']['secretkey'], YII::$app->params['qiuniu']['qurl'], YII::$app->params['qiuniu']['bucket']);
            $key = WechatUtil::getUUID();
            $qiniu->uploadFile($imgFile,$key);
            $url = $qiniu->getLink($key);
            unlink($imgFile);
            
            $model->domain = '123.m.yyy.com';
            $mp = WechatUtil::createWechatUrl();
            $model->head = $url;
            $model->mpurl = $mp['url'];
            $model->mptoken = $mp['token'];
            $model->uid = YII::$app->user->identity->id;
            if ($model->save()){
                return $this->redirect(['account/wechat']);
            }
        }
        $this->layout = "upload";
        return $this->render('create');
    }
    
    /**
     * 编辑微信号
     * @return
     */
    public function actionUpdate(){
        if ($wechat = Wechat::findOne(['uid'=>YII::$app->user->identity->id])){
            $wechat->setScenario('update');
            if ($wechat->load(Yii::$app->request->post())){
                if (!empty($_FILES['Wechat']['name']['head'])){
                    $image = UploadedFile::getInstance($wechat, 'head');
                    $ext = $image->getExtension();
                    $imageName = time().rand(1000,9999).'.'.$ext;
                    $baseurl =  dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'web'.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR;
                    $imgFile = $baseurl.$imageName;
                    $image->saveAs($imgFile);
                    
                    $qiniu = new Qiniu(YII::$app->params['qiuniu']['accesskey'], YII::$app->params['qiuniu']['secretkey'], YII::$app->params['qiuniu']['qurl'], YII::$app->params['qiuniu']['bucket']);
                    $key = WechatUtil::getUUID();
                    $qiniu->uploadFile($imgFile,$key);
                    $url = $qiniu->getLink($key);
                    unlink($imgFile);
                    $wechat->head = $url;
                }
                if ($wechat->save()){
                    return $this->redirect(['wechat']);
                }
            }
            return $this->render('update',['model'=>$wechat]);
        }
        return $this->redirect(['wechat']);
    }
    
    /**
     * 微信支付配置
     */
    public function actionWxpay(){
    	$wxpay = WxPay::findOne(['aid'=>YII::$app->user->identity->id]);
    	$request = YII::$app->request;
    	if ($wxpay === null){
    		if($request->isPost){
    			$wxpay = new WxPay(['scenario' => 'create']);
    			if ($wxpay->load(Yii::$app->request->post())){
    				$wxpay->aid = YII::$app->user->identity->id;
    				$baseurl =  dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR.'common'.DIRECTORY_SEPARATOR.'cert'.DIRECTORY_SEPARATOR;
    				if (!empty($_FILES['WxPay']['name']['rootca'])){
    					$image = UploadedFile::getInstance($wxpay, 'rootca');
	    				$ext = $image->getExtension();
	    				$imageName = time().'.'.$ext;
	    				$imgFile = $baseurl.$imageName;
	    				$image->saveAs($imgFile);
	    				$wxpay->rootca = $imageName;
    				}
    				if (!empty($_FILES['WxPay']['name']['apiclientkey'])){
    					$image = UploadedFile::getInstance($wxpay, 'apiclientkey');
    					$ext = $image->getExtension();
    					$imageName = time().'.'.$ext;
    					$imgFile = $baseurl.$imageName;
    					$image->saveAs($imgFile);
    					$wxpay->apiclientkey = $imageName;
    				}
    				if (!empty($_FILES['WxPay']['name']['apiclientcert'])){
    					$image = UploadedFile::getInstance($wxpay, 'apiclientcert');
    					$ext = $image->getExtension();
    					$imageName = time().'.'.$ext;
    					$imgFile = $baseurl.$imageName;
    					$image->saveAs($imgFile);
    					$wxpay->apiclientcert = $imageName;
    				}
    				if ($wxpay->save()){
    					return $this->redirect(['account/wxpay']);
    				}
    			}
    		}
    		return $this->render('wxpayc');
    	}else {
    		if($request->isPost){
    			$wxpay->setScenario('update');
    			if ($wxpay->load(Yii::$app->request->post())){
    				$wxpay->aid = YII::$app->user->identity->id;
    				$baseurl =  dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR.'common'.DIRECTORY_SEPARATOR.'cert'.DIRECTORY_SEPARATOR;
    				if (!empty($_FILES['WxPay']['name']['rootca'])){
    					$image = UploadedFile::getInstance($wxpay, 'rootca');
    					$ext = $image->getExtension();
    					$imageName = time().'.'.$ext;
    					$imgFile = $baseurl.$imageName;
    					$image->saveAs($imgFile);
    					$wxpay->rootca = $imageName;
    				}
    				if (!empty($_FILES['WxPay']['name']['apiclientkey'])){
    					$image = UploadedFile::getInstance($wxpay, 'apiclientkey');
    					$ext = $image->getExtension();
    					$imageName = time().'.'.$ext;
    					$imgFile = $baseurl.$imageName;
    					$image->saveAs($imgFile);
    					$wxpay->apiclientkey = $imageName;
    				}
    				if (!empty($_FILES['WxPay']['name']['apiclientcert'])){
    					$image = UploadedFile::getInstance($wxpay, 'apiclientcert');
    					$ext = $image->getExtension();
    					$imageName = time().'.'.$ext;
    					$imgFile = $baseurl.$imageName;
    					$image->saveAs($imgFile);
    					$wxpay->apiclientcert = $imageName;
    				}
    				if ($wxpay->save()){
    					return $this->redirect(['account/wxpay']);
    				}
    			}
    		}
    		return $this->render('wxpayu',['model'=>$wxpay]);
    	}
    }
    
    
    /**
     * 操作日志文件
     * @return Ambigous <string, string>
     */
    public function actionLog(){
        $query = UserLog::find()->where(['aid'=>Yii::$app->user->identity->id]);
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count(),'pageSize' => 10]);
        $models = $query->offset($pages->offset)->orderBy(['id' => SORT_DESC])->limit($pages->limit)->all();
        return $this->render('log', ['model' => $models,'page' => $pages]);
    }
}
