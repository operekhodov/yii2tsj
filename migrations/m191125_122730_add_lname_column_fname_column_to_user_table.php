<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%user}}`.
 */
class m191125_122730_add_lname_column_fname_column_to_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'lname', $this->string());
        $this->addColumn('{{%user}}', 'fname', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%user}}', 'lname');
        $this->dropColumn('{{%user}}', 'fname');
    }
}
