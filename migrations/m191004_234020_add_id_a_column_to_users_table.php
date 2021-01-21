<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%user}}`.
 */
class m191004_234020_add_id_a_column_to_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'id_a', $this->integer());
        
        // Добавляем foreign key
        $this->addForeignKey(
            'area_id',  // это "условное имя" ключа
            '{{%user}}', // это название текущей таблицы
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
        $this->dropColumn('{{%user}}', 'id_a');
        
        //Добавляем удаление внешнего ключа
          $this->dropForeignKey(
              'area_id',
              '{{%area}}'
          );        
    }
}
