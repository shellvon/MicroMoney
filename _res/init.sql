# ************************************************************
# Sequel Pro SQL dump
# Version 4499
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.7.11)
# Database: money
# Generation Time: 2016-06-15 06:31:44 +0000
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
  `description` varchar(255) DEFAULT NULL,
  `paid_day` datetime DEFAULT NULL,
  `type` int(11) unsigned NOT NULL,
  `operator_uid` int(11) DEFAULT NULL,
  `is_deal` int(11) NOT NULL DEFAULT '0',
  `is_delete` int(11) NOT NULL DEFAULT '0',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `update_time` int(11) NOT NULL DEFAULT '0',
  `paid_uid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `cost_records` WRITE;
/*!40000 ALTER TABLE `cost_records` DISABLE KEYS */;

INSERT INTO `cost_records` (`id`, `cost`, `description`, `paid_day`, `type`, `operator_uid`, `is_deal`, `is_delete`, `create_time`, `update_time`, `paid_uid`)
VALUES
	(1,3,'买菜','2016-06-14 00:00:00',2,13,1,0,1465894729,1465894729,9),
	(2,2,'吃的','2016-06-14 00:00:00',2,13,1,0,1465894908,1465913215,9),
	(3,30,'买菜','2016-06-14 00:00:00',2,13,1,0,1465913160,1465913160,13),
	(4,400,'买菜','2016-06-14 00:00:00',2,13,1,0,1465913198,1465913198,9),
	(5,20,'买菜','2016-06-14 00:00:00',17,10,1,0,1465913540,1465913540,10),
	(6,30,'买菜哦','2016-06-14 00:00:00',2,10,1,0,1465913966,1465913966,12),
	(7,30,'debug','2016-06-14 00:00:00',2,10,1,0,1465914163,1465914163,11),
	(8,20,'json测试','2016-06-15 00:00:00',2,10,1,0,1465954286,1465954286,10),
	(9,44,'买菜','2016-06-15 00:00:00',2,10,0,0,1465954661,1465961476,9),
	(10,40,'买菜r','2016-06-08 00:00:00',2,10,0,0,1465960587,1465961308,9);

/*!40000 ALTER TABLE `cost_records` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table cost_type
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cost_type`;

CREATE TABLE `cost_type` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `who` text,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `cost_type` WRITE;
/*!40000 ALTER TABLE `cost_type` DISABLE KEYS */;

INSERT INTO `cost_type` (`id`, `who`, `description`)
VALUES
	(2,'9','测试'),
	(3,'9,10','测试,中文测试'),
	(4,'9,11','测试,啥'),
	(5,'9,10,11','测试,中文测试,啥'),
	(6,'9,12','测试,我测试'),
	(7,'9,10,12','测试,中文测试,我测试'),
	(8,'9,11,12','测试,啥,我测试'),
	(9,'9,10,11,12','测试,中文测试,啥,我测试'),
	(10,'9,13','测试,测试呀'),
	(11,'9,10,13','测试,中文测试,测试呀'),
	(12,'9,11,13','测试,啥,测试呀'),
	(13,'9,10,11,13','测试,中文测试,啥,测试呀'),
	(14,'9,12,13','测试,我测试,测试呀'),
	(15,'9,10,12,13','测试,中文测试,我测试,测试呀'),
	(16,'9,11,12,13','测试,啥,我测试,测试呀'),
	(17,'9,10,11,12,13','测试,中文测试,啥,我测试,测试呀');

/*!40000 ALTER TABLE `cost_type` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table notification
# ------------------------------------------------------------

DROP TABLE IF EXISTS `notification`;

CREATE TABLE `notification` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `content` text,
  `type` tinyint(11) NOT NULL DEFAULT '1',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `sender` int(11) NOT NULL DEFAULT '0',
  `action` varchar(255) DEFAULT NULL,
  `target_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `notification` WRITE;
/*!40000 ALTER TABLE `notification` DISABLE KEYS */;

