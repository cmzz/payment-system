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


## 本地开发

建议使用 laradock 开发本项目



## 项目依赖
- php > 7.1
    - extension-redis
- mysql >= 5.7
- redis
- composer 
