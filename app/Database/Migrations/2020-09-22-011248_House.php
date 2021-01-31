<?php declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class House extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'         => [
                'type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'auto_increment' => TRUE,
            ],
            'house_name' => [
                'type' => 'VARCHAR', 'constraint' => 1024, 'default' => '', 'comment' => '房屋名',
            ],
            'address'    => [
                'type' => 'VARCHAR', 'constraint' => 1024, 'default' => '', 'comment' => '房屋地址',
            ],
            'master'     => [
                'type' => 'VARCHAR', 'constraint' => 1024, 'default' => '', 'comment' => '房屋所有人',
            ],
            'charger'    => [
                'type' => 'INT', 'constraint' => 11, 'default' => 0, 'unsigned' => TRUE, 'comment' => '房屋负责人',
            ],
            'room_num'   => [
                'type' => 'INT', 'constraint' => 11, 'default' => 0, 'unsigned' => TRUE, 'comment' => '房间数',
            ],
            'size'       => [
                'type' => 'INT', 'constraint' => 11, 'default' => 0, 'unsigned' => TRUE, 'comment' => '面积数',
            ],
            'type'       => [
                'type' => 'INT', 'constraint' => 11, 'default' => 0, 'unsigned' => TRUE, 'comment' => '房屋类型',
            ],
            'is_del'     => [
                'type' => 'TINYINT', 'constraint' => 1, 'default' => 1, 'unsigned' => TRUE, 'comment' => '是否删除 1是未删除,2是已删除',
            ],
            'is_all'     => [
                'type' => 'TINYINT', 'constraint' => 1, 'default' => 1, 'unsigned' => TRUE, 'comment' => '是否删除 1是未租出去,2是已租出部分,3已满租',
            ],
        ]);
        $this->forge->addKey('id', TRUE);
        $this->forge->addKey('charger', false);
        $this->forge->addField("add_time timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间'");
        $this->forge->addField("del_time timestamp  DEFAULT NULL COMMENT '删除时间'");
        $this->forge->addField("create_time timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '建造时间'");
        $res = $this->forge->createTable('house', false, ['comment' => '房屋信息表']);
        if ($res === false) {
            exit('迁移失败');
        }
    }

    //--------------------------------------------------------------------

    public function down()
    {
        $this->forge->dropTable('file');
    }
}
