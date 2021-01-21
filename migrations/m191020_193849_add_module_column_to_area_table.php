<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%area}}`.
 */
class m191020_193849_add_module_column_to_area_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%area}}', 'module', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%area}}', 'module');
    }
}
