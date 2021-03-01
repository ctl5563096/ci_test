<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Carousel extends Migration
{
	public function up()
	{
        $this->forge->addField([
            'id'         => [
                'type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'auto_increment' => TRUE,
            ],
            'content'       => [
                'type' => 'VARCHAR', 'constraint' => 1024, 'default' => '', 'comment' => '轮播图内容',
            ],
            'is_enabled' => [
                'type' => 'TINYINT', 'constraint' => 1, 'default' => 1, 'comment' => '是否启用参数 1为否 2为是',
            ],
            'image_url' => [
                'type' => 'VARCHAR', 'constraint' => 1024, 'default' => '', 'comment' => 'url路径',
            ],
            'add_by'     => [
                'type' => 'VARCHAR', 'constraint' => 11, 'default' => '', 'comment' => '添加人',
            ],
            'update_by'     => [
                'type' => 'VARCHAR', 'constraint' => 11, 'default' => '', 'comment' => '修改人',
            ],
        ]);
        $this->forge->addKey('id', TRUE);
        $this->forge->addField("add_time timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间'");
        $this->forge->addField("update_time timestamp DEFAULT NULL COMMENT '修改时间'");
        $res = $this->forge->createTable('carousel', false, ['comment' => '轮播图表']);
        if ($res === false) {
            exit('迁移失败');
        }
	}

	//--------------------------------------------------------------------

	public function down()
	{
        $this->forge->dropTable('carousel');
	}
}
