<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%topicans}}`.
 */
class m191101_004219_create_topicans_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%topicans}}', [
            'id' => $this->primaryKey(),
            'id_u' => $this->integer()->notNull(),
            'id_q' => $this->integer()->notNull(),
            'answer' => $this->string()->notNull(),
            'note' => $this->string()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%topicans}}');
    }
}