INSERT INTO `notification` (`id`, `content`, `type`, `create_time`, `sender`, `action`, `target_id`)
VALUES
	(1,'注册了新用户',1,1465883255,13,'register',NULL),
	(2,'增加了一个deal',1,1465894729,13,'insert_deal',NULL),
	(3,'添加了一个20元的交易,ID为:2',1,1465894908,13,'insert_deal',NULL),
	(4,'结算了一个deal',1,1465895166,13,'single_deal',NULL),
	(5,'更新了一个deal',1,1465900240,13,'update_deal',NULL),
	(6,'更新了一个deal',1,1465900291,13,'update_deal',NULL),
	(7,'更新了一个deal',1,1465913096,13,'update_deal',NULL),
	(8,'添加了一个30元的交易,ID为:3',1,1465913160,13,'insert_deal',NULL),
	(9,'添加了一个400元的交易,ID为:4',1,1465913198,13,'insert_deal',NULL),
	(10,'更新了一个deal',1,1465913215,13,'update_deal',NULL),
	(11,'结算了一个deal',1,1465913239,13,'single_deal',NULL),
	(12,'添加了一个20元的交易,ID为:5',1,1465913540,10,'insert_deal',NULL),
	(13,'添加了一个30元的交易,ID为:6',1,1465913966,10,'insert_deal',NULL),
	(14,'添加了一个30元的交易,ID为:7',1,1465914163,10,'insert_deal',NULL),
	(15,'添加了一个20元的交易,ID为:8',1,1465954286,10,'insert_deal',NULL),
	(16,'批量计算deal',1,1465954465,10,'batch_deal',NULL),
	(17,'添加了一个4元的交易,ID为:9',1,1465954661,10,'insert_deal',NULL),
	(18,'添加了一个4元的交易,ID为:10',1,1465960587,10,'insert_deal',NULL),
	(19,'更新了一个deal',1,1465961148,10,'update_deal',NULL),
	(20,'更新了一个deal',1,1465961308,10,'update_deal',NULL),
	(21,'更新了一个deal',1,1465961476,10,'update_deal',NULL);

/*!40000 ALTER TABLE `notification` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table operation_logs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `operation_logs`;

CREATE TABLE `operation_logs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `create_time` int(11) NOT NULL DEFAULT '0',
  `action` varchar(255) NOT NULL DEFAULT 'insert_deal',
  `operator_id` int(11) NOT NULL DEFAULT '0',
  `old_data` text,
  `new_data` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `operation_logs` WRITE;
/*!40000 ALTER TABLE `operation_logs` DISABLE KEYS */;

