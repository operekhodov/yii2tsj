<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%module}}`.
 */
class m200204_091520_add_sub_action_column_to_module_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%module}}', 'sub_action', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%module}}', 'sub_action');
    }
}
