<?php
/**
 * Created By PhpStorm
 * User: Pony
 * Data: 2025/8/16
 * Time: 16:21
 */
declare(strict_types=1);

namespace App\Controllers\Api\Tag;

use App\Controllers\Api\Base;
use App\Enums\Code;
use App\Services\TagService;
use Exception;

class Delete extends Base
{
    public function index(): void
    {
        $this->postFilter();
        try {
            $params = $this->getJsonInputParams();
            $uuid = $params['uuid'] ?? '';
            if ($this->isValidUuid($uuid) !== true) {
                throw new Exception('无效的标签UUID');
            }
            $svc = new TagService();
            $svc->del($uuid);
            $data = [
                'code' => Code::OK,
                'message' => '删除标签成功',
            ];
        } catch (Exception $e) {
            $data = [
                'code' => Code::FAIL,
                'message' => $e->getMessage() ?: '删除标签失败'
            ];
        }
        $this->render($data);
    }
}