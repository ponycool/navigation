<?php
/**
 * Created By PhpStorm
 * User: Pony
 * Data: 2025/3/4
 * Time: 16:54
 */
declare(strict_types=1);

namespace App\Controllers\Api\Category;

use App\Controllers\Api\Base;
use App\Enums\Code;
use App\Services\CategoryService;
use Exception;

class Detail extends Base
{
    /**
     * 获取分类详情
     * @return void
     */
    public function index(): void
    {
        $this->postFilter();
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
        $this->verifyJsonInputByRules($rules);
        try {
            $params = $this->getJsonInputParams();
            $uuid = $params['uuid'] ?? null;
            if ($this->validateUuid($uuid) !== true) {
                throw new Exception('无效的分类UUID');
            }
            $svc = new CategoryService();
            $res = $svc->getCategoryByUuid($uuid);
            if (is_null($res)) {
                throw new Exception('获取分类失败');
            }
            $data = [
                'code' => Code::OK,
                'message' => '获取分类成功',
            ];
            $data = array_merge($data, $res);
        } catch (Exception $e) {
            $data = [
                'code' => Code::FAIL,
                'message' => $e->getMessage() ?: '获取分类失败',
            ];
        }
        $this->render($data);
    }
}