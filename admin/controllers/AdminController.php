<?php
namespace admin\controllers;

use Yii;
use common\models\admin\AdminForm;
use yii\data\Pagination;
use common\models\admin\Admin;
use common\models\admin\AdminLog;
use common\models\user\User;
use common\models\user\UserForm;

use common\models\Wechat;
use common\models\Agent;
use common\models\Auth;
use common\models\City;
use yii\web\UploadedFile;
use crazyfd\qiniu\Qiniu;
use common\helpers\Util;

/**
 * Site controller
 */
class AdminController extends BackendController
{
	public $layout = 'layout';
	
	public function actionIndex()
	{
		$model = Admin::findOne(Yii::$app->user->identity->id);
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
	}
	
	/***************************代理商*****************************/
	/**
	 * 修改密码
	 */
	public function actionPasswd(){
	    $request = YII::$app->request;
	    if($request->isPost){
	        $model = Admin::findOne(Yii::$app->user->identity->id);
	        $param = $request->post('adminForm');
	        if (($param['newpasswd']==$param['repasswd']) && ($model->validatePassword($param['oldpasswd']))){
	            $model->setPassword($param['repasswd']);
	            if ($model->save()){
	                return $this->redirect(['admin/list']);
	            }
	            Yii::$app->session->addFlash('alerts', '密码修改失败');
	            return $this->refresh();
	        }
	        Yii::$app->session->addFlash('alerts', '旧密码不正确或两次密码不正确');
	        return $this->refresh();
	    }else{
	        return $this->render('passwd');
	    }
	}
	
    /**
     * 代理用户帐号管理
     * @return Ambigous <string, string>
     */
    public function actionList(){
    	$query = Admin::find()->where(['role'=>Admin::ROLE_USER]);
    	$countQuery = clone $query;
    	$pages = new Pagination(['totalCount' => $countQuery->count(),'pageSize' => 10]);
    	$models = $query->offset($pages->offset)->orderBy(['id' => SORT_DESC])->limit($pages->limit)->all();
    	return $this->render('list', ['model' => $models,'page' => $pages]);
    }
    
    public function actionCreate(){
    	$request = Yii::$app->request;
    	if($request->isPost){
    		$model = new AdminForm(['scenario' => 'create']);
	    	if ($model->load($request->post()) && $model->signup()){
	    		return $this->redirect(['list']);
	    	}
    	}
    	return $this->render('create');
    }
    
    public function actionFindusername(){
    	$request = YII::$app->request;
    	$param = $request->post('param','');
    	$response = Yii::$app->response;
    	$response->format = \yii\web\Response::FORMAT_JSON;
    	if (Admin::findByUname($param)){
    		return $response->data = ['info' => '帐号已经存在','status'=> 'x'];
    	}
    	return  $response->data = ['info' => '验证通过','status'=> 'y'];
    }
    
    public function actionDelete($id=0){
    	$model = Admin::findOne($id);
    	 if ($model === null){
    		return $this->redirect(['list']);
    	}
    	$role = YII::$app->user->identity->role;
    	if (($role < $model->role) || ($model->cid == YII::$app->user->identity->id)){
    		$model->delete();
    	}
    	return $this->redirect(['list']);
    }
    
    /***************************用户*****************************/
    
    public function actionUlist(){
    	$query = User::find();
    	$countQuery = clone $query;
    	$pages = new Pagination(['totalCount' => $countQuery->count(),'pageSize' => 10]);
    	$models = $query->offset($pages->offset)->orderBy(['id' => SORT_DESC])->limit($pages->limit)->all();
    	return $this->render('ulist', ['model' => $models,'page' => $pages]);
    }
    
    public function actionUcreate(){
    	$request = Yii::$app->request;
    	if($request->isPost){
    		$model = new UserForm(['scenario' => 'create']);
	    	if ($model->load($request->post()) && $model->signup()){
	    		return $this->redirect(['ulist']);
	    	}
    	}
    	return $this->render('ucreate');
    }
    
    public function actionFinduname(){
    	$request = YII::$app->request;
    	$param = $request->post('param','');
    	$response = Yii::$app->response;
    	$response->format = \yii\web\Response::FORMAT_JSON;
    	if (User::findByUname($param)){
    		return $response->data = ['info' => '帐号已经存在','status'=> 'x'];
    	}
    	return $response->data = ['info' => '验证通过','status'=> 'y'];
    }
    
    public function actionUdelete($id=0){
    	$model = User::findOne($id);
    	if ($model === null){
    		return $this->redirect(['ulist']);
    	}
    	$role = YII::$app->user->identity->role;
    	if (($role < $model->role) || ($model->cid == YII::$app->user->identity->id)){
    		$model->delete();
    	}
    	return $this->redirect(['ulist']);
    }
    
    
    
