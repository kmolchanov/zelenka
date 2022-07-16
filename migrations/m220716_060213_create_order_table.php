<?php

use app\migrations\Migration;

/**
 * Handles the creation of table `{{%order}}`.
 */
class m220716_060213_create_order_table extends Migration
{
    private $tableName = '{{%order}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey()->comment('ID'),
            'real_id' => $this->integer()->notNull()->comment('Настоящий ID'),
            'user_name' => $this->string()->comment('Имя пользователя'),
            'user_phone' => $this->string(20)->comment('Номер телефона пользователя'),
            'warehouse_id' => $this->integer()->comment('ID склада'),
            'created_at' => $this->dateTime()->comment('Дата и время создания'),
            'updated_at' => $this->dateTime()->comment('Дата и время обновления'),
            'status' => $this->tinyInteger()->comment('Статус'),
            'items_count' => $this->integer()->defaultValue(0)->comment('Количество элементов заказа'),
        ], $this->tableOptions);

        $this->createIndex('order_real_id_unique', $this->tableName, ['real_id'], true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('order_real_id_unique', $this->tableName);

        $this->dropTable($this->tableName);
    }
}
