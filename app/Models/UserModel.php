<?php declare(strict_types=1);


namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $primaryKey = 'id';
    protected $table      = 'admin_user';
    protected $returnType = 'array';
}