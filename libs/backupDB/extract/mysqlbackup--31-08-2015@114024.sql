--
-- A Mysql Backup System 
--
-- Export created: 2015/08/31 on 11:40


--
-- Database : bz_timestamp
--
-- --------------------------------------------------
-- ---------------------------------------------------
SET AUTOCOMMIT = 0 ;
SET FOREIGN_KEY_CHECKS=0 ;
--
-- Tabel structure for table `t_role`
--
DROP TABLE  IF EXISTS `t_role`;
CREATE TABLE `t_role` (
  `role_id` varchar(20) NOT NULL,
  `role_name` varchar(100) NOT NULL,
  `role_discription` text NOT NULL,
  `role_key` int(1) NOT NULL,
  `create_uid` varchar(20) NOT NULL,
  `create_date` datetime NOT NULL,
  `update_uid` varchar(20) NOT NULL,
  `update_date` datetime NOT NULL,
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `t_role`  VALUES ( "55d3f18cd06f7","Administrator","","1","00072","2015-08-19 10:01:32","00072","2015-08-19 10:10:34");
INSERT INTO `t_role`  VALUES ( "55d3f371153b2","Accounting","","2","00072","2015-08-19 10:09:37","00072","2015-08-19 14:00:46");
INSERT INTO `t_role`  VALUES ( "55d3f3978049b","Leader FP","Leader Floorplan","3","00072","2015-08-19 10:10:15","00072","2015-08-19 10:11:28");
INSERT INTO `t_role`  VALUES ( "55d3f3cbbcba8","Leader PE","Leader Photo edit","4","00072","2015-08-19 10:11:07","00072","2015-08-19 10:11:38");
INSERT INTO `t_role`  VALUES ( "55d6e95e578c5","Leader 3D","","5","00072","2015-08-21 16:03:26","","0000-00-00 00:00:00");
INSERT INTO `t_role`  VALUES ( "55d6e971079fe","Leader CA","","6","00072","2015-08-21 16:03:45","","0000-00-00 00:00:00");
INSERT INTO `t_role`  VALUES ( "55dad51d7c8fe","Leader SU","","7","00072","2015-08-24 15:26:05","","0000-00-00 00:00:00");
INSERT INTO `t_role`  VALUES ( "55dbd8dec5516","Leader IT","","8","00072","2015-08-25 09:54:22","","0000-00-00 00:00:00");


SET FOREIGN_KEY_CHECKS = 1 ; 
COMMIT ; 
SET AUTOCOMMIT = 1 ; 
