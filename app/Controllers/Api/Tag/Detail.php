<?php
/**
 * Created By PhpStorm
 * User: Pony
 * Data: 2025/8/16
 * Time: 16:22
 */
declare(strict_types=1);

namespace App\Controllers\Api\Tag;

use App\Controllers\Api\Base;
use App\Enums\Code;
use App\Services\TagService;
use Exception;

class Detail extends Base
{
    public function index(): void
    {
        $this->postFilter();
        $rules = $this->getUuidRule();
        $this->verifyJsonInputByRules($rules);
        try {
            $params = $this->getJsonInputParams();
            $uuid = $params['uuid'] ?? '';
            if ($this->isValidUuid($uuid) !== true) {
                throw new Exception('无效的标签UUID');
            }
            $svc = new TagService();
            $res = $svc->getTagByUuid($uuid);
            if (is_null($res)) {
                throw new Exception('获取标签失败');
            }
            $data = [
                'code' => Code::OK,
                'message' => '获取标签成功',
            ];
            $data = array_merge($data, $res);
        } catch (Exception $e) {
            $data = [
                'code' => Code::FAIL,
                'message' => $e->getMessage() ?: '获取标签失败',
            ];
        }
        $this->render($data);
    }

}