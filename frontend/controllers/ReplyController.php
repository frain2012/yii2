<?php
namespace frontend\controllers;

use Yii;
use yii\data\Pagination;
use crazyfd\qiniu\Qiniu;
use common\helpers\Util;
use yii\web\UploadedFile;
use common\models\base\BaseReply;

/**
 * 微信帐号管理
 * @author Frain
 *
 */
class ReplyController extends BackendController
{
    public $layout = 'layout';
    
    public function actionSubscribe(){
    	$wid = Yii::$app->request->get('wid',0);
    	$uid = Yii::$app->user->identity->id;
    	if(YII::$app->request->isPost){
    		$letev = BaseReply::findOne(['uid'=>Yii::$app->user->identity->id,'wid'=>$wid,'module'=>"subscribe"]);
    		$type = Yii::$app->request->post('type',0);
    		$content = Yii::$app->request->post('content','');
    		if($letev == null){
    			$letev = new BaseReply();
    			$letev->uid = $uid;
    			$letev->wid = $wid;
    		}
    		$letev->setScenario("subscribe");
    		$letev->type = $type;
    		$letev->content = $content;
    		$letev->module='subscribe';
    		$letev->ishide=1;
    		$letev->save();
    	}
    	$model = BaseReply::findOne(['uid'=>Yii::$app->user->identity->id,'wid'=>$wid,'module'=>"subscribe"]);
    	return $this->render('subscribe',['model'=>$model]);
    }
    
    public function actionKeywordlist() {
    	$wid = Yii::$app->request->get('wid',0);
    	$uid = Yii::$app->user->identity->id;
    	$query = BaseReply::find()->where(['uid'=>$uid,'wid'=>$wid,'ishide'=>0]);
    	$countQuery = clone $query;
    	$pages = new Pagination(['totalCount' => $countQuery->count(),'pageSize' => 10]);
    	$models = $query->offset($pages->offset)->orderBy(['id' => SORT_DESC])->limit($pages->limit)->all();
    	return $this->render('list',['model'=>$models,'wid'=>$wid,'page' => $pages]);
    }
    
    public function actionCreate(){
    	$wid = Yii::$app->request->get('wid',0);
    	$uid = Yii::$app->user->identity->id;
    	if(YII::$app->request->isPost){
    		$letev = BaseReply::findOne(['uid'=>Yii::$app->user->identity->id,'wid'=>$wid,'module'=>"subscribe"]);
    		$type = Yii::$app->request->post('type',0);
    		$content = Yii::$app->request->post('content','');
    		if($letev == null){
    			$letev = new BaseReply();
    			$letev->uid = $uid;
    			$letev->wid = $wid;
    		}
    		$letev->setScenario("subscribe");
    		$letev->type = $type;
    		$letev->content = $content;
//     		$letev->module='subscribe';
    		$letev->save();
    	}
    	$model = BaseReply::findOne(['uid'=>Yii::$app->user->identity->id,'wid'=>$wid,'module'=>"subscribe"]);
    	return $this->render('create',['model'=>$model]);
    }
    
    public function actionUpdate(){
        /* if ($wechat = Wechat::findOne(['uid'=>YII::$app->user->identity->id])){
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
                    $key = time();
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
        return $this->redirect(['wechat']); */
    }
}
