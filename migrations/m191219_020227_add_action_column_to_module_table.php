<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%module}}`.
 */
class m191219_020227_add_action_column_to_module_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%module}}', 'action', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%module}}', 'action');
    }
}
