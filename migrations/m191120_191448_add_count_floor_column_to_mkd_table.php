<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%mkd}}`.
 */
class m191120_191448_add_count_floor_column_to_mkd_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%mkd}}', 'count_floor', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%mkd}}', 'count_floor');
    }
}
