<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Role extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'         => [
                'type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'auto_increment' => TRUE,
            ],
            'role_name'  => [
                'type' => 'VARCHAR', 'constraint' => 1024, 'default' => '', 'comment' => '角色名',
            ],
            'is_enabled' => [
                'type' => 'TINYINT', 'constraint' => 1, 'default' => 0, 'unsigned' => TRUE, 'comment' => '是否启用',
            ],
        ]);
        $this->forge->addKey('id', TRUE);
        $this->forge->addField("created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间'");
        $res = $this->forge->createTable('role', false, ['comment' => '角色表']);
        if ($res === false) {
            exit('迁移失败');
        }
    }

    //--------------------------------------------------------------------

    public function down()
    {
        $this->forge->dropTable('role');
    }
}
