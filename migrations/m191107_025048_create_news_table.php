<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%news}}`.
 */
class m191107_025048_create_news_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%news}}', [
            'id' => $this->primaryKey(),
            'id_a' => $this->integer()->notNull(),
            'title' => $this->string()->notNull(),
            'body' => $this->text(),
            'imagesmas' => $this->string(),
            'note' => $this->string(),            
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%news}}');
    }
}
