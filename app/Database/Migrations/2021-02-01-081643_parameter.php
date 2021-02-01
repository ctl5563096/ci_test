<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use phpDocumentor\Reflection\Types\False_;

class Parameter extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'         => [
                'type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'auto_increment' => TRUE,
            ],
            'name'       => [
                'type' => 'VARCHAR', 'constraint' => 1024, 'default' => '', 'comment' => '名称',
            ],
            'code'       => [
                'type' => 'VARCHAR', 'constraint' => 1024, 'default' => '', 'comment' => '参数编码',
            ],
            'para_name'  => [
                'type' => 'VARCHAR', 'constraint' => 1024, 'default' => '', 'comment' => '参数名称',
            ],
            'para_value' => [
                'type' => 'INT', 'constraint' => 11, 'default' => 0, 'comment' => '参数值',
            ],
            'weight'     => [
                'type' => 'INT', 'constraint' => 11, 'default' => 0, 'comment' => '参数值',
            ],
            'is_enabled' => [
                'type' => 'TINYINT', 'constraint' => 1, 'default' => 1, 'comment' => '是否启用参数 1为否 2为是',
            ],
            'add_by'     => [
                'type' => 'VARCHAR', 'constraint' => 11, 'default' => '', 'comment' => '添加人',
            ],
            'update_by'     => [
                'type' => 'VARCHAR', 'constraint' => 11, 'default' => '', 'comment' => '修改人',
            ],
        ]);
        $this->forge->addKey('id', TRUE);
        $this->forge->addKey('para_value', False);
        $this->forge->addField("add_time timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间'");
        $this->forge->addField("update_time timestamp DEFAULT NULL COMMENT '修改时间'");
        $res = $this->forge->createTable('parameter', false, ['comment' => '系统参数表']);
        if ($res === false) {
            exit('迁移失败');
        }
    }

    //--------------------------------------------------------------------

    public function down()
    {
        $this->forge->dropTable('parameter');
    }
}
