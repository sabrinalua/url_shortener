<?php

use yii\db\Migration;

/**
 * Handles the creation of table `access`.
 */
class m170817_044046_create_access_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('access', [
            'id' => $this->primaryKey(),
            'key_id'=>$this->string(15)->notNull(),
            'ip_address' => $this->string(255),
            'city' => $this->string(100),
            'region' => $this->string(100),
            'country' => $this->string(100),
            'geolocation' => $this->string(100),
            'os' => $this->string(100),
            'browser'=>$this->string(100),
            'browser_version'=>$this->string(100),
            'device_type'=>$this->string(100),
            'access_date' => $this->dateTime() . ' DEFAULT NOW()',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('access');
    }
}
