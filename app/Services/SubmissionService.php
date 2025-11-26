<?php
/**
 * Created by PhpStorm.
 * User: Pony
 * Date: 2025/07/05
 * Time: 14:45 下午
 */
declare(strict_types=1);

namespace App\Services;

use App\Entities\Submission;
use App\Entities\SubmissionMeta;
use App\Entities\SubmissionTag;
use App\Enums\SubmissionStatus;
use Exception;

class SubmissionService extends BaseService
{
    public function getCreateRules(): array
    {
        $rules = [
            'websiteName' => [
                'rules' => 'required|max_length[200]',
                'errors' => [
                    'required' => '参数网站站点名称[websiteName]不能为空',
                    'max_length' => '参数网站站点名称[websiteName]无效，字符长度不能超过200个字符',
                ]
            ],
            'url' => [
                'rules' => 'required|max_length[200]|valid_url',
                'errors' => [
                    'required' => '参数网站站点URL[url]不能为空',
                    'max_length' => '参数网站站点URL[url]无效，字符长度不能超过200个字符',
                    'valid_url' => '参数网站站点URL[url]无效,请输入有效的URL地址'
                ]
            ]
        ];
        return array_merge(
            $this->getBaseRules(),
            $rules
        );
    }

    protected function getBaseRules(): array
    {
        $rules = [
            'cid' => [
                'rules' => 'if_exist|is_natural_no_zero',
                'errors' => [
                    'is_natural_no_zero' => '参数分类ID[cid]无效,分类ID必须为非零自然数',
                ]
            ],
            'websiteName' => [
                'rules' => 'if_exist|max_length[200]',
                'errors' => [
                    'max_length' => '参数网站站点名称[websiteName]无效，字符长度不能超过200个字符',
                ]
            ],
            'url' => [
                'rules' => 'if_exist|max_length[200]|valid_url',
                'errors' => [
                    'max_length' => '参数网站站点URL[url]无效，字符长度不能超过200个字符',
                    'valid_url' => '参数网站站点URL[url]无效,请输入有效的URL地址'
                ]
            ],
            'favicon' => [
                'rules' => 'if_exist|max_length[200]',
                'errors' => [
                    'max_length' => '参数网站站点图标URL[favicon]无效,字符长度不能超过200个字符',
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
            'tags' => [
                'rules' => 'if_exist|max_length[200]|valid_json',
                'errors' => [
                    'max_length' => '参数网站站点标签[tags]无效，字符长度不能超过200个字符',
                    'valid_json' => '参数网站站点标签[tags]无效，请输入有效的JSON格式数据'
                ]
            ],
            'github' => [
                'rules' => 'if_exist|max_length[200]',
                'errors' => [
                    'max_length' => '参数Github仓库地址[github]无效，字符长度不能超过200个字符',
                ]
            ]
        ];
        return array_merge(
            parent::getBaseRules(),
            $rules
        );
    }

    /**
     * @param array $data
     * @return bool
     * @throws Exception
     */
    public function create(array $data): bool
    {
        $data = $this->prepare($data);
        $cid = $data['cid'] ?? null;
        if (!is_null($cid)) {
            $cid = (int)$cid;
            // 判断分类是否存在
            $categorySvc = new CategoryService();
            $category = $categorySvc->getFirstById($cid);
            if (!$category) {
                throw new Exception('导航分类不存在');
            }
        }
        // 校验标签
        $tagData = $data['tags'] ?? null;
        if (!is_null($tagData)) {
            $tagSvc = new TagService();
            $tagRecords = $tagSvc->get();
            $tagNameList = array_column($tagRecords, 'tag_name');
            $tagIdList = array_column($tagRecords, 'id');
            foreach ($tagData as $item) {
                if (!in_array($item['name'], $tagNameList)) {
                    throw new Exception('标签不存在');
                }
                if (isset($item['id']) && !in_array($item['id'], $tagIdList)) {
                    throw new Exception('标签ID不存在');
                }
                if (isset($item['id'])) {
                    $tagIndex = array_search($item['id'], $tagIdList);
                    if ($tagIndex !== false && $tagRecords[$tagIndex]['tag_name'] !== $item['name']) {
                        throw new Exception('标签名称与ID不匹配');
                    }
                }
            }
        }
        $github = $data['github'] ?? null;
        // 校验网址是否已经收录过
        $url = $data['url'];
        $cond = [
            'url' => $url
        ];
        $record = $this->getFirstByCond($cond);
        if ($record) {
            throw new Exception('网址已经收录过了，请勿重复提交');
        }
        // 处理Favicon
        $favicon = $data['favicon'] ?? null;
        if (!is_null($favicon)) {
            $faviconSvc = new WebsiteFaviconService();
            $isValidFavicon = $faviconSvc->isValidFaviconUrl($favicon);
            if (!$isValidFavicon) {
                throw new Exception('无效的Favicon');
            }
        }
        // 保存
        $db = $this->getDb();
        $db->transStart();
        try {
            $submission = new Submission();
            $submission->fillData($data)
                ->filterInvalidProperties()
                ->setStatus(SubmissionStatus::PENDING->value);
            $res = $this->insert($submission);
            if (!$res) {
                throw new Exception('网址收录失败');
            }
            $id = $this->getInsertId();
            if (!$id) {
                log_message('error', '网址收录时获取ID失败');
                throw new Exception('网址收录失败');
            }
            // 批量保存标签
            if (!empty($tagData)) {
                $tags = [];
                foreach ($tagData as $item) {
                    $tag = new SubmissionTag();
                    $tag->setSubmissionId($id)
                        ->setTagId((int)$item['id']);
                    $tags[] = $tag;
                }
                $tagSvc = new SubmissionTagService();
                $res = $tagSvc->insertBatch($tags);
                if (!$res) {
                    log_message('error', '网址收录时保存标签失败，标签数据:{tags}',
                        [
                            'tags' => $tagData
                        ]
                    );
                    throw new Exception('网址收录失败');
                }
            }
            // 保存元数据
            if (!is_null($github)) {
                $meta = new SubmissionMeta();
                $meta->setSubmissionId($id)
                    ->setMetaKey('github')
                    ->setMetaValue($github);
                $metaSvc = new SubmissionMetaService();
                $res = $metaSvc->insert($meta);
                if (!$res) {
                    log_message('error', '网址收录时保存元数据失败，元数据数据:{meta}', [
                        'meta' => [
                            'github' => $github
                        ]
                    ]);
                    throw new Exception('网址收录失败');
                }
            }
        } catch (Exception $e) {
            $db->transRollback();
            throw $e;
        }
        $db->transComplete();

        if ($db->transStatus() === false) {
            log_message('error', '网址收录失败，服务端错误，事务回滚');
            throw new Exception('网址收录失败,服务器错误。请稍后再试。');
        }

        return true;
    }

    /**
     * @param array $data
     * @return array
     * @throws Exception
     */
    private function prepare(array $data): array
    {
        $data = parent::prepareData($data);
        $tags = $data['tags'] ?? null;
        if (!is_null($tags)) {
            $tags = @json_decode($tags, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('标签格式错误,必须为有效的JSON字符串');
            }
            if (is_array($tags) && !empty($tags)) {
                $data['tags'] = $tags;
            } else {
                unset($data['tags']);
            }
        }
        return $data;
    }
}
