<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddColRuleSort extends Migration
{
	public function up()
	{
        $this->forge->addColumn('rule', "sort Int(11) NOT NULL DEFAULT 0 COMMENT '排序'");
	}

	//--------------------------------------------------------------------

	public function down()
	{
        $this->forge->dropColumn('rule',['sort']);
	}
}
