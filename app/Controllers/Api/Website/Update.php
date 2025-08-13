<?php
/**
 * Created By PhpStorm
 * User: Pony
 * Data: 2025/3/7
 * Time: 14:26
 */
declare(strict_types=1);

namespace App\Controllers\Api\Website;

use App\Controllers\Api\Base;
use App\Enums\Code;
use App\Services\WebsiteService;
use Exception;

class Update extends Base
{
    /**
     * 更新站点
     * @return void
     */
    public function index(): void
    {
        $this->postFilter();
        $svc = new WebsiteService();
        $rules = $svc->getUpdateRules();
        $this->verifyJsonInputByRules($rules);
        try {
            $params = $this->getJsonInputParams();
            $uuid = $params['uuid'] ?? null;
            if ($this->validateUuid($uuid) !== true) {
                throw new Exception('无效的站点UUID');
            }
            $res = $svc->update($params);
            if ($res !== true) {
                throw new Exception($res);
            }
            $data = [
                'code' => Code::OK,
                'message' => '更新站点成功',
            ];
        } catch (Exception $e) {
            $data = [
                'code' => Code::FAIL,
                'message' => $e->getMessage() ?: '更新站点失败'
            ];
        }
        $this->render($data);
    }
}