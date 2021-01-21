<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bank}}`.
 */
class m190908_124203_create_bank_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bank}}', [
            'id' => $this->primaryKey(),
            'client_id' => $this->string(),
            'secret_id' => $this->string(),
            'code' => $this->string(),
            'response_type' => $this->string(),
            'authorization_code' => $this->string(),
            'refresh_token' => $this->string(),
            'access_token' => $this->string(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%bank}}');
    }
}
