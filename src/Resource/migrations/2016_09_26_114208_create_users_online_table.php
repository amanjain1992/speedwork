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

class CreateUsersOnlineTable extends MigrationAbstract
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        $table = $this->getSchema()->createTable('#__users_online');

        $table->addColumn('user_id', 'string', ['length' => 10]);

        $table->addColumn('last_active', 'integer', ['length' => 10]);
        $table->addColumn('default_status', 'smallint')->setLength(4);
        $table->addColumn('current_status', 'smallint')->setLength(4);

        $table->addUniqueIndex(['user_id'], 'user_id');
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        $this->getSchema()->dropTable('#__users_online');
    }
}
