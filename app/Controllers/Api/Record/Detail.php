<?php
/**
 * Created By PhpStorm
 * User: Pony
 * Data: 2025/3/20
 * Time: 10:21
 */
declare(strict_types=1);

namespace App\Controllers\Api\Record;

use App\Controllers\Api\Base;
use App\Enums\Code;
use App\Services\RecordService;
use Exception;

class Detail extends Base
{
    /**
     * 获取记录详情
     * @return void
     */
    public function index(): void
    {
        $this->postFilter();
        $rules = [
            'uuid' => [
                'rules' => 'required|min_length[35]|max_length[37]',
                'errors' => [
                    'required' => '参数记录UUID[uuid]为必填项',
                    'min_length' => '参数记录UUID[uuid]无效',
                    'max_length' => '参数记录UUID[uuid]无效',
                ]
            ],
        ];
        $this->verifyJsonInputByRules($rules);
        try {
            $params = $this->getJsonInputParams();
            $uuid = $params['uuid'] ?? null;
            if ($this->validateUUID($uuid) !== true) {
                throw new Exception('无效的记录UUID');
            }
            $svc = new RecordService();
            $res = $svc->getDetailByUuid($uuid);
            if (is_null($res)) {
                throw new Exception('获取记录失败');
            }
            $data = [
                'code' => Code::OK,
                'message' => '获取记录成功',
            ];
            $data = array_merge($data, $res);
        } catch (Exception $e) {
            $data = [
                'code' => Code::FAIL,
                'message' => $e->getMessage() ?: '获取记录失败',
            ];
        }
        $this->render($data);
    }
}