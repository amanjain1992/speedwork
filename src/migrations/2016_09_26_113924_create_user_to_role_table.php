<?php

use Speedwork\Database\Migration\MigrationAbstract;

class CreateUserToRoleTable extends MigrationAbstract
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        $table = $this->getSchema()->createTable('#__user_to_role');

        $table->addColumn('user_id', 'string')->setLength(10)->setNotNull(true);

        $table->addColumn('role_id', 'integer')->setLength(10)->setUnsigned(true);

        $table->setPrimaryKey(['user_id', 'role_id']);
        $table->addIndex(['user_id'], 'user_id');
        $table->addIndex(['role_id'], 'role_id');

        // Adding Foreign key
        $table->addForeignKeyConstraint($this->getSchema()->getTable('#__users'), ['user_id'], ['userid'], ['onDelete' => 'CASCADE']);
        $table->addForeignKeyConstraint($this->getSchema()->getTable('#__user_roles'), ['role_id'], ['role_id'], ['onDelete' => 'CASCADE']);
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        $this->getSchema()->dropTable('#__user_to_role');
    }
}
