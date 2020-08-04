<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddColUserRole extends Migration
{
    public function up()
    {
        $this->forge->addColumn('admin_user', "role INT NOT NULL DEFAULT 0 COMMENT '角色ID'");
    }

    //--------------------------------------------------------------------

    public function down()
    {
        $this->forge->dropColumn('admin_user', 'role');
    }
}