INSERT INTO `operation_logs` (`id`, `create_time`, `action`, `operator_id`, `old_data`, `new_data`)
VALUES
	(1,1465894729,'insert_deal',13,'null','{\"operator_uid\":\"13\",\"create_time\":1465894729,\"update_time\":1465894729,\"paid_uid\":\"9\",\"cost\":\"3\",\"paid_day\":\"2016-06-14\",\"type\":\"2\",\"description\":\"\\u4e70\\u83dc\"}'),
	(2,1465894908,'insert_deal',13,'null','{\"operator_uid\":\"13\",\"create_time\":1465894908,\"update_time\":1465894908,\"paid_uid\":\"9\",\"cost\":\"20\",\"paid_day\":\"2016-06-14\",\"type\":\"2\",\"description\":\"\\u4e70\\u83dc\"}'),
	(3,1465895166,'single_deal',13,'null','{\"id\":\"1\"}'),
	(4,1465900240,'update_deal',13,'{\"id\":\"2\",\"cost\":\"20\",\"description\":\"\\u4e70\\u83dc\",\"paid_day\":\"2016-06-14 00:00:00\",\"type\":\"2\",\"operator_uid\":\"13\",\"is_deal\":\"0\",\"is_delete\":\"0\",\"create_time\":\"1465894908\",\"update_time\":\"1465894908\",\"paid_uid\":\"9\"}','{\"paid_uid\":\"9\",\"cost\":\"29\",\"paid_day\":\"2016-06-14\",\"type\":\"2\",\"description\":\"\\u4e70\\u83dc\",\"update_time\":1465900240}'),
	(5,1465900291,'update_deal',13,'{\"id\":\"2\",\"cost\":\"29\",\"description\":\"\\u4e70\\u83dc\",\"paid_day\":\"2016-06-14 00:00:00\",\"type\":\"2\",\"operator_uid\":\"13\",\"is_deal\":\"0\",\"is_delete\":\"0\",\"create_time\":\"1465894908\",\"update_time\":\"1465900240\",\"paid_uid\":\"9\"}','{\"paid_uid\":\"9\",\"cost\":\"24\",\"paid_day\":\"2016-06-14\",\"type\":\"2\",\"description\":\"\\u4e70\\u83dc\",\"update_time\":1465900291}'),
	(6,1465913096,'update_deal',13,'{\"id\":\"2\",\"cost\":\"24\",\"description\":\"\\u4e70\\u83dc\",\"paid_day\":\"2016-06-14 00:00:00\",\"type\":\"2\",\"operator_uid\":\"13\",\"is_deal\":\"0\",\"is_delete\":\"0\",\"create_time\":\"1465894908\",\"update_time\":\"1465900291\",\"paid_uid\":\"9\"}','{\"paid_uid\":\"9\",\"cost\":\"24\",\"paid_day\":\"2016-06-14\",\"type\":\"2\",\"description\":\"\\u5403\\u7684\",\"update_time\":1465913096}'),
	(7,1465913160,'insert_deal',13,'null','{\"operator_uid\":\"13\",\"create_time\":1465913160,\"update_time\":1465913160,\"paid_uid\":\"13\",\"cost\":\"30\",\"paid_day\":\"2016-06-14\",\"type\":\"2\",\"description\":\"\\u4e70\\u83dc\"}'),
	(8,1465913198,'insert_deal',13,'null','{\"operator_uid\":\"13\",\"create_time\":1465913198,\"update_time\":1465913198,\"paid_uid\":\"9\",\"cost\":\"400\",\"paid_day\":\"2016-06-14\",\"type\":\"2\",\"description\":\"\\u4e70\\u83dc\"}'),
	(9,1465913215,'update_deal',13,'{\"id\":\"2\",\"cost\":\"24\",\"description\":\"\\u5403\\u7684\",\"paid_day\":\"2016-06-14 00:00:00\",\"type\":\"2\",\"operator_uid\":\"13\",\"is_deal\":\"0\",\"is_delete\":\"0\",\"create_time\":\"1465894908\",\"update_time\":\"1465913096\",\"paid_uid\":\"9\"}','{\"paid_uid\":\"9\",\"cost\":\"2\",\"paid_day\":\"2016-06-14\",\"type\":\"2\",\"description\":\"\\u5403\\u7684\",\"update_time\":1465913215}'),
	(10,1465913239,'single_deal',13,'null','{\"id\":\"4\"}'),
	(11,1465913540,'insert_deal',10,'null','{\"operator_uid\":\"10\",\"create_time\":1465913540,\"update_time\":1465913540,\"paid_uid\":\"10\",\"cost\":\"20\",\"paid_day\":\"2016-06-14\",\"type\":\"17\",\"description\":\"\\u4e70\\u83dc\"}'),
	(12,1465913966,'insert_deal',10,'null','{\"operator_uid\":\"10\",\"create_time\":1465913966,\"update_time\":1465913966,\"paid_uid\":\"12\",\"cost\":\"30\",\"paid_day\":\"2016-06-14\",\"type\":\"2\",\"description\":\"\\u4e70\\u83dc\\u54e6\"}'),
	(13,1465914163,'insert_deal',10,'null','{\"operator_uid\":\"10\",\"create_time\":1465914163,\"update_time\":1465914163,\"paid_uid\":\"11\",\"cost\":\"30\",\"paid_day\":\"2016-06-14\",\"type\":\"2\",\"description\":\"debug\"}'),
	(14,1465954286,'insert_deal',10,'null','{\n    \"operator_uid\": \"10\",\n    \"create_time\": 1465954286,\n    \"update_time\": 1465954286,\n    \"paid_uid\": \"10\",\n    \"cost\": \"20\",\n    \"paid_day\": \"2016-06-15\",\n    \"type\": \"2\",\n    \"description\": \"json测试\"\n}'),
	(15,1465954465,'batch_deal',10,'[\n    {\n        \"id\": \"2\",\n        \"cost\": \"2\",\n        \"description\": \"吃的\",\n        \"paid_day\": \"2016-06-14 00:00:00\",\n        \"type\": \"2\",\n        \"operator_uid\": \"13\",\n        \"is_deal\": \"0\",\n        \"is_delete\": \"0\",\n        \"create_time\": \"1465894908\",\n        \"update_time\": \"1465913215\",\n        \"paid_uid\": \"9\"\n    },\n    {\n        \"id\": \"3\",\n        \"cost\": \"30\",\n        \"description\": \"买菜\",\n        \"paid_day\": \"2016-06-14 00:00:00\",\n        \"type\": \"2\",\n        \"operator_uid\": \"13\",\n        \"is_deal\": \"0\",\n        \"is_delete\": \"0\",\n        \"create_time\": \"1465913160\",\n        \"update_time\": \"1465913160\",\n        \"paid_uid\": \"13\"\n    },\n    {\n        \"id\": \"5\",\n        \"cost\": \"20\",\n        \"description\": \"买菜\",\n        \"paid_day\": \"2016-06-14 00:00:00\",\n        \"type\": \"17\",\n        \"operator_uid\": \"10\",\n        \"is_deal\": \"0\",\n        \"is_delete\": \"0\",\n        \"create_time\": \"1465913540\",\n        \"update_time\": \"1465913540\",\n        \"paid_uid\": \"10\"\n    },\n    {\n        \"id\": \"6\",\n        \"cost\": \"30\",\n        \"description\": \"买菜哦\",\n        \"paid_day\": \"2016-06-14 00:00:00\",\n        \"type\": \"2\",\n        \"operator_uid\": \"10\",\n        \"is_deal\": \"0\",\n        \"is_delete\": \"0\",\n        \"create_time\": \"1465913966\",\n        \"update_time\": \"1465913966\",\n        \"paid_uid\": \"12\"\n    },\n    {\n        \"id\": \"7\",\n        \"cost\": \"30\",\n        \"description\": \"debug\",\n        \"paid_day\": \"2016-06-14 00:00:00\",\n        \"type\": \"2\",\n        \"operator_uid\": \"10\",\n        \"is_deal\": \"0\",\n        \"is_delete\": \"0\",\n        \"create_time\": \"1465914163\",\n        \"update_time\": \"1465914163\",\n        \"paid_uid\": \"11\"\n    },\n    {\n        \"id\": \"8\",\n        \"cost\": \"20\",\n        \"description\": \"json测试\",\n        \"paid_day\": \"2016-06-15 00:00:00\",\n        \"type\": \"2\",\n        \"operator_uid\": \"10\",\n        \"is_deal\": \"0\",\n        \"is_delete\": \"0\",\n        \"create_time\": \"1465954286\",\n        \"update_time\": \"1465954286\",\n        \"paid_uid\": \"10\"\n    }\n]','null'),
	(16,1465954661,'insert_deal',10,NULL,'{\n    \"operator_uid\": \"10\",\n    \"create_time\": 1465954661,\n    \"update_time\": 1465954661,\n    \"paid_uid\": \"10\",\n    \"cost\": \"4\",\n    \"paid_day\": \"2016-06-15\",\n    \"type\": \"2\",\n    \"description\": \"买菜\"\n}'),
	(17,1465960587,'insert_deal',10,NULL,'{\n    \"operator_uid\": \"10\",\n    \"create_time\": 1465960587,\n    \"update_time\": 1465960587,\n    \"paid_uid\": \"10\",\n    \"cost\": \"4\",\n    \"paid_day\": \"2016-06-08\",\n    \"type\": \"2\",\n    \"description\": \"买菜\"\n}'),
	(18,1465961148,'update_deal',10,'{\n    \"id\": \"10\",\n    \"cost\": \"4\",\n    \"description\": \"买菜\",\n    \"paid_day\": \"2016-06-08 00:00:00\",\n    \"type\": \"2\",\n    \"operator_uid\": \"10\",\n    \"is_deal\": \"0\",\n    \"is_delete\": \"0\",\n    \"create_time\": \"1465960587\",\n    \"update_time\": \"1465960587\",\n    \"paid_uid\": \"10\"\n}','{\n    \"paid_uid\": \"9\",\n    \"cost\": \"40\",\n    \"paid_day\": \"2016-06-08\",\n    \"type\": \"2\",\n    \"description\": \"买菜\",\n    \"update_time\": 1465961148\n}'),
	(19,1465961308,'update_deal',10,'{\n    \"id\": \"10\",\n    \"cost\": \"40\",\n    \"description\": \"买菜\",\n    \"paid_day\": \"2016-06-08 00:00:00\",\n    \"type\": \"2\",\n    \"operator_uid\": \"10\",\n    \"is_deal\": \"0\",\n    \"is_delete\": \"0\",\n    \"create_time\": \"1465960587\",\n    \"update_time\": \"1465961148\",\n    \"paid_uid\": \"9\"\n}','{\n    \"paid_uid\": \"9\",\n    \"cost\": \"40\",\n    \"paid_day\": \"2016-06-08\",\n    \"type\": \"2\",\n    \"description\": \"买菜r\",\n    \"update_time\": 1465961308\n}'),
	(20,1465961476,'update_deal',10,'{\n    \"id\": \"9\",\n    \"cost\": \"4\",\n    \"description\": \"买菜\",\n    \"paid_day\": \"2016-06-15 00:00:00\",\n    \"type\": \"2\",\n    \"operator_uid\": \"10\",\n    \"is_deal\": \"0\",\n    \"is_delete\": \"0\",\n    \"create_time\": \"1465954661\",\n    \"update_time\": \"1465954661\",\n    \"paid_uid\": \"10\"\n}','{\n    \"paid_uid\": \"9\",\n    \"cost\": \"44\",\n    \"paid_day\": \"2016-06-15\",\n    \"type\": \"2\",\n    \"description\": \"买菜\",\n    \"update_time\": 1465961476\n}');

