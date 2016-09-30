<?php

use Speedwork\Database\Migration\MigrationAbstract;

class CreateAddonShorturlsTable extends MigrationAbstract
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        $table = $this->getSchema()->createTable('#__addon_shorturls');

        $table->addColumn('id', 'integer')
                ->setUnsigned(true)
                ->setAutoincrement(true);

        $table->addColumn('redirect', 'string')->setLength(5)->setNotNull(true);
        $table->addColumn('component', 'string')->setLength(50)->setNotNull(true);
        $table->addColumn('uniqueid', 'string')->setLength(50)->setNotNull(true);
        $table->addColumn('shorturl', 'string', ['length' => 255, 'notnull' => true]);
        $table->addColumn('originalurl', 'string', ['length' => 255, 'notnull' => true]);
        $table->addColumn('created', 'string', ['length' => 10, 'notnull' => true]);
        $table->addColumn('status', 'smallint', ['length' => 1, 'default' => 1]);

        $table->setPrimaryKey(['id']);
        $table->addUniqueIndex(['shorturl'], 'shorturl');
        $table->addIndex(['status'], 'status');
        $table->addIndex(['component'], 'component');
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        $this->getSchema()->dropTable('#__addon_shorturls');
    }
}
