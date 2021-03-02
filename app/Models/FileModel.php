<?php declare(strict_types=1);


namespace App\Models;


use App\Common\BaseModel;

/**
 * 文件模型
 *
 * Class FileModel
 * @package App\Models
 */
class FileModel extends BaseModel
{
    // 表的主键 必须要有以保证模型的正常工作
    protected $primaryKey = 'id';
    // 表名
    protected $table = 'file';
    // 模型的返回类型
    protected $returnType = 'array';
    // 开启安全删除 一般使用了deleted_at字段作为删除字段 暂时不使用
    //protected $useSoftDeletes = true;
    // 数据库的时间格式
    protected $dateFormat = 'datetime';
    // 更新或者插入时候 允许插入或者更新的字段
    protected $allowedFields = [
        'file_name', 'new_file_name', 'url', 'size', 'is_del', 'upload_time', 'del_time', 'type',
    ];
    // 验证规则
    protected $validationRules = [
        'file_name'     => 'required',
        'new_file_name' => 'required',
        'url'           => 'required',
    ];
    // 验证返回的错误信息
    protected $validationMessages = [
        'file_name'     => [
            'required' => '文件名不能为空',
        ],
        'new_file_name' => [
            'required' => '新文件名不能为空',
        ],
        'url'           => [
            'required' => 'url不能为空',
        ],
    ];

    // 是否跳过验证数据
    protected $skipValidation = false;

    /**
     * Notes: 新增记录
     *
     * @param array $data
     * @param array $other
     * @return bool|int|mixed|string
     * @throws \ReflectionException
     * @author: chentulin
     * Date: 2020/9/17
     * Time: 22:47
     */
    public function insertRecord(array $data, $other = [])
    {
        $model = new self();
        $this->db->transBegin();
        $res = $model->insert($data);
        if ($res) {
            // 更新对应的表
            $res = $this->addField($data['type'], $other, $data['url']);
            if (!$res) {
                // 回滚事务
                $this->db->transRollback();
                return false;
            }
            // 提交事务
            $this->db->transCommit();
            return $res;
        } else {
            return false;
        }
    }

    /**
     * Notes: 获取url
     *
     * @param int $id
     * @return string
     * @author: chentulin
     * Date: 2020/9/18
     * Time: 11:52
     */
    public function getUrl(int $id): string
    {
        $model = new self();
        $res   = $model->find($id);
        return $res['url'];
    }

    /**
     * Notes: 添加头像路径
     *
     * @param string $type
     * @param array $other
     * @param string $url
     * @param int $idUrl
     * @return bool
     * @throws \ReflectionException
     * @author: chentulin
     * Date: 2020/9/20
     * Time: 19:39
     */
    public function addField(string $type, array $other, string $url = '', int $idUrl = 0)
    {
        $res = false;
        // 判断是什么类型的
        if (!$type) {
            return false;
        }
        switch ($type) {
            case 'avatar':
                $model = new UserModel();
                $res   = $model->updateInfoForUserId((int)$other['id'], ['avatar' => $url]);
                break;
            case 'carousel':
                $model = new CarouselModel();
                $res   = $model->updateInfo((int)$other['id'], ['image_url' => $url, 'update_by' => $other['update_by'], 'update_time' => date('Y-m-d H:i:s')]);
                break;
            default:
                $url = $idUrl;
                $res = true;
        }
        return $res;
    }
}