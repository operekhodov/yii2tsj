<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%message}}`.
 */
class m191023_011518_create_message_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%message}}', [
            'id' => $this->primaryKey(),
            'from' => $this->integer()->notNull(),
            'to' => $this->integer()->notNull(),
            'text' => $this->text()->notNull()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%message}}');
    }
}
