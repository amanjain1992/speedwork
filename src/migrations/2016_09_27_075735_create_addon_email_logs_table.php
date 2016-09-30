<?php

use Speedwork\Database\Migration\MigrationAbstract;

class CreateAddonEmailLogsTable extends MigrationAbstract
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        $table = $this->getSchema()->createTable('#__addon_email_logs');

        $table->addColumn('id', 'bigint')->setLength(20)
                ->setUnsigned(true)
                ->setAutoincrement(true);

        $table->addColumn('from_email', 'string')->setLength(100)->setNotNull(true);
        $table->addColumn('to_email', 'string')->setLength(256)->setNotNull(true);
        $table->addColumn('subject', 'string')->setLength(255)->setNotNull(true);
        $table->addColumn('message', 'text')->setNotNull(true);
        $table->addColumn('reason', 'string')->setLength(200)->setNotNull(true);
        $table->addColumn('created', 'integer', ['length' => 10, 'unsigned' => true]);
        $table->addColumn('status', 'smallint', ['length' => 1, 'unsigned' => true]);

        $table->setPrimaryKey(['id']);
        $table->addIndex(['status'], 'status');
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        $this->getSchema()->dropTable('#__addon_email_logs');
    }
}
