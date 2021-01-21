<?php

namespace app\models;

use Yii;
use app\models\Area;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "moddocs".
 *
 * @property int $id
 * @property string $title
 * @property string $path
 * @property int $id_a
 */
class Moddocs extends \yii\db\ActiveRecord
{
	public $imageFiles;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'moddocs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'path', 'id_a'], 'required'],
            [['id_a'], 'integer'],
            [['title', 'path'], 'string', 'max' => 255],
			[['imageFiles'], 'file', 'skipOnEmpty' => true, 'maxFiles' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title_file'),
            'path' => Yii::t('app', 'Path_file'),
            'id_a' => Yii::t('app', 'Id A'),
            'imageFiles' => Yii::t('app', 'moddocs_file'),
        ];
    }
    public function getArea()
    {
        return $this->hasOne(Area::className(), ['id' => 'id_a']);
    }
    public function getAreaInfo() {
        return $this->area->info;
    }
    public static function getAlldocarea()
    {
        return ArrayHelper::map(self::find()->all(), 'title', 'path');//->where(['role' => 'supervisors'])->orderBy('fio')->all(), 'id', 'fio');
    }    
}
