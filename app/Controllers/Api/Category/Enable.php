<?php
/**
 * Created By PhpStorm
 * User: Pony
 * Data: 2025/3/4
 * Time: 17:16
 */
declare(strict_types=1);

namespace App\Controllers\Api\Category;

use App\Controllers\Api\Base;
use App\Enums\Code;
use App\Services\CategoryService;
use Exception;

class Enable extends Base
{
    /**
     * 启用分类
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
            $data = [
                'status' => 1
            ];
            $cond = [
                'uuid' => $uuid
            ];
            $res = $svc->updateByCond($data, $cond);
            if ($res === false) {
                throw new Exception('启用分类失败');
            }
            $data = [
                'code' => Code::OK,
                'message' => '启用分类成功',
            ];
        } catch (Exception $e) {
            $data = [
                'code' => Code::FAIL,
                'message' => $e->getMessage() ?: '启用分类失败'
            ];
        }
        $this->render($data);
    }
}