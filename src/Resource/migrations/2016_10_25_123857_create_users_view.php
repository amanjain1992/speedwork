<?php

use Speedwork\Database\Migration\MigrationAbstract;

class CreateUsersView extends MigrationAbstract
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        $database = $this->getConnection();

        $sql = $database->toSql('#__users', [
            'fields' => [
                'userid',
                'role_id',
                'username',
                'email',
                'mobile',
                'gender',
                "(CASE avatar WHEN '' THEN 'avatar.png' ELSE avatar END) AS avatar",
                "REPLACE(CONCAT(first_name,' ',last_name), '  ',' ') AS name",
                "CONCAT('@', username) AS user",
                'status AS active',
                'created AS since',
            ],
        ]);

        $this->getSchema()->createView('#__users_view', $sql);
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        $this->getSchema()->dropView('#__users_view');
    }
}
