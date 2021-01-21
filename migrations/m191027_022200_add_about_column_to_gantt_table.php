<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%gantt}}`.
 */
class m191027_022200_add_about_column_to_gantt_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%gantt}}', 'about', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%gantt}}', 'about');
    }
}
