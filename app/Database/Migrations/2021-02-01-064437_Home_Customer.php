<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class HomeCustomer extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'             => [
                'type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'auto_increment' => TRUE,
            ],
            'real_name'      => [
                'type' => 'VARCHAR', 'constraint' => 1024, 'default' => '', 'comment' => '真实姓名',
            ],
            'sex'            => [
                'type' => 'TINYINT', 'constraint' => 1, 'default' => 3, 'comment' => '1/男,2/nv,3/保密',
            ],
            'openid'         => [
                'type' => 'VARCHAR', 'constraint' => 1024, 'default' => '', 'comment' => '微信身份标识',
            ],
            'add_type'       => [
                'type' => 'TINYINT', 'constraint' => 2, 'default' => 1, 'comment' => '新增来源',
            ],
            'phone_num'      => [
                'type' => 'int', 'constraint' => 11, 'default' => 11, 'comment' => '手机号码',
            ],
            'id_card'        => [
                'type' => 'VARCHAR', 'constraint' => 1024, 'default' => '', 'comment' => '身份证',
            ],
            'front_card_url' => [
                'type' => 'VARCHAR', 'constraint' => 1024, 'default' => '', 'comment' => '身份证前面照片url',
            ],
            'back_card_url'  => [
                'type' => 'VARCHAR', 'constraint' => 1024, 'default' => '', 'comment' => '身份证后面照片url',
            ],
            'update_by'     => [
                'type' => 'VARCHAR', 'constraint' => 11, 'default' => '', 'comment' => '修改人',
            ]
        ]);
        $this->forge->addKey('id', TRUE);
        $this->forge->addField("add_time timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间'");
        $this->forge->addField("update_time timestamp DEFAULT NULL COMMENT '新增来源'");
        $res = $this->forge->createTable('customer_home', false, ['comment' => '租客用户表']);
        if ($res === false) {
            exit('迁移失败');
        }
    }

    //--------------------------------------------------------------------

    public function down()
    {
        $this->forge->dropTable('customer_home');
    }
}
