<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%topic}}`.
 */
class m200401_002843_add_avtor_column_to_topic_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%topic}}', 'avtor', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%topic}}', 'avtor');
    }
}
