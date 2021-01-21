<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%indications}}`.
 */
class m190910_000337_create_indications_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%indications}}', [
            'id' => $this->primaryKey()->notNull(),
        	'id_u' => $this->integer()->notNull(),
        	'created_at' => $this->string()->notNull(),
        	'gvs' => $this->string(6)->notNull(),
        	'gvs1' => $this->string(6)->notNull(),
        	'hvs' => $this->string(6)->notNull(),
        	'hvs1' => $this->string(6)->notNull(),
        	'elday' => $this->string(6)->notNull(),
        	'elnight' => $this->string(6)->notNull(),
        ]);
        // Добавляем foreign key
        $this->addForeignKey(
            'id_user',  // это "условное имя" ключа
            '{{%indications}}', // это название текущей таблицы
            'id_u', // это имя поля в текущей таблице, которое будет ключом
            '{{%user}}', // это имя таблицы, с которой хотим связаться
            'id', // это поле таблицы, с которым хотим связаться
            'CASCADE'
        );        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%indications}}');
    }
}
