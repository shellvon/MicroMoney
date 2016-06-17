#MicroMoney

和室友记账使用的简单的Web程序.

依赖的技术有

+ MicroMan框架(-_-# 你没看错,就是Microman里面写的那个单文件,我人生第一个MVC框架...)
+ AdminLTE模版.
+ WebSocket  (如果要加入和室友聊天的话....)

##产生原因
    每次室友之间结算钱的时候动手笔算,过于麻烦,还容易算错,同时也为了追溯往期消费记录的具体明细.所以商量由我开发一个简单的记账系统来帮助
    室友之前记录消费情况,选用PHP是因为室友们也看得懂.(世界上最好的编程语言)
    早期版本是单文件index.php,日志记录使用txt方式json_encode/json_decode处理.后来想改成MVC方便加功能,所以临时写了一个MicroMan.

##功能描述
    1. 多用户记账
    2. 团队消费记录明细(支付人,支付金额,支付日期,支付用途,受益人(即谁也消费了这笔钱,需要之后结算的))
    3. 个人消费记录(消费金额,需要结算的金额,带有较为好看的UI进度效果)
    4. 操作日志(添加记录,修改记录,结算记录,(对比历史数据的功能计划中))
    5. 操作通知(当发生操作日志时其他用户会收到通知(少了一些字段,仅可以显示,无法实现点击去溯源))

##效果图.
![主页效果](_res/index_snap.png)

![个人资料](_res/profile_snap.png)

![添加数据](_res/add_record_snap.png)


##TODO:

 - [ ] **前端重构**
    - 倾向于向SPA(单页面应用)发展.目前考虑:
   1. angular/vue等前端库,(由于使用了bootstrap及很多插件,这些插件依赖jQuery,可能导致vue和bootstrap某些不兼容.)
   2. 使用类似网易云音乐的方式,监听a标签点击事件,利用iframe实现内容加载和URL跳转.
   3. SPA多是ajax请求，部分后端接口也得随着修改.

 - [ ] **后端框架Microman修改**
    - 目前框架不支持很好的SQL日志.
    - 不支持复杂的SQL,连联表都不支持(需要增加比如join/limit/group/order等内置函数)
    - 不是很有效的action管理(比如自定义路由)
    - 高耦合了,比如要自己实现新的模版引擎/数据库链接管理等的时候会发现...(至少也得提供某个interface,然后支持set含有这些interface实现的东西)
    - 倾向于使用DI,抽时间看一下[Pimple](http://pimple.sensiolabs.org/)源代码.

 - [ ] **聊天功能的支持**
    - 优先使用Workerman,因为这个比较熟悉.

 - [x] **要不要弄个微信公众号?** (~~无此需求,个人微信公众号实现起来较为简单(因为只用处理文本信息)~~)
    - 记账的时候直接用微信发某种格式,从而达到随时可以记账的需求?

## 已知并且我无法解决问题:
   1. 日志页的table如果不设置fixed,如果让td不被过长的内容撑大?(目前使用width限制了px,并且用overflow-x来在x方向滚动.但我很讨厌)


##Change Logs

+ 2016-6-17 修改部分Code-Style,更新README.md,修复部分错别字和修改TOD等.
+ 2016-6-13~15 增加系统通知和日志查询,修复了部分BUG
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