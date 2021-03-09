<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPhone extends Migration
{
	public function up()
	{
        $this->forge->addColumn('admin_user', "phone_num varchar(256) NOT NULL DEFAULT '' COMMENT '电话号码' ");
	}

	//--------------------------------------------------------------------

	public function down()
	{
        $this->forge->dropColumn('rule',['phone_num']);
	}
}
