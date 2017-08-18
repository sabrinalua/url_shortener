<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "access".
 *
 * @property integer $id
 * @property string $key_id
 * @property string $ip_address
 * @property string $city_region_country
 * @property string $access_date
 */
class Access extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'access';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['city_region_country'], 'required'],
            [['access_date'], 'safe'],
            [['key_id'], 'string', 'max' => 15],
            [['ip_address'], 'string', 'max' => 255],
            [['city_region_country'], 'string', 'max' => 12],
            [['city_region_country'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'key_id' => 'Key ID',
            'ip_address' => 'Ip Address',
            'city_region_country' => 'City Region Country',
            'access_date' => 'Access Date',
        ];
    }
}
