<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%area}}`.
 */
class m191004_214801_create_area_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%area}}', [
            'id' => $this->primaryKey(),
            'createddate' => $this->integer()->notNull(),
            'status' => $this->smallInteger()->notNull()->defaultValue(0),
            'info' => $this->string(),
            'notes' => $this->string(),
			'logo' => $this->string(),
			'inn' => $this->string(),
			'address' => $this->string(),
			'about' => $this->string(),
			'type' => $this->smallInteger()->notNull()->defaultValue(0),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%area}}');
    }
}
