<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class File extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'            => [
                'type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'auto_increment' => TRUE,
            ],
            'file_name'     => [
                'type' => 'VARCHAR', 'constraint' => 1024, 'default' => '', 'comment' => '原本文件名',
            ],
            'new_file_name' => [
                'type' => 'VARCHAR', 'constraint' => 1024, 'default' => '', 'comment' => '新文件名',
            ],
            'url'           => [
                'type' => 'VARCHAR', 'constraint' => 1024, 'default' => '', 'comment' => '基础url',
            ],
            'type'           => [
                'type' => 'VARCHAR', 'constraint' => 300, 'default' => '', 'comment' => '类型',
            ],
            'size'          => [
                'type' => 'INT', 'constraint' => 11, 'default' => 0, 'comment' => '文件大小',
            ],
            'is_del'        => [
                'type' => 'TINYINT', 'constraint' => 1, 'default' => 1, 'unsigned' => TRUE, 'comment' => '是否删除 1是未删除,2是已删除',
            ],
        ]);
        $this->forge->addKey('id', TRUE);
        $this->forge->addField("upload_time timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间'");
        $this->forge->addField("del_time timestamp  DEFAULT NULL COMMENT '删除时间'");
        $res = $this->forge->createTable('file', false, ['comment' => '文件关系表']);
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
