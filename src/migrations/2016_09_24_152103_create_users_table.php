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

class CreateUsersTable extends MigrationAbstract
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        $table = $this->getSchema()->createTable('#__users');

        $table->addColumn('userid', 'string')->setLength(10)->setNotNull(true);
        $table->addColumn('role_id', 'integer');
        $table->addColumn('username', 'string')->setLength(50)->setNotNull(true);
        $table->addColumn('email', 'string')->setLength(150)->setNotNull(true);
        $table->addColumn('password', 'string')->setLength(150)->setNotNull(true);
        $table->addColumn('token', 'string')->setLength(150)->setNotNull(true);
        $table->addColumn('mobile', 'string')->setLength(25)->setNotNull(true);
        $table->addColumn('first_name', 'string')->setLength(50)->setNotNull(true);
        $table->addColumn('last_name', 'string')->setLength(50)->setNotNull(true);
        $table->addColumn('gender', 'string')->setLength(10)->setNotNull(true);
        $table->addColumn('avatar', 'string')->setLength(100)->setNotNull(true);
        $table->addColumn('activation_key', 'string')->setLength(100)->setNotNull(true);
        $table->addColumn('activated_at', 'integer')->setLength(10)->setNotNull(true);
        $table->addColumn('last_pw_change', 'integer')->setLength(10)->setNotNull(true);
        $table->addColumn('last_signin', 'integer')->setLength(10)->setNotNull(true);
        $table->addColumn('ip', 'string')->setLength(100)->setNotNull(true);
        $table->addColumn('meta', 'text');

        $table->addColumn('status', 'smallint')->setLength(1)->setDefault(1);
        $table->addColumn('created', 'integer', ['length' => 10]);
        $table->addColumn('modified', 'integer', ['length' => 10]);

        $table->setPrimaryKey(['userid'], 'userid');
        $table->addUniqueIndex(['username'], 'username');
        $table->addIndex(['status'], 'status');
        $table->addIndex(['role_id'], 'role_id');
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        $this->getSchema()->dropTable('#__users');
    }
}
