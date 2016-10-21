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

class CreateUserSocialTable extends MigrationAbstract
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        $table = $this->getSchema()->createTable('#__user_social');

        $table->addColumn('id', 'bigint')->setLength(20)
                ->setUnsigned(true)
                ->setAutoincrement(true);

        $table->addColumn('user_id', 'string')->setLength(10)->setNotNull(true);
        $table->addColumn('provider_id', 'smallint')->setLength(5)->setNotNull(true)->setUnsigned(true);
        $table->addColumn('identifier', 'string')->setLength(200)->setNotNull(true);
        $table->addColumn('email', 'string')->setLength(200)->setNotNull(true);
        $table->addColumn('display_name', 'string')->setLength(100)->setNotNull(true);
        $table->addColumn('session_info', 'text');
        $table->addColumn('profile', 'text');
        $table->addColumn('created', 'integer', ['length' => 10]);
        $table->addColumn('modified', 'integer', ['length' => 10]);
        $table->addColumn('status', 'smallint', ['length' => 3]);

        $table->setPrimaryKey(['id']);
        $table->addIndex(['user_id', 'provider_id', 'identifier'], 'user_provider_identifier');
        $table->addIndex(['provider_id'], 'provider_id');
        $table->addIndex(['status'], 'status');
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        $this->getSchema()->dropTable('#__user_social');
    }
}
