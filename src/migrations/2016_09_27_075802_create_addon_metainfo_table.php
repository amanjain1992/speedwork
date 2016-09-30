<?php

use Speedwork\Database\Migration\MigrationAbstract;

class CreateAddonMetainfoTable extends MigrationAbstract
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        $table = $this->getSchema()->createTable('#__addon_metainfo');

        $table->addColumn('id', 'bigint')->setLength(20)
                ->setUnsigned(true)
                ->setAutoincrement(true);

        $table->addColumn('uniqid', 'string')->setLength(20)->setNotNull(true);
        $table->addColumn('component', 'string')->setLength(100)->setNotNull(true);
        $table->addColumn('url', 'string')->setLength(256)->setNotNull(true);
        $table->addColumn('canonical', 'text')->setNotNull(true);
        $table->addColumn('title', 'string')->setLength(255)->setNotNull(true);
        $table->addColumn('descn', 'text')->setNotNull(true);
        $table->addColumn('keywords', 'text')->setNotNull(true);
        $table->addColumn('meta', 'text')->setNotNull(true);
        $table->addColumn('created', 'integer', ['length' => 10, 'notnull' => true]);
        $table->addColumn('status', 'smallint', ['length' => 1, 'default' => 1]);

        $table->setPrimaryKey(['id']);
        $table->addIndex(['uniqid'], 'uniqid');
        $table->addIndex(['status'], 'status');
        $table->addIndex(['component'], 'component');
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        $this->getSchema()->dropTable('#__addon_metainfo');
    }
}
