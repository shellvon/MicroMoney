#MicroMoney

和室友记账使用的简单的Web程序.

依赖的技术有

+ MicroMan框架(-_-# 你没看错,就是Microman里面写的那个单文件,我人生第一个MVC框架...)
+ AdminLTE模版,正在接入.
+ WebSocket  (如果要加入和室友聊天的话....)


##效果图.
![主页效果](_res/index_snap.png)

![个人资料](_res/profile_snap.png)

![添加数据](_res/add_record_snap.png)



##TODO:

 - [ ] **前端重构**
    - 倾向于像SPA(单页面应用)发展.目前考虑:
   1. angular/vue等前端库,(由于使用了bootstrap及很多插件,这些插件依赖jQuery,可能导致vue和bootstrap某些不兼容.该问题如何处理?)
   2. 使用类似网易云音乐的方式,监听a标签点击,利用iframe实现内容加载和URL跳转.
   3. SPA多是ajax请求，部分后端接口也得随着修改.

 - [ ] **后端框架修改**
    - 目前框架不支持很好的SQL日志
    - 不支持复杂的SQL,连连表都不支持(需要增加比如join/limit/group/order等内置函数)
    - 不是很有效的action管理(比如自定义路由)
 - [ ] **聊天功能的支持**
    - 到底应该使用nodejs还是PHP的workerman?
 - [ ] **要不要弄个微信公众号?**
    - 记账的时候直接用微信发某种格式,从而达到随时可以记账的需求?


## 已知并且我无法解决问题:
   1. 日志页的table如果不设置fixed,如果让td不被过长的内容撑大?(目前使用width限制了px,并且用overflow-x来在x方向滚动.但我很讨厌)


##Change Logs

+ 2016-6-14~15 增加系统通知和日志查询,修复了部分BUG
+ 2016-4-19 完成CRUD基础功能。
+ 2016-4-16 加入数据库支持,增加MicroDatabase
+ 2016-4-15 导入AdminLTE支持.增加MicroTemplate/Utility支持.
+ 2016-4-14 创建该项目,实现MicroMan原型.


##简单说明

1. 搭建PHP-FPM+Nginx配置环境，Nginx配置文件例子见[microman.conf](./microman.conf)
2. 在`App/Config`目录下修改自己的Database配置，并且导入`_res`目录下的[init.sql](./_res/init.sql)文件。
3. 修改自己host配置（如果你nginx配置了server name的话）为你喜欢的域名(和nginx中servername保持一致)
4. 打开浏览器可以看效果。

###To be continue.