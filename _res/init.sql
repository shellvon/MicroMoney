# ************************************************************
# Sequel Pro SQL dump
# Version 4499
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.7.11)
# Database: money
# Generation Time: 2016-04-19 09:13:34 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table cost_records
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cost_records`;

CREATE TABLE `cost_records` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cost` float NOT NULL COMMENT '消费金额',
  `description` varchar(255) DEFAULT NULL COMMENT '消费描述',
  `paid_day` datetime DEFAULT NULL COMMENT '消费日期',
  `type` int(11) unsigned NOT NULL COMMENT '消费类型ID',
  `operator_uid` int(11) DEFAULT NULL COMMENT '操作人ID',
  `is_deal` int(11) NOT NULL DEFAULT '0' COMMENT '是否结算',
  `is_delete` int(11) NOT NULL DEFAULT '0' COMMENT '是否删除',
  `create_time` int(11) DEFAULT NULL COMMENT '添加记录时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  `paid_uid` int(11) DEFAULT NULL COMMENT '支付人uid',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table cost_type
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cost_type`;

CREATE TABLE `cost_type` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `who` text,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table user
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nickname` varchar(40) DEFAULT NULL COMMENT '用户昵称',
  `username` varchar(40) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(40) NOT NULL DEFAULT '' COMMENT '密码',
  `avatar` varchar(255) DEFAULT NULL COMMENT '头像',
  `register_time` int(11) NOT NULL COMMENT '注册时间',
  `job` varchar(100) DEFAULT NULL COMMENT '工作',
  `gender` int(11) DEFAULT '0' COMMENT '性别',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;

INSERT INTO `user` (`id`, `nickname`, `username`, `password`, `avatar`, `register_time`, `job`, `gender`)
VALUES
	(4,'shellvon','helloword','helloword','/dist/img/avatar0.png',1461056474,'helloword',0);

/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
