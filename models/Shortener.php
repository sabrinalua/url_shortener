<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "shortener".
 *
 * @property integer $id
 * @property string $url
 * @property string $key
 * @property string $created_at
 */
class Shortener extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shortener';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['url'], 'required'],
            [['created_at'], 'safe'],
            [['url'], 'string', 'max' => 255],
            [['key'], 'string', 'max' => 12],
            [['key'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'url' => 'Url',
            'key' => 'Key',
            'created_at' => 'Created At',
        ];
    }
}
