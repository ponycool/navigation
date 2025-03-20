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
     * 获取基础验证规则
     * @return array[]
     */
    public function getBaseRules(): array
    {
        return [
            'websiteId' => [
                'rules' => 'if_exist|is_natural',
                'errors' => [
                    'is_natural' => '参数站点ID[websiteId]无效,分类ID必须为自然数',
                ]
            ],
            'websiteName' => [
                'rules' => 'if_exist|max_length[200]',
                'errors' => [
                    'max_length' => '参数网站站点名称[websiteName]无效，字符长度不能超过200个字符',
                ]
            ],
            'url' => [
                'rules' => 'if_exist|max_length[200]',
                'errors' => [
                    'max_length' => '参数网站站点URL[url]无效，字符长度不能超过200个字符',
                ]
            ],
            'browser' => [
                'rules' => 'if_exist|max_length[50]',
                'errors' => [
                    'max_length' => '参数浏览器名称[browser]无效,字符长度不能超过50个字符',
                ]
            ],
            'version' => [
                'rules' => 'if_exist|max_length[50]',
                'errors' => [
                    'max_length' => '参数浏览器的版本号[version]无效,字符长度不能超过50个字符',
                ]
            ],
            'mobile' => [
                'rules' => 'if_exist|max_length[50]',
                'errors' => [
                    'max_length' => '参数移动设备的名称[mobile]无效，字符长度不能超过50个字符',
                ]
            ],
            'platform' => [
                'rules' => 'if_exist|max_length[50]',
                'errors' => [
                    'max_length' => '参数操作系统[platform]无效，字符长度不能超过50个字符',
                ]
            ],
            'referrer' => [
                'rules' => 'if_exist|max_length[200]',
                'errors' => [
                    'max_length' => '参数引用网站[referrer]无效，字符长度不能超过200个字符',
                ]
            ],
            'ip' => [
                'rules' => 'if_exist|max_length[50]',
                'errors' => [
                    'max_length' => '参数IP地址[ip]无效，字符长度不能超过50个字符',
                ]
            ],
            'startTime' => [
                'rules' => 'if_exist|valid_date[Y-m-d]',
                'errors' => [
                    'valid_date' => '参数创建开始时间[startTime]无效，必须为"Y-m-d"格式的字符串',
                ]
            ],
            'endTime' => [
                'rules' => 'if_exist|valid_date[Y-m-d]',
                'errors' => [
                    'valid_date' => '参数创建结束时间[endTime]无效，必须为"Y-m-d"格式的字符串',
                ]
            ],
        ];
    }

    /**
     * 获取查询规则
     * @return array
     */
    public function getQueryRules(): array
    {
        return array_merge($this->getBaseRules(), [
            'keyword' => [
                'rules' => 'if_exist|max_length[50]',
                'errors' => [
                    'max_length' => '参数搜索关键词[keyword]无效，字符长度不能超过50个字符',
                ]
            ],
        ]);
    }

    /**
     * 根据UUID获取点击记录详情
     * @param string $uuid
     * @return array|null
     */
    public function getDetailByUuid(string $uuid): ?array
    {
        if ($this->validateUUID($uuid) !== true) {
            return null;
        }
        return $this->getFirstByUuid($uuid);
    }

    /**
     * 获取记录列表
     * @param array $params
     * @return array
     */
    public function getList(array $params): array
    {
        $page = (int)($params['page'] ?? 1);
        $pageSize = (int)($params['pageSize'] ?? 10);
        $websiteId = $params['websiteId'] ?? null;
        $websiteName = $params['websiteName'] ?? null;
        $url = $params['url'] ?? null;
        $browser = $params['browser'] ?? null;
        $version = $params['version'] ?? null;
        $mobile = $params['mobile'] ?? null;
        $platform = $params['platform'] ?? null;
        $referrer = $params['referrer'] ?? null;
        $ip = $params['ip'] ?? null;
        $startTime = $params['startTime'] ?? null;
        $endTime = $params['endTime'] ?? null;
        $keyword = $params['keyword'] ?? null;
        $cond = [];
        if (!is_null($websiteId)) {
            $cond['website_id'] = $websiteId;
        }
        if (!is_null($websiteName)) {
            $cond['website_name'] = $websiteName;
        }
        if (!is_null($url)) {
            $cond['url'] = $url;
        }
        if (!is_null($browser)) {
            $cond['browser'] = $browser;
        }
        if (!is_null($version)) {
            $cond['version'] = $version;
        }
        if (!is_null($mobile)) {
            $cond['mobile'] = $mobile;
        }
        if (!is_null($platform)) {
            $cond['platform'] = $platform;
        }
        if (!is_null($referrer)) {
            $cond['referrer'] = $referrer;
        }
        if (!is_null($ip)) {
            $cond['ip'] = $ip;
        }
        if (!is_null($startTime)) {
            $cond["strftime('%Y-%m-%d',created_at) >= "] = $startTime;
        }
        if (!is_null($endTime)) {
            $cond["strftime('%Y-%m-%d',created_at) <= "] = $endTime;
        }
        if (!empty($keyword)) {
            $cond['orLike'] = [
                'website_name' => $keyword,
                'url' => $keyword,
                'user_agent' => $keyword,
                'ip' => $keyword,
            ];
        }
        return $this->getPage($page, $pageSize, $cond);
    }

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
