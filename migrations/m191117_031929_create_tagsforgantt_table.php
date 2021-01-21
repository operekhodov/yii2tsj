<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tagsforgantt}}`.
 */
class m191117_031929_create_tagsforgantt_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tagsforgantt}}', [
            'id'	=> $this->primaryKey(),
            'id_a'	=> $this->integer()->notNull(),
            'name'	=> $this->string(),
            'value' => $this->string()->notNull(),
            'note'	=> $this->string(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%tagsforgantt}}');
    }
}
