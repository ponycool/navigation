<?php
/**
 * Created By PhpStorm
 * User: Pony
 * Data: 2025/3/7
 * Time: 13:23
 */
declare(strict_types=1);

namespace App\Controllers\Api\Website;

use App\Controllers\Api\Base;
use App\Enums\Code;
use App\Services\WebsiteService;
use Exception;

class Delete extends Base
{
    /**
     * 删除站点
     * @return void
     */
    public function index(): void
    {
        $this->postFilter();
        try {
            $params = $this->getJsonInputParams();
            $uuid = $params['uuid'] ?? null;
            if ($this->validateUUID($uuid) !== true) {
                throw new Exception('无效的站点UUID');
            }
            $svc = new WebsiteService();
            $res = $svc->del($uuid);
            if ($res !== true) {
                throw new Exception($res);
            }
            $data = [
                'code' => Code::OK,
                'message' => '删除站点成功',
            ];
        } catch (Exception $e) {
            $data = [
                'code' => Code::FAIL,
                'message' => $e->getMessage() ?: '删除站点失败'
            ];
        }
        $this->render($data);
    }
}