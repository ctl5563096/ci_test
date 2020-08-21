<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddColRuleMehtods extends Migration
{
    public function up()
    {
        $this->forge->addColumn('rule', "controller VARCHAR(255) NOT NULL DEFAULT '' COMMENT '控制器'");
        $this->forge->addColumn('rule', "action VARCHAR(255) NOT NULL DEFAULT '' COMMENT '方法'");
    }

    //--------------------------------------------------------------------

    public function down()
    {
        $this->forge->dropColumn('rule',['controller','action']);
    }
}
