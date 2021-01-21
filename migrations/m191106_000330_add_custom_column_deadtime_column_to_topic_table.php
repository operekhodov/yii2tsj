<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%topic}}`.
 */
class m191106_000330_add_custom_column_deadtime_column_to_topic_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%topic}}', 'custom', $this->string());
        $this->addColumn('{{%topic}}', 'deadtime', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%topic}}', 'custom');
        $this->dropColumn('{{%topic}}', 'deadtime');
    }
}
