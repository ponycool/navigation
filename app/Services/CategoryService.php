<?php
/**
 * Created by PhpStorm.
 * User: Pony
 * Date: 2025/03/01
 * Time: 16:30 下午
 */
declare(strict_types=1);

namespace App\Services;

use App\Entities\Category;
use App\Enums\DeletedStatus;

class CategoryService extends BaseService
{
    /**
     * 获取基础验证规则
     * @return array[]
     */
    public function getBaseRules(): array
    {
        return [
            'pid' => [
                'rules' => 'if_exist|is_natural',
                'errors' => [
                    'is_natural' => '参数父级ID[pid]无效,父级ID必须为自然数',
                ]
            ],
            'name' => [
                'rules' => 'required|max_length[50]',
                'errors' => [
                    'required' => '参数分类名称[name]无效，分类名称为必填项',
                    'max_length' => '参数分类名称[name]无效，字符长度不能超过50个字符',
                ]
            ],
            'icon' => [
                'rules' => 'if_exist|max_length[50]',
                'errors' => [
                    'max_length' => '参数分类图标[icon]无效,字符长度不能超过50个字符',
                ]
            ],
            'code' => [
                'rules' => 'if_exist|max_length[20]',
                'errors' => [
                    'max_length' => '参数分类编码[code]无效，字符长度不能超过20个字符',
                ]
            ],
            'level' => [
                'rules' => 'if_exist|is_natural_no_zero',
                'errors' => [
                    'is_natural_no_zero' => '参数分类层级[level]无效，必须为非零自然数',
                ]
            ],
            'sortIndex' => [
                'rules' => 'if_exist|is_natural_no_zero',
                'errors' => [
                    'is_natural_no_zero' => '参数排序索引[sortIndex]无效，必须为非零自然数',
                ]
            ],
            'status' => [
                'rules' => 'if_exist|in_list[true,false]',
                'errors' => [
                    'in_list' => '参数分类状态[status]无效，必须为"true"或"false"',
                ]
            ],
        ];
    }

    /**
     * 获取创建验证规则
     * @return array
     */
    public function getCreateRules(): array
    {
        $rules = [
            'name' => [
                'rules' => 'required|max_length[50]',
                'errors' => [
                    'required' => '参数分类名称[name]无效，分类名称为必填项',
                    'max_length' => '参数分类名称[name]无效，字符长度不能超过50个字符',
                ]
            ],
        ];
        return array_merge(
            $this->getBaseRules(),
            $rules
        );
    }

    /**
     * 获取更新验证规则
     * @return array
     */
    public function getUpdateRules(): array
    {
        $rules = [
            'uuid' => [
                'rules' => 'required|min_length[35]|max_length[37]',
                'errors' => [
                    'required' => '参数分类UUID[uuid]为必填项',
                    'min_length' => '参数分类UUID[uuid]无效',
                    'max_length' => '参数分类UUID[uuid]无效',
                ]
            ],
        ];
        return array_merge(
            $this->getBaseRules(),
            $rules
        );
    }

    /**
     * 获取分类列表
     * @return array
     */
    public function getList(): array
    {
        $sql = [
            'SELECT id,uuid,pid,name,icon,code,level,sort_index,status,created_at,updated_at ',
            'FROM swap_category ',
            'WHERE deleted_at IS NULL ',
            'AND deleted = ? ',
            'ORDER BY sort_index ASC, created_at DESC ',
        ];
        $sqlParams = [
            DeletedStatus::UNDELETED->value,
        ];

        $sql = $this->assembleSql($sql);
        $this->setResultType('array');
        $res = $this->query($sql, $sqlParams);
        if (count($res) > 0) {
            $res = self::mergeMedia($res);
        }
        return $res;
    }

    /**
     * 获取启用的分类列表
     * @return array
     */
    public function getEnableList(): array
    {
        $sql = [
            'SELECT id,uuid,pid,name,icon,code,level,sort_index,status,created_at,updated_at ',
            'FROM swap_category ',
            'WHERE status = 1 ',
            'AND deleted_at IS NULL ',
            'AND deleted = ? ',
            'ORDER BY sort_index ASC, created_at DESC ',
        ];
        $sqlParams = [
            DeletedStatus::UNDELETED->value,
        ];

        $sql = $this->assembleSql($sql);
        $this->setResultType('array');
        $res = $this->query($sql, $sqlParams);
        if (count($res) > 0) {
            $res = self::mergeMedia($res);
        }
        return $res;
    }

