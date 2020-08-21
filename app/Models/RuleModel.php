<?php declare(strict_types=1);

namespace App\Models;

use App\Common\BaseModel;

/**
 * 权限模型
 *
 * Class RuleModel
 * @package App\Models
 */
class RuleModel extends BaseModel
{
    // 表的主键 必须要有以保证模型的正常工作
    protected $primaryKey = 'id';
    // 表名
    protected $table = 'rule';
    // 模型的返回类型
    protected $returnType = 'array';
    // 开启安全删除 一般使用了deleted_at字段作为删除字段 暂时不使用
    //protected $useSoftDeletes = true;
    // 数据库的时间格式
    protected $dateFormat = 'datetime';
    // 更新或者插入时候 允许插入或者更新的字段
    protected $allowedFields = [
        'rule_name', 'pid', 'is_menu', 'url', 'icon', 'controller', 'action'
    ];
    // 验证规则
    protected $validationRules = [
        'rule_name' => 'required|is_unique[rule.rule_name,id,{id}]',
        'pid'       => 'required',
    ];
    // 验证返回的错误信息
    protected $validationMessages = [
        'rule_name' => [
            'required'  => '权限名不能为空',
            'is_unique' => '权限名已存在',
        ],
        'pid'       => [
            'required' => '父ID不能为空',
        ],
    ];

    // 是否跳过验证数据
    protected $skipValidation = false;

    // 菜单数组
    protected $menus;

    /**
     * Notes: 添加权限
     *
     * Author: chentulin
     * DateTime: 2020/8/4 19:43
     * E-MAIL: <chentulinys@163.com>
     * @param $data
     * @return void | array | int
     * @throws \ReflectionException
     */
    public function addRule($data)
    {
        // 对菜单数据类型进行标准化
        $data['is_menu']   = (int)$data['is_menu'];
        $data['pid']       = (int)$data['pid'];
        $data['rule_name'] = $data['name'];
        $ruleId            = $this->insert($data, true);
        if ($ruleId === false) return ['code' => 20001, 'msg' => '添加权限失败', 'result' => current($this->errors())];
        else return (int)$ruleId;
    }

    /**
     * Notes: 根据权限获取菜单
     *
     * Author: chentulin
     * DateTime: 2020/8/5 16:56
     * E-MAIL: <chentulinys@163.com>
     */
    public function getMenuByRole()
    {
        $menuArr     = $this->select('id ,pid ,url ,icon ,rule_name')->where('is_menu', 1)->findAll();
        $this->menus = $menuArr;
        // 整理菜单
        return $this->getMenuList($menuArr);
    }

    /**
     * Notes: 整理菜单 返回前端需要数据
     *
     * Author: chentulin
     * DateTime: 2020/8/5 17:09
     * E-MAIL: <chentulinys@163.com>
     * @param array $data
     * @return array
     */
    protected function getMenuList(array $data): array
    {
        $menu = [];
        foreach ($data as $key => $items) {
            if ($items['pid'] == "0") {
                unset($data[$key]);
                $menu[] = $this->buildMenuTree($items, (int)$items['id']);
            }
        }
        return $menu;
    }

    /**
     * Notes: 生成菜单树
     *
     * Author: chentulin
     * DateTime: 2020/8/5 17:16
     * E-MAIL: <chentulinys@163.com>
     * @param array $val
     * @param int $pid
     * @return array
     */
    protected function buildMenuTree(array $val, int $pid): array
    {
        $childS = $this->getChildMenu($val, $pid);
        if (isset($childS['childNode'])) {
            foreach ($childS['childNode'] as $key => $value) {
                $children = $this->buildMenuTree($value, (int)$value['id']);
                if (null != $children['childNode']) {
                    $childS['childNode'][$key]['childNode'] = $children['childNode'];
                }
            }
        }
        return $childS;
    }

    /**
     * Notes: 获取子菜单
     *
     * Author: chentulin
     * DateTime: 2020/8/5 17:19
     * E-MAIL: <chentulinys@163.com>
     * @param $items
     * @param $rid
     * @return mixed
     */
    protected function getChildMenu($items, $rid)
    {
        foreach ($this->menus as $key => $value) {
            if ($value['pid'] == $rid) {
                unset($this->menus[$key]);
                $items['childNode'][] = $value;
            }
        }
        return $items;
    }


