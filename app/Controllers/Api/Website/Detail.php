<?php
/**
 * Created By PhpStorm
 * User: Pony
 * Data: 2025/3/7
 * Time: 14:11
 */
declare(strict_types=1);

namespace App\Controllers\Api\Website;

use App\Controllers\Api\Base;
use App\Services\WebsiteService;
use App\Enums\Code;
use Exception;

class Detail extends Base
{
    /**
     * 获取站点详情
     * @return void
     */
    public function index(): void
    {
        $this->postFilter();
        $rules = [
            'uuid' => [
                'rules' => 'required|min_length[35]|max_length[37]',
                'errors' => [
                    'required' => '参数站点UUID[uuid]为必填项',
                    'min_length' => '参数站点UUID[uuid]无效',
                    'max_length' => '参数站点UUID[uuid]无效',
                ]
            ],
        ];
        $this->verifyJsonInputByRules($rules);
        try {
            $params = $this->getJsonInputParams();
            $uuid = $params['uuid'] ?? null;
            if ($this->validateUuid($uuid) !== true) {
                throw new Exception('无效的站点UUID');
            }
            $svc = new WebsiteService();
            $res = $svc->getDetailByUuid($uuid);
            if (is_null($res)) {
                throw new Exception('获取站点失败');
            }
            $data = [
                'code' => Code::OK,
                'message' => '获取站点成功',
            ];
            $data = array_merge($data, $res);
        } catch (Exception $e) {
            $data = [
                'code' => Code::FAIL,
                'message' => $e->getMessage() ?: '获取站点失败',
            ];
        }
        $this->render($data);
    }
}