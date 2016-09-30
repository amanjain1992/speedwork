<?php

use Speedwork\Database\Migration\MigrationAbstract;

class CreateUserGrantsTable extends MigrationAbstract
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        $table = $this->getSchema()->createTable('#__user_grants');

        $table->addColumn('user_id', 'string')->setLength(10)->setNotNull(true);

        $table->addColumn('grants', 'text');

        $table->addUniqueIndex(['user_id'], 'user_id');

        // Adding Foreign key
        $table->addForeignKeyConstraint($this->getSchema()->getTable('#__users'), ['user_id'], ['userid'], ['onDelete' => 'CASCADE']);
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        $this->getSchema()->dropTable('#__user_grants');
    }
}
