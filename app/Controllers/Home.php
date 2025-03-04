<?php

namespace App\Controllers;

use App\Services\CarouselService;
use App\Services\CategoryService;

class Home extends Web
{
    public function index(): void
    {
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
        $category = $categorySvc->getEnableList();
        $data = [
            'carousel' => $carousel,
            'category' => $category
        ];
        $this->setTitle($title)
            ->setDescription($description)
            ->setKeywords($keywords)
            ->setTemplate('home')
            ->setPage('home')
            ->render($data);
    }
}
