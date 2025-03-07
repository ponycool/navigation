<?php
/**
 * Created by PhpStorm.
 * User: Pony
 * Date: 2025/03/07
 * Time: 14:06 下午
 */
declare(strict_types=1);

namespace App\Entities;

use Exception;

class Website extends Base
{
    protected int $id = 0;
    protected string $uuid;
    protected int $cid;
    protected string $website_name;
    protected string $url;
    protected int $icon;
    protected string $icon_url;
    protected string $description;
    protected int $rating;
    protected int $click_count;
    protected int $check_count;
    protected string $last_check_time;
    protected int $offline_count;
    protected int $health_status;
    protected int $sort_index;
    protected string $creation_method;
    protected int $status;
    protected string $created_at;
    protected string $updated_at;
    protected string $deleted_at;
    protected int $deleted;
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
     * @return int
     */
    public function getCid(): int
    {
        return $this->cid;
    }

    /**
     * @param int $cid
     * @return $this
     */
    public function setCid(int $cid): Website
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
     * @return int
     */
    public function getIcon(): int
    {
        return $this->icon;
    }

    /**
     * @param int $icon
     * @return $this
     */
    public function setIcon(int $icon): Website
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
     * @return int
     */
    public function getRating(): int
    {
        return $this->rating;
    }

    /**
     * @param int $rating
     * @return $this
     */
    public function setRating(int $rating): Website
    {
        $this->rating = $rating;
        $this->attributes['rating'] = $this->rating;
        return $this;
    }

    /**
     * @return int
     */
    public function getClickCount(): int
    {
        return $this->click_count;
    }

    /**
     * @param int $click_count
     * @return $this
     */
    public function setClickCount(int $click_count): Website
    {
        $this->click_count = $click_count;
        $this->attributes['click_count'] = $this->click_count;
        return $this;
    }

    /**
     * @return int
     */
    public function getCheckCount(): int
    {
        return $this->check_count;
    }

    /**
     * @param int $check_count
     * @return $this
     */
    public function setCheckCount(int $check_count): Website
    {
        $this->check_count = $check_count;
        $this->attributes['check_count'] = $this->check_count;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastCheckTime(): string
    {
        return $this->last_check_time;
    }

    /**
     * @param string $last_check_time
     * @return $this
     */
    public function setLastCheckTime(string $last_check_time): Website
    {
        $this->last_check_time = $last_check_time;
        $this->attributes['last_check_time'] = $this->last_check_time;
        return $this;
    }

    /**
     * @return int
     */
    public function getOfflineCount(): int
    {
        return $this->offline_count;
    }

    /**
     * @param int $offline_count
     * @return $this
     */
    public function setOfflineCount(int $offline_count): Website
    {
        $this->offline_count = $offline_count;
        $this->attributes['offline_count'] = $this->offline_count;
        return $this;
    }

    /**
     * @return int
     */
    public function getHealthStatus(): int
    {
        return $this->health_status;
    }

    /**
     * @param int $health_status
     * @return $this
     */
    public function setHealthStatus(int $health_status): Website
    {
        $this->health_status = $health_status;
        $this->attributes['health_status'] = $this->health_status;
        return $this;
    }

    /**
     * @return int
     */
    public function getSortIndex(): int
    {
        return $this->sort_index;
    }

    /**
     * @param int $sort_index
     * @return $this
     */
    public function setSortIndex(int $sort_index): Website
    {
        $this->sort_index = $sort_index;
        $this->attributes['sort_index'] = $this->sort_index;
        return $this;
    }

    /**
     * @return string
     */
    public function getCreationMethod(): string
    {
        return $this->creation_method;
    }

    /**
     * @param string $creation_method
     * @return $this
     */
    public function setCreationMethod(string $creation_method): Website
    {
        $this->creation_method = $creation_method;
        $this->attributes['creation_method'] = $this->creation_method;
        return $this;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     * @return $this
     */
    public function setStatus(int $status): Website
    {
        $this->status = $status;
        $this->attributes['status'] = $this->status;
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
     * @return int
     */
    public function getDeleted(): int
    {
        return $this->deleted;
    }

}
