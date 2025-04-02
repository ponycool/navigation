<?php
/**
 * Created by PhpStorm.
 * User: Pony
 * Date: 2025/04/02
 * Time: 16:46 下午
 */
declare(strict_types=1);

namespace App\Entities;

use Exception;

class WebsiteTag extends Base
{
    protected int $id = 0;
    protected string $uuid;
    protected int $website_id;
    protected int $tag_id;
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
                '初始化 WebsiteTag Entity 失败，error：{msg}',
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
    public function setId(int $id): WebsiteTag
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
    public function setUuid(string $uuid = ''): WebsiteTag
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
    public function setWebsiteId(int $website_id): WebsiteTag
    {
        $this->website_id = $website_id;
        $this->attributes['website_id'] = $this->website_id;
        return $this;
    }

    /**
     * @return int
     */
    public function getTagId(): int
    {
        return $this->tag_id;
    }

    /**
     * @param int $tag_id
     * @return $this
     */
    public function setTagId(int $tag_id): WebsiteTag
    {
        $this->tag_id = $tag_id;
        $this->attributes['tag_id'] = $this->tag_id;
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
