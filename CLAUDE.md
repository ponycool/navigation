# CLAUDE.md — navigation（酷码导航）

## 项目简介
基于 CodeIgniter 4（PHP 8.3+）的导航/书签应用，后台管理使用 React + Arco Design，前台使用 jQuery + Twig 模板。

## 常用命令

```bash
# 安装依赖
composer install

# CodeIgniter CLI（数据库迁移、代码生成等）
php spark <命令>

# Docker 构建
make build
make -e version=X.Y.Z build

# Docker 运行
docker run -d -p 80:80 -v navigation-data:/opt/navigation/writable/data --name navigation ponycool/navigation:latest
```

项目根目录无 `phpunit.xml`，暂无独立测试命令。

## 架构说明

### 请求流程
`public/index.php` → CI4 路由 → `app/Config/CustomRoutes.php` → Controllers → Services → Models → Entities

### 核心约定
- **动态路由：** `app/Config/CustomRoutes.php` 将 kebab-case URL 段映射到 PascalCase 控制器命名空间。
- **Service → Model 自动映射：** Service 类名自动对应 Model（如 `WebsiteService` → `WebsiteModel`），数据表名使用 `m_` 前缀。
- **API 鉴权：** JWT token 通过查询参数 `?token=<jwt>` 传递，在 `app/Controllers/Api/Base.php` 中验证。
- **响应码枚举：** `app/Enums/Code.php`。
- **安全 Trait：** `DetectMaliciousContentTrait` 和 `DetectIllegalContentTrait`，挂载于控制器以拦截恶意请求。
- **多语言：** `app/Language/` 目录，支持 6 种语言。

### 前台 / 后台
- 后台管理：React 应用，位于 `public/admin/`。
- 前台主题：jQuery，Twig 模板位于 `app/Views/theme/`。

## 数据库
- 默认：SQLite3，文件路径 `writable/data/nav.db`。
- 同时支持 MySQL 5.7+ / MariaDB 10.3+。
