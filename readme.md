## 文档
* https://laravel-china.org/docs/5.3

## Laravel-admin 文档

http://z-song.github.io/laravel-admin/#/zh/

> [Admin Demo](http://120.26.143.106/admin)
> 账号/密码:admin/admin

## 视频
* https://www.laravist.com
* http://www.imooc.com/search/?words=laravel

## Laravel 速查表

https://cs.laravel-china.org/
## laravel环境
```
php >5.6.*  可安装wamp3.0
```
## 安装
```
# 安装laravel
composer require encore/laravel-admin "1.3.*"
如果没有安装composer请先安装 
安装步骤如下：
http://jingyan.baidu.com/article/4f34706ed04013e386b56d72.html
```
## windows系统需要更改本地域名到你本地的laravel下的public文件下
```
具体操作：
更改本地域名   修改c盘的hosts文件（添加127.0.0.1  你自己的网址（随便给））
apache/conf文件下
 将Include conf/extra/httpd-vhosts.conf的#号去掉
 添加apache 文件下的httpd-vhosts.conf文件
<VirtualHost *:80>
    ServerAdmin webmaster@dummy-host2.example.com
    DocumentRoot "你的本地路径制定到public文件下（绝对路径）"
    ServerName www.leipu.com（你刚才修改的网址）
    ErrorLog "logs/www.leipu.com-error.log（你的日志地址）"
    CustomLog "logs/www.leipu.com-access.log（你的日志地址）" common
</VirtualHost>

```
## 安装laravel-admin
```
首先确保安装好了laravel，并且数据库连接设置正确。
在config/app.php加入ServiceProvider:
Encore\Admin\Providers\AdminServiceProvider::class
然后运行下面的命令来发布资源：
php artisan vendor:publish --tag=laravel-admin
在该命令会生成配置文件config/admin.php，可以在里面修改安装的地址、数据库连接、以及表名。

然后运行下面的命令完成安装：
php artisan admin:install
启动服务后，在浏览器打开 （你自己刚才设置的网址）/admin/ ,使用用户名 admin 和密码 admin登陆.
```
## 安装或更新依赖包
```apacheconfig
composer install （安装就好）
```
```apacheconfig
composer update
```

## 配置

将项目根目录中的 `.env.example` 文件复制一份，并重命名为 `.env`。开发人员根据本机服务器或者正式环境的需求的不同，可自由修改 `.env` 中的环境变量。`.env` 文件已被忽略，不得被提交 git 中。
```
cp .env.example .env
```

## 生成 APP_KEY

```
php artisan key:generate 
```


## 生成资源软链接
```
php artisan storage:link
```

## 生成专题软链接
```apacheconfig
php artisan Zlink
```

## 目录权限
```apacheconfig
chmod -R 777 storage
chmod -R 777 bootstrap/cache
```

## 创建 admin 后台
```apacheconfig
php artisan admin:install
```

## 常用命令

#### 生成 Controller

```
php artisan make:controller UserController
```

#### 生成 Migrations
```
php artisan make:migration create_article --create=article

php artisan make:migration alter_article
```

#### 生成 Middleware

```bash
php artisan make:middleware UserMiddleware
```

#### artisan 命令列表

```$xslt
php artisan list
```


## 坑

* phpredis 扩展用源码包安装
* [OneinStack如何支持fileinfo？](https://oneinstack.com/question/oneinstack-how-to-support-the-fileinfo/)
