<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%gantt}}`.
 */
class m200324_004614_add_id_u_column_files_column_comments_column_to_gantt_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%gantt}}', 'id_u', $this->integer());
        $this->addColumn('{{%gantt}}', 'files', $this->string());
        $this->addColumn('{{%gantt}}', 'comments', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%gantt}}', 'id_u');
        $this->dropColumn('{{%gantt}}', 'files');
        $this->dropColumn('{{%gantt}}', 'comments');
    }
}
