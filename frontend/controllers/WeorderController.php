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
use common\models\weorder\TWeorderStore;
use yii\web\NotFoundHttpException;
use common\models\weorder\TWeorderOrder;

/**
 * 营销推广->大转盘
 * @author Frain
 *
 */
class WeorderController extends BackendController
{
	public $layout = 'layout';

	public function actionList(){
		$wid = Yii::$app->request->get('wid',0);
		$query = TWeorderOrder::find()->where(['uid'=>Yii::$app->user->identity->id,'wid'=>$wid]);
		$countQuery = clone $query;
		$pages = new Pagination(['totalCount' => $countQuery->count(),'pageSize' => 10]);
		$models = $query->offset($pages->offset)->orderBy(['id' => SORT_DESC])->limit($pages->limit)->all();
		return $this->render('list',['model'=>$models,'wid'=>$wid,'page' => $pages]);
	}
	
	public function actionCreate(){
		$this->layout='datetime';
    	$wid = Yii::$app->request->get('wid',0);
    	if (Yii::$app->request->isPost){
    		$model = new TWeorderOrder(['scenario' => 'create']);
    		$model->setAttributes(Yii::$app->request->post());
    		$model->wid = $wid;
    		$model->uid = Yii::$app->user->identity->id;
    		if ($model->save()){
    			$this->redirect(['weorder/list','wid'=>$wid]);
    		}
    	}
    	return $this->render('create',['wid'=>$wid]);
    }
    
    
    public function actionUpdate(){
    	$this->layout='datetime';
    	$wid = Yii::$app->request->get('wid',0);
    	$id = Yii::$app->request->get('id',0);
    	$model = TWeorderOrder::findOne(['uid'=>Yii::$app->user->identity->id,'wid'=>$wid,'id'=>$id]);
    	if($model == null){
    		throw new NotFoundHttpException("记录不存在");
    	}
    	if (Yii::$app->request->isPost){
    		$model->setScenario('update');
    		$model->setAttributes(Yii::$app->request->post());
    		if ($model->save()){
    			$this->redirect(['weorder/list','wid'=>$wid]);
    		}
    	}
    	return $this->render('update',['model'=>$model,'wid'=>$wid]);
    }
	
	/****************************积分商品****************************************/
    
    public function actionStore(){
    	$wid = Yii::$app->request->get('wid',0);
    	$query = TWeorderStore::find()->where(['uid'=>Yii::$app->user->identity->id,'wid'=>$wid]);
    	$countQuery = clone $query;
    	$pages = new Pagination(['totalCount' => $countQuery->count(),'pageSize' => 10]);
    	$models = $query->offset($pages->offset)->orderBy(['id' => SORT_DESC])->limit($pages->limit)->all();
    	return $this->render('store',['model'=>$models,'wid'=>$wid,'page' => $pages]);
    }
    
    
    public function actionScreate(){
    	$wid = Yii::$app->request->get('wid',0);
    	if (Yii::$app->request->isPost){
    		$model = new TWeorderStore(['scenario' => 'create']);
    		$model->setAttributes(Yii::$app->request->post());
    		$model->wid = $wid;
    		$model->uid = Yii::$app->user->identity->id;
    		if ($model->save()){
    			$this->redirect(['weorder/store','wid'=>$wid]);
    		}
    	}
    	return $this->render('screate',['wid'=>$wid]);
    }
    
    
    public function actionSupdate(){
    	$wid = Yii::$app->request->get('wid',0);
    	$id = Yii::$app->request->get('id',0);
    	$model = TWeorderStore::findOne(['uid'=>Yii::$app->user->identity->id,'wid'=>$wid,'id'=>$id]);
    	if($model == null){
    		throw new NotFoundHttpException("记录不存在");
    	}
    	if (Yii::$app->request->isPost){
    		$model->setScenario('update');
    		$model->setAttributes(Yii::$app->request->post());
    		if ($model->save()){
    			$this->redirect(['weorder/store','wid'=>$wid]);
    		}
    	}
    	return $this->render('supdate',['model'=>$model,'wid'=>$wid]);
    }
}
