<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Rule extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'        => [
                'type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'auto_increment' => TRUE,
            ],
            'rule_name' => [
                'type' => 'VARCHAR', 'constraint' => 1024, 'default' => '', 'comment' => '权限名字',
            ],
            'pid'       => [
                'type' => 'INT', 'constraint' => 11, 'default' => 0, 'unsigned' => TRUE, 'comment' => '父权限Id',
            ],
            'is_menu'   => [
                'type' => 'TINYINT', 'constraint' => 1, 'default' => 0, 'unsigned' => TRUE, 'comment' => '是否为菜单',
            ],
        ]);
        $this->forge->addKey('id', TRUE);
        $this->forge->addField("created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间'");
        $res = $this->forge->createTable('rule', false, ['comment' => '权限表']);
        if ($res === false) {
            exit('迁移失败');
        }
    }

    //--------------------------------------------------------------------

    public function down()
    {
        $this->forge->dropTable('rule');
    }
}
