<?php
/**
 * Created By PhpStorm
 * User: Pony
 * Data: 2025/11/25
 * Time: 16:54
 */
declare(strict_types=1);

namespace App\Services;

use DOMDocument;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Uri;
use GuzzleHttp\Psr7\UriResolver;
use GuzzleHttp\RequestOptions;

class WebsiteFaviconService extends BaseService
{
    /**
     * @param string $url 网站URL
     * @param bool $returnContent 是否返回图标内容
     * @param int $timeout 超时时间（秒）
     * @param bool $verifySsl 是否验证SSL证书
     * @return string|false
     */
    public function parseFavicon(
        string $url,
        bool   $returnContent = false,
        int    $timeout = 5,
        bool   $verifySsl = true,
    ): string|false
    {
        // 解析URL
        $parsed = parse_url($url);
        if (empty($parsed['host'])) {
            log_message('info', "解析网站Favicon时，获取URL失败。{url}缺少主机名", ['url' => $url]);
            return false;
        }

        // 解构赋值
        $host = $parsed['host'];
        $port = $parsed['port'] ?? '';
        $scheme = $parsed['scheme'] ?? 'http';
        $baseHost = $port ? "$host:$port" : $host;
        $baseUrl = "$scheme://$baseHost";

        // 缓存命中直接返回，缓存可使用Redis
        // if ($cache !== null && isset($cache[$baseHost])) {
        //     return $returnContent
        //         ? @file_get_contents($cache[$baseHost])
        //         : $cache[$baseHost];
        // }

        // 验证主机可解析
        $ip = gethostbyname($host);
        if (!filter_var($ip, FILTER_VALIDATE_IP) || $ip === $host) {
            log_message('info', "解析网站Favicon时，{host}主机解析失败。", ['host' => $host]);
            return false;
        }

        // 初始化Guzzle客户端（带重试中间件）
        $handlerStack = HandlerStack::create();
        $handlerStack->push(Middleware::retry(
            function (
                int              $retries,
                Request          $request,
                ?Response        $response,
                ?GuzzleException $exception
            ): bool {
                return match (true) {
                    // 超过最大重试次数、网络错误重试、服务器错误重试
                    $retries < 2, $exception !== null, $response?->getStatusCode() >= 500 => true,
                    default => false
                };
            }
        ));

        // Guzzle客户端（构造函数属性提升风格配置）
        $client = new Client([
            'handler' => $handlerStack,
            'timeout' => $timeout,
            'connect_timeout' => $timeout,
            RequestOptions::VERIFY => $verifySsl,
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36'
            ],
            'allow_redirects' => [
                'max' => 3,
                'strict' => false,
                'referer' => true,
            ]
        ]);

        // 从HTML提取favicon
        $faviconUrls = [];
        $baseUri = new Uri($baseUrl);
        $path = $parsed['path'] ?? '/';
        $htmlUri = UriResolver::resolve($baseUri, new Uri($path));
        $htmlUrl = (string)$htmlUri;
        try {
            $response = $client->get($htmlUrl);
            if ($response->getStatusCode() === 200) {
                $dom = new DOMDocument();
                libxml_use_internal_errors(true);
                $dom->loadHTML($response->getBody()->getContents());
                libxml_clear_errors();

                // 遍历link标签
                foreach ($dom->getElementsByTagName('link') as $link) {
                    $rel = strtolower(trim($link->getAttribute('rel') ?? ''));
                    // 使用match判断rel类型
                    if (match ($rel) {
                        'icon', 'shortcut icon', 'apple-touch-icon' => true,
                        default => false
                    }) {
                        $href = $link->getAttribute('href');
                        if (!empty($href)) {
                            $faviconUri = UriResolver::resolve($htmlUri, new Uri($href));
                            $faviconUrls[] = (string)$faviconUri;
                        }
                    }
                }
            }
        } catch (GuzzleException $e) {
            log_message('error', "解析Favicon时，获取HTML失败（{htmlUrl}）：{message}",
                [
                    'htmlUrl' => $htmlUrl,
                    'message' => $e->getMessage()
                ]
            );
        }

        // 补充默认路径
        $defaultPaths = [
            '/favicon.ico', '/favicon.png', '/favicon.svg', '/favicon.gif',
            '/Favicon.ico', '/Favicon.png',
            '/apple-touch-icon.png', '/apple-touch-icon-precomposed.png',
            '/static/favicon.ico', '/images/favicon.png',
            '/favicon.ico?v=1', '/favicon.png?v=2',
        ];
        foreach ($defaultPaths as $path) {
            $faviconUrls[] = $baseUrl . $path;
        }
        $faviconUrls = array_values(array_unique($faviconUrls));

        // 图片有效性验证
        $isValidImage = fn(string $content): bool => match (true) {
            // ICO
            str_starts_with($content, "\x00\x00\x01\x00") => true,
            // PNG
            str_starts_with($content, "\x89\x50\x4E\x47") => true,
            // JPG
            str_starts_with($content, "\xFF\xD8\xFF") => true,
            // SVG
            str_starts_with($content, "<?xml") || str_starts_with($content, "<svg") => true,
            default => false
        };

