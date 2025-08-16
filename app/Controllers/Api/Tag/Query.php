<?php
/**
 * Created By PhpStorm
 * User: Pony
 * Data: 2025/8/16
 * Time: 16:24
 */
declare(strict_types=1);

namespace App\Controllers\Api\Tag;

use App\Controllers\Api\Base;
use App\Enums\Code;
use App\Services\TagService;
use Exception;

class Query extends Base
{
    public function index(): void
    {
        $this->postFilter();
        $svc = new TagService();
        try {
            $list = $svc->getList();
            $data = [
                'code' => Code::OK,
                'message' => '获取标签列表成功',
            ];
            $data = array_merge($data, $list);
        } catch (Exception $e) {
            $data = [
                'code' => Code::FAIL,
                'message' => $e->getMessage() ?: '获取标签列表失败',
            ];
        }
        $this->render($data);
    }
}