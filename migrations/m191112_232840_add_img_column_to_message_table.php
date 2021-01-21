<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%message}}`.
 */
class m191112_232840_add_img_column_to_message_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%message}}', 'img', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%message}}', 'img');
    }
}
