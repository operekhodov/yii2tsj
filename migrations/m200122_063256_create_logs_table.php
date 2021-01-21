<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%logs}}`.
 */
class m200122_063256_create_logs_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%logs}}', [
            'id' => $this->primaryKey(),
			'id_a' => $this->integer(),
			'id_u' => $this->integer(),
			'created_at' => $this->string()->notNull(),
			'ip' => $this->string()->notNull(),
			'uagent' => $this->string()->notNull(),
			'action' => $this->string()->notNull(),
			'info' => $this->string()->notNull(),
			'dop' => $this->string()->notNull(),
			'note' => $this->string()->notNull(),
        ]);
        // Добавляем foreign key
        $this->addForeignKey(
            'id_area',  // это "условное имя" ключа
            '{{%logs}}', // это название текущей таблицы
            'id_a', // это имя поля в текущей таблице, которое будет ключом
            '{{%area}}', // это имя таблицы, с которой хотим связаться
            'id', // это поле таблицы, с которым хотим связаться
            'CASCADE'
        );        
        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%logs}}');
        //Добавляем удаление внешнего ключа
          $this->dropForeignKey(
              'id_area',
              '{{%area}}'
          );         
    }
}
