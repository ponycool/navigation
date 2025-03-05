<?php
/**
 * Created By PhpStorm
 * User: Pony
 * Data: 2025/3/4
 * Time: 17:38
 */
declare(strict_types=1);

namespace App\Controllers\Api\Category;

use App\Controllers\Api\Base;
use App\Enums\Code;
use App\Services\CategoryService;
use Exception;

class Query extends Base
{
    /**
     * 获取分类列表
     * @return void
     */
    public function index(): void
    {
        $this->postFilter();
        $svc = new CategoryService();
        try {
            $list = $svc->getList();
            $data = [
                'code' => Code::OK,
                'message' => '获取分类列表成功',
            ];
            $data = array_merge($data, $list);
        } catch (Exception $e) {
            $data = [
                'code' => Code::FAIL,
                'message' => $e->getMessage() ?: '获取分类列表失败',
            ];
        }
        $this->render($data);
    }
}