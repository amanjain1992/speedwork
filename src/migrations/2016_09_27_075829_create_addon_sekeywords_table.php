<?php

use Speedwork\Database\Migration\MigrationAbstract;

class CreateAddonSekeywordsTable extends MigrationAbstract
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        $table = $this->getSchema()->createTable('#__addon_sekeywords');

        $table->addColumn('id', 'bigint')->setLength(20)
                ->setUnsigned(true)
                ->setAutoincrement(true);

        $table->addColumn('keyword', 'string')->setLength(255)->setNotNull(true);
        $table->addColumn('search_engine', 'string')->setLength(20)->setNotNull(true);
        $table->addColumn('created', 'integer', ['length' => 10, 'notnull' => true]);

        $table->setPrimaryKey(['id']);
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        $this->getSchema()->dropTable('#__addon_sekeywords');
    }
}
