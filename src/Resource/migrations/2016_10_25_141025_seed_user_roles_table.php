<?php

use Speedwork\Database\Migration\MigrationAbstract;

class SeedUserRolesTable extends MigrationAbstract
{
    /**
     * Run the migrations.
     */
    public function up()
    {
    }

    /**
     * Seed the databas with records.
     * Will be run both up and own cases.
     */
    public function seed()
    {
        $database = $this->getConnection();
    }

    /**
     * Insert records.
     */
    public function seedUp()
    {
        $database = $this->getConnection();

        $save                 = [];
        $save['role_id']      = 1;
        $save['name']         = 'admin';
        $save['display_name'] = 'Admin';
        $save['grants']       = '{"include":["home:*","*","admin_home:*"],"exclude":[]}';
        $save['ordering']     = 1;

        $database->save('#__user_roles', $save);
    }

    /**
     * Remove records.
     */
    public function seedDown()
    {
        $database = $this->getConnection();
        $database->delete('#__user_roles', ['role_id' => 1]);
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
    }
}
