<?php
/**
 * Created By PhpStorm
 * User: Pony
 * Data: 2025/3/4
 * Time: 17:42
 */
declare(strict_types=1);

namespace App\Controllers\Api\Category;

use App\Controllers\Api\Base;
use App\Enums\Code;
use App\Services\CategoryService;
use Exception;

class Update extends Base
{
    /**
     * 更新分类
     * @return void
     */
    public function index(): void
    {
        $this->postFilter();
        $svc = new CategoryService();
        $rules = $svc->getUpdateRules();
        $this->verifyJsonInputByRules($rules);
        try {
            $params = $this->getJsonInputParams();
            $uuid = $params['uuid'] ?? null;
            if ($this->validateUuid($uuid) !== true) {
                throw new Exception('无效的分类UUID');
            }
            $res = $svc->update($params);
            if ($res !== true) {
                throw new Exception($res);
            }
            $data = [
                'code' => Code::OK,
                'message' => '更新分类成功',
            ];
        } catch (Exception $e) {
            $data = [
                'code' => Code::FAIL,
                'message' => $e->getMessage() ?: '更新分类失败'
            ];
        }
        $this->render($data);
    }
}