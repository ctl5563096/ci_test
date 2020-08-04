<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RoleRule extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'   => [
                'type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'auto_increment' => TRUE,
            ],
            'role' => [
                'type' => 'INT', 'constraint' => 11, 'default' => 0, 'unsigned' => TRUE, 'comment' => '角色ID',
            ],
            'rule' => [
                'type' => 'INT', 'constraint' => 11, 'default' => 0, 'unsigned' => TRUE, 'comment' => '权限ID',
            ],
        ]);
        $this->forge->addKey('id', TRUE);
        $this->forge->addField("created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间'");
        $res = $this->forge->createTable('role_rule', false, ['comment' => '角色权限对应表']);
        if ($res === false) {
            exit('迁移失败');
        }
    }

    //--------------------------------------------------------------------

    public function down()
    {
        $this->forge->dropTable('role_rule');
    }
}
