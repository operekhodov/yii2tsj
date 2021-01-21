<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%bank}}`.
 */
class m190910_132704_add_sandbox_column_to_bank_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%bank}}', 'sandbox', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%bank}}', 'sandbox');
    }
}
