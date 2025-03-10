<?php
/**
 * Created by PhpStorm.
 * User: Pony
 * Date: 2025/03/07
 * Time: 16:05 下午
 */
declare(strict_types=1);

namespace App\Services;

use App\Entities\Record;

class RecordService extends BaseService
{
    /**
     * 创建记录
     * @param int $websiteId
     * @param string $websiteName
     * @param string $url
     * @return bool
     */
    public function create(int $websiteId, string $websiteName, string $url): bool
    {
        $request = service('request');
        $agent = $request->getUserAgent();
        $record = new Record();
        $record->setWebsiteId($websiteId)
            ->setWebsiteName($websiteName)
            ->setUrl($url)
            ->setBrowser($agent->getBrowser())
            ->setVersion($agent->getVersion())
            ->setMobile($agent->getMobile())
            ->setPlatform($agent->getPlatform())
            ->setReferrer($agent->getReferrer())
            ->setUserAgent($agent->getAgentString())
            ->setIp($request->getIPAddress());
        return $this->insert($record);
    }
}
