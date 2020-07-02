<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AdminUser extends Migration
{
	public function up()
	{
        $this->forge->addField([
            'id' => [
                'type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'auto_increment' => TRUE
            ],
            'user_name' => [
                'type' => 'VARCHAR', 'constraint' => 1024,'default'=> '', 'comment' => '用户名'
            ],
            'password' => [
                'type' => 'VARCHAR', 'constraint' => 1024, 'default'=> '', 'comment' => '密码'
            ],
            'is_use' => [
                'type' => 'TINYINT', 'constraint' => 4, 'default' => 0 , 'comment' => '是否启用 ,0/不是 1/是'
            ],
            'is_black' => [
                'type' => 'TINYINT', 'constraint' => 4, 'default' => 0 , 'comment' => '是否为黑名单 ,0/不是 1/是'
            ],
            'email' => [
                'type' => 'VARCHAR', 'constraint' => 1024, 'default'=> '', 'comment' => '邮箱'
            ]
        ]);
        $this->forge->addField("created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间'");
        $this->forge->addKey('id', TRUE);
        $this->forge->addKey('user_name' ,false ,false);
        $this->forge->createTable('admin_user');
	}

	//--------------------------------------------------------------------

	public function down()
	{
		$this->forge->dropTable('admin_user');
	}
}
