# payment-system

[![Build Status](https://travis-ci.com/cmzz/payment-system.svg?branch=master)](https://travis-ci.com/cmzz/payment-system)
![](https://img.shields.io/swagger/valid/2.0/https/raw.githubusercontent.com/OAI/OpenAPI-Specification/master/examples/v2.0/json/petstore-expanded.json.svg)
![](https://img.shields.io/badge/php-7.2-blue.svg)
![](https://img.shields.io/badge/mysql-%3E%3D5.7-blue.svg)
![](https://img.shields.io/badge/laravel-5.8-blue.svg)

基于 Laravel 5.8 + omnipay 开发的支持多个应用接入的聚合支付系统。简单配置即可使用。

## 功能列表

- 支持多应用接入
    - [x] 可以通过 command 添加应用
- 支持多种三方平台接入
    - 支持 QQ钱包
        - [x] Native 已验证
        - [ ] JS支付 开发完成但未经线上验证
        - [ ] App支付 开发完成但未经线上验证
    - 支持 支付宝
        - [x] 电脑网站支付 已验证
        - [x] 手机网站支付 已验证
        - [ ] JSAPI 开发完成但未经线上验证
        - [ ] APP支付 未完成
        - [ ] 当面付 未完成
    - 支持 微信支付
        - [x] 原生扫码支付　已验证
        - [x] H5支付　已验证
        - [ ] 微信网页、公众号、小程序支付　开发完成但未经线上验证
        - [ ] APP支付　开发完成但未经线上验证
        - [ ] 刷卡支付　开发完成但未经线上验证
- [ ] 认证
- [ ] 并发控制
- [ ] 交易日志
- [ ] 事件
- [ ] Webhook通知
- [ ] 订单管理api
- [ ] 退款
- [ ] 订单关闭
- [ ] 交易统计接口
- [ ] 交易数据推送
- [ ] 管理面板
    - 管理面板前段页面
    - 管理面板后端接口
- [ ] 客户端SDK
- [ ] 文档编写

## 使用教程
1. 项目配置

2. 支付宝开放平台配置

3. 微信支付后台配置

4. QQ钱包后台配置

5. 应用接入

## 文档


## 最佳实践



## 工具命令


## 项目依赖
- php > 7.1
    - extension-redis
- mysql >= 5.7
- redis
- composer 

## 本地开发

推荐使用 [Laradock](https://github.com/laradock/laradock) 作为开发环境。

推荐的配置步骤如下：

1. 在你的工作目录，配置 Laradock
    ```bash
    git clone --branch member git@github.com:laradock/laradock.git
    ```

2. 修改 `laradock/.env` 配置文件

    修改映射目录为自己的真实项目目录 `APP_CODE_PATH_HOST = YourSelf Application Path`
    
    修改 MYSQL 版本为 5.7 `MYSQL_VERSION=5.7`
    
    为了节省编译时间且在不需要使用前端的情况下，可以取消node，yarn的安装
    
    ```
    WORKSPACE_INSTALL_NODE = false
    WORKSPACE_INSTALL_YARN = false
    ```
    
    其他的参数调整都是可选的，具体可参考文档 [relevant](https://docs.docker.com/compose/compose-file/compose-file-v2/) [documentations](http://laradock.io/documentation/) 

3. 运行 docker 容器
    
    通过下面的命令来构建容器和启动容器:
    
    ```bash
    docker-compose up -d mysql redis workspace nginx rabbitmq
    ```
    
    如果容器没有正常启动，可以去掉 `-d` 参数，并查看控制台输出的日志以确定原因。
     
    停止容器: 
    ```bash
    docker-compose down
    ```

4. 通过 Compose 安装依赖
    
    所有的依赖都是通过 Composer 来管理的，通过下面的命令来安装：
    
    ```bash
    docker exec -it laradock_workspace_1 bash
    ```
    
    然后执行:
    
    ```bash
    composer install
    ```

5. 修改 `.env` 文件
    
    在项目根目录 `.env` 文件为项目的配置文件，可以从`.env.example`复制后进行修改：
    
    ```bash
    cp .env.example .env
    ```
    
    可能需要修改项目的关键配置，例如 mysql 相关的配置修改如下：
    
    ``` 
    DB_CONNECTION=mysql
    DB_HOST=mysql
    DB_PORT=3306
    DB_DATABASE=YourSelf Database
    DB_USERNAME=root
    DB_PASSWORD=root
    ```

6. 初始化项目
    
    在 workspace 容器中，执行下列命令：
    
    ```bash
    php artisan key:generate
    php artisan migrate
    php artisan db:seed
    ```

## Related

- [omnipay](https://github.com/thephpleague/omnipay)
- [omnipay-qpay](https://github.com/kuangjy2/omnipay-qpay)
- [omnipay-alipay](https://github.com/lokielse/omnipay-alipay)
- [omnipay-wechatpay](https://github.com/lokielse/omnipay-wechatpay)


欢迎贡献、共同完善！
