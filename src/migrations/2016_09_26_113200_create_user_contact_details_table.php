<?php

use Speedwork\Database\Migration\MigrationAbstract;

class CreateUserContactDetailsTable extends MigrationAbstract
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        $table = $this->getSchema()->createTable('#__user_contact_details');

        $table->addColumn('user_id', 'string')->setLength(10)->setNotNull(true);
        $table->addColumn('alt_email', 'string')->setLength(100)->setNotNull(true);
        $table->addColumn('website', 'string')->setLength(100)->setNotNull(true);
        $table->addColumn('phone', 'string')->setLength(20)->setNotNull(true);
        $table->addColumn('fax', 'string')->setLength(50)->setNotNull(true);
        $table->addColumn('address_line', 'string')->setLength(200)->setNotNull(true);
        $table->addColumn('address_line2', 'string')->setLength(200)->setNotNull(true);
        $table->addColumn('locality', 'string')->setLength(100)->setNotNull(true);
        $table->addColumn('city', 'string')->setLength(50)->setNotNull(true);
        $table->addColumn('state', 'string')->setLength(50)->setNotNull(true);
        $table->addColumn('country', 'string')->setLength(50)->setNotNull(true);
        $table->addColumn('zipcode', 'string')->setLength(10)->setNotNull(true);

        $table->addUniqueIndex(['user_id'], 'user_id');
        // Adding Foreign key
        $table->addForeignKeyConstraint($this->getSchema()->getTable('#__users'), ['user_id'], ['userid'], ['onDelete' => 'CASCADE']);
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        $this->getSchema()->dropTable('#__user_contact_details');
    }
}
