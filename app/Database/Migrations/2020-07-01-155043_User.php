<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class User extends Migration
{
	public function up()
	{
        $this->forge->addField([
            'blog_id' => [
                'type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE, 'auto_increment' => TRUE
            ], 'blog_title' => [

                'type' => 'VARCHAR', 'constraint' => '100',
            ], 'blog_description' => [

                'type' => 'TEXT', 'null' => TRUE,
            ],

        ]); $this->forge->addKey('blog_id', TRUE); $this->forge->createTable('blog');
	}

	//--------------------------------------------------------------------

	public function down()
	{
		//
	}
}
