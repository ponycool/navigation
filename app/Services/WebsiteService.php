<?php
/**
 * Created by PhpStorm.
 * User: Pony
 * Date: 2025/03/06
 * Time: 16:54 下午
 */
declare(strict_types=1);

namespace App\Services;

use App\Entities\Website;
use App\Enums\DeletedStatus;
use App\Enums\WebsiteCreationMethod;
use Carbon\Carbon;
use DOMDocument;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class WebsiteService extends BaseService
{
    /**
     * 获取基础验证规则
     * @return array[]
     */
    public function getBaseRules(): array
    {
        return [
            'cid' => [
                'rules' => 'if_exist|is_natural',
                'errors' => [
                    'is_natural' => '参数分类ID[cid]无效,分类ID必须为自然数',
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
            'icon' => [
                'rules' => 'if_exist|max_length[50]',
                'errors' => [
                    'max_length' => '参数网站站点图标[icon]无效,字符长度不能超过50个字符',
                ]
            ],
            'iconUrl' => [
                'rules' => 'if_exist|max_length[200]',
                'errors' => [
                    'max_length' => '参数网站站点图标URL[iconUrl]无效,字符长度不能超过200个字符',
                ]
            ],
            'description' => [
                'rules' => 'if_exist|max_length[1000]',
                'errors' => [
                    'max_length' => '参数网站站点描述[description]无效，字符长度不能超过1000个字符',
                ]
            ],
            'rating' => [
                'rules' => 'if_exist|is_natural',
                'errors' => [
                    'is_natural' => '参数网站站点评分[rating]无效，必须为自然数',
                ]
            ],
            'sortIndex' => [
                'rules' => 'if_exist|is_natural_no_zero',
                'errors' => [
                    'is_natural_no_zero' => '参数排序索引[sortIndex]无效，必须为非零自然数',
                ]
            ],
            'status' => [
                'rules' => 'if_exist|in_list[true,false]',
                'errors' => [
                    'in_list' => '参数网站站点状态[status]无效，必须为"true"或"false"',
                ]
            ],
        ];
    }

    /**
     * 获取创建验证规则
     * @return array
     */
    public function getCreateRules(): array
    {
        $rules = [
            'cid' => [
                'rules' => 'required|is_natural',
                'errors' => [
                    'required' => '参数分类ID[cid]为必填项',
                    'is_natural' => '参数分类ID[cid]无效,分类ID必须为自然数',
                ]
            ],
            'websiteName' => [
                'rules' => 'required|max_length[50]',
                'errors' => [
                    'required' => '参数网站站点名称[websiteName]无效，网站站点名称为必填项',
                    'max_length' => '参数网站站点名称[websiteName]无效，字符长度不能超过50个字符',
                ]
            ],
            'url' => [
                'rules' => 'required|max_length[200]',
                'errors' => [
                    'required' => '参数网站站点URL[url]无效，网站站点URL为必填项',
                    'max_length' => '参数网站站点URL[url]无效，字符长度不能超过200个字符',
                ]
            ],
        ];
        return array_merge(
            $this->getBaseRules(),
            $rules
        );
    }

    /**
     * 获取更新验证规则
     * @return array
     */
    public function getUpdateRules(): array
    {
        $rules = [
            'uuid' => [
                'rules' => 'required|min_length[35]|max_length[37]',
                'errors' => [
                    'required' => '参数网站站点UUID[uuid]为必填项',
                    'min_length' => '参数网站站点UUID[uuid]无效',
                    'max_length' => '参数网站站点UUID[uuid]无效',
                ]
            ],
        ];
        return array_merge(
            $this->getBaseRules(),
            $rules
        );
    }

    /**
     * 获取网站站点列表
     * @param array $params
     * @return array
     */
    public function getList(array $params): array
    {
        $page = (int)($params['page'] ?? 1);
        $pageSize = (int)($params['pageSize'] ?? 10);
        $cid = $params['cid'] ?? null;
        $websiteName = $params['websiteName'] ?? null;
        $url = $params['url'] ?? null;
        $healthStatus = $params['healthStatus'] ?? null;
        $status = $params['status'] ?? null;
        $startTime = $params['start_time'] ?? null;
        $endTime = $params['end_time'] ?? null;
        $keyword = $params['keyword'] ?? null;
        $isPage = $params['isPage'] ?? true;
        $limit = $params['limit'] ?? null;

        $sql = [
            'SELECT id,uuid,cid,website_name,url,icon,icon_url,description,rating,click_count,check_count,last_check_time,',
            'offline_count,health_status,sort_index,status,created_at,updated_at ',
            'FROM swap_website ',
            'WHERE deleted_at IS NULL ',
            'AND deleted = ? ',
        ];
        $sqlParams = [
            DeletedStatus::UNDELETED->value,
        ];
        if (!is_null($cid)) {
            $sql[] = 'AND cid = ? ';
            $sqlParams[] = $cid;
        }
        if (!is_null($websiteName)) {
            $sql[] = 'AND website_name LIKE ? ';
            $sqlParams[] = '%' . $websiteName . '%';
        }
        if (!is_null($url)) {
            $sql[] = 'AND url LIKE ? ';
            $sqlParams[] = '%' . $url . '%';
        }
        if (!is_null($healthStatus)) {
            $sql[] = 'AND health_status = ? ';
            $sqlParams[] = $healthStatus;
        }
        if (!is_null($status)) {
            $sql[] = 'AND status = ? ';
            $sqlParams[] = $status;
        }
        if (!is_null($startTime)) {
            $sql[] = "AND DATE_FORMAT(last_check_time, '%Y-%m-%d') >= ? ";
            $sqlParams[] = $startTime;
        }
        if (!is_null($endTime)) {
            $sql[] = "AND DATE_FORMAT(last_check_time, '%Y-%m-%d') <= ? ";
            $sqlParams[] = $endTime;
        }
        if (!is_null($keyword)) {
            $sql[] = 'AND (website_name LIKE ? OR url LIKE ?) ';
            $sqlParams[] = '%' . $keyword . '%';
            $sqlParams[] = '%' . $keyword . '%';
        }
        $sql[] = 'ORDER BY sort_index DESC,created_at DESC';
        if (!$isPage && !is_null($limit)) {
            $sql[] = ' LIMIT ? ';
            $sqlParams[] = $limit;
        }
        $sql = $this->assembleSql($sql);
        if ($isPage) {
            $res = $this->getPageByQuery($sql, $sqlParams, $page, $pageSize);
            if ($res['total'] > 0 && is_array($res['pageData'])) {
                $res['pageData'] = $this->mergeData($res['pageData']);
            }

        } else {
            $this->setResultType('array');
            $res = $this->query($sql, $sqlParams);
            if (count($res) > 0) {
                $res = self::mergeData($res);
            }
        }

        return $res;
    }

    /**
     * 根据UUID获取网站站点
     * @param string $uuid
     * @return array|null
     */
    public function getDetailByUuid(string $uuid): ?array
    {
        if ($this->validateUUID($uuid) !== true) {
            return null;
        }
        $res = $this->getFirstByUuid($uuid);
        if (count($res) > 0) {
            $res = self::mergeData([$res])[0];
        }
        return $res;
    }

    public function getListByCond(array $params): array
    {
        $cid = $params['cid'] ?? null;
        echo $cid;
        return [];
    }

    /**
     * 创建网站站点
     * @param array $params
     * @return bool|string
     */
    public function create(array $params): bool|string
    {
        $data = self::prepare($params);
        if (is_string($data)) {
            return $data;
        }

        $cond = [
            'url' => $params['url']
        ];
        $record = $this->getFirstByCond($cond);
        if (!empty($record)) {
            return '网站站点已存在';
        }
        $website = new Website();
        $website->fill($data)
            ->filterInvalidProperties();

        $res = $this->insert($website);
        if ($res !== true) {
            return '创建网站站点失败';
        }
        return true;
    }

    /**
     * 更新网站站点
     * @param array $params
     * @return bool|string
     */
    public function update(array $params): bool|string
    {
        $data = self::prepare($params);
        if (is_string($data)) {
            return $data;
        }

        $raw = $this->getFirstByUuid($data['uuid']);
        if (empty($raw)) {
            return '网站站点UUID不存在';
        }

        $website = new Website();
        $website->fillData($data)
            ->filterInvalidProperties();

        $res = $this->updateByUuid($website);
        if ($res !== true) {
            return '更新网站站点失败';
        }
        return true;
    }


    /**
     * 更新网站站点状态
     * @param string $url
     * @return bool
     */
    public function updateWebsiteStatus(string $url): bool
    {
        $cond = [
            'url' => $url
        ];
        $record = $this->getFirstByCond($cond);
        if (empty($record)) {
            return false;
        }
        $record['click_count'] = (int)$record['click_count'] + 1;
        // 站点健康检查
        $lastCheckTime = $record['last_check_time'];
        if (is_null($lastCheckTime)) {
            $healthStatus = self::checkWebsiteHealth($url);
            $record['health_status'] = (int)$healthStatus;
            $record['last_check_time'] = Carbon::now()->format('Y-m-d H:i:s');
            $record['check_count'] = 1;
            $record['offline_count'] = $healthStatus ? 0 : 1;
            $record['offline_count_subtotal'] = $healthStatus ? 0 : 1;
        } else {
            $lastCheckTime = Carbon::createFromFormat('Y-m-d H:i:s', $record['last_check_time']);
            if (!$lastCheckTime->isToday()) {
                $healthStatus = self::checkWebsiteHealth($url);
                $record['health_status'] = (int)$healthStatus;
                $record['last_check_time'] = Carbon::now()->format('Y-m-d H:i:s');
                $record['check_count'] = (int)$record['check_count'] + 1;
                $record['offline_count'] = $healthStatus ?: (int)$record['offline_count'] + 1;
                $record['offline_count_subtotal'] = $healthStatus ?: (int)$record['offline_count_subtotal'] + 1;
            }
        }
        $record['offline_count'] = (int)$record['offline_count'];
        $record['offline_count_subtotal'] = (int)$record['offline_count_subtotal'];
        $website = new Website();
        $website->fillData($record)
            ->filterInvalidProperties();
        $res = $this->updateById($website);
        // 保存点击记录
        $recordSvc = new RecordService();
        $recordSvc->create($record['id'], $record['website_name'], $record['url']);
        return $res;
    }

    /**
     * 删除网站站点
     * @param string $uuid
     * @return bool|string
     */
    public function del(string $uuid): bool|string
    {
        $category = $this->getFirstByUuid($uuid);
        if (empty($category)) {
            return '网站站点UUID不存在';
        }
        $id = (int)$category['id'];
        $res = $this->delete($id);
        if ($res !== true) {
            return '删除网站站点失败';
        }
        return true;
    }

    /**
     * 预处理数据
     * @param array $data
     * @return string|array
     */
    public function prepare(array $data): string|array
    {
        $data = $this->convertParamsToSnakeCase($data);
        $url = $data['url'] ?? null;
        if (!is_null($url)) {
            if (!filter_var($url, FILTER_VALIDATE_URL)) {
                return '无效的网站站点URL';
            }
        }
        // 校验图片
        $mediaSvc = new MediaService();
        $iconName = $data['icon'] ?? null;
        if (!is_null($iconName)) {
            $media = $mediaSvc->getByMediaName($iconName);
            if (empty($media)) {
                return '无效的网站站点图标';
            }
            $data['icon'] = (int)$media['id'];
        }

        // 处理状态
        if (!is_null($data['status'] ?? null)) {
            $data['status'] = (int)($data['status'] === 'true');
        }

        $data['cid'] = (int)($data['cid'] ?? 0);
        $data['rating'] = (int)($data['rating'] ?? 0);
        $data['sort_index'] = (int)($data['sort_index'] ?? 1);
        $data['creation_method'] = WebsiteCreationMethod::MANUAL->name;

        if (array_key_exists('click_count', $data)) {
            unset($data['click_count']);
        }
        if (array_key_exists('check_count', $data)) {
            unset($data['check_count']);
        }
        if (array_key_exists('last_check_time', $data)) {
            unset($data['last_check_time']);
        }
        if (array_key_exists('offline_count', $data)) {
            unset($data['offline_count']);
        }
        if (array_key_exists('offline_count_subtotal', $data)) {
            unset($data['offline_count_subtotal']);
        }
        if (array_key_exists('health_status', $data)) {
            unset($data['health_status']);
        }
        return $data;
    }

    /**
     * 检查网站健康
     * @param string $url
     * @return bool
     */
    private function checkWebsiteHealth(string $url): bool
    {
        $client = new Client([
            'timeout' => 5
        ]);
        try {
            $response = $client->request('GET', $url);
            $statusCode = $response->getStatusCode();
            if ($statusCode === 200) {
                return true;
            } else {
                return false;
            }
        } catch (GuzzleException) {
            return false;
        }
    }

    /**
     * 解析网站
     * @param string $url
     * @return false|array
     */
    public function parse(string $url): false|array
    {
        $client = new Client([
            'defaults' => [
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36'
                ]
            ],
            'timeout' => 10
        ]);
        try {
            $response = $client->request('GET', $url);
            $statusCode = $response->getStatusCode();
            if ($statusCode === 200) {
                $html = $response->getBody()->getContents();

                // 获取响应头中的字符编码
                $contentType = $response->getHeaderLine('Content-Type');
                $encoding = null;
                if (preg_match('/charset=([^\s;]+)/i', $contentType, $matches)) {
                    $encoding = $matches[1];
                }

                // 如果没有从响应头中获取到编码，尝试从 HTML 内容中获取
                if (!$encoding) {
                    if (preg_match('/<meta\s+charset=["\']?([^"\'>]+)/i', $html, $matches)) {
                        $encoding = $matches[1];
                    } elseif (preg_match('/<meta\s+http-equiv=["\']?Content-Type["\']?\s+content=["\']?text\/html;\s*charset=([^\s"\'>]+)/i', $html, $matches)) {
                        $encoding = $matches[1];
                    }
                }

                // 如果编码存在且不是 UTF-8，进行转换
                if ($encoding && strtolower($encoding) !== 'utf-8') {
                    $html = mb_convert_encoding($html, 'UTF-8', $encoding);
                }

                $html = mb_encode_numericentity(
                    htmlspecialchars_decode(
                        htmlentities(
                            $html,
                            ENT_NOQUOTES,
                            'UTF-8',
                            false
                        ),
                        ENT_NOQUOTES
                    ),
                    [0x80, 0x10FFFF, 0, ~0],
                    'UTF-8'
                );

                // 初始化 DOMDocument 对象
                $dom = new DOMDocument();
                $dom->encoding = 'UTF-8';
                // 抑制 libxml 错误
                libxml_use_internal_errors(true);
                $dom->loadHTML($html);
                libxml_clear_errors();

                // 获取标题
                $titleNode = $dom->getElementsByTagName('title')->item(0);
                $title = $titleNode ? $titleNode->nodeValue : '';

                // 获取描述
                $description = '';
                $metaTags = $dom->getElementsByTagName('meta');
                foreach ($metaTags as $meta) {
                    if ($meta->getAttribute('name') === 'description') {
                        $description = $meta->getAttribute('content');
                        break;
                    }
                }

                // 获取图标
                $icon = '';
                $linkTags = $dom->getElementsByTagName('link');
                foreach ($linkTags as $link) {
                    if ($link->getAttribute('rel') === 'icon' || $link->getAttribute('rel') === 'shortcut icon') {
                        $icon = $link->getAttribute('href');
                        break;
                    }
                }

                // 解析基础URL
                $parsedUrl = parse_url($url);
                $baseUrl = $parsedUrl['scheme'] . '://' . $parsedUrl['host'];
                if (!empty($parsedUrl['port'])) {
                    $baseUrl .= ':' . $parsedUrl['port'];
                }

                // 处理相对路径的图标地址
                if (!empty($icon)) {
                    if (!empty($parsedUrl['path'])) {
                        $path = rtrim(dirname($parsedUrl['path']), '/');
                    } else {
                        $path = '';
                    }

                    // 构建绝对路径
                    if (!filter_var($icon, FILTER_VALIDATE_URL)) {
                        if (str_starts_with($icon, '../')) {
                            // 处理 ../ 路径
                            $parts = array_merge(explode('/', $path), explode('/', $icon));
                            $absPath = [];
                            foreach ($parts as $part) {
                                if ($part === '..') {
                                    array_pop($absPath);
                                } elseif ($part !== '' && $part !== '.') {
                                    $absPath[] = $part;
                                }
                            }
                            $icon = $baseUrl . '/' . implode('/', $absPath);
                        } elseif (str_starts_with($icon, './')) {
                            // 处理 ./ 路径
                            $icon = $baseUrl . '/' . ltrim($icon, './');
                        } else {
                            // 处理相对路径
                            $icon = $baseUrl . '/' . ltrim($icon, '/');
                        }
                    }
                }
                return [
                    'title' => $title,
                    'description' => $description,
                    'baseUrl' => $baseUrl,
                    'icon' => $icon,
                ];
            }
            return false;
        } catch (GuzzleException) {
            return false;
        }
    }

    /**
     * 合并数据
     * @param array $list
     * @return array
     */
    private function mergeData(array $list): array
    {
        $cidList = [];
        $iconList = [];
        foreach ($list as $item) {
            $cidList[] = $item['cid'];
            if (!is_null($item['icon'])) {
                $iconList[] = $item['icon'];
            }
        }
        $categorySvc = new CategoryService();
        if (!empty($cidList)) {
            $categoryList = $categorySvc->getList();
            foreach ($categoryList as $category) {
                foreach ($list as &$item) {
                    if ($category['id'] === $item['cid']) {
                        $item['category'] = $category['name'];
                    }
                }
            }
        }
        $mediaSvc = new MediaService();
        if (!empty($iconList)) {
            $mediaList = $mediaSvc->getMedia($iconList);
            foreach ($mediaList as $media) {
                foreach ($list as &$item) {
                    if ($media['id'] === $item['icon']) {
                        $item['icon'] = $media['file_url'];
                    }
                }
            }
        }

        return $list;
    }
}
