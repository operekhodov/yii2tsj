<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%indicats}}`.
 */
class m200703_002248_create_indicats_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%indicats}}', [
            'id' => $this->primaryKey(),
            'id_u' => $this->integer(),
            'created_at' => $this->integer(),
            'indinow' => $this->text(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%indicats}}');
    }
}
