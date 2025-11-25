<?php

namespace App\Controllers;

use App\Services\CarouselService;
use App\Services\CategoryService;
use App\Services\WebsiteService;
use App\Services\TagService as TagSvc;

class Home extends Web
{
    /**
     * 首页
     * @return void
     */
    public function index(): void
    {
        $mode = 'default';
        $cid = $this->request->getGet('cid');
        if (!is_null($cid)) {
            $cid = intval($cid);
        }
        $queryStr = $this->request->getGet('q');
        if (!is_null($queryStr)) {
            $mode = 'query';
            $queryStr = urldecode($queryStr);
        }
        $url = $this->request->getGet('url');
        if (!is_null($url)) {
            $mode = 'query';
            $url = urldecode($url);
        }
        $settings = self::getSettings();
        $title = lang('App.home') . ' - ' . $settings['site_name'] ?? '酷码导航';
        $description = $settings['site_description'] ?? '酷码导航是一个轻量简洁易用的网址导航，汇集全网优质网址及资源的中文上网导航。';
        $keywords = [
            '上网导航',
            '网址大全',
            '网址导航',
            '网址导航大全',
            '中文网站导航',
        ];
        $keywords = $settings['site_keywords'] ?? implode(',', $keywords);
        $carouselSvc = new CarouselService();
        $carousel = $carouselSvc->getEnableList();
        $categorySvc = new CategoryService();
        $categories = $categorySvc->getEnableList();
        $websiteCategories = $categorySvc->getChildById($categories, $cid);
        $cidList = array_column($websiteCategories, 'id');
        $cond = [
            'cidList' => $cidList,
            'queryStr' => $queryStr,
            'url' => $url
        ];
        $websiteSvc = new WebsiteService();
        $website = $websiteSvc->getListByCond($cond);
        // 如果存在搜索词，则重新处理结果分类
        if (!is_null($queryStr) || !is_null($url)) {
            $websiteCategories = [];
            foreach ($categories as $category) {
                foreach ($website as $item) {
                    if ($item['cid'] == $category['id']) {
                        $websiteCategories[] = $category;
                        break;
                    }
                }
            }
        }
        // 收录数量
        $websiteCount = $websiteSvc->getEnableTotal();
        // 标签
        $tagSvc = new TagSvc();
        $tags = $tagSvc->get();
        $data = [
            'mode' => $mode,
            'cid' => $cid,
            'queryStr' => $queryStr,
            'carousel' => $carousel,
            'categories' => $categories,
            'websiteCategories' => $websiteCategories,
            'website' => $website,
            'websiteCount' => $websiteCount,
            'tags' => $tags,
        ];
        $template = $this->isMobile() ? 'mobile' : 'index';
        $this->setTitle($title)
            ->setDescription($description)
            ->setKeywords($keywords)
            ->setTemplate($template)
            ->setPage('index')
            ->render($data);
    }
}
