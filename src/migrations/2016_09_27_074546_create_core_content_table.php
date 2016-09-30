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

class CreateCoreContentTable extends MigrationAbstract
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        $table = $this->getSchema()->createTable('#__core_content');

        $table->addColumn('post_id', 'integer')
                ->setUnsigned(true)
                ->setAutoincrement(true);

        $table->addColumn('user_id', 'string')->setLength(10)->setNotNull(true);
        $table->addColumn('post_type', 'string')->setLength(20)->setNotNull(true);
        $table->addColumn('post_name', 'string')->setLength(255)->setNotNull(true);
        $table->addColumn('post_title', 'string')->setLength(256)->setNotNull(true);
        $table->addColumn('post_content', 'text')->setNotNull(true);
        $table->addColumn('meta', 'text')->setNotNull(true);
        $table->addColumn('ordering', 'integer', ['length' => 10, 'notnull' => true]);
        $table->addColumn('created', 'integer', ['length' => 10, 'notnull' => true]);
        $table->addColumn('modified', 'integer', ['length' => 10, 'notnull' => true]);
        $table->addColumn('status', 'smallint', ['length' => 1, 'default' => 1]);

        $table->setPrimaryKey(['post_id']);
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
        $this->getSchema()->dropTable('#__core_content');
    }
}
