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

class CreateUserLoginAttemptsTable extends MigrationAbstract
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        $table = $this->getSchema()->createTable('#__user_login_attempts');

        $table->addColumn('id', 'integer')
                ->setUnsigned(true)
                ->setAutoincrement(true);

        $table->addColumn('username', 'string')->setLength(100)->setNotNull(true);
        $table->addColumn('ip_address', 'string', ['length' => 50]);
        $table->addColumn('attempts', 'integer')->setLength(10)->setUnsigned(true)->setNotNull(true);
        $table->addColumn('last_attempt_at', 'integer')->setLength(10)->setNotNull(true);
        $table->addColumn('suspended', 'smallint')->setLength(4)->setUnsigned(true)->setDefault(1);
        $table->addColumn('suspended_at', 'integer')->setLength(10)->setNotNull(true);

        $table->setPrimaryKey(['id']);
        $table->addIndex(['ip_address'], 'ip_address');
        $table->addIndex(['username'], 'username');
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        $this->getSchema()->dropTable('#__user_login_attempts');
    }
}
