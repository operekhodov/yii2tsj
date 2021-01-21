<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%gantt}}`.
 */
class m191117_053138_add_id_a_column_tags_column_chekbox_column_to_gantt_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%gantt}}', 'id_a', $this->integer());
        $this->addColumn('{{%gantt}}', 'chekbox', $this->string());
        $this->addColumn('{{%gantt}}', 'tags', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%gantt}}', 'id_a');
        $this->dropColumn('{{%gantt}}', 'chekbox');
        $this->dropColumn('{{%gantt}}', 'tags');
    }
}
