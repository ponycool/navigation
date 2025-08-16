<?php
/**
 * Created by PhpStorm.
 * User: Pony
 * Date: 2025/04/02
 * Time: 16:45 下午
 */
declare(strict_types=1);

namespace App\Services;

use App\Entities\Tag;
use Exception;

class TagService extends BaseService
{
    public function getBaseRules(): array
    {
        $rules = [
            'tagName' => [
                'rules' => 'if_exist|min_length[1]|max_length[50]',
                'errors' => [
                    'min_length' => '标签名称长度不能小于1个字符',
                    'max_length' => '标签名称长度不能超过50个字符'
                ]
            ],
            'tagDescription' => [
                'rules' => 'if_exist|min_length[1]|max_length[255]',
                'errors' => [
                    'min_length' => '标签描述长度不能小于1个字符',
                    'max_length' => '标签描述长度不能超过255个字符'
                ]
            ],
        ];
        return array_merge(
            parent::getBaseRules(),
            $rules
        );
    }

    public function getCreateRules(): array
    {
        $rules = [
            'tagName' => [
                'rules' => 'required|min_length[1]|max_length[50]',
                'errors' => [
                    'required' => '标签名称不能为空',
                    'min_length' => '标签名称长度不能小于1个字符',
                    'max_length' => '标签名称长度不能超过50个字符'
                ]
            ]
        ];
        return array_merge(
            self::getBaseRules(),
            $rules
        );
    }

    public function getUpdateRules(): array
    {
        $rules = [
            'uuid' => [
                'rules' => 'required|uuid',
                'errors' => [
                    'required' => '标签UUID不能为空',
                    'uuid' => '标签UUID格式错误'
                ]
            ]
        ];
        return array_merge(
            self::getBaseRules(),
            $rules
        );
    }

    public function getTagByUuid(string $uuid): ?array
    {
        if ($this->isValidUuid($uuid) !== true) {
            return null;
        }
        return $this->getFirstByUuid($uuid);
    }

    public function getList(): array
    {
        return $this->get();
    }

    /**
     * @throws Exception
     */
    public function create(array $data): bool
    {
        $data = self::prepare($data);
        $tagName = $data['tag_name'];
        $cond = [
            'tag_name' => $tagName
        ];
        $record = $this->getFirstByCond($cond);
        if ($record) {
            throw new Exception('标签已存在');
        }
        $tag = new Tag();
        $tag->fill($data)
            ->filterInvalidProperties();
        $res = $this->insert($tag);
        if ($res !== true) {
            throw new Exception('创建标签失败');
        }
        return true;
    }

    /**
     * 更新标签
     * @throws Exception
     */
    public function update(array $data): bool
    {
        $data = self::prepare($data);
        $record = $this->getFirstByUuid($data['uuid']);
        if (!$record) {
            throw new Exception('标签不存在');
        }

        $tag = new Tag();
        $tag->fillData($data)
            ->filterInvalidProperties();

        $res = $this->updateByUuid($tag);
        if ($res !== true) {
            throw new Exception('更新标签失败');
        }
        return true;
    }

    /**
     * 删除标签
     * @throws Exception
     */
    public function del(string $uuid): bool
    {
        $record = $this->getFirstByUuid($uuid);
        if (!$record) {
            throw new Exception('标签不存在');
        }
        $id = (int)$record['id'];
        $res = $this->delete($id);
        if ($res !== true) {
            throw new Exception('删除标签失败');
        }
        return true;
    }

    private function prepare(array $data): array
    {
        return parent::prepareData($data);
    }
}
