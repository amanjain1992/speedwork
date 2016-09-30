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

class CreateAddonEmailBlacklistTable extends MigrationAbstract
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        $table = $this->getSchema()->createTable('#__addon_email_blacklist');

        $table->addColumn('id', 'integer')
                ->setUnsigned(true)
                ->setAutoincrement(true);

        $table->addColumn('email', 'string', ['length' => 100, 'notnull' => true]);

        $table->addColumn('created', 'integer', ['length' => 10, 'notnull' => true]);
        $table->setPrimaryKey(['id']);
        $table->addIndex(['email'], 'email');
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        $this->getSchema()->dropTable('#__addon_email_blacklist');
    }
}
