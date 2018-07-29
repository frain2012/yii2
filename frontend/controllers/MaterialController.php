<?php
namespace frontend\controllers;

use Yii;
use yii\data\Pagination;
use crazyfd\qiniu\Qiniu;
use common\helpers\Util;
use yii\web\UploadedFile;
use common\models\base\BaseMaterial;
use yii\web\NotFoundHttpException;

/**
 * 图文素材管理
 * @author Frain
 *
 */
class MaterialController extends BackendController
{
    public $layout = 'layout';
    
    
    public function actionList(){
    	$wid = Yii::$app->request->get('wid',0);
    	$uid = Yii::$app->user->identity->id;
    	$query = BaseMaterial::find()->where(['uid'=>$uid,'wid'=>$wid]);
    	$countQuery = clone $query;
    	$pages = new Pagination(['totalCount' => $countQuery->count(),'pageSize' => 10]);
    	$models = $query->offset($pages->offset)->orderBy(['id' => SORT_DESC])->limit($pages->limit)->all();
    	return $this->render('list',['model'=>$models,'wid'=>$wid,'page' => $pages]);
    }
    
    public function actionCreate() {
    	$wid = Yii::$app->request->get('wid',0);
    	$uid = Yii::$app->user->identity->id;
    	if(YII::$app->request->isPost){
    		$model = new BaseMaterial;
    		$model->setScenario('create');
    		$model->uid = $uid;
    		$model->wid = $wid;
    		if ($model->load(Yii::$app->request->post())) {
    			$image = UploadedFile::getInstance($model, 'image');
    			$ext = $image->getExtension();
    			$imageName = time().rand(1000,9999).'.'.$ext;
    			$baseurl =  dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'web'.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR;
    			$imgFile = $baseurl.$imageName;
    			$image->saveAs($imgFile);
    			
//     			$qiniu = new Qiniu(YII::$app->params['qiuniu']['accesskey'], YII::$app->params['qiuniu']['secretkey'], YII::$app->params['qiuniu']['qurl'], YII::$app->params['qiuniu']['bucket']);
//     			$key = time();
//     			$qiniu->uploadFile($imgFile,$key);
//     			$url = $qiniu->getLink($key);
//     			unlink($imgFile);
    			$model->image = $imageName;
    			if ($model->save()){
    				return $this->redirect(['material/list?wid='.$wid]);
    			}
    		}
    	}
        return $this->render('create',['wid'=>$wid]);
    }
    
    
    public function actionUpdate(){
    	$id = Yii::$app->request->get('id',0);
    	$wid = Yii::$app->request->get('wid',0);
    	$uid = Yii::$app->user->identity->id;
    	$material =  BaseMaterial::findOne(['uid'=>$uid,'wid'=>$wid,'id'=>$id]);
    	if($material === null){
    		throw new NotFoundHttpException("不存在该记录");
    	}
    	if(YII::$app->request->isPost){
    		$material->setScenario("update");
    		if ($material->load(Yii::$app->request->post())){
    			if (!empty($_FILES['BaseMaterial']['name']['image'])){
    				$image = UploadedFile::getInstance($material, 'image');
    				$ext = $image->getExtension();
    				$imageName = time().rand(1000,9999).'.'.$ext;
    				$baseurl =  dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'web'.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR;
    				$imgFile = $baseurl.$imageName;
    				$image->saveAs($imgFile);
    				 
    				//     			$qiniu = new Qiniu(YII::$app->params['qiuniu']['accesskey'], YII::$app->params['qiuniu']['secretkey'], YII::$app->params['qiuniu']['qurl'], YII::$app->params['qiuniu']['bucket']);
    				//     			$key = time();
    				//     			$qiniu->uploadFile($imgFile,$key);
    				//     			$url = $qiniu->getLink($key);
    				//     			unlink($imgFile);
    				$material->image = $imageName;
    			}
    			if ($material->save()){
    				return $this->redirect(['material/list?wid='.$wid]);
    			}
    		}
    	}
    	return $this->render('update',['model'=>$material]);
    }
    
    
    public function actionDelete(){
    	$id = Yii::$app->request->get('id',0);
    	$wid = Yii::$app->request->get('wid',0);
    	$uid = Yii::$app->user->identity->id;
    	$modle = BaseMaterial::findOne(['uid'=>$uid,'wid'=>$wid,'id'=>$id]);
    	if($modle !== null){
    		$modle->delete();
    		return $this->redirect(['material/list?wid='.$wid]);
    	}
    	throw new NotFoundHttpException("不存在该记录");
    }
}
