<?php declare(strict_types=1);

namespace App\Controllers;

use App\Common\RestController;
use App\Models\FileModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;


/**
 * 上传文件控制器
 *
 * Class Upload
 * @package App\Controller
 * @property FileModel $modelObj
 */
class Upload extends RestController
{
    protected $modelName = 'App\Models\FileModel';

    protected $modelObj;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        $this->modelObj = new FileModel();
        parent::initController($request, $response, $logger);
    }

    /**
     * Notes: 上传文件主方法
     *
     * @throws \ReflectionException
     * @author: chentulin
     * Date: 2020/9/17
     * Time: 15:44
     */
    public function upload()
    {
        // 被允许上传的文件后缀
        $extensionArr = ['jpg', 'jpeg', 'png', 'gif'];
        // 获取随参
        $params = $this->request->getPost();
        if (count($params) === 0) {
            return $this->respondApi(['code' => 60001, 'msg' => '无法获取文件参数']);
        } elseif (empty($params['name'])) {
            return $this->respondApi(['code' => 60002, 'msg' => '无法获取文件name参数']);
        } elseif (empty($params['type'])) {
            return $this->respondApi(['code' => 60003, 'msg' => '无法获取文件type参数']);
        }
        // 获取文件对象
        $fileObj = $this->request->getFile($params['name']);
        if (!$fileObj) {
            return $this->respondApi(['code' => 60004, 'msg' => '无法获取文件']);
        }
        // 获取上传错误信息
        if (!$fileObj->isValid()) {
            return $this->respondApi(['code' => 60005, 'msg' => '无法获取文件,' . $fileObj->getErrorString() . '(' . $fileObj->getError() . ')']);
        }
        // 获取文件后缀, 进行检查,防止上传其他代码文件
        $extension = $fileObj->getClientExtension();
        if (!in_array($extension, $extensionArr, true)) {
            return $this->respondApi(['code' => 60006, 'msg' => '上传失败，文件类型不被允许']);
        }
        // 获取文件原文件名
        $fileName = $fileObj->getName();
        // 生成新的文件名
        $newName = time() . '_' . $params['type'] . '.' . $extension;
        // 新文件路径
        $path = FILE_URL . $params['type'];
        // 移动文件
        $res = $fileObj->move($path, $newName);
        if (!$res) {
            return $this->respondApi(['code' => 60007, 'msg' => '上传失败，文件类型不被允许']);
        }
        // 保存到数据库的完整路径
        $allPath = $path . '/' . $newName;
        // 获取文件大小
        $size = $fileObj->getSize();
        // 保存到数据库的数组
        $insertArr = [
            'file_name'     => $fileName,
            'new_file_name' => $newName,
            'url'           => $allPath,
            'upload_time'   => date('Y-m-d H:i:s'),
            'size'          => $size,
            'type'          => $params['type']
        ];
        $other     = $params['other'];
        $res       = $this->modelObj->insertRecord($insertArr, json_decode($other ,true));
        if ($res) {
            return $this->respondApi(['url' => $allPath]);
        } else {
            return $this->respondApi(['code' => 60008, 'msg' => '上传失败，请联系管理员']);
        }
    }
}