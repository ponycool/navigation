<?php
/**
 * Created by PhpStorm.
 * User: Pony
 * Date: 2025/02/13
 * Time: 16:25 下午
 */
declare(strict_types=1);

namespace App\Entities;

use Exception;

class Website extends Base
{
    protected string $id = 0;
    protected string $uuid;
    protected string $cid;
    protected string $website_name;
    protected string $url;
    protected string $icon;
    protected string $icon_url;
    protected string $description;
    protected string $rating;
    protected string $audit_status;
    protected string $audit_time;
    protected string $sort_index;
    protected string $created_at;
    protected string $updated_at;
    protected string $deleted_at;
    protected string $deleted;
    protected $dates = [
    ];
    protected $casts = [
        'deleted' => 'boolean'
    ];

    public function __construct(array $data = null)
    {
        parent::__construct($data);
        try {
            $this->setUuid();
        } catch (Exception $e) {
            log_message(
                'error',
                '初始化 Website Entity 失败，error：{msg}',
                ['msg' => $e->getMessage()]
            );
        }
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId(int $id): Website
    {
        $this->id = $id;
        $this->attributes['id'] = $this->id;
        return $this;
    }

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * @param string $uuid
     * @return $this
     * @throws Exception
     */
    public function setUuid(string $uuid = ''): Website
    {
        $this->uuid = $uuid ?: $this->generateUuid();
        $this->attributes['uuid'] = $this->uuid;
        return $this;
    }

    /**
     * @return string
     */
    public function getCid(): string
    {
        return $this->cid;
    }

    /**
     * @param string $cid
     * @return $this
     */
    public function setCid(string $cid): Website
    {
        $this->cid = $cid;
        $this->attributes['cid'] = $this->cid;
        return $this;
    }

    /**
     * @return string
     */
    public function getWebsiteName(): string
    {
        return $this->website_name;
    }

    /**
     * @param string $website_name
     * @return $this
     */
    public function setWebsiteName(string $website_name): Website
    {
        $this->website_name = $website_name;
        $this->attributes['website_name'] = $this->website_name;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return $this
     */
    public function setUrl(string $url): Website
    {
        $this->url = $url;
        $this->attributes['url'] = $this->url;
        return $this;
    }

    /**
     * @return string
     */
    public function getIcon(): string
    {
        return $this->icon;
    }

    /**
     * @param string $icon
     * @return $this
     */
    public function setIcon(string $icon): Website
    {
        $this->icon = $icon;
        $this->attributes['icon'] = $this->icon;
        return $this;
    }

    /**
     * @return string
     */
    public function getIconUrl(): string
    {
        return $this->icon_url;
    }

    /**
     * @param string $icon_url
     * @return $this
     */
    public function setIconUrl(string $icon_url): Website
    {
        $this->icon_url = $icon_url;
        $this->attributes['icon_url'] = $this->icon_url;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription(string $description): Website
    {
        $this->description = $description;
        $this->attributes['description'] = $this->description;
        return $this;
    }

    /**
     * @return string
     */
    public function getRating(): string
    {
        return $this->rating;
    }

    /**
     * @param string $rating
     * @return $this
     */
    public function setRating(string $rating): Website
    {
        $this->rating = $rating;
        $this->attributes['rating'] = $this->rating;
        return $this;
    }

    /**
     * @return string
     */
    public function getAuditStatus(): string
    {
        return $this->audit_status;
    }

    /**
     * @param string $audit_status
     * @return $this
     */
    public function setAuditStatus(string $audit_status): Website
    {
        $this->audit_status = $audit_status;
        $this->attributes['audit_status'] = $this->audit_status;
        return $this;
    }

    /**
     * @return string
     */
    public function getAuditTime(): string
    {
        return $this->audit_time;
    }

    /**
     * @param string $audit_time
     * @return $this
     */
    public function setAuditTime(string $audit_time): Website
    {
        $this->audit_time = $audit_time;
        $this->attributes['audit_time'] = $this->audit_time;
        return $this;
    }

    /**
     * @return string
     */
    public function getSortIndex(): string
    {
        return $this->sort_index;
    }

    /**
     * @param string $sort_index
     * @return $this
     */
    public function setSortIndex(string $sort_index): Website
    {
        $this->sort_index = $sort_index;
        $this->attributes['sort_index'] = $this->sort_index;
        return $this;
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    /**
     * @return string
     */
    public function getUpdatedAt(): string
    {
        return $this->updated_at;
    }

    /**
     * @return string
     */
    public function getDeletedAt(): string
    {
        return $this->deleted_at;
    }

    /**
     * @return string
     */
    public function getDeleted(): string
    {
        return $this->deleted;
    }

}
