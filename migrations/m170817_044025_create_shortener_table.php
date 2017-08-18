<?php

use yii\db\Migration;

/**
 * Handles the creation of table `shortener`.
 */
class m170817_044025_create_shortener_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('shortener', [
            'id' => $this->primaryKey(),
            'url' => $this->string(255)->notNull(),
            'key' => $this->string(12)->notNull()->unique(),
            'created_at' => $this->dateTime() . ' DEFAULT NOW()',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('shortener');
    }
}
