<?php

/*
 * This file is part of the Speedwork package.
 *
 * (c) Sankar <sankar.suda@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

use Speedwork\Database\Migration\MigrationAbstract;

class CreateUsersApiTable extends MigrationAbstract
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        $table = $this->getSchema()->createTable('#__users_api');

        $table->addColumn('id', 'bigint')
                ->setUnsigned(true)
                ->setAutoincrement(true);

        $table->addColumn('user_id', 'string', ['length' => 10]);
        $table->addColumn('api_key', 'string', ['length' => 100]);
        $table->addColumn('api_secret', 'string', ['length' => 250]);
        $table->addColumn('allowed_ip', 'text');
        $table->addColumn('created', 'integer', ['length' => 10]);
        $table->addColumn('modified', 'integer', ['length' => 10]);
        $table->addColumn('status', 'smallint', ['length' => 1]);

        $table->setPrimaryKey(['id']);
        $table->addIndex(['user_id'], 'user_id');
        $table->addIndex(['api_key'], 'api_key');
        $table->addIndex(['status'], 'status');

        // Adding Foreign key
        $table->addForeignKeyConstraint($this->getSchema()->getTable('#__users'), ['user_id'], ['userid'], ['onDelete' => 'CASCADE']);
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        $this->getSchema()->dropTable('#__users_api');
    }
}
