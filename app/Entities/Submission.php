<?php
/**
 * Created by PhpStorm.
 * User: Pony
 * Date: 2025/07/05
 * Time: 14:45 下午
 */
declare(strict_types=1);

namespace App\Entities;

use Exception;

class Submission extends Base
{
    protected int $id = 0;
    protected string $uuid;
    protected string $ulid;
    protected int $cid;
    protected string $website_name;
    protected string $url;
    protected string $description;
    protected string $icon_url;
    protected int $rating;
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
            $this->setUlid();
        } catch (Exception $e) {
            log_message(
                'error',
                '初始化 Submission Entity 失败，error：{msg}',
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
    public function setId(int $id): Submission
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
    public function setUuid(string $uuid = ''): Submission
    {
        $this->uuid = $uuid ?: $this->generateUuid();
        $this->attributes['uuid'] = $this->uuid;
        return $this;
    }
    /**
     * @return string
     */
    public function getUlid(): string
    {
        return $this->ulid;
    }

    /**
     * @param string $ulid
     * @return $this
     * @throws Exception
     */
    public function setUlid(string $ulid = ''): Submission
    {
        $this->ulid = $ulid ?: $this->generateUlid();
        $this->attributes['ulid'] = $this->ulid;
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
    public function setCid(int $cid): Submission
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
    public function setWebsiteName(string $website_name): Submission
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
    public function setUrl(string $url): Submission
    {
        $this->url = $url;
        $this->attributes['url'] = $this->url;
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
    public function setDescription(string $description): Submission
    {
        $this->description = $description;
        $this->attributes['description'] = $this->description;
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
    public function setIconUrl(string $icon_url): Submission
    {
        $this->icon_url = $icon_url;
        $this->attributes['icon_url'] = $this->icon_url;
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
    public function setRating(int $rating): Submission
    {
        $this->rating = $rating;
        $this->attributes['rating'] = $this->rating;
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
    public function setStatus(int $status): Submission
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
