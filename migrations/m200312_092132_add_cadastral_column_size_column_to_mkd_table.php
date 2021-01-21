<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%mkd}}`.
 */
class m200312_092132_add_cadastral_column_size_column_to_mkd_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%mkd}}', 'cadastral', $this->string());
        $this->addColumn('{{%mkd}}', 'size', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%mkd}}', 'cadastral');
        $this->dropColumn('{{%mkd}}', 'size');
    }
}
