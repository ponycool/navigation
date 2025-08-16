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

class Update extends Base
{
    public function index(): void
    {
        $this->postFilter();
        $svc = new TagService();
        $rules = $svc->getUpdateRules();
        $this->verifyJsonInputByRules($rules);
        try {
            $params = $this->getJsonInputParams();
            $uuid = $params['uuid'] ?? '';
            if ($this->isValidUuid($uuid) !== true) {
                throw new Exception('无效的导航标签UUID');
            }
            $svc->update($params);
            $data = [
                'code' => Code::OK,
                'message' => '更新导航标签成功',
            ];
        } catch (Exception $e) {
            $data = [
                'code' => Code::FAIL,
                'message' => $e->getMessage() ?: '更新导航标签失败'
            ];
        }
        $this->render($data);
    }

}