    /**
     * Notes: var_dump格式化数组
     *
     * Author: chentulin
     * DateTime: 2020/8/5 17:36
     * E-MAIL: <chentulinys@163.com>
     * @param $vars
     * @param string $label
     * @param bool $return
     * @return string|null
     */
    public function dump($vars, $label = '', $return = false)
    {
        if (ini_get('html_errors')) {
            $content = "<pre>\n";
            if ($label != '') {
                $content .= "<strong>{$label} :</strong>\n";
            }
            $content .= htmlspecialchars(print_r($vars, true));
            $content .= "\n</pre>\n";
        } else {
            $content = $label . " :\n" . print_r($vars, true);
        }
        if ($return) {
            return $content;
        }
        echo $content;
        return null;
    }

    /**
     * Notes: 获取所有的权限
     *
     * @return array
     * @author: chentulin
     * Date: 2020/8/10
     * Time: 17:13
     */
    public function getAllRule()
    {
        $menuArr     = $this->select('id as id ,pid,rule_name as label')->findAll();
        $this->menus = $menuArr;
        // 整理权限
        return $this->getMenuList($menuArr);
    }

    /**
     * Notes: 获取所有的角色
     *
     * @author: chentulin
     * Date: 2020/8/11
     * Time: 16:31
     */
    public function getAllRole()
    {
        $query = $this->connect->table('role');
        return $query->select('id,role_name as role')->where('is_enabled', 1)->get()->getResult('array');
    }

    /**
     * Notes: 根据角色Id选中权限
     *
     * @param int $id
     * @return array
     * @author: chentulin
     * Date: 2020/8/11
     * Time: 18:01
     */
    public function getRuleByRoleId(int $id): array
    {
        $res = [];
        // 如果是管理员 就返回所有权限id
        if ($id === 1) {
            $res = $this->select('id')->findAll();
        } else {
            $res = $this->getRuleById($id);
        }
        return $res;
    }

    /**
     * Notes: 修改角色权限
     *
     * @param array $params
     * @return array
     * @author: chentulin
     * Date: 2020/8/13
     * Time: 19:12
     */
    public function changeRoleByRule(array $params): array
    {
        if ((int)$params['id'] === 1) {
            return ['code' => 20004, 'msg' => '超级管理员不允许修改权限，默认拥有全部权限', ''];
        }
        $id                 = $params['id'];
        $ruleArr            = array_column($this->getRuleById((int)$id), 'id');
        $fun                = function ($string) {
            return (int)$string;
        };
        $baseRuleIntArrBase = array_map($fun, $ruleArr);
        $postRuleIntArrBase = array_map($fun, $params['changeRuleArr']);
        if ((count(array_diff($baseRuleIntArrBase, $postRuleIntArrBase)) === 0) && (count(array_diff($postRuleIntArrBase, $baseRuleIntArrBase)) === 0)) {
            return ['code' => 20005, 'msg' => '请确认你有更改权限', ''];
        }
        $addArr = array_diff($postRuleIntArrBase, $baseRuleIntArrBase);
        $delArr = array_diff($baseRuleIntArrBase, $postRuleIntArrBase);
        $res    = false;
        // 新增权限
        if (count($addArr) !== 0) {
            $insertArr = [];
            foreach ($addArr as $val) {
                $insertArr[] = [
                    'role' => $id,
                    'rule' => $val,
                ];
            }
            $qb     = $this->connect->table('role_rule');
            $result = $qb->insertBatch($insertArr);
            if ($result === false) {
                $res = $result;
            } else {
                $res = true;
            }
        }
        // 取消权限
        if (count($delArr) !== 0) {
            $qbDel = $this->connect->table('role_rule');
            $qbDel->where('role', $id);
            $qbDel->whereIn('rule', $delArr);
            $resDel = $qbDel->delete();
            if ($resDel === false) {
                $res = $resDel;
            } else {
                $res = true;
            }
        }
        if ($res) {
            return [];
        } else {
            return ['code' => 20006, 'msg' => '修改权限失败', ''];
        }
    }

    /**
     * Notes: 获取角色权限
     *
     * Author: chentulin
     * DateTime: 2020/8/14 9:51
     * E-MAIL: <chentulinys@163.com>
     * @param int $id
     * @return array
     */
    public function getRuleById(int $id)
    {
        $query = $this->connect->table('role_rule');
        return $query->select('rule as id')->where('role', (int)$id)->get()->getResultArray();
    }

    /**
     * Notes: 获取权限详情
     *
     * @param int $id
     * @return array
     * @author: chentulin
     * Date: 2020/8/20
     * Time: 17:38
     */
    public function ruleDetail(int $id)
    {
        $query = $this->connect->table('rule');
        return $query->select('*')->where('id', (int)$id)->get()->getResultArray();
    }

    /**
     * Notes: 修改权限
     *
     * @param array $data
     * @author: chentulin
     * Date: 2020/8/20
     * Time: 20:01
     */
    public function editRule(array $data)
    {

    }
}