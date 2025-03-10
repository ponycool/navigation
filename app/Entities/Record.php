<?php
/**
 * Created by PhpStorm.
 * User: Pony
 * Date: 2025/03/07
 * Time: 16:05 下午
 */
declare(strict_types=1);

namespace App\Entities;

use Exception;

class Record extends Base
{
    protected int $id = 0;
    protected string $uuid;
    protected int $website_id;
    protected string $website_name;
    protected string $url;
    protected string $browser;
    protected string $version;
    protected string $mobile;
    protected string $platform;
    protected string $referrer;
    protected string $user_agent;
    protected string $ip;
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
                '初始化 Record Entity 失败，error：{msg}',
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
    public function setId(int $id): Record
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
    public function setUuid(string $uuid = ''): Record
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
    public function setWebsiteId(int $website_id): Record
    {
        $this->website_id = $website_id;
        $this->attributes['website_id'] = $this->website_id;
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
    public function setWebsiteName(string $website_name): Record
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
    public function setUrl(string $url): Record
    {
        $this->url = $url;
        $this->attributes['url'] = $this->url;
        return $this;
    }

    /**
     * @return string
     */
    public function getBrowser(): string
    {
        return $this->browser;
    }

    /**
     * @param string $browser
     * @return $this
     */
    public function setBrowser(string $browser): Record
    {
        $this->browser = $browser;
        $this->attributes['browser'] = $this->browser;
        return $this;
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * @param string $version
     * @return $this
     */
    public function setVersion(string $version): Record
    {
        $this->version = $version;
        $this->attributes['version'] = $this->version;
        return $this;
    }

    /**
     * @return string
     */
    public function getMobile(): string
    {
        return $this->mobile;
    }

    /**
     * @param string $mobile
     * @return $this
     */
    public function setMobile(string $mobile): Record
    {
        $this->mobile = $mobile;
        $this->attributes['mobile'] = $this->mobile;
        return $this;
    }

    /**
     * @return string
     */
    public function getPlatform(): string
    {
        return $this->platform;
    }

    /**
     * @param string $platform
     * @return $this
     */
    public function setPlatform(string $platform): Record
    {
        $this->platform = $platform;
        $this->attributes['platform'] = $this->platform;
        return $this;
    }

    /**
     * @return string
     */
    public function getReferrer(): string
    {
        return $this->referrer;
    }

    /**
     * @param string $referrer
     * @return $this
     */
    public function setReferrer(string $referrer): Record
    {
        $this->referrer = $referrer;
        $this->attributes['referrer'] = $this->referrer;
        return $this;
    }

    /**
     * @return string
     */
    public function getUserAgent(): string
    {
        return $this->user_agent;
    }

    /**
     * @param string $user_agent
     * @return $this
     */
    public function setUserAgent(string $user_agent): Record
    {
        $this->user_agent = $user_agent;
        $this->attributes['user_agent'] = $this->user_agent;
        return $this;
    }

    /**
     * @return string
     */
    public function getIp(): string
    {
        return $this->ip;
    }

    /**
     * @param string $ip
     * @return $this
     */
    public function setIp(string $ip): Record
    {
        $this->ip = $ip;
        $this->attributes['ip'] = $this->ip;
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