/*!40000 ALTER TABLE `operation_logs` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nickname` varchar(40) DEFAULT NULL,
  `username` varchar(40) NOT NULL,
  `password` varchar(40) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `register_time` int(11) NOT NULL,
  `job` varchar(100) DEFAULT NULL,
  `gender` int(11) DEFAULT NULL,
  `fortest` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;

INSERT INTO `user` (`id`, `nickname`, `username`, `password`, `avatar`, `register_time`, `job`, `gender`, `fortest`)
VALUES
	(9,'测试','admin2','admin2','/dist/img/avatar2.png',1465810131,'hello',NULL,NULL),
	(10,'中文测试','admin','admin','/dist/img/avatar3.png',1465810166,'admin',NULL,NULL),
	(11,'啥','pythoner','pythoner','/dist/img/avatar1.png',1465810322,'pythoner',NULL,NULL),
	(12,'我测试','python','python','/dist/img/avatar3.png',1465883153,'python',NULL,NULL),
	(13,'测试呀','javascript','javascript','/dist/img/avatar2.png',1465883255,'javascript',NULL,NULL);

/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_notify
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_notify`;

CREATE TABLE `user_notify` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `notify_id` int(11) NOT NULL DEFAULT '0',
  `receiver` int(11) NOT NULL DEFAULT '0',
  `is_read` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `user_notify` WRITE;
/*!40000 ALTER TABLE `user_notify` DISABLE KEYS */;

