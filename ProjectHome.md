PMS (PHP Message Server) 是一个 PHP 的消息处理服务框架

本框架基于 hush-framework 开发，参考地址：http://code.google.com/p/hush-framework/

By -- James.Huang (黄隽实)

系统简介

1、关于协议

采用 Json 以及 JsonRpc 作为系统数据交换协议，方便快速。

2、关于 Server

支持 多进程，多端口，多消息列表，性能强大。

3、关于 Client

支持 send / recv / clear / stats 等操作，功能全面。

4、关于 XA 接口

提供 start / prepare / commit / rollback 的 Transaction 操作