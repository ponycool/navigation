<?php
/**
 * Created By PhpStorm
 * User: Pony
 * Data: 2025/3/1
 * Time: 16:56
 */
declare(strict_types=1);

namespace App\Controllers\Api\Category;

use App\Controllers\Api\Base;
use App\Enums\Code;
use App\Services\CategoryService;
use Exception;

class Create extends Base
{
    /**
     * 创建分类
     * @return void
     */
    public function index(): void
    {
        $this->postFilter();
        $svc = new CategoryService();
        $rules = $svc->getCreateRules();
        $this->verifyJsonInputByRules($rules);
        try {
            $params = $this->getJsonInputParams();
            $res = $svc->create($params);
            if ($res !== true) {
                throw new Exception($res);
            }
            $data = [
                'code' => Code::OK,
                'message' => '创建分类成功',
            ];
        } catch (Exception $e) {
            $data = [
                'code' => Code::FAIL,
                'message' => $e->getMessage() ?: '创建分类失败'
            ];
        }
        $this->render($data);
    }
}