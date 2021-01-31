<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAvatar extends Migration
{
	public function up()
	{
        $this->forge->addColumn('admin_user', "avatar varchar(800) NOT NULL DEFAULT '' COMMENT '用户头像'");
	}

	//--------------------------------------------------------------------

	public function down()
	{
        $this->forge->dropColumn('admin_user',['avatar']);
	}
}
