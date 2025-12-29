<p align="center">
  <a href="https://cms.ponycool.com">
    <img src="https://cdn.static.ponycool.com/img/navigation/favicon/favicon.svg" width="130" />
  </a>
  <br />
  <b>酷码导航</b>
  <p align="center">简洁易用的网址导航</p>
  <p align="center">借助成熟的 Codeigniter4 与 React 技术栈，结合后端框架的高效数据处理能力与前端框架的出色交互体验，确保系统稳定运行。</p>
  <p align="center">
    <a href="README_EN.md"><img alt="english" src="https://img.shields.io/static/v1.svg?label=&message=English&style=flat-square&color=ff5000"></a>
    <img src="https://img.shields.io/github/v/release/ponycool/navigation" />
    <a href="https://github.com/ponycool/navigation/stargazers"><img src="https://img.shields.io/github/stars/ponycool/navigation" alt="Stars"/></a>
    <img alt="Codeigniter4" src="https://img.shields.io/static/v1.svg?label=&message=Codeigniter4&style=flat-square&color=C82B38">
    <img alt="React" src="https://img.shields.io/static/v1.svg?label=&message=React&style=flat-square&color=C82B38">
    <img src="https://img.shields.io/github/license/ponycool/navigation" />
  </p>
</p>

## 酷码导航

基于Codeigniter4和React简洁易用的网址导航

[官网](https://nav.ponycool.com)

## 预览

#### WEB端

![WEB端](https://cdn.static.ponycool.com/img/navigation/screenshot/web.png)

#### 管理后台

![管理后台](https://cdn.static.ponycool.com/img/navigation/screenshot/admin_screenshot_compressed.png)

## 安装

### 系统要求

我们推荐服务器运行PHP 8.3或更高版本；数据库软件默认SQLite3，也可采用MySQL 5.7或更高版本、MariaDB 10.3或更高版本。
我们也推荐Apache或Nginx作为运行酷码导航的可靠选项，但您也可以选择其他HTTP服务器软件。

### 使用docker运行

```shell
sudo docker run -d --restart=unless-stopped -p 80:80 ponycool/navigation:latest
# 国内源
sudo docker run -d --restart=unless-stopped -p 80:80 registry.cn-qingdao.aliyuncs.com/ponycool/navigation:latest

# 数据持久化，请使用具名挂载的方式进行挂载
docker run -d -p 80:80 -v navigation-data:/opt/navigation/writable/data --name navigation ponycool/navigation:latest
# 国内源
docker run -d -p 80:80 -v navigation-data:/opt/navigation/writable/data --name navigation registry.cn-qingdao.aliyuncs.com/ponycool/navigation:latest
```

## 使用

### 访问

安装成功后，通过浏览器访问酷码导航

```
# 前台
地址: http://<服务器IP地址>:<服务运行端口>
# 后台
地址: http://<服务器IP地址>:<服务运行端口>/admin/
用户名: admin
密码: admin123!
```

### 配置

JWT密钥，必须为32位随机字符串，示例如下：

jwt.secret = 0W************IZAa

### 编译镜像

```shell
# 编译镜像
make build
# 编译指定版本镜像
# make -e version=1.0.0 build
# 手动本地编译镜像
# docker build -t ponycool/navigation:latest .
# 查看已经安装PHP模块，容器内执行
# php -m
# 镜像测试端口8080
# docker run -it --rm -p 9000:8080 --name navigation ponycool/navigation:latest
```

## 支持

[Website](https://ponycool.com/navigation/index)

[issue](https://github.com/ponycool/navigation/issues)

Mail:pony@ponycool.com

## 鸣谢

[CodeIgniter4](https://github.com/codeigniter4/CodeIgniter4)

[Arco Design](https://github.com/arco-design/arco-design)

[React](https://reactjs.org/)

[Twig](https://twig.symfony.com/)

[UUID](https://github.com/ramsey/uuid)

[JetBrains](https://www.jetbrains.com/)

<img alt="JetBrains" height="80" src="https://resources.jetbrains.com/storage/products/company/brand/logos/jb_beam.png" width="80"/>

## License

For commercial sites, themes, projects, and applications, keep your source code private/proprietary by purchasing a [Commercial License](https://ponycool.com/navigation/price).

Licensed under the GNU General Public License 3.0 for compatible open source projects and non-commercial use.

Copyright 2025-present PonyCool