<?php

use yii\db\Migration;

/**
 * Handles dropping columns from table `{{%tasks}}`.
 */
class m190824_211312_drop_tags_column_from_tasks_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('{{%tasks}}', 'tags');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('{{%tasks}}', 'tags', $this->integer());
    }
}
