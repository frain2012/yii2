<?php
namespace api\controllers;

use Yii;
use yii\web\Controller;
use common\wechat\WechatEngine;

/**
 * 消息统url
 * @author Frain
 *
 */
class ApiController extends Controller
{
    public function actionError()
    {
        $exception = \Yii::$app->errorHandler->exception;
        if ($exception !== null) {
            return date('Y-m-d H:i:s');
//             return $this->renderPartial('error', ['exception' => $exception]);
        }
    }
    
    public function actionIndex()
    {
        $wechatEngine = new WechatEngine();
        $wechatEngine->handle();
    }
}
