<?php

namespace common\models\fudai;

use Yii;

/**
 * This is the model class for table "t_plug_ysfudai_list".
 *
 * @property integer $id
 * @property integer $bid
 * @property integer $yid
 * @property integer $fid
 */
class TPlugFudaiList extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 't_plug_ysfudai_list';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bid','yid','fid'], 'required'],
            [['bid','yid','fid'], 'integer']
        ];
    }

//    public function scenarios()
//    {
//        return [
//            'setAdd'=>['bid','name','telphone','telpeople','sharetitle','headimg','shareimg','sharedesc'],
//        ];
//    }

    /**
     * @inheritdoc
     */
//    public function attributeLabels()
//    {
//        return [
//            'id' => 'ID',
//            'bid' => 'Bid',
//            'name' => 'Name',
//            'telphone' => 'Telphone',
//            'telpeople' => 'Telpeople',
//            'headimg' => 'Headimg',
//            'shareimg' => 'Shareimg',
//            'sharetitle' => 'Sharetitle',
//            'sharedesc' => 'Sharedesc',
//        ];
//    }
}
