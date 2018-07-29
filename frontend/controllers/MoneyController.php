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
use common\models\money\MoneyData;

/**
 * 基础功能->关键回复
 * @author Frain
 *
 */
class MoneyController extends BackendController
{
	public $layout = 'layout';

	/**
	 * 红包列表
	 * @return Ambigous <string, string>
	 */
	public function actionList(){
		$wid = Yii::$app->request->get('wid',0);
		$query = MoneyData::find()->where(['aid'=>Yii::$app->user->identity->id,'wid'=>$wid]);
		$countQuery = clone $query;
		$pages = new Pagination(['totalCount' => $countQuery->count(),'pageSize' => 10]);
		$models = $query->offset($pages->offset)->orderBy(['id' => SORT_DESC])->limit($pages->limit)->all();
		return $this->render('list',['model'=>$models,'wid'=>$wid,'page' => $pages]);
	}
	
	/**
	 * 创建活动
	 * @return 
	 */
	public function actionCreate(){
		$wid = Yii::$app->request->get('wid',0);
		if (Yii::$app->request->isPost){
			$model = new MoneyData(['scenario' => 'create']);
			$model->setAttributes(Yii::$app->request->post());
			$model->wid = $wid;
			$model->aid = Yii::$app->user->identity->id;
			if ($model->save()){
				$this->redirect(['money/list','wid'=>$wid]);
			}
		}
		$this->layout='datetime';
		return $this->render('create',['wid'=>$wid]);
    }
    
    public function actionUpdate(){
    	$wid = Yii::$app->request->get('wid',0);
    	$id = Yii::$app->request->get('id',0);
    	$this->layout='datetime';
    	$model = MoneyData::findOne(['aid'=>Yii::$app->user->identity->id,'wid'=>$wid,'id'=>$id]);
    	if ($model){
    		if (Yii::$app->request->isPost){
    			$model->setScenario('update');
    			$model->setAttributes(Yii::$app->request->post());
    			if ($model->save()){
    				$this->redirect(['money/list','wid'=>$wid]);
    			}
    		}
    		return $this->render('update',['model'=>$model,'wid'=>$wid]);
    	}
    	$this->redirect(['money/list','wid'=>$wid]);
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
    
    public function actionWechat(){
    	$model = Wechat::findAll(['uid'=>YII::$app->user->identity->id]);
    	return $this->render('wechat',['model'=>$model,'msg'=>'你还可以添加两个帐号！']);
    }
    
    /* public function actionUpdate(){
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
    } */
    
    public function actionLog(){
        $query = UserLog::find()->where(['aid'=>Yii::$app->user->identity->id]);
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count(),'pageSize' => 10]);
        $models = $query->offset($pages->offset)->orderBy(['id' => SORT_DESC])->limit($pages->limit)->all();
        return $this->render('log', ['model' => $models,'page' => $pages]);
    }
}
