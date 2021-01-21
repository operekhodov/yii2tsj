<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%tasks}}`.
 */
class m190908_084917_add_admap_column_to_tasks_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%tasks}}', 'admap', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%tasks}}', 'admap');
    }
}
