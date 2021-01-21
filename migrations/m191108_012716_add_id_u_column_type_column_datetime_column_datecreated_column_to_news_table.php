<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%news}}`.
 */
class m191108_012716_add_id_u_column_type_column_datetime_column_datecreated_column_to_news_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%news}}', 'id_u', $this->integer());
        $this->addColumn('{{%news}}', 'type', $this->string());
        $this->addColumn('{{%news}}', 'datetime', $this->string());
        $this->addColumn('{{%news}}', 'datecreated', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%news}}', 'id_u');
        $this->dropColumn('{{%news}}', 'type');
        $this->dropColumn('{{%news}}', 'datetime');
        $this->dropColumn('{{%news}}', 'datecreated');
    }
}
