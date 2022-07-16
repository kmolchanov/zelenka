<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%order}}".
 *
 * @property int $id ID
 * @property int $real_id Настоящий ID
 * @property string|null $user_name Имя пользователя
 * @property string|null $user_phone Номер телефона пользователя
 * @property int|null $warehouse_id ID склада
 * @property string|null $created_at Дата и время создания
 * @property string|null $updated_at Дата и время обновления
 * @property int|null $status Статус
 * @property int|null $items_count Количество элементов заказа
 */
class Order extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%order}}';
    }

    /**
     * @return array[]
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => false,
                'updatedAtAttribute' => 'updated_at',
                'value' => Yii::$app->formatter->asDatetime('now', 'php:Y-m-d H:i:s'),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['real_id'], 'required'],
            [['real_id', 'warehouse_id', 'status', 'items_count'], 'integer'],
            [['created_at', 'updated_at'], 'datetime', 'format' => 'php:Y-m-d H:i:s'],
            [['user_name'], 'string', 'max' => 255],
            [['user_phone'], 'string', 'max' => 20],
            [['real_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'real_id' => 'Настоящий ID',
            'user_name' => 'Имя пользователя',
            'user_phone' => 'Номер телефона пользователя',
            'warehouse_id' => 'ID склада',
            'created_at' => 'Дата и время создания',
            'updated_at' => 'Дата и время обновления',
            'status' => 'Статус',
            'items_count' => 'Количество элементов заказа',
        ];
    }

    /**
     * {@inheritdoc}
     * @return OrderQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new OrderQuery(get_called_class());
    }
}