        // 尝试所有可能的URL
        foreach ($faviconUrls as $favUrl) {
            try {
                // 验证Content-Type和文件头
                $headResponse = $client->head($favUrl);
                if (str_contains($headResponse->getHeaderLine('Content-Type'), 'image/')) {
                    $content = $client->get($favUrl)->getBody()->getContents();
                    if ($isValidImage($content)) {
                        // $cache[$baseHost] = $favUrl ?? null;
                        return $returnContent ? $content : $favUrl;
                    }
                }
            } catch (GuzzleException) {
                // 忽略单个错误
            }

            // 协议切换
            $switchedUrl = str_contains($favUrl, 'http://')
                ? str_replace('http://', 'https://', $favUrl)
                : str_replace('https://', 'http://', $favUrl);

            try {
                $switchedHead = $client->head($switchedUrl);
                if (str_contains($switchedHead->getHeaderLine('Content-Type'), 'image/')) {
                    $content = $client->get($switchedUrl)->getBody()->getContents();
                    if ($isValidImage($content)) {
                        // $cache[$baseHost] = $switchedUrl ?? null;
                        return $returnContent ? $content : $switchedUrl;
                    }
                }
            } catch (GuzzleException) {
                // 忽略错误
            }
        }

        // 第三方服务fallback
        $thirdPartyUrls = [
            "https://icons.duckduckgo.com/ip3/$host.ico",
            "https://t1.gstatic.com/faviconV2?client=SOCIAL&type=FAVICON&url=$baseUrl&size=64",
            "https://favicon.yandex.net/favicon/$host",
        ];
        foreach ($thirdPartyUrls as $thirdUrl) {
            try {
                $content = $client->get($thirdUrl)->getBody()->getContents();
                if ($isValidImage($content)) {
                    // $cache[$baseHost] = $thirdUrl ?? null;
                    return $returnContent ? $content : $thirdUrl;
                }
            } catch (GuzzleException) {
                // 忽略错误
            }
        }

        return false;
    }

    /**
     * 校验一个字符串是否为有效的 favicon URL
     * @param string $url 待校验的 URL
     * @param bool $strict 是否启用严格模式（通过 HEAD 请求验证 Content-Type）
     * @param int $timeout 请求超时时间（秒），仅严格模式生效
     * @param Client|null $httpClient 请求超时时间（秒），仅严格模式生效
     * @return bool
     */
    public function isValidFaviconUrl(
        string  $url,
        bool    $strict = false,
        int     $timeout = 5,
        ?Client $httpClient = null
    ): bool
    {
        // 基础 URL 格式校验
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        // 协议必须是 http 或 https
        $scheme = parse_url($url, PHP_URL_SCHEME);
        if (!in_array(strtolower($scheme ?? ''), ['http', 'https'], true)) {
            return false;
        }

        // SSRF 防护：解析主机并校验 IP 是否为公网地址
        $host = parse_url($url, PHP_URL_HOST);
        if (!$host) {
            return false;
        }

        if (!$this->isPublicHost($host)) {
            // 拒绝私有/保留/本地地址
            return false;
        }

        // 严格模式：使用 Guzzle 发起 HEAD 请求
        if ($strict) {
            $client = $httpClient ?? new Client();

            try {
                $response = $client->request('HEAD', $url, [
                    'timeout' => $timeout,
                    'connect_timeout' => $timeout,
                    'allow_redirects' => [
                        'max' => 3, // 最多跟随 3 次重定向
                    ],
                    'headers' => [
                        'User-Agent' => 'FaviconValidator/1.0 (Guzzle)',
                    ],
                    // 关键：禁止非 HTTP(S) 协议（Guzzle 默认已限制，但显式更安全）
                    'curl' => [
                        CURLOPT_PROTOCOLS => CURLPROTO_HTTP | CURLPROTO_HTTPS,
                    ],
                ]);

                $contentType = $response->getHeaderLine('Content-Type');
                return stripos(trim($contentType), 'image/') === 0;

            } catch (RequestException|GuzzleException) {
                // 网络错误、4xx/5xx、超时等均视为无效
                return false;
            }
        }

        // 宽松模式：检查扩展名
        $path = parse_url($url, PHP_URL_PATH);
        if (!$path) {
            return false;
        }

        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $imageExtensions = ['png', 'jpg', 'jpeg', 'gif', 'ico', 'svg', 'webp', 'bmp', 'avif'];

        return in_array($ext, $imageExtensions, true);
    }

    /**
     * 判断主机是否解析为公网 IP 地址（防止 SSRF 和 DNS Rebinding）
     * @param string $host 域名或 IP 字符串
     * @return bool 若为公网地址返回 true，否则 false
     */
    private function isPublicHost(string $host): bool
    {
        // 如果是直接的 IP 地址
        if (filter_var($host, FILTER_VALIDATE_IP)) {
            // 必须是公网 IP（非私有、非保留、非回环）
            return filter_var(
                    $host,
                    FILTER_VALIDATE_IP,
                    FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
                ) !== false;
        }

        // 是域名：解析 A/AAAA 记录
        // 注意：dns_get_record 在 PHP 8 中仍可能抛出警告，但我们用 @ 抑制（生产中建议日志记录）
        $records = @dns_get_record($host, DNS_A | DNS_AAAA);
        if ($records === false || $records === []) {
            // 无法解析视为不安全或无效
            return false;
        }

        foreach ($records as $record) {
            $ip = $record['ip'] ?? null;
            if ($ip && filter_var($ip, FILTER_VALIDATE_IP)) {
                // 只要有一个解析结果是私有/保留 IP，就拒绝
                if (filter_var(
                        $ip,
                        FILTER_VALIDATE_IP,
                        FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
                    ) === false) {
                    return false;
                }
            }
        }

        return true;
    }
}