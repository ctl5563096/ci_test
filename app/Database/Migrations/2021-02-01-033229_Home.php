<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Home extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => [
                'type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'auto_increment' => TRUE,
            ],
            'home_num'    => [
                'type' => 'VARCHAR', 'constraint' => 1024, 'default' => '', 'comment' => '房间号',
            ],
            'floor'       => [
                'type' => 'INT', 'constraint' => 3, 'default' => 0, 'comment' => '楼层',
            ],
            'customer_id' => [
                'type' => 'INT', 'constraint' => 11, 'default' => 0, 'comment' => '租客',
            ],
            'charger'     => [
                'type' => 'INT', 'constraint' => 11, 'default' => 0, 'unsigned' => TRUE, 'comment' => '房间负责人',
            ],
            'size'        => [
                'type' => 'INT', 'constraint' => 11, 'default' => 0, 'unsigned' => TRUE, 'comment' => '房间面积',
            ],
            'type'        => [
                'type' => 'INT', 'constraint' => 11, 'default' => 0, 'unsigned' => TRUE, 'comment' => '房屋类型',
            ],
            'money'       => [
                'type' => 'INT', 'constraint' => 11, 'default' => 0, 'unsigned' => TRUE, 'comment' => '租金,单位分',
            ],
            'is_del'      => [
                'type' => 'TINYINT', 'constraint' => 1, 'default' => 1, 'unsigned' => TRUE, 'comment' => '是否删除 1是未删除,2是已删除',
            ],
            'is_all'      => [
                'type' => 'TINYINT', 'constraint' => 1, 'default' => 1, 'unsigned' => TRUE, 'comment' => '是否以出租 1是未租出去,2是已满租',
            ],
            'is_pay'      => [
                'type' => 'TINYINT', 'constraint' => 1, 'default' => 1, 'unsigned' => TRUE, 'comment' => '本月是否已交租 1是已交租,2是未交租',
            ],
        ]);
        $this->forge->addKey('id', TRUE);
        $this->forge->addKey('charger', false);
        $this->forge->addKey('customer_id', false);
        $this->forge->addField("in_time timestamp DEFAULT NULL COMMENT '入住时间'");
        $this->forge->addField("out_time timestamp DEFAULT NULL COMMENT '本次退租时间'");
        $this->forge->addField("add_time timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间'");
        $this->forge->addField("del_time timestamp  DEFAULT NULL COMMENT '删除时间'");
        $res = $this->forge->createTable('home', false, ['comment' => '房间信息表']);
        if ($res === false) {
            exit('迁移失败');
        }
    }

    //--------------------------------------------------------------------

    public function down()
    {
        $this->forge->dropTable('home');
    }
}
