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

class CreateCoreMenuTable extends MigrationAbstract
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        $table = $this->getSchema()->createTable('#__core_menu');

        $table->addColumn('id', 'integer')
                ->setUnsigned(true)
                ->setAutoincrement(true);

        $table->addColumn('parent_id', 'integer', ['length' => 10])->setUnsigned(true);
        $table->addColumn('menu_type', 'string')->setlength(75)->setNotNull(true);
        $table->addColumn('name', 'string', ['length' => 256, 'notnull' => true]);
        $table->addColumn('link', 'text')->setNotNull(true);
        $table->addColumn('attributes', 'text');
        $table->addColumn('access', 'smallint', ['length' => 1, 'default' => 0]);
        $table->addColumn('status', 'smallint', ['length' => 1, 'unsigned' => true]);
        $table->addColumn('ordering', 'integer', ['length' => 10, 'unsigned' => true]);
        $table->addColumn('created', 'integer', ['length' => 10, 'unsigned' => true]);

        $table->setPrimaryKey(['id']);
        $table->addIndex(['menu_type'], 'menu_type');
        $table->addIndex(['access'], 'access');
        $table->addIndex(['status'], 'status');
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        $this->getSchema()->dropTable('#__core_menu');
    }
}
