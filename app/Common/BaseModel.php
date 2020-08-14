<?php declare(strict_types=1);


namespace App\Common;


use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use CodeIgniter\Validation\ValidationInterface;

/**
 * 模型积累
 *
 * Class BaseModel
 * @package App\Common
 */
class BaseModel extends Model
{
    public $connect;

    public function __construct(ConnectionInterface &$db = null, ValidationInterface $validation = null)
    {
        $this->connect = \Config\Database::connect();
        parent::__construct($db, $validation);
    }
}