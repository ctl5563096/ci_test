<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddColRule extends Migration
{
	public function up()
	{
        $this->forge->addColumn('rule', "url VARCHAR(255) NOT NULL DEFAULT '' COMMENT '菜单路径'");
        $this->forge->addColumn('rule', "icon VARCHAR(255) NOT NULL DEFAULT '' COMMENT '菜单图标'");
	}

	//--------------------------------------------------------------------

	public function down()
	{
		$this->forge->dropColumn('rule',['url','icon']);
	}
}
