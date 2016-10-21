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

class CreateNotificationsTable extends MigrationAbstract
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        $table = $this->getSchema()->createTable('#__notifications');

        $table->addColumn('id', 'bigint')
                ->setUnsigned(true)
                ->setAutoincrement(true);

        $table->addColumn('user_id', 'string')->setLength(10)->setNotNull(true);
        $table->addColumn('noty_group', 'integer');
        $table->addColumn('message', 'text')->setNotNull(true);
        $table->addColumn('meta', 'text');
        $table->addColumn('created', 'integer', ['length' => 10, 'notnull' => true]);
        $table->addColumn('modified', 'integer', ['length' => 10, 'notnull' => true]);
        $table->addColumn('status', 'integer', ['length' => 1, 'notnull' => true]);

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
        $this->getSchema()->dropTable('#__notifications');
    }
}
