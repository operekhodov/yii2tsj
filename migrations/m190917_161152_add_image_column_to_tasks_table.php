<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%tasks}}`.
 */
class m190917_161152_add_image_column_to_tasks_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%tasks}}', 'imagebd', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%tasks}}', 'imagebd');
    }
}