    public function actionAreacreate(){
        /* $request = Yii::$app->request;
    	if($request->isPost){
    		$city = new City(['scenario' => 'create']);
    		if ($city->load($request->post())){
    		    $image = UploadedFile::getInstance($city, 'qrcode');
    		    $ext = $image->getExtension();
    		    $imageName = time().rand(1000,9999).'.'.$ext;
    		    $baseurl =  dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'web'.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR;
    		    $imgFile = $baseurl.$imageName;
    		    $image->saveAs($imgFile);
    		    $qiniu = new Qiniu(YII::$app->params['qiuniu']['accesskey'], YII::$app->params['qiuniu']['secretkey'], YII::$app->params['qiuniu']['qurl'], YII::$app->params['qiuniu']['bucket']);
    		    $key = Util::getUUID();
    		    $qiniu->uploadFile($imgFile,$key);
    		    $url = $qiniu->getLink($key);
    		    unlink($imgFile);
    		    $city->qrcode = $url;
    		    if ($city->save()){
    		        return $this->redirect(['area']);
    		    }
    			return $this->render('areacreate');
    		}
    	}
    	return $this->render('areacreate'); */
    }
    
    public function actionAreadelete($id=0){
        /* $id = YII::$app->request->get('id','');
        $model = City::findOne(['id'=>$id]);
        if ($model === null){
            return $this->renderPartial('../error/msg',['msg'=>'不存在该记录']);
        }
        $model->delete();
        return $this->redirect(['area']); */
    }
    
    public function actionAreaedit($id=0){
        /* $request = Yii::$app->request;
        $id = $request->get('id','');
        $model = City::findOne(['id'=>$id]);
        if ($model === null){
            return $this->renderPartial('../error/msg',['msg'=>'不存在该记录']);
        }
        if($request->isPost){
            $model->setScenario('update');
            if ($model->load($request->post())){
                if (!empty($_FILES['City']['name']['qrcode'])){
                    $image = UploadedFile::getInstance($model, 'qrcode');
                    $ext = $image->getExtension();
                    $imageName = time().rand(1000,9999).'.'.$ext;
                    $baseurl =  dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'web'.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR;
                    $imgFile = $baseurl.$imageName;
                    $image->saveAs($imgFile);
                
                    $qiniu = new Qiniu(YII::$app->params['qiuniu']['accesskey'], YII::$app->params['qiuniu']['secretkey'], YII::$app->params['qiuniu']['qurl'], YII::$app->params['qiuniu']['bucket']);
                    $key = Util::getUUID();
                    $qiniu->uploadFile($imgFile,$key);
                    $url = $qiniu->getLink($key);
                    unlink($imgFile);
                    $model->qrcode = $url;
                }
                if ($model->validate() && $model->save()){
                    return $this->redirect(['area']);
                }
            }
            return $this->render('areaupdate',['model'=>$model]);
        }
        return $this->render('areaupdate',['model'=>$model]); */
    }
    
    public function actionEdit($id=0){
        /* $model = Admin::findOne($id);
        if ($model === null){
            return $this->redirect(['list']);
        }
        $role = YII::$app->user->identity->role;
        if (($role < $model->role) || ($model->cid == YII::$app->user->identity->id)){
            $request = Yii::$app->request;
            if (Yii::$app->request->isPost){
                $model->nickname = $request->post('nickname');
                $model->setPassword($request->post('password'));
                if ($model->save()){
                    return $this->redirect(['list']);
                }
                return $this->render('update', ['model' => $model]);
            }
            return $this->render('update', ['model' => $model]);
        } */
    }
    
    public function actionUedit($id=0){
        /* $model = User::findOne($id);
        if ($model === null){
            return $this->redirect(['ulist']);
        }
        $role = YII::$app->user->identity->role;
        if (($role < $model->role) || ($model->cid == YII::$app->user->identity->id)){
            $request = Yii::$app->request;
            if (Yii::$app->request->isPost){
                $model->nickname = $request->post('nickname');
                $model->setPassword($request->post('password'));
                if ($model->save()){
                    return $this->redirect(['ulist']);
                }
                return $this->render('uupdate', ['model' => $model]);
            }
            return $this->render('uupdate', ['model' => $model]);
        } */
    }
    
    public function actionStatus($id=0){
        /* $model = Admin::findOne($id);
        if ($model === null){
            return $this->redirect(['list']);
        }
        $role = YII::$app->user->identity->role;
        if (($role < $model->role) || ($model->cid == YII::$app->user->identity->id)){
            $model->status = ($model->status === 10) ? 0 : 10;
            $model->save();
            return $this->redirect(['list']);
        }
        return $this->redirect(['list']); */
    }
    
