<?php
/**
 * Created By PhpStorm
 * User: Pony
 * Data: 2025/3/20
 * Time: 10:35
 */
declare(strict_types=1);

namespace App\Controllers\Api\Record;

use App\Controllers\Api\Base;
use App\Enums\Code;
use App\Services\RecordService;
use Exception;

class Query extends Base
{
    /**
     * 获取记录列表
     * @return void
     */
    public function index(): void
    {
        $this->postFilter();
        $this->validatePageParamsFromJsonInput();
        $svc = new RecordService();
        $rules = $svc->getQueryRules();
        $this->verifyJsonInputByRules($rules);
        try {
            $params = $this->getJsonInputParams();
            $list = $svc->getList($params);
            $data = [
                'code' => Code::OK,
                'message' => '获取记录列表成功',
            ];
            $data = array_merge($data, $list);
        } catch (Exception $e) {
            $data = [
                'code' => Code::FAIL,
                'message' => $e->getMessage() ?: '获取记录列表失败',
            ];
        }
        $this->render($data);
    }
}