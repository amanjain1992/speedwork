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

class CreateUserLoginHistoryTable extends MigrationAbstract
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        $table = $this->getSchema()->createTable('#__user_login_history');

        $table->addColumn('id', 'integer')
                ->setUnsigned(true)
                ->setAutoincrement(true);

        $table->addColumn('user_id', 'string')->setLength(10)->setNotNull(true);
        $table->addColumn('session_id', 'string')->setLength(64)->setNotNull(true);
        $table->addColumn('source', 'string')->setLength(10)->setNotNull(true);
        $table->addColumn('ip', 'string')->setLength(20)->setNotNull(true);
        $table->addColumn('host', 'string')->setLength(128)->setNotNull(true);
        $table->addColumn('agent', 'string')->setLength(255)->setNotNull(true);
        $table->addColumn('referer', 'string')->setLength(255)->setNotNull(true);
        $table->addColumn('meta', 'text');
        $table->addColumn('created', 'integer', ['length' => 10]);
        $table->addColumn('modified', 'integer', ['length' => 10]);
        $table->addColumn('status', 'smallint', ['length' => 1]);

        $table->setPrimaryKey(['id']);
        $table->addIndex(['user_id'], 'user_id');
        $table->addIndex(['status'], 'status');

        // Adding Foreign key
        $table->addForeignKeyConstraint($this->getSchema()->getTable('#__users'), ['user_id'], ['userid'], ['onDelete' => 'CASCADE']);
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        $this->getSchema()->dropTable('#__user_login_history');
    }
}
