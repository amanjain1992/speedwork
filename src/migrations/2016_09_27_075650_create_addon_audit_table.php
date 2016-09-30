<?php

use Speedwork\Database\Migration\MigrationAbstract;

class CreateAddonAuditTable extends MigrationAbstract
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        $table = $this->getSchema()->createTable('#__addon_audit');

        $table->addColumn('id', 'bigint')->setLength(20)
                ->setUnsigned(true)
                ->setAutoincrement(true);

        $table->addColumn('user_id', 'string')->setLength(10)->setNotNull(true);
        $table->addColumn('table_name', 'string')->setLength(50)->setNotNull(true);
        $table->addColumn('route', 'string')->setLength(50)->setNotNull(true);
        $table->addColumn('sql_query', 'text')->setNotNull(true);
        $table->addColumn('meta', 'text')->setNotNull(true);
        $table->addColumn('ip', 'string')->setLength(25)->setNotNull(true);
        $table->addColumn('type', 'string')->setLength(15)->setNotNull(true);
        $table->addColumn('created', 'integer', ['length' => 10, 'notnull' => true]);

        $table->setPrimaryKey(['id']);
        $table->addIndex(['user_id'], 'user_id');

        // Adding Foreign key
        $table->addForeignKeyConstraint($this->getSchema()->getTable('#__users'), ['user_id'], ['userid'], ['onDelete' => 'CASCADE']);
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        $this->getSchema()->dropTable('#__addon_audit');
    }
}
