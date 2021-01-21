<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tasks}}`.
 */
class m190823_014705_create_tasks_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tasks}}', [
            'id' => $this->primaryKey(),
            'createddate' => $this->integer()->notNull(),
            'finishdate' => $this->integer()->notNull(),
            'status' => $this->smallInteger()->notNull()->defaultValue(0),
            'tags' => $this->integer()->notNull(),
            'info' => $this->string()->notNull(),
            'assignedto' => $this->integer()->notNull(),
            'notes' => $this->string(),
            'createdby' => $this->integer()->notNull(),

        ], $tableOptions);
 
        // Добавляем foreign key
        $this->addForeignKey(
            'whocreated',  // это "условное имя" ключа
            '{{%tasks}}', // это название текущей таблицы
            'createdby', // это имя поля в текущей таблице, которое будет ключом
            '{{%user}}', // это имя таблицы, с которой хотим связаться
            'id', // это поле таблицы, с которым хотим связаться
            'CASCADE'
        );
        // Добавляем foreign key
        $this->addForeignKey(
            'whoworked',  // это "условное имя" ключа
            '{{%tasks}}', // это название текущей таблицы
            'assignedto', // это имя поля в текущей таблице, которое будет ключом
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
        $this->dropTable('{{%tasks}}');

        //Добавляем удаление внешнего ключа
          $this->dropForeignKey(
              'whocreated',
              '{{%user}}'
          );
        //Добавляем удаление внешнего ключа
          $this->dropForeignKey(
              'whoworked',
              '{{%user}}'
          );
    }
}
