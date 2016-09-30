<?php

use Speedwork\Database\Migration\MigrationAbstract;

class CreateAddonSmsLogsTable extends MigrationAbstract
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        $table = $this->getSchema()->createTable('#__addon_sms_logs');

        $table->addColumn('id', 'bigint')->setLength(20)
                ->setUnsigned(true)
                ->setAutoincrement(true);

        $table->addColumn('mobile', 'string')->setLength(10)->setNotNull(true);
        $table->addColumn('message', 'text')->setNotNull(true);
        $table->addColumn('created', 'integer', ['length' => 10, 'unsigned' => true, 'notnull' => true]);
        $table->addColumn('status', 'smallint', ['length' => 1, 'unsigned' => true, 'notnull' => true]);

        $table->setPrimaryKey(['id']);
        $table->addIndex(['status'], 'status');
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        $this->getSchema()->dropTable('#__addon_sms_logs');
    }
}
