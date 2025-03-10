<?php
/**
 * Created By PhpStorm
 * User: Pony
 * Data: 2025/3/6
 * Time: 17:02
 */
declare(strict_types=1);

namespace App\Controllers\Api\Website;

use App\Controllers\Api\Base;
use App\Enums\Code;
use App\Services\WebsiteService;
use Exception;

class Create extends Base
{
    /**
     * 创建网站站点
     * @return void
     */
    public function index(): void
    {
        $this->postFilter();
        $svc = new WebsiteService();
        $rules = $svc->getCreateRules();
        $this->verifyJsonInputByRules($rules);
        try {
            $params = $this->getJsonInputParams();
            $res = $svc->create($params);
            if ($res !== true) {
                throw new Exception($res);
            }
            $data = [
                'code' => Code::OK,
                'message' => '创建网站站点成功',
            ];
        } catch (Exception $e) {
            $data = [
                'code' => Code::FAIL,
                'message' => $e->getMessage() ?: '创建网站站点失败'
            ];
        }
        $this->render($data);
    }
}