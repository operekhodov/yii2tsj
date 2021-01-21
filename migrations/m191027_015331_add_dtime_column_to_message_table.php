<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%message}}`.
 */
class m191027_015331_add_dtime_column_to_message_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%message}}', 'dtime', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%message}}', 'dtime');
    }
}
