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

class CreateUserRolesTable extends MigrationAbstract
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        $table = $this->getSchema()->createTable('#__user_roles');

        $table->addColumn('role_id', 'integer')
                ->setUnsigned(true)
                ->setAutoincrement(true);

        $table->addColumn('name', 'string')->setLength(100)->setNotNull(true);
        $table->addColumn('display_name', 'string')->setLength(100)->setNotNull(true);
        $table->addColumn('grants', 'text');
        $table->addColumn('ordering', 'integer')->setLength(10)->setUnsigned(true);

        $table->setPrimaryKey(['role_id']);
        $table->addUniqueIndex(['name'], 'name');
        $table->addIndex(['ordering'], 'ordering');
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        $this->getSchema()->dropTable('#__user_roles');
    }
}
