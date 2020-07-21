<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddColUserSex extends Migration
{
	public function up()
	{
        $this->forge->addColumn('admin_user', "sex TINYINT NOT NULL DEFAULT 1 COMMENT '1/男 2/女 3/未知'");
	}

	//--------------------------------------------------------------------

	public function down()
	{
        $this->forge->dropColumn('admin_user', 'sex');
	}
}
