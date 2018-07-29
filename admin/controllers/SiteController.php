<?php
namespace admin\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use common\models\admin\AdminForm;
use common\models\admin\Admin;


class SiteController extends Controller
{
    
    public function behaviors()
	{
		return [
		'access' => [
			'class' => AccessControl::className(),
			'rules' => [
				[
					'allow'   => true,
					'actions' => ['login','error'],
				],
				[
					'allow' => true,
					'roles' => ['@'],
				],
				[
					'actions' => ['logout'],
					'allow'   => true,
					'roles'   => ['?'],
				],
			],
		  ]
		];
	}

    
    public function actionError() {
        echo 'error';
    }

    public function actionIndex()
    {
        return $this->renderPartial('index');
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new AdminForm(['scenario' => 'login']);
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $this->layout = 'login';
            return $this->render('login');
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }
}
