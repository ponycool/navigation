<?php
/**
 * Created By PhpStorm
 * User: Pony
 * Data: 2025/3/5
 * Time: 16:56
 */
declare(strict_types=1);

namespace App\Controllers\Api\Open\Track;

use App\Controllers\Api\Base;
use App\Enums\Code;
use App\Services\WebsiteService;
use Exception;

class Ping extends Base
{
    /**
     * 点击追踪
     * @return void
     */
    public function index(): void
    {
        $this->postFilter();
        $url = $this->request->getGet('url');
        try {
            if (empty($url)) {
                throw new Exception('url不能为空');
            }
            if (!filter_var($url, FILTER_VALIDATE_URL)) {
                throw new Exception('url格式错误');
            }
            $svc = new WebsiteService();
            $res = $svc->updateWebsiteStatus($url);
            if ($res !== true) {
                throw new Exception('更新网站状态失败');
            }
            $data = [
                'code' => Code::OK,
            ];
        } catch (Exception) {
            $data = [
                'code' => Code::FAIL,
            ];
        }
        $this->render($data);
    }
}