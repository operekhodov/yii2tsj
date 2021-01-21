<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%options_}}`.
 */
class m190823_043950_create_options__table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%options_}}', [
            'id' => $this->primaryKey(),
            'id_u' => $this->integer(),
            'id_t' => $this->integer(),
            'name' => $this->string()->notNull(),
            'value' => $this->string(),
        ]);
        // Добавляем foreign key
        $this->addForeignKey(
            'id_user',  // это "условное имя" ключа
            '{{%options_}}', // это название текущей таблицы
            'id_u', // это имя поля в текущей таблице, которое будет ключом
            '{{%user}}', // это имя таблицы, с которой хотим связаться
            'id', // это поле таблицы, с которым хотим связаться
            'CASCADE'
        );
        // Добавляем foreign key
        $this->addForeignKey(
            'id_task',  // это "условное имя" ключа
            '{{%options_}}', // это название текущей таблицы
            'id_t', // это имя поля в текущей таблице, которое будет ключом
            '{{%tasks}}', // это имя таблицы, с которой хотим связаться
            'id', // это поле таблицы, с которым хотим связаться
            'CASCADE'
        );        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%options_}}');
        
        //Добавляем удаление внешнего ключа
          $this->dropForeignKey(
              'id_user',
              '{{%user}}'
          );
        //Добавляем удаление внешнего ключа
          $this->dropForeignKey(
              'id_task',
              '{{%tasks}}'
          );        
    }
}