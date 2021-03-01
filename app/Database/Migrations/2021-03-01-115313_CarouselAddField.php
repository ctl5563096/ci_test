<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CarouselAddField extends Migration
{
	public function up()
	{
        $fields = array(
            'name' => array('type' => 'VARCHAR', 'constraint' => 1024,'default' => '', 'comment' => '轮播图名称', 'after' => 'id')
        );
        $res = $this->forge->addColumn('carousel', $fields);
        if ($res === false) {
            exit('迁移失败');
        }
	}

	//--------------------------------------------------------------------

	public function down()
	{
        $this->forge->dropColumn('carousel', 'name');
	}
}