INSERT INTO `user_notify` (`id`, `notify_id`, `receiver`, `is_read`)
VALUES
	(1,1,9,0),
	(2,1,10,1),
	(3,1,11,0),
	(4,1,12,0),
	(5,1,13,1),
	(6,2,9,0),
	(7,2,10,1),
	(8,2,11,0),
	(9,2,12,0),
	(10,2,13,1),
	(11,3,9,0),
	(12,3,10,1),
	(13,3,11,0),
	(14,3,12,0),
	(15,3,13,1),
	(16,4,9,0),
	(17,4,10,1),
	(18,4,11,0),
	(19,4,12,0),
	(20,4,13,1),
	(21,5,9,0),
	(22,5,10,1),
	(23,5,11,0),
	(24,5,12,0),
	(25,5,13,1),
	(26,6,9,0),
	(27,6,10,1),
	(28,6,11,0),
	(29,6,12,0),
	(30,6,13,1),
	(31,7,9,0),
	(32,7,10,1),
	(33,7,11,0),
	(34,7,12,0),
	(35,7,13,1),
	(36,8,9,0),
	(37,8,10,1),
	(38,8,11,0),
	(39,8,12,0),
	(40,8,13,1),
	(41,9,9,0),
	(42,9,10,1),
	(43,9,11,0),
	(44,9,12,0),
	(45,9,13,0),
	(46,10,9,0),
	(47,10,10,1),
	(48,10,11,0),
	(49,10,12,0),
	(50,10,13,0),
	(51,11,9,0),
	(52,11,10,1),
	(53,11,11,0),
	(54,11,12,0),
	(55,11,13,0),
	(56,12,9,0),
	(57,12,10,1),
	(58,12,11,0),
	(59,12,12,0),
	(60,12,13,0),
	(61,13,9,0),
	(62,13,10,1),
	(63,13,11,0),
	(64,13,12,0),
	(65,13,13,0),
	(66,14,9,0),
	(67,14,10,1),
	(68,14,11,0),
	(69,14,12,0),
	(70,14,13,0),
	(71,15,9,0),
	(72,15,10,1),
	(73,15,11,0),
	(74,15,12,0),
	(75,15,13,0),
	(76,16,9,0),
	(77,16,10,1),
	(78,16,11,0),
	(79,16,12,0),
	(80,16,13,0),
	(81,17,9,0),
	(82,17,10,1),
	(83,17,11,0),
	(84,17,12,0),
	(85,17,13,0),
	(86,18,9,0),
	(87,18,10,1),
	(88,18,11,0),
	(89,18,12,0),
	(90,18,13,0),
	(91,19,9,0),
	(92,19,10,1),
	(93,19,11,0),
	(94,19,12,0),
	(95,19,13,0),
	(96,20,9,0),
	(97,20,10,1),
	(98,20,11,0),
	(99,20,12,0),
	(100,20,13,0),
	(101,21,9,0),
	(102,21,10,1),
	(103,21,11,0),
	(104,21,12,0),
	(105,21,13,0);

/*!40000 ALTER TABLE `user_notify` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
