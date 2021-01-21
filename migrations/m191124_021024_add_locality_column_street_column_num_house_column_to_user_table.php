<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%user}}`.
 */
class m191124_021024_add_locality_column_street_column_num_house_column_to_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'locality', $this->string());
        $this->addColumn('{{%user}}', 'street', $this->string());
        $this->addColumn('{{%user}}', 'num_house', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%user}}', 'locality');
        $this->dropColumn('{{%user}}', 'street');
        $this->dropColumn('{{%user}}', 'num_house');
    }
}
