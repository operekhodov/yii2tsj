<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%topic}}`.
 */
class m191101_004157_create_topic_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%topic}}', [
            'id' => $this->primaryKey(),
            'id_a' => $this->integer()->notNull(),            
            'topic' => $this->string()->notNull(),
            'quest' => $this->string()->notNull(),
            'type' => $this->string()->notNull(),
            'answermas' => $this->string()->notNull(),
            'imagesmas' => $this->string(),
            'note' => $this->string(),
        ]);
        
        // Добавляем foreign key
        $this->addForeignKey(
            'id_area',  // это "условное имя" ключа
            '{{%topic}}', // это название текущей таблицы
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
        $this->dropTable('{{%topic}}');
        
        //Добавляем удаление внешнего ключа
          $this->dropForeignKey(
              'id_area',
              '{{%area}}'
          );          
    }
}
