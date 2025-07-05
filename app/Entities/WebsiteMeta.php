<?php
/**
 * Created by PhpStorm.
 * User: Pony
 * Date: 2025/07/05
 * Time: 14:44 下午
 */
declare(strict_types=1);

namespace App\Entities;

use Exception;

class WebsiteMeta extends Base
{
    protected int $id = 0;
    protected string $uuid;
    protected int $website_id;
    protected string $meta_key;
    protected string $meta_value;
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
                '初始化 WebsiteMeta Entity 失败，error：{msg}',
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
    public function setId(int $id): WebsiteMeta
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
    public function setUuid(string $uuid = ''): WebsiteMeta
    {
        $this->uuid = $uuid ?: $this->generateUuid();
        $this->attributes['uuid'] = $this->uuid;
        return $this;
    }

    /**
     * @return int
     */
    public function getWebsiteId(): int
    {
        return $this->website_id;
    }

    /**
     * @param int $website_id
     * @return $this
     */
    public function setWebsiteId(int $website_id): WebsiteMeta
    {
        $this->website_id = $website_id;
        $this->attributes['website_id'] = $this->website_id;
        return $this;
    }

    /**
     * @return string
     */
    public function getMetaKey(): string
    {
        return $this->meta_key;
    }

    /**
     * @param string $meta_key
     * @return $this
     */
    public function setMetaKey(string $meta_key): WebsiteMeta
    {
        $this->meta_key = $meta_key;
        $this->attributes['meta_key'] = $this->meta_key;
        return $this;
    }

    /**
     * @return string
     */
    public function getMetaValue(): string
    {
        return $this->meta_value;
    }

    /**
     * @param string $meta_value
     * @return $this
     */
    public function setMetaValue(string $meta_value): WebsiteMeta
    {
        $this->meta_value = $meta_value;
        $this->attributes['meta_value'] = $this->meta_value;
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
