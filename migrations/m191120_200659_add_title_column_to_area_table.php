<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%area}}`.
 */
class m191120_200659_add_title_column_to_area_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%area}}', 'title', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%area}}', 'title');
    }
}
