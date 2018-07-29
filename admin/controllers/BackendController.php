<?php
namespace admin\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

/**
 * 后台总控制器
 */
class BackendController extends Controller
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
    
}