    public function actionUstatus($id=0){
        /* $model = User::findOne($id);
        if ($model === null){
            return $this->redirect(['ulist']);
        }
        $role = YII::$app->user->identity->role;
        if (($role < $model->role) || ($model->cid == YII::$app->user->identity->id)){
            $model->status = ($model->status === 10) ? 0 : 10;
            $model->save();
            return $this->redirect(['ulist']);
        }
        return $this->redirect(['ulist']); */
    }
    
    public function actionWechat(){
    	/* $model = Wechat::findAll(['userid'=>YII::$app->user->identity->id]);
    	return $this->render('wechat',array('model'=>$model,'msg'=>'你还可以添加两个帐号！')); */
    }
    
    public function actionLog(){
        /* $query = AdminLog::find()->where(['aid'=>Yii::$app->user->identity->id]);
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count(),'pageSize' => 10]);
        $models = $query->offset($pages->offset)->orderBy(['id' => SORT_DESC])->limit($pages->limit)->all();
        return $this->render('log', ['model' => $models,'page' => $pages]); */
    }
    
    
    /**
     * 越权登录
     * @return string
     */
    public function actionAuth($id=0){
        /* if (\Yii::$app->user->isGuest) {
            return $this->render('../error/msg', ['msg' => '您还未登录']);
        }
        $admin = Admin::findOne(['id'=>Yii::$app->user->identity->id,'status'=>Admin::STATUS_ACTIVE]);
        if ($admin === null){
            return $this->render('../error/msg', ['msg' => '您帐号存在异常']);
        }
        if (Yii::$app->user->identity->id === $id){
            return $this->render('../error/msg', ['msg' => '检测此帐号不能越权']);
        }
       $type = YII::$app->request->get('type',3);
       switch ($type){
           case 1:
               $agent = Agent::findOne(['id'=>$id,'role'=>Agent::ROLE_AGENT,'status'=>Agent::STATUS_ACTIVE]);
               if ($agent === null){
                   return $this->render('../error/msg', ['msg' => '您越权的帐号存在异常']);
               }
               if ($admin->role > $agent->role){
                   return $this->render('../error/msg', ['msg' => '您没有足够的权限越权']);
               }
               $auth = Auth::findOne(['uid'=>$agent->id,'sid'=>$agent->id]);
               if ($auth === null){
                   $auth = new  Auth();
                   $auth->uid = $admin->id;
                   $auth->sid = $agent->id;
                   $auth->type = $type;
                   $auth->token = YII::$app->security->generateRandomString(64);
                   $auth->save();
//                    $token = YII::$app->security->encryptByPassword($auth->token.'&type='.$type.'id='.$user->id,YII::$app->params['auth']['key']);
                   return $this->redirect(YII::$app->params['auth']['agent'].$auth->token);
               }else {
                   $auth->token = YII::$app->security->generateRandomString(64);
                   $auth->isuser = 0;
                   $auth->save();
//                    $token = YII::$app->security->encryptByPassword($auth->token.'&type='.$type.'id='.$user->id,YII::$app->params['auth']['key']);
                   return $this->redirect(YII::$app->params['auth']['agent'].$auth->token);
               }
           case 2:
               $user = User::findOne(['id'=>$id,'role'=>User::ROLE_USER,'status'=>User::STATUS_ACTIVE]);
               if ($user === null){
                   return $this->render('../error/msg', ['msg' => '您越权的帐号存在异常']);
               }
               if ($admin->role > $user->role){
                   return $this->render('../error/msg', ['msg' => '您没有足够的权限越权']);
               }
               $auth = Auth::findOne(['uid'=>$admin->id,'sid'=>$user->id]);
               if ($auth === null){
                   $auth = new  Auth();
                   $auth->uid = $admin->id;
                   $auth->sid = $user->id;
                   $auth->type = $type;
                   $auth->isuser = 0;
                   $auth->token = YII::$app->security->generateRandomString(64);
                   if ($auth->save()){
                       return $this->redirect(YII::$app->params['auth']['user'].$auth->token);
                   }
                   //                    $token = YII::$app->security->encryptByPassword($auth->token.'&type='.$type.'id='.$user->id,YII::$app->params['auth']['key']);
                   return $this->render('../error/msg', ['msg' => '请稍后，系统繁忙~~~']);
               }else {
                   $auth->token = YII::$app->security->generateRandomString(64);
                   $auth->isuser = 0;
                   if ($auth->save()){
                       return $this->redirect(YII::$app->params['auth']['user'].$auth->token);
                   }
                   //                    $token = YII::$app->security->encryptByPassword($auth->token.'&type='.$type.'id='.$user->id,YII::$app->params['auth']['key']);
                   return $this->render('../error/msg', ['msg' => '请稍后，系统繁忙~~~']);
               }
           default:
               return $this->render('../error/msg', ['msg' => '您越权失败']);
       } */
    }
}
