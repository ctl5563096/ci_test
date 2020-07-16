<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddColUser extends Migration
{
	public function up()
	{
        $this->forge->addColumn('admin_user', "token varchar(800) NOT NULL DEFAULT '' COMMENT '用户token'");
	}

	//--------------------------------------------------------------------

	public function down()
	{
		$this->forge->dropColumn('admin_user', 'token');
	}
}
