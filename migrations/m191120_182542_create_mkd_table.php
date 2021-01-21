<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%mkd}}`.
 */
class m191120_182542_create_mkd_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%mkd}}', [
            'id' => $this->primaryKey(),
			'id_a' => $this->integer(),
			'city' => $this->string()->notNull(),
			'street' => $this->string()->notNull(),
			'number_house' => $this->string()->notNull(),
			'count_porch' => $this->string(),
			'count_apartment' => $this->string()->notNull(),
			'note' => $this->string(),            
        ]);
        // Добавляем foreign key
        $this->addForeignKey(
            'id_area',  // это "условное имя" ключа
            '{{%mkd}}', // это название текущей таблицы
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
        $this->dropTable('{{%mkd}}');
        
        //Добавляем удаление внешнего ключа
          $this->dropForeignKey(
              'id_area',
              '{{%area}}'
          );        
    }
}
