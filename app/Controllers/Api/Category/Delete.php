<?php
/**
 * Created By PhpStorm
 * User: Pony
 * Data: 2025/3/4
 * Time: 16:31
 */
declare(strict_types=1);

namespace App\Controllers\Api\Category;

use App\Controllers\Api\Base;
use App\Enums\Code;
use App\Services\CategoryService;
use Exception;

class Delete extends Base
{
    /**
     * 删除分类
     * @return void
     */
    public function index(): void
    {
        $this->postFilter();
        try {
            $params = $this->getJsonInputParams();
            $uuid = $params['uuid'] ?? null;
            if ($this->validateUUID($uuid) !== true) {
                throw new Exception('无效的分类UUID');
            }
            $svc = new CategoryService();
            $res = $svc->del($uuid);
            if ($res !== true) {
                throw new Exception($res);
            }
            $data = [
                'code' => Code::OK,
                'message' => '删除分类成功',
            ];
        } catch (Exception $e) {
            $data = [
                'code' => Code::FAIL,
                'message' => $e->getMessage() ?: '删除分类失败'
            ];
        }
        $this->render($data);
    }
}