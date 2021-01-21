<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%gantt}}`.
 */
class m191026_033324_create_gantt_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%gantt}}', [
            'id' => $this->primaryKey(),
            'status' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'start' => $this->string()->notNull(),
            'end' => $this->string()->notNull(),
            'parent' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%gantt}}');
    }
}
