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

class CreateUserSocialProvidersTable extends MigrationAbstract
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        $table = $this->getSchema()->createTable('#__user_social_providers');

        $table->addColumn('provider_id', 'smallint')->setLength(5)->setNotNull(true)
                ->setUnsigned(true)
                ->setAutoincrement(true);

        $table->addColumn('provider', 'string')->setLength(50)->setNotNull(true);
        $table->addColumn('title', 'string')->setLength(100)->setNotNull(true);
        $table->addColumn('meta', 'text')->setNotNull(true);
        $table->addColumn('options', 'text')->setNotNull(true);
        $table->addColumn('is_default', 'smallint')->setLength(3)->setNotNull(true)->setUnsigned(true);
        $table->addColumn('ordering', 'integer')->setLength(10)->setNotNull(true);
        $table->addColumn('status', 'smallint', ['length' => 3]);
        $table->addColumn('created', 'integer', ['length' => 10]);
        $table->addColumn('modified', 'integer', ['length' => 10]);

        $table->setPrimaryKey(['provider_id']);
        $table->addUniqueIndex(['provider'], 'provider');
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        $this->getSchema()->dropTable('#__user_social_providers');
    }
}
