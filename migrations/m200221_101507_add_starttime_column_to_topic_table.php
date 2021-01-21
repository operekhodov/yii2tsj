<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%topic}}`.
 */
class m200221_101507_add_starttime_column_to_topic_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%topic}}', 'starttime', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%topic}}', 'starttime');
    }
}
