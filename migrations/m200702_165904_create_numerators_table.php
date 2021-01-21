<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%numerators}}`.
 */
class m200702_165904_create_numerators_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%numerators}}', [
            'id' => $this->primaryKey(),
            'id_u' => $this->integer(),
            'type' => $this->integer(),
            'num' => $this->integer(),
            'note' => $this->string(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%numerators}}');
    }
}
