<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%user}}`.
 */
class m191023_031549_add_type_column_nroom_column_nexit_column_nfloor_column_nid_column_space_column_share_column_ncad_column_typeuse_column_email_column_to_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'type', $this->string());
        $this->addColumn('{{%user}}', 'nroom', $this->string());
        $this->addColumn('{{%user}}', 'nexit', $this->string());
        $this->addColumn('{{%user}}', 'nfloor', $this->string());
        $this->addColumn('{{%user}}', 'nid', $this->string());
        $this->addColumn('{{%user}}', 'space', $this->string());
        $this->addColumn('{{%user}}', 'share', $this->string());
        $this->addColumn('{{%user}}', 'ncad', $this->string());
        $this->addColumn('{{%user}}', 'typeuse', $this->string());
        $this->addColumn('{{%user}}', 'email', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%user}}', 'type');
        $this->dropColumn('{{%user}}', 'nroom');
        $this->dropColumn('{{%user}}', 'nexit');
        $this->dropColumn('{{%user}}', 'nfloor');
        $this->dropColumn('{{%user}}', 'nid');
        $this->dropColumn('{{%user}}', 'space');
        $this->dropColumn('{{%user}}', 'share');
        $this->dropColumn('{{%user}}', 'ncad');
        $this->dropColumn('{{%user}}', 'typeuse');
        $this->dropColumn('{{%user}}', 'email');
    }
}
