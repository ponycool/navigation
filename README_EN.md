<p align="center">
  <a href="https://cms.ponycool.com">
    <img src="https://cdn.static.ponycool.com/img/navigation/favicon/favicon.svg" width="130" />
  </a>
  <br />
  <b>Cool Code Navigation</b>
  <p align="center">A simple and easy-to-use website navigation</p>
  <p align="center">Leveraging the mature Codeigniter4 and React technology stacks, combining the efficient data processing capabilities of the backend framework with the excellent interactive experience of the frontend framework, ensures the stable operation of the system.</p>
  <p align="center">
    <a href="README.md"><img alt="中文" src="https://img.shields.io/static/v1.svg?label=&message=Chinese&style=flat-square&color=ff5000"></a>
    <img src="https://img.shields.io/github/v/release/ponycool/navigation" />
    <a href="https://github.com/ponycool/navigation/stargazers"><img src="https://img.shields.io/github/stars/ponycool/navigation" alt="Stars"/></a>
    <img alt="Codeigniter4" src="https://img.shields.io/static/v1.svg?label=&message=Codeigniter4&style=flat-square&color=C82B38">
    <img alt="React" src="https://img.shields.io/static/v1.svg?label=&message=React&style=flat-square&color=C82B38">
    <img src="https://img.shields.io/github/license/ponycool/navigation" />
  </p>
</p>

## Cool Code Navigation

A simple and easy-to-use website navigation based on Codeigniter4 and React

[Official Website](https://nav.ponycool.com)

## Preview

#### Web Version

![Web Version](https://cdn.static.ponycool.com/img/navigation/screenshot/web.png)

#### Admin Dashboard

![Admin Dashboard](https://cdn.static.ponycool.com/img/navigation/screenshot/admin_screenshot_compressed.png)

## Installation

### System Requirements

We recommend running PHP 8.3 or higher on your server; the default database software is SQLite3, but you can also use MySQL 5.7 or higher, or MariaDB 10.3 or higher.
We also recommend Apache or Nginx as reliable options for running Cool Code Navigation, but you can choose other HTTP server software.

### Running with Docker

```shell
sudo docker run -d --restart=unless-stopped -p 80:80 ponycool/navigation:latest
# Domestic source
sudo docker run -d --restart=unless-stopped -p 80:80 registry.cn-qingdao.aliyuncs.com/ponycool/navigation:latest

# Data persistence. Please use named volume mounting.
docker run -d -p 80:80 -v navigation-data:/opt/navigation/writable/data --name navigation ponycool/navigation:latest
# Domestic source
docker run -d -p 80:80 -v navigation-data:/opt/navigation/writable/data --name navigation registry.cn-qingdao.aliyuncs.com/ponycool/navigation:latest
```

## Usage

### Access

After successful installation, access Cool Code Navigation through your browser.

```
# Frontend
Address: http://<Server IP Address>:<Service Port>
# Backend
Address: http://<Server IP Address>:<Service Port>/admin/
Username: admin
Password: admin123!
```

### Configuration

The JWT secret key must be a 32-character random string. An example is as follows:

jwt.secret = 0W************IZAa

### Build Image

```shell
# Build image
make build
# Build a specific version of the image
# make -e version=1.0.0 build
# Manually build the image locally
# docker build -t ponycool/navigation:latest .
# Check installed PHP modules. Execute inside the container.
# php -m
# Test the image on port 8080
# docker run -it --rm -p 9000:8080 --name navigation ponycool/navigation:latest
```

## Support

[Website](https://ponycool.com/navigation/index)

[issue](https://github.com/ponycool/navigation/issues)

Mail:pony@ponycool.com

## Acknowledgments

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