<?php

use Speedwork\Database\Migration\MigrationAbstract;

class CreateCoreMenuTypesTable extends MigrationAbstract
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        $table = $this->getSchema()->createTable('#__core_menu_types');

        $table->addColumn('id', 'integer')
                ->setUnsigned(true)
                ->setAutoincrement(true);

        $table->addColumn('menu_type', 'string')->setLength(75)->setNotNull(true);
        $table->addColumn('title', 'string')->setLength(255)->setNotNull(true);
        $table->addColumn('descn', 'string')->setLength(255)->setNotNull(true);

        $table->setPrimaryKey(['id']);
        $table->addIndex(['menu_type'], 'menu_type');
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        $this->getSchema()->dropTable('#__core_menu_types');
    }
}
