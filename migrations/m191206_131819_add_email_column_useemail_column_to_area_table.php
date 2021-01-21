<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%area}}`.
 */
class m191206_131819_add_email_column_useemail_column_to_area_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%area}}', 'email', $this->string());
        $this->addColumn('{{%area}}', 'useemail', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%area}}', 'email');
        $this->dropColumn('{{%area}}', 'useemail');
    }
}
