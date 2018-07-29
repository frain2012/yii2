<?php
namespace backend\controllers;

use yii\filters\AccessControl;
use yii\web\Controller;
use Yii;

class AccountController extends BackendController
{

    public function actionHome(){
        $this->layout="user";
        $hbid = Yii::$app->user->identity->id;
        $connection=Yii::$app->db;
        $sql = "SELECT name,`desc`,url from t_business_plug tbp LEFT JOIN t_plug tp ON tbp.tpid=tp.id AND tbp.bid=$hbid ORDER BY tbp.tpid ASC ";
        $data=$connection->createCommand($sql)->queryAll();
        return $this->render("home",['model'=>$data]);
    }

    public function actionSwitch()
    {
        return $this->render("switch");
    }

    public function actionBiz(){
        $this->layout="user";
        return $this->render("biz");
    }

    public  function actionPassword(){
        $this->layout="user";
        return $this->render("passwd");
    }
}

