<?php
namespace backend\controllers;

use common\models\base\TBusinessDomain;
use common\models\base\TBusinessQrcode;
use common\models\fudai\TPlugFudaiBase;
use common\models\fudai\TPlugFudaiDetail;
use common\models\fudai\TPlugFudaiList;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use Yii;
use yii\data\Pagination;

class PlugController extends BackendController
{
    public $layout="fudai";

    public function actionYsfudai(){
        $hbid = Yii::$app->user->identity->id;
        if(Yii::$app->request->isPost){
            Yii::$app->response->format=Response::FORMAT_JSON;
            $id = Yii::$app->request->post('id','');
            if (empty($id)){
                return ['status'=>1,'msg'=>'参数为空'];
            }
            $action = Yii::$app->request->post('action','');
            $status = Yii::$app->request->post('status',1);
            switch ($action){
                case 'update':
                    $rs = TPlugFudaiDetail::updateAll(['stauts'=>$status],['id'=>$id]);
                    return ['status'=>0,'msg'=>'更新成功'];
                default:
                    return ['status'=>1,'msg'=>'参数错误'];
            }
        }
        $title = Yii::$app->request->get('title','');
        $model = TPlugFudaiDetail::find()->select(['id','name','start','end','stauts','bid'])->where(['bid'=>$hbid]);
        if (!empty($title)){
            $model->andWhere(['like','name',$title]);;
        }
        $status = Yii::$app->request->get('status',0);
        if ($status>0){
            $model->andWhere(['=','stauts',$status]);
        }
        $pages = new Pagination(['totalCount' =>$model->count(), 'pageSize' =>1]);
        $data = $model->offset($pages->offset)->limit($pages->limit)->all();
        //查询域名
        $domain = TBusinessDomain::findOne(['bid'=>$hbid,'status'=>0]);
        return $this->render("ysfudai",['model'=>$data,'title'=>$title,'status'=>$status,'pages'=>$pages,'domain'=>$domain]);
    }

    public function actionYsfudaiconfig(){
        $hbid = Yii::$app->user->identity->id;
        if(Yii::$app->request->isPost){
            Yii::$app->response->format=Response::FORMAT_JSON;
            $model = TPlugFudaiBase::findOne(['bid'=>$hbid]);
            if(empty($model)){
                $model = new TPlugFudaiBase();
                $model->scenario= "setAdd";
                $model->bid= $hbid;
                if($model->load(Yii::$app->request->post(),"") && $model->save()){
                    return ['status'=>0,'msg'=>'新增成功'];
                }
                return ['status'=>1,'msg'=>'新增失败'];
            }else{
                $model = TPlugFudaiBase::findOne(['bid'=>$hbid]);
                $model->scenario= "setAdd";
                $model->bid= Yii::$app->user->identity->getId();
                if($model->load(Yii::$app->request->post(),"") && $model->save()){
                    return ['status'=>0,'msg'=>'保存成功'];
                }
                return ['status'=>1,'msg'=>'保存失败'];
            }
        }
        $data = TPlugFudaiBase::findOne(['bid'=>$hbid]);
        return $this->render("ysfudai-set",['data'=>$data]);
    }

    public function actionGetmplist()
    {
        $id = Yii::$app->request->get('id','');
        $bid = Yii::$app->request->get('bid','');
        //$bid = Yii::$app->user->identity->id;
        $sql ='SELECT name,fid,tbd.bid FROM(SELECT * FROM t_plug_ysfudai_list tpyl WHERE tpyl.yid='.$id.' AND tpyl.bid='.$bid.') t LEFT JOIN t_business_qrcode tbd ON tbd.id=t.fid';
        $data = Yii::$app->db->createCommand($sql)->queryAll();
        //查询域名
        $domain = TBusinessDomain::findOne(['bid'=>$bid,'status'=>0]);
        return $this->renderPartial('mplist',['data'=>$data,'domain'=>$domain]);
    }


    public function actionYsfudaiedit(){
        $hbid = Yii::$app->user->identity->id;
        if(Yii::$app->request->isPost) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $id = Yii::$app->request->post('id','');
            if(empty($id)){
                $model = new TPlugFudaiDetail();
                $model->scenario="setInfo";
                $model->bid= $hbid;

                $loadData = Yii::$app->request->post();
                $foucuData = $loadData['focusacct'];
                unset($loadData['focusacct']);

                if($model->load($loadData,"") && $model->save()){
                    if (!empty($foucuData)){
                        foreach ($foucuData as $key){
                            $tmd = new TPlugFudaiList();
                            $tmd->bid=$hbid;
                            $tmd->yid=$model->id;
                            $tmd->fid=$key;
                            $tmd->save();
                        }
                    }

                    return ['status'=>0,'msg'=>'新增成功'];
                }
                return ['status'=>1,'msg'=>'新增失败'];
            }else{
                $model = TPlugFudaiDetail::findOne(['bid'=>$hbid,'id'=>$id]);
                $model->scenario= "setInfo";
                $model->bid= $hbid;

                $loadData = Yii::$app->request->post();
                $foucuData = $loadData['focusacct'];
                unset($loadData['focusacct']);
                if($model->load($loadData,"") && $model->save()){
                    if (!empty($foucuData)){
                        TPlugFudaiList::deleteAll(['bid'=>$hbid,'yid'=>$id]);
                        foreach ($foucuData as $key){
                            $tmd = new TPlugFudaiList();
                            $tmd->bid=$hbid;
                            $tmd->yid=$id;
                            $tmd->fid=$key;
                            $tmd->save();
                        }
                    }
                    return ['status'=>0,'msg'=>'保存成功'];
                }
                var_dump($model->getErrors());
                return ['status'=>1,'msg'=>'保存失败'];
            }
        }
        $id = Yii::$app->request->get('id','');
        $data = TPlugFudaiDetail::findOne(['id'=>$id,'bid'=>$hbid]);
        $qrcodes = TBusinessQrcode::find()->select(['id','name'])->andWhere(['bid'=>$hbid])->asArray()->all();
        foreach ($qrcodes as &$qrcode){
            if(empty($id)){
                $qrcode['num']=0;
            }else{
                $num = TPlugFudaiList::find()->where(['bid'=>$hbid,'yid'=>$id,'fid'=>$qrcode['id']])->count();
                $qrcode['num']=$num;
            }
        }
        return $this->render("ysfudai-edit",['data'=>$data,'qrcodes'=>$qrcodes]);
    }

    public function actionFudai(){
        return $this->render("fudai");
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

