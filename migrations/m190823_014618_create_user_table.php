<?php

use yii\db\Migration;

class m190823_014618_create_user_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'username' => $this->string()->notNull(),
            'auth_key' => $this->string(32),
            'password_hash' => $this->string()->notNull(),
            'status' => $this->smallInteger()->notNull()->defaultValue(0),
            'role' => $this->string(32),
        ], $tableOptions);
 
        $this->createIndex('idx-user-username', '{{%user}}', 'username');
        $this->createIndex('idx-user-status', '{{%user}}', 'status');
    }

    public function safeDown()
    {
        $this->dropTable('{{%user}}');
    }
}
