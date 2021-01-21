<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%devtoken}}`.
 */
class m190831_055303_create_devtoken_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%devtoken}}', [
            'id' => $this->primaryKey(),
            'deviceid' => $this->integer()->notNull(),
            'token' => $this->integer()->notNull(),
        ], $tableOptions);
 
        $this->createIndex('idx-devtoken-deviceid', '{{%devtoken}}', 'deviceid');
        $this->createIndex('idx-devtoken-token', '{{%devtoken}}', 'token');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%devtoken}}');
    }
}
