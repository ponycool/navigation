<?php
/**
 * Created By PhpStorm
 * User: Pony
 * Data: 2025/3/7
 * Time: 16:26
 */
declare(strict_types=1);

namespace App\Controllers\Api\Open\Website;

use App\Controllers\Api\Base;
use App\Enums\Code;
use App\Services\WebsiteService;
use Exception;

class Parse extends Base
{
    /**
     * 站点解析
     * @return void
     */
    public function index(): void
    {
        $url = $this->request->getGet('url');
        try {
            if (empty($url)) {
                throw new Exception('url不能为空');
            }
            if (!filter_var($url, FILTER_VALIDATE_URL)) {
                throw new Exception('url格式错误');
            }
            $svc = new WebsiteService();
            $res = $svc->parse($url);
            if ($res === false) {
                throw new Exception('站点解析失败');
            }
            $data = [
                'code' => Code::OK,
                'message' => '站点解析成功',
            ];
            $data = array_merge($data, $res);
        } catch (Exception $e) {
            $data = [
                'code' => Code::FAIL,
                'message' => $e->getMessage() ?: '站点解析失败'
            ];
        }
        $this->render($data);
    }
}