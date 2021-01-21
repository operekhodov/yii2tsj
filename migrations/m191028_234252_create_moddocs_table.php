<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%moddocs}}`.
 */
class m191028_234252_create_moddocs_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%moddocs}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'path' => $this->string()->notNull(),
            'id_a' => $this->integer()->notNull(),
        ]);
        
        // Добавляем foreign key
        $this->addForeignKey(
            'id_area',  // это "условное имя" ключа
            '{{%moddocs}}', // это название текущей таблицы
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
        $this->dropTable('{{%moddocs}}');
        
        //Добавляем удаление внешнего ключа
          $this->dropForeignKey(
              'id_area',
              '{{%area}}'
          );        
    }
}
