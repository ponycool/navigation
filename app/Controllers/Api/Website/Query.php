<?php
/**
 * Created By PhpStorm
 * User: Pony
 * Data: 2025/3/7
 * Time: 14:24
 */
declare(strict_types=1);

namespace App\Controllers\Api\Website;

use App\Controllers\Api\Base;
use App\Enums\Code;
use App\Services\WebsiteService;
use Exception;

class Query extends Base
{
    /**
     * 获取站点列表
     * @return void
     */
    public function index(): void
    {
        $this->postFilter();
        $this->validatePageParamsFromJsonInput();
        $svc = new WebsiteService();
        $rules = $svc->getBaseRules();
        $this->verifyJsonInputByRules($rules);
        try {
            $params = $this->getJsonInputParams();
            $list = $svc->getList($params);
            $data = [
                'code' => Code::OK,
                'message' => '获取站点列表成功',
            ];
            $data = array_merge($data, $list);
        } catch (Exception $e) {
            $data = [
                'code' => Code::FAIL,
                'message' => $e->getMessage() ?: '获取站点列表失败',
            ];
        }
        $this->render($data);
    }
}