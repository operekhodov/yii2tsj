<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%mkd}}`.
 */
class m200608_100207_add_status_column_to_mkd_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%mkd}}', 'status', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%mkd}}', 'status');
    }
}