    /**
     * 根据UUID获取分类
     * @param string $uuid
     * @return array|null
     */
    public function getCategoryByUuid(string $uuid): ?array
    {
        if ($this->validateUUID($uuid) !== true) {
            return null;
        }
        $res = $this->getFirstByUuid($uuid);
        if (count($res) > 0) {
            $res = self::mergeMedia([$res])[0];
        }
        return $res;
    }

    /**
     * 根据父级ID获取子级分类
     * @param array $list
     * @param int|null $id
     * @return array
     */
    public function getChildById(array $list, int|null $id): array
    {
        $res = [];
        if (is_null($id)) {
            foreach ($list as $item) {
                if ($item['pid'] == 0 && $item['level'] == 1) {
                    $id = $item['id'];
                    break;
                }
            }
        }
        $ids = array_column($list, 'id');
        $key = array_search($id, $ids);
        $category = $key !== false ? $list[$key] : null;
        if (is_null($category)) {
            return $res;
        }
        $res = $this->getChildByPid($list, $id);
        if (count($res) > 0) {
            // 如果存在三级分类，则直接返回三级分类
            $tertiaryCategories = array_filter($res, function ($item) {
                return $item['level'] == 3;
            });
            if (count($tertiaryCategories) > 0) {
                return $tertiaryCategories;
            }
        }
        return $res;
    }

    /**
     * 根据父级ID递归获取子级分类
     * @param array $list
     * @param int $pid
     * @return array
     */
    public function getChildByPid(array $list, int $pid = 0): array
    {
        $res = [];
        foreach ($list as $item) {
            if ($item['pid'] == $pid) {
                $children = $this->getChildByPid($list, $item['id']);
                if (!empty($children)) {
                    $res = array_merge($res, $children);
                }
                $res[] = $item;
            }
        }
        return $res;
    }

    /**
     * 创建分类
     * @param array $params
     * @return bool|string
     */
    public function create(array $params): bool|string
    {
        // 准备数据
        $data = self::prepare($params);
        if (is_string($data)) {
            return $data;
        }

        $category = new Category();
        $category->fill($data)
            ->filterInvalidProperties();

        $res = $this->insert($category);
        if ($res !== true) {
            return '创建分类失败';
        }
        return true;
    }

    /**
     * 更新分类
     * @param array $params
     * @return bool|string
     */
    public function update(array $params): bool|string
    {
        $data = self::prepare($params);
        if (is_string($data)) {
            return $data;
        }

        $raw = $this->getFirstByUuid($data['uuid']);
        if (empty($raw)) {
            return '分类UUID不存在';
        }

        $category = new Category();
        $category->fillData($data)
            ->filterInvalidProperties();

        $res = $this->updateByUuid($category);
        if ($res !== true) {
            return '更新分类失败';
        }
        return true;
    }

    /**
     * 删除分类
     * @param string $uuid
     * @return bool|string
     */
    public function del(string $uuid): bool|string
    {
        $category = $this->getFirstByUuid($uuid);
        if (empty($category)) {
            return '分类UUID不存在';
        }
        $id = (int)$category['id'];
        $res = $this->delete($id);
        if ($res !== true) {
            return '删除分类失败';
        }
        return true;
    }

    /**
     * 预处理数据
     * @param array $data
     * @return string|array
     */
    public function prepare(array $data): string|array
    {
        $data = $this->convertParamsToSnakeCase($data);
        // 校验图片
        $mediaSvc = new MediaService();
        $iconName = $data['icon'] ?? null;
        if (!is_null($iconName)) {
            $media = $mediaSvc->getByMediaName($iconName);
            if (empty($media)) {
                return '无效的分类图标';
            }
            $data['icon'] = (int)$media['id'];
        }

        // 处理状态
        if (!is_null($data['status'] ?? null)) {
            $data['status'] = (int)($data['status'] === 'true');
        }

        $data['pid'] = (int)($data['pid'] ?? 0);
        $data['level'] = (int)($data['level'] ?? 1);
        $data['sort_index'] = (int)($data['sort_index'] ?? 1);

        return $data;
    }

    private function mergeMedia(array $list): array
    {
        $iconList = [];
        foreach ($list as $item) {
            if (!is_null($item['icon'])) {
                $iconList[] = $item['icon'];
            }
        }
        $mediaSvc = new MediaService();
        if (!empty($iconList)) {
            $mediaList = $mediaSvc->getMedia($iconList);
            foreach ($mediaList as $media) {
                foreach ($list as &$item) {
                    if ($media['id'] === $item['icon']) {
                        $item['icon'] = $media['file_url'];
                    }
                }
            }
        }

        return $list;
    }
}
