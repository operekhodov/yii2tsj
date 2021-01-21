<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%mkd}}`.
 */
class m200228_075309_add_geo_column_to_mkd_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%mkd}}', 'geo', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%mkd}}', 'geo');
    }
}
