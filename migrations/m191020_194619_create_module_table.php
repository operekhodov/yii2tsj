<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%module}}`.
 */
class m191020_194619_create_module_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%module}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%module}}');
    }
}
