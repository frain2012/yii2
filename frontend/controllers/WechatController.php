<?php
namespace frontend\controllers;

use Yii;
use yii\data\Pagination;
use common\models\User;
use common\models\user\Wechat;
use common\models\user\UserLog;
use crazyfd\qiniu\Qiniu;
use common\helpers\Util;
use yii\web\UploadedFile;

/**
 * 微信帐号管理
 * @author Frain
 *
 */
class WechatController extends BackendController
{
    public $layout = 'layout';
    
    public function actionIndex($aid=0){
        $model = Wechat::findOne(['uid'=>YII::$app->user->identity->id,'id'=>$aid]);
        return $this->renderPartial('index',['model'=>$model]);
    }
    
    public function actionMain($aid=0) {
        $model = Wechat::findOne(['uid'=>YII::$app->user->identity->id,'id'=>$aid]);
        return $this->render('main',['model'=>$model]);
    }
    
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
            $key = time();
            $qiniu->uploadFile($imgFile,$key);
            $url = $qiniu->getLink($key);
            unlink($imgFile);
    
            $model->domain = '123.m.yyy.com';
            $mp = Util::createWechatUrl();
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
        return $this->redirect(['wechat']);
    }
    
    
    public function actionSet($aid=0){
        $model = $this->findModel($aid);
        $model->setScenario('set');
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['wechat/set/aid/'.$aid]);
        } else {
            return $this->render('set',array('model'=>$model));
        }
    }
    
    protected function findModel($aid)
    {
        if (($model = Wechat::findOne(['uid'=>YII::$app->user->identity->id,'id'=>$aid])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
