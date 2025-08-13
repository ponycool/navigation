<?php
/**
 * Created By PhpStorm
 * User: Pony
 * Data: 2025/3/7
 * Time: 14:20
 */
declare(strict_types=1);

namespace App\Controllers\Api\Website;

use App\Controllers\Api\Base;
use App\Enums\Code;
use App\Services\WebsiteService;
use Exception;

class Enable extends Base
{
    /**
     * 启用站点
     * @return void
     */
    public function index(): void
    {
        $this->postFilter();
        try {
            $params = $this->getJsonInputParams();
            $uuid = $params['uuid'] ?? null;
            if ($this->validateUuid($uuid) !== true) {
                throw new Exception('无效的站点UUID');
            }
            $svc = new WebsiteService();
            $data = [
                'offline_count_subtotal' => 0,
                'health_status' => 1,
                'status' => 1,
            ];
            $cond = [
                'uuid' => $uuid
            ];
            $res = $svc->updateByCond($data, $cond);
            if ($res === false) {
                throw new Exception('启用站点失败');
            }
            $data = [
                'code' => Code::OK,
                'message' => '启用站点成功',
            ];
        } catch (Exception $e) {
            $data = [
                'code' => Code::FAIL,
                'message' => $e->getMessage() ?: '启用站点失败'
            ];
        }
        $this->render($data);
    }
}