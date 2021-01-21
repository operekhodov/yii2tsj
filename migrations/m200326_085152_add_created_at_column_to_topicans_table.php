<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%topicans}}`.
 */
class m200326_085152_add_created_at_column_to_topicans_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%topicans}}', 'created_at', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%topicans}}', 'created_at');
    }
}
