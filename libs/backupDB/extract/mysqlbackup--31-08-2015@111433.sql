--
-- A Mysql Backup System 
--
-- Export created: 2015/08/31 on 11:14


--
-- Database : bz_timestamp
--
-- --------------------------------------------------
-- ---------------------------------------------------
SET AUTOCOMMIT = 0 ;
SET FOREIGN_KEY_CHECKS=0 ;
--
-- Tabel structure for table `t_admin_user`
--
DROP TABLE  IF EXISTS `t_admin_user`;
CREATE TABLE `t_admin_user` (
  `admin_user_id` varchar(20) NOT NULL,
  `uid` varchar(20) NOT NULL,
  `role_key` int(1) NOT NULL,
  `create_uid` varchar(10) NOT NULL,
  `create_date` datetime NOT NULL,
  `update_uid` varchar(10) NOT NULL,
  `update_date` datetime NOT NULL,
  PRIMARY KEY (`admin_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `t_admin_user`  VALUES ( "55d4007e5c386","00001","1","00072","2015-08-19 11:05:18","","0000-00-00 00:00:00");
INSERT INTO `t_admin_user`  VALUES ( "55d4008917b7a","00006","1","00072","2015-08-19 11:05:29","","0000-00-00 00:00:00");
INSERT INTO `t_admin_user`  VALUES ( "55d4009d7c719","00003","1","00072","2015-08-19 11:05:49","","0000-00-00 00:00:00");
INSERT INTO `t_admin_user`  VALUES ( "55d400ac12553","00133","2","00072","2015-08-19 11:06:04","","0000-00-00 00:00:00");
INSERT INTO `t_admin_user`  VALUES ( "55d400b511d37","00072","1","00072","2015-08-19 11:06:13","00072","2015-08-25 08:39:12");
INSERT INTO `t_admin_user`  VALUES ( "55d400bf4bf10","00074","1","00072","2015-08-19 11:06:23","","0000-00-00 00:00:00");
INSERT INTO `t_admin_user`  VALUES ( "55d43390bdb42","00157","1","00072","2015-08-19 14:43:12","","0000-00-00 00:00:00");
INSERT INTO `t_admin_user`  VALUES ( "55d433ac60d48","00101","1","00072","2015-08-19 14:43:40","00072","2015-08-24 09:00:18");
INSERT INTO `t_admin_user`  VALUES ( "55d436a825863","00018","3","00072","2015-08-19 14:56:24","","0000-00-00 00:00:00");
INSERT INTO `t_admin_user`  VALUES ( "55d436b24b255","00012","4","00072","2015-08-19 14:56:34","","0000-00-00 00:00:00");
INSERT INTO `t_admin_user`  VALUES ( "55d541856445f","00023","1","00072","2015-08-20 09:55:01","00072","2015-08-24 09:00:07");
INSERT INTO `t_admin_user`  VALUES ( "55dad50c4ddcd","00009","6","00072","2015-08-24 15:25:48","","0000-00-00 00:00:00");
INSERT INTO `t_admin_user`  VALUES ( "55dad52b40214","00017","7","00072","2015-08-24 15:26:19","","0000-00-00 00:00:00");
INSERT INTO `t_admin_user`  VALUES ( "55dad545c0e57","00079","5","00072","2015-08-24 15:26:45","","0000-00-00 00:00:00");


--
-- Tabel structure for table `t_calendar`
--
DROP TABLE  IF EXISTS `t_calendar`;
CREATE TABLE `t_calendar` (
  `calendar_id` varchar(20) NOT NULL,
  `calendar_mate_id` varchar(20) NOT NULL,
  `work_shift_id` varchar(20) NOT NULL,
  `uid` varchar(20) NOT NULL,
  `calendar_date_start` date NOT NULL,
  `calendar_date_end` date NOT NULL,
  `calendar_bg_color` varchar(50) NOT NULL,
  `calendar_border_color` varchar(50) NOT NULL,
  `team` varchar(5) NOT NULL,
  `create_uid` varchar(10) NOT NULL,
  `create_date` datetime NOT NULL,
  `update_uid` varchar(10) NOT NULL,
  `update_date` datetime NOT NULL,
  PRIMARY KEY (`calendar_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `t_calendar`  VALUES ( "55d2b91d54eba","","55c871499df11","00082","2015-08-10","2015-08-15","rgb(0, 115, 183)","rgb(255, 255, 255)","FP","00072","2015-08-18 11:48:29","00072","2015-08-19 15:38:47");
INSERT INTO `t_calendar`  VALUES ( "55d2b91f8b75d","","55c871499df11","00121","2015-08-10","2015-08-15","rgb(0, 115, 183)","rgb(255, 255, 255)","FP","00072","2015-08-18 11:48:31","00072","2015-08-18 11:48:36");
INSERT INTO `t_calendar`  VALUES ( "55d6cc52ddec0","","55c871499df11","1234567890","2015-08-17","2015-08-22","rgb(221, 75, 57)","rgb(255, 255, 255)","FP","00072","2015-08-21 13:59:30","00072","2015-08-21 13:59:32");
INSERT INTO `t_calendar`  VALUES ( "55d6dd177f8ad","","55c871499df11","00082","2015-08-24","2015-08-29","rgb(0, 115, 183)","rgb(255, 255, 255)","FP","00018","2015-08-21 15:11:03","00018","2015-08-21 15:11:29");
INSERT INTO `t_calendar`  VALUES ( "55d6dd1bddf7e","","55c871499df11","00138","2015-08-24","2015-08-29","rgb(0, 115, 183)","rgb(255, 255, 255)","FP","00018","2015-08-21 15:11:07","00018","2015-08-21 15:11:34");
INSERT INTO `t_calendar`  VALUES ( "55d6dd63382f4","","55c871499df11","1234567890","2015-08-31","2015-09-05","rgb(221, 75, 57)","rgb(255, 255, 255)","FP","00018","2015-08-21 15:12:19","00018","2015-08-21 15:12:22");
INSERT INTO `t_calendar`  VALUES ( "55dbad85a67bd","","55bf1082af74b","00012","2015-08-25","2015-08-25","rgb(0, 115, 183)","rgb(255, 255, 255)","PE","00012","2015-08-25 06:49:25","","0000-00-00 00:00:00");
INSERT INTO `t_calendar`  VALUES ( "55dbad85a951b","55dbad85a67bd","55c94bea93fbe","00012","2015-08-26","2015-08-26","rgb(255, 133, 27)","rgb(255, 255, 255)","PE","00012","2015-08-25 06:49:25","","0000-00-00 00:00:00");
INSERT INTO `t_calendar`  VALUES ( "55dbada673a8a","","55bf1082af74b","00081","2015-08-24","2015-08-24","rgb(0, 115, 183)","rgb(255, 255, 255)","PE","00012","2015-08-25 06:49:58","","0000-00-00 00:00:00");
INSERT INTO `t_calendar`  VALUES ( "55dbada67663f","55dbada673a8a","55c94bea93fbe","00081","2015-08-25","2015-08-25","rgb(255, 133, 27)","rgb(255, 255, 255)","PE","00012","2015-08-25 06:49:58","","0000-00-00 00:00:00");
INSERT INTO `t_calendar`  VALUES ( "55dbada9c1b34","","55bf1082af74b","00019","2015-08-24","2015-08-24","rgb(0, 115, 183)","rgb(255, 255, 255)","PE","00012","2015-08-25 06:50:01","","0000-00-00 00:00:00");
INSERT INTO `t_calendar`  VALUES ( "55dbada9c48a2","55dbada9c1b34","55c94bea93fbe","00019","2015-08-25","2015-08-25","rgb(255, 133, 27)","rgb(255, 255, 255)","PE","00012","2015-08-25 06:50:01","","0000-00-00 00:00:00");
INSERT INTO `t_calendar`  VALUES ( "55dbadac4a734","","55bf1082af74b","00051","2015-08-24","2015-08-24","rgb(0, 115, 183)","rgb(255, 255, 255)","PE","00012","2015-08-25 06:50:04","","0000-00-00 00:00:00");
INSERT INTO `t_calendar`  VALUES ( "55dbadac4cbd4","55dbadac4a734","55c94bea93fbe","00051","2015-08-25","2015-08-25","rgb(255, 133, 27)","rgb(255, 255, 255)","PE","00012","2015-08-25 06:50:04","","0000-00-00 00:00:00");
INSERT INTO `t_calendar`  VALUES ( "55dbfa83e1903","","55bf1082af74b","00135","2015-08-26","2015-08-26","rgb(0, 115, 183)","rgb(255, 255, 255)","PE","","2015-08-25 12:17:55","","0000-00-00 00:00:00");
INSERT INTO `t_calendar`  VALUES ( "55dbfa83e45ab","55dbfa83e1903","55c94bea93fbe","00135","2015-08-27","2015-08-27","rgb(255, 133, 27)","rgb(255, 255, 255)","PE","","2015-08-25 12:17:55","","0000-00-00 00:00:00");
INSERT INTO `t_calendar`  VALUES ( "55dbfa9870749","","55bf1082af74b","00021","2015-08-27","2015-08-27","rgb(0, 115, 183)","rgb(255, 255, 255)","PE","","2015-08-25 12:18:16","","0000-00-00 00:00:00");
INSERT INTO `t_calendar`  VALUES ( "55dbfa98735e0","55dbfa9870749","55c94bea93fbe","00021","2015-08-28","2015-08-28","rgb(255, 133, 27)","rgb(255, 255, 255)","PE","","2015-08-25 12:18:16","","0000-00-00 00:00:00");
INSERT INTO `t_calendar`  VALUES ( "55dbfaaae470b","","55bf1082af74b","00123","2015-08-28","2015-08-28","rgb(0, 115, 183)","rgb(255, 255, 255)","PE","","2015-08-25 12:18:34","","0000-00-00 00:00:00");
INSERT INTO `t_calendar`  VALUES ( "55dbfaaae723c","55dbfaaae470b","55c94bea93fbe","00123","2015-08-31","2015-08-31","rgb(255, 133, 27)","rgb(255, 255, 255)","PE","","2015-08-25 12:18:34","","0000-00-00 00:00:00");
INSERT INTO `t_calendar`  VALUES ( "55dbfab814797","","55bf1082af74b","00019","2015-08-31","2015-08-31","rgb(0, 115, 183)","rgb(255, 255, 255)","PE","","2015-08-25 12:18:48","","0000-00-00 00:00:00");
INSERT INTO `t_calendar`  VALUES ( "55dbfab8170f4","55dbfab814797","55c94bea93fbe","00019","2015-09-01","2015-09-01","rgb(255, 133, 27)","rgb(255, 255, 255)","PE","","2015-08-25 12:18:48","","0000-00-00 00:00:00");


SET FOREIGN_KEY_CHECKS = 1 ; 
COMMIT ; 
SET AUTOCOMMIT = 1 ; 
