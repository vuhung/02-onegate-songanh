-- MySQL Administrator dump 1.4
--
-- ------------------------------------------------------
-- Server version	5.0.24a-community-nt


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


--
-- Create schema tracuu_mhcu
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ tracuu_mhcu;
USE tracuu_mhcu;

--
-- Table structure for table `tracuu_mhcu`.`file`
--

DROP TABLE IF EXISTS `file`;
CREATE TABLE `file` (
  `ID_DK` int(11) NOT NULL auto_increment,
  `FOLDER` varchar(50) default NULL,
  `ID_OBJECT` int(11) default NULL,
  `MASO` varchar(64) default NULL,
  `NAM` int(11) default NULL,
  `THANG` int(11) default NULL,
  `MIME` varchar(100) default NULL,
  `FILENAME` varchar(512) default NULL,
  `TYPE` int(2) default NULL COMMENT '1: hscv va soan thao; 3: vbden\r\n10,12: giao viec\r\n13: iso',
  `CONTENT` longtext,
  `USER` int(11) default NULL,
  `TIME_UPDATE` datetime default NULL,
  PRIMARY KEY  (`ID_DK`),
  KEY `ID_OBJECT` (`ID_OBJECT`),
  KEY `TYPE` (`TYPE`),
  KEY `MASO` (`MASO`),
  KEY `TYPE_ID_OBJECT` (`TYPE`,`ID_OBJECT`),
  FULLTEXT KEY `CONTENT` (`CONTENT`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='Kieu file dinh kem\r\n-1: dang luu, chua xac dinh loai ho so \r';

--
-- Dumping data for table `tracuu_mhcu`.`file`
--

/*!40000 ALTER TABLE `file` DISABLE KEYS */;
INSERT INTO `file` (`ID_DK`,`FOLDER`,`ID_OBJECT`,`MASO`,`NAM`,`THANG`,`MIME`,`FILENAME`,`TYPE`,`CONTENT`,`USER`,`TIME_UPDATE`) VALUES 
 (1,'D:\\\\\\\\Upload\\2009\\7',5330,NULL,2009,7,'application/pdf','free_DN_flex2as3.pdf',-1,NULL,139,'2009-07-13 15:35:40'),
 (2,'D:\\\\\\\\Upload\\2009\\7',5331,'d1595e22784c0a1460026911142dfa1d',2009,7,'application/pdf','free_DN_flex2as3.pdf',-1,NULL,139,'2009-07-13 15:37:36');
/*!40000 ALTER TABLE `file` ENABLE KEYS */;


--
-- Table structure for table `tracuu_mhcu`.`file_resource`
--

DROP TABLE IF EXISTS `file_resource`;
CREATE TABLE `file_resource` (
  `ID_FILE` int(11) NOT NULL auto_increment,
  `TEN` varchar(1024) default NULL,
  PRIMARY KEY  (`ID_FILE`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tracuu_mhcu`.`file_resource`
--

/*!40000 ALTER TABLE `file_resource` DISABLE KEYS */;
/*!40000 ALTER TABLE `file_resource` ENABLE KEYS */;


--
-- Table structure for table `tracuu_mhcu`.`fk_groups_actions`
--

DROP TABLE IF EXISTS `fk_groups_actions`;
CREATE TABLE `fk_groups_actions` (
  `ID_G` int(11) NOT NULL,
  `ID_ACT` int(11) NOT NULL,
  PRIMARY KEY  (`ID_G`,`ID_ACT`),
  KEY `FK_GROUPS_ACTIONS_FK` (`ID_G`),
  KEY `FK_GROUPS_ACTIONS2_FK` (`ID_ACT`),
  CONSTRAINT `fk_groups_actions_fk` FOREIGN KEY (`ID_G`) REFERENCES `qtht_groups` (`ID_G`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_groups_actions_fk1` FOREIGN KEY (`ID_ACT`) REFERENCES `qtht_actions` (`ID_ACT`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tracuu_mhcu`.`fk_groups_actions`
--

/*!40000 ALTER TABLE `fk_groups_actions` DISABLE KEYS */;
INSERT INTO `fk_groups_actions` (`ID_G`,`ID_ACT`) VALUES 
 (3,29),
 (3,30),
 (3,34),
 (3,35),
 (3,36),
 (3,37),
 (3,38),
 (3,39),
 (3,136),
 (3,139),
 (3,184),
 (3,185),
 (3,187),
 (3,189),
 (3,220),
 (3,222),
 (3,223),
 (3,279),
 (7,312);
/*!40000 ALTER TABLE `fk_groups_actions` ENABLE KEYS */;


--
-- Table structure for table `tracuu_mhcu`.`fk_groups_modules`
--

DROP TABLE IF EXISTS `fk_groups_modules`;
CREATE TABLE `fk_groups_modules` (
  `ID_G` int(11) NOT NULL,
  `ID_MOD` int(11) NOT NULL,
  PRIMARY KEY  (`ID_G`,`ID_MOD`),
  KEY `FK_GROUPS_MODULES_FK` (`ID_G`),
  KEY `FK_GROUPS_MODULES2_FK` (`ID_MOD`),
  CONSTRAINT `fk_groups_modules_fk` FOREIGN KEY (`ID_G`) REFERENCES `qtht_groups` (`ID_G`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_groups_modules_fk1` FOREIGN KEY (`ID_MOD`) REFERENCES `qtht_modules` (`ID_MOD`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tracuu_mhcu`.`fk_groups_modules`
--

/*!40000 ALTER TABLE `fk_groups_modules` DISABLE KEYS */;
/*!40000 ALTER TABLE `fk_groups_modules` ENABLE KEYS */;


--
-- Table structure for table `tracuu_mhcu`.`fk_quitrinhs_loaihoso`
--

DROP TABLE IF EXISTS `fk_quitrinhs_loaihoso`;
CREATE TABLE `fk_quitrinhs_loaihoso` (
  `ID_FK_QUITRINHSLOAIHOSO` int(11) NOT NULL auto_increment,
  `ID_QUITRINH` int(11) default NULL,
  `ID_LOAIHOSO` int(11) default NULL,
  PRIMARY KEY  (`ID_FK_QUITRINHSLOAIHOSO`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tracuu_mhcu`.`fk_quitrinhs_loaihoso`
--

/*!40000 ALTER TABLE `fk_quitrinhs_loaihoso` DISABLE KEYS */;
INSERT INTO `fk_quitrinhs_loaihoso` (`ID_FK_QUITRINHSLOAIHOSO`,`ID_QUITRINH`,`ID_LOAIHOSO`) VALUES 
 (1,2,1),
 (2,2,2);
/*!40000 ALTER TABLE `fk_quitrinhs_loaihoso` ENABLE KEYS */;


--
-- Table structure for table `tracuu_mhcu`.`fk_thutuc_giaytos`
--

DROP TABLE IF EXISTS `fk_thutuc_giaytos`;
CREATE TABLE `fk_thutuc_giaytos` (
  `ID_FK_THUTUCGIAYTOS` int(11) NOT NULL auto_increment,
  `ID_THUTUC` int(11) default NULL,
  `ID_GIAYTO` int(11) default NULL,
  PRIMARY KEY  (`ID_FK_THUTUCGIAYTOS`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tracuu_mhcu`.`fk_thutuc_giaytos`
--

/*!40000 ALTER TABLE `fk_thutuc_giaytos` DISABLE KEYS */;
/*!40000 ALTER TABLE `fk_thutuc_giaytos` ENABLE KEYS */;


--
-- Table structure for table `tracuu_mhcu`.`fk_thutucs_loaihoso`
--

DROP TABLE IF EXISTS `fk_thutucs_loaihoso`;
CREATE TABLE `fk_thutucs_loaihoso` (
  `ID_FK_THUTUCSLOAIHOSO` int(11) NOT NULL auto_increment,
  `ID_THUTUC` int(11) default NULL,
  `ID_LOAIHOSO` int(11) default NULL,
  PRIMARY KEY  (`ID_FK_THUTUCSLOAIHOSO`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tracuu_mhcu`.`fk_thutucs_loaihoso`
--

/*!40000 ALTER TABLE `fk_thutucs_loaihoso` DISABLE KEYS */;
INSERT INTO `fk_thutucs_loaihoso` (`ID_FK_THUTUCSLOAIHOSO`,`ID_THUTUC`,`ID_LOAIHOSO`) VALUES 
 (2,1,2),
 (3,8,2),
 (4,9,2),
 (5,10,2),
 (6,11,2),
 (7,12,2);
/*!40000 ALTER TABLE `fk_thutucs_loaihoso` ENABLE KEYS */;


--
-- Table structure for table `tracuu_mhcu`.`fk_users_actions`
--

DROP TABLE IF EXISTS `fk_users_actions`;
CREATE TABLE `fk_users_actions` (
  `ID_U` int(11) NOT NULL,
  `ID_ACT` int(11) NOT NULL,
  PRIMARY KEY  (`ID_U`,`ID_ACT`),
  KEY `FK_USERS_ACTIONS_FK` (`ID_U`),
  KEY `FK_USERS_ACTIONS2_FK` (`ID_ACT`),
  CONSTRAINT `fk_users_actions_fk` FOREIGN KEY (`ID_U`) REFERENCES `qtht_users` (`ID_U`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_users_actions_fk1` FOREIGN KEY (`ID_ACT`) REFERENCES `qtht_actions` (`ID_ACT`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tracuu_mhcu`.`fk_users_actions`
--

/*!40000 ALTER TABLE `fk_users_actions` DISABLE KEYS */;
/*!40000 ALTER TABLE `fk_users_actions` ENABLE KEYS */;


--
-- Table structure for table `tracuu_mhcu`.`fk_users_groups`
--

DROP TABLE IF EXISTS `fk_users_groups`;
CREATE TABLE `fk_users_groups` (
  `ID_U` int(11) NOT NULL,
  `ID_G` int(11) NOT NULL,
  PRIMARY KEY  (`ID_U`,`ID_G`),
  KEY `FK_USERS_GROUPS_FK` (`ID_U`),
  KEY `FK_USERS_GROUPS2_FK` (`ID_G`),
  CONSTRAINT `fk_users_groups_fk` FOREIGN KEY (`ID_U`) REFERENCES `qtht_users` (`ID_U`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_users_groups_fk1` FOREIGN KEY (`ID_G`) REFERENCES `qtht_groups` (`ID_G`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tracuu_mhcu`.`fk_users_groups`
--

/*!40000 ALTER TABLE `fk_users_groups` DISABLE KEYS */;
INSERT INTO `fk_users_groups` (`ID_U`,`ID_G`) VALUES 
 (51,6),
 (52,8),
 (53,8),
 (54,6),
 (55,8),
 (56,6),
 (57,6),
 (58,6),
 (59,6),
 (60,8),
 (61,6),
 (62,6),
 (63,6),
 (64,6),
 (65,6),
 (66,8),
 (67,6),
 (68,6),
 (69,6),
 (70,8),
 (71,6),
 (72,6),
 (73,6),
 (74,6),
 (75,6),
 (76,6),
 (77,6),
 (78,6),
 (79,6),
 (80,6),
 (81,6),
 (82,6),
 (83,6),
 (83,8),
 (84,6),
 (86,6),
 (87,6),
 (88,6),
 (89,8),
 (90,8),
 (139,3);
/*!40000 ALTER TABLE `fk_users_groups` ENABLE KEYS */;


--
-- Table structure for table `tracuu_mhcu`.`fk_users_modules`
--

DROP TABLE IF EXISTS `fk_users_modules`;
CREATE TABLE `fk_users_modules` (
  `ID_U` int(11) NOT NULL,
  `ID_MOD` int(11) NOT NULL,
  PRIMARY KEY  (`ID_U`,`ID_MOD`),
  KEY `FK_USERS_MODULES_FK` (`ID_U`),
  KEY `FK_USERS_MODULES2_FK` (`ID_MOD`),
  CONSTRAINT `fk_users_modules_fk` FOREIGN KEY (`ID_U`) REFERENCES `qtht_users` (`ID_U`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_users_modules_fk1` FOREIGN KEY (`ID_MOD`) REFERENCES `qtht_modules` (`ID_MOD`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tracuu_mhcu`.`fk_users_modules`
--

/*!40000 ALTER TABLE `fk_users_modules` DISABLE KEYS */;
/*!40000 ALTER TABLE `fk_users_modules` ENABLE KEYS */;


--
-- Table structure for table `tracuu_mhcu`.`fk_vanbanlienquan_object`
--

DROP TABLE IF EXISTS `fk_vanbanlienquan_object`;
CREATE TABLE `fk_vanbanlienquan_object` (
  `ID_FK_VANBANLIENQUANOBJECT` int(11) NOT NULL auto_increment,
  `ID_VANBANLIENQUAN` int(11) default NULL,
  `ID_OBJECT` int(11) default NULL,
  PRIMARY KEY  (`ID_FK_VANBANLIENQUANOBJECT`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tracuu_mhcu`.`fk_vanbanlienquan_object`
--

/*!40000 ALTER TABLE `fk_vanbanlienquan_object` DISABLE KEYS */;
/*!40000 ALTER TABLE `fk_vanbanlienquan_object` ENABLE KEYS */;


--
-- Table structure for table `tracuu_mhcu`.`gen_lichnghilam`
--

DROP TABLE IF EXISTS `gen_lichnghilam`;
CREATE TABLE `gen_lichnghilam` (
  `ID_LNL` int(11) NOT NULL auto_increment,
  `TUNGAY` date default NULL,
  `DENNGAY` date default NULL,
  `ISRANGE` int(11) default NULL,
  `DESCRIPTION` varchar(256) default NULL,
  PRIMARY KEY  (`ID_LNL`),
  UNIQUE KEY `TUNGAY` (`TUNGAY`,`DENNGAY`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tracuu_mhcu`.`gen_lichnghilam`
--

/*!40000 ALTER TABLE `gen_lichnghilam` DISABLE KEYS */;
INSERT INTO `gen_lichnghilam` (`ID_LNL`,`TUNGAY`,`DENNGAY`,`ISRANGE`,`DESCRIPTION`) VALUES 
 (1,'2008-11-12','2008-11-12',1,'Test 1');
/*!40000 ALTER TABLE `gen_lichnghilam` ENABLE KEYS */;


--
-- Table structure for table `tracuu_mhcu`.`gen_nonworkingdates`
--

DROP TABLE IF EXISTS `gen_nonworkingdates`;
CREATE TABLE `gen_nonworkingdates` (
  `ID_NWKD` int(11) NOT NULL auto_increment,
  `NAME` varchar(128) default NULL,
  `WDAY` int(11) default NULL,
  `ISYEAR` bit(1) default NULL,
  `ISMONTH` int(11) default NULL,
  `ISREALDATE` int(11) default NULL,
  `ISMOON` int(11) default NULL,
  `ISCOMMON` int(11) default NULL,
  `BDAY` int(11) default NULL,
  `BMON` int(11) default NULL,
  `BYEAR` int(11) default NULL,
  `EDAY` int(11) default NULL,
  `EMON` int(11) default NULL,
  `EYEAR` int(11) default NULL,
  PRIMARY KEY  (`ID_NWKD`),
  KEY `BDAY` (`BDAY`,`BMON`),
  KEY `EDAY` (`EDAY`,`EMON`)
) ENGINE=MyISAM AUTO_INCREMENT=47 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tracuu_mhcu`.`gen_nonworkingdates`
--

/*!40000 ALTER TABLE `gen_nonworkingdates` DISABLE KEYS */;
INSERT INTO `gen_nonworkingdates` (`ID_NWKD`,`NAME`,`WDAY`,`ISYEAR`,`ISMONTH`,`ISREALDATE`,`ISMOON`,`ISCOMMON`,`BDAY`,`BMON`,`BYEAR`,`EDAY`,`EMON`,`EYEAR`) VALUES 
 (36,NULL,NULL,NULL,1,NULL,NULL,NULL,26,9,NULL,26,9,NULL),
 (22,NULL,0,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL),
 (39,NULL,6,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL),
 (40,NULL,NULL,NULL,1,NULL,NULL,NULL,13,11,NULL,13,11,NULL),
 (33,NULL,NULL,NULL,1,NULL,NULL,NULL,3,10,NULL,3,10,NULL),
 (35,NULL,NULL,NULL,1,NULL,NULL,NULL,25,9,NULL,25,9,NULL),
 (45,NULL,NULL,NULL,1,NULL,NULL,NULL,25,12,NULL,25,12,NULL),
 (46,NULL,NULL,NULL,1,NULL,NULL,NULL,1,1,NULL,1,1,NULL);
/*!40000 ALTER TABLE `gen_nonworkingdates` ENABLE KEYS */;


--
-- Table structure for table `tracuu_mhcu`.`gen_temp`
--

DROP TABLE IF EXISTS `gen_temp`;
CREATE TABLE `gen_temp` (
  `id_temp` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`id_temp`)
) ENGINE=MyISAM AUTO_INCREMENT=5364 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tracuu_mhcu`.`gen_temp`
--

/*!40000 ALTER TABLE `gen_temp` DISABLE KEYS */;
INSERT INTO `gen_temp` (`id_temp`) VALUES 
 (5330),
 (5331),
 (5332),
 (5333),
 (5334),
 (5335),
 (5336),
 (5337),
 (5338),
 (5339),
 (5340),
 (5341),
 (5342),
 (5343),
 (5344),
 (5345),
 (5346),
 (5347),
 (5348),
 (5349),
 (5350),
 (5351),
 (5352),
 (5353),
 (5354),
 (5355),
 (5356),
 (5357),
 (5358),
 (5359),
 (5360),
 (5361),
 (5362),
 (5363);
/*!40000 ALTER TABLE `gen_temp` ENABLE KEYS */;


--
-- Table structure for table `tracuu_mhcu`.`giayto`
--

DROP TABLE IF EXISTS `giayto`;
CREATE TABLE `giayto` (
  `ID_GIAYTO` int(11) NOT NULL auto_increment,
  `TEN` varchar(1024) default NULL,
  `ID_RESOURCE` int(11) default NULL,
  `GHICHU` text,
  PRIMARY KEY  (`ID_GIAYTO`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tracuu_mhcu`.`giayto`
--

/*!40000 ALTER TABLE `giayto` DISABLE KEYS */;
/*!40000 ALTER TABLE `giayto` ENABLE KEYS */;


--
-- Table structure for table `tracuu_mhcu`.`gioithieu`
--

DROP TABLE IF EXISTS `gioithieu`;
CREATE TABLE `gioithieu` (
  `ID_GIOITHIEU` int(11) NOT NULL,
  `GIOITHIEU` text,
  `COCAUTOCHUC` text,
  `IMAGE` varchar(1024) default NULL,
  PRIMARY KEY  (`ID_GIOITHIEU`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tracuu_mhcu`.`gioithieu`
--

/*!40000 ALTER TABLE `gioithieu` DISABLE KEYS */;
INSERT INTO `gioithieu` (`ID_GIOITHIEU`,`GIOITHIEU`,`COCAUTOCHUC`,`IMAGE`) VALUES 
 (1,'PHA+PG1ldGEgY29udGVudD0idGV4dC9odG1sOyBjaGFyc2V0PXV0Zi04IiBodHRwLWVxdWl2PSJDb250ZW50LVR5cGUiPjxtZXRhIGNvbnRlbnQ9IldvcmQuRG9jdW1lbnQiIG5hbWU9IlByb2dJZCI+PG1ldGEgY29udGVudD0iTWljcm9zb2Z0IFdvcmQgMTEiIG5hbWU9IkdlbmVyYXRvciI+PG1ldGEgY29udGVudD0iTWljcm9zb2Z0IFdvcmQgMTEiIG5hbWU9Ik9yaWdpbmF0b3IiPjxsaW5rIGhyZWY9ImZpbGU6Ly8vQzolNUNET0NVTUUlN0UxJTVDdHJ1bmdsdiU1Q0xPQ0FMUyU3RTElNUNUZW1wJTVDbXNvaHRtbDElNUMwMSU1Q2NsaXBfZmlsZWxpc3QueG1sIiByZWw9IkZpbGUtTGlzdCIgLz48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjwhLS1baWYgZ3RlIG1zbyA5XT48eG1sPg0KIDx3OldvcmREb2N1bWVudD4NCiAgPHc6Vmlldz5Ob3JtYWw8L3c6Vmlldz4NCiAgPHc6Wm9vbT4wPC93Olpvb20+DQogIDx3OlB1bmN0dWF0aW9uS2VybmluZyAvPg0KICA8dzpWYWxpZGF0ZUFnYWluc3RTY2hlbWFzIC8+DQogIDx3OlNhdmVJZlhNTEludmFsaWQ+ZmFsc2U8L3c6U2F2ZUlmWE1MSW52YWxpZD4NCiAgPHc6SWdub3JlTWl4ZWRDb250ZW50PmZhbHNlPC93Oklnbm9yZU1peGVkQ29udGVudD4NCiAgPHc6QWx3YXlzU2hvd1BsYWNlaG9sZGVyVGV4dD5mYWxzZTwvdzpBbHdheXNTaG93UGxhY2Vob2xkZXJUZXh0Pg0KICA8dzpDb21wYXRpYmlsaXR5Pg0KICAgPHc6QnJlYWtXcmFwcGVkVGFibGVzIC8+DQogICA8dzpTbmFwVG9HcmlkSW5DZWxsIC8+DQogICA8dzpXcmFwVGV4dFdpdGhQdW5jdCAvPg0KICAgPHc6VXNlQXNpYW5CcmVha1J1bGVzIC8+DQogICA8dzpEb250R3Jvd0F1dG9maXQgLz4NCiAgPC93OkNvbXBhdGliaWxpdHk+DQogIDx3OkJyb3dzZXJMZXZlbD5NaWNyb3NvZnRJbnRlcm5ldEV4cGxvcmVyNDwvdzpCcm93c2VyTGV2ZWw+DQogPC93OldvcmREb2N1bWVudD4NCjwveG1sPjwhW2VuZGlmXS0tPjwhLS1baWYgZ3RlIG1zbyA5XT48eG1sPg0KIDx3OkxhdGVudFN0eWxlcyBEZWZMb2NrZWRTdGF0ZT0iZmFsc2UiIExhdGVudFN0eWxlQ291bnQ9IjE1NiI+DQogPC93OkxhdGVudFN0eWxlcz4NCjwveG1sPjwhW2VuZGlmXS0tPjwvc3Bhbj48c3R5bGUgdHlwZT0idGV4dC9jc3MiPjwvc3R5bGU+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48IS0tW2lmIGd0ZSBtc28gMTBdPg0KPHN0eWxlPg0KIC8qIFN0eWxlIERlZmluaXRpb25zICovDQogdGFibGUuTXNvTm9ybWFsVGFibGUNCgl7bXNvLXN0eWxlLW5hbWU6IlRhYmxlIE5vcm1hbCI7DQoJbXNvLXRzdHlsZS1yb3diYW5kLXNpemU6MDsNCgltc28tdHN0eWxlLWNvbGJhbmQtc2l6ZTowOw0KCW1zby1zdHlsZS1ub3Nob3c6eWVzOw0KCW1zby1zdHlsZS1wYXJlbnQ6IiI7DQoJbXNvLXBhZGRpbmctYWx0OjBpbiA1LjRwdCAwaW4gNS40cHQ7DQoJbXNvLXBhcmEtbWFyZ2luOjBpbjsNCgltc28tcGFyYS1tYXJnaW4tYm90dG9tOi4wMDAxcHQ7DQoJbXNvLXBhZ2luYXRpb246d2lkb3ctb3JwaGFuOw0KCWZvbnQtc2l6ZToxMC4wcHQ7DQoJZm9udC1mYW1pbHk6IlRpbWVzIE5ldyBSb21hbiI7DQoJbXNvLWFuc2ktbGFuZ3VhZ2U6IzA0MDA7DQoJbXNvLWZhcmVhc3QtbGFuZ3VhZ2U6IzA0MDA7DQoJbXNvLWJpZGktbGFuZ3VhZ2U6IzA0MDA7fQ0KPC9zdHlsZT4NCjwhW2VuZGlmXS0tPiAgPC9zcGFuPjwvbWV0YT48L21ldGE+PC9tZXRhPjwvbWV0YT48L3A+PHA+PG1ldGEgY29udGVudD0idGV4dC9odG1sOyBjaGFyc2V0PXV0Zi04IiBodHRwLWVxdWl2PSJDb250ZW50LVR5cGUiPjxtZXRhIGNvbnRlbnQ9IldvcmQuRG9jdW1lbnQiIG5hbWU9IlByb2dJZCI+PG1ldGEgY29udGVudD0iTWljcm9zb2Z0IFdvcmQgMTEiIG5hbWU9IkdlbmVyYXRvciI+PG1ldGEgY29udGVudD0iTWljcm9zb2Z0IFdvcmQgMTEiIG5hbWU9Ik9yaWdpbmF0b3IiPjxsaW5rIGhyZWY9ImZpbGU6Ly8vQzolNUNET0NVTUUlN0UxJTVDdHJ1bmdsdiU1Q0xPQ0FMUyU3RTElNUNUZW1wJTVDbXNvaHRtbDElNUMwMSU1Q2NsaXBfZmlsZWxpc3QueG1sIiByZWw9IkZpbGUtTGlzdCIgLz48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjwhLS17MTI1MTQ0MzE4MjY0MTB9LS0+PCEtLXsxMjUxNDQzMTgyNjQxMX0tLT48L3NwYW4+PHN0eWxlIHR5cGU9InRleHQvY3NzIj48L3N0eWxlPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+PCEtLXsxMjUxNDQzMTgyNjQxM30tLT4gIDwvc3Bhbj48L21ldGE+PC9tZXRhPjwvbWV0YT48L21ldGE+PC9wPjxwPjxtZXRhIGNvbnRlbnQ9InRleHQvaHRtbDsgY2hhcnNldD11dGYtOCIgaHR0cC1lcXVpdj0iQ29udGVudC1UeXBlIj48bWV0YSBjb250ZW50PSJXb3JkLkRvY3VtZW50IiBuYW1lPSJQcm9nSWQiPjxtZXRhIGNvbnRlbnQ9Ik1pY3Jvc29mdCBXb3JkIDExIiBuYW1lPSJHZW5lcmF0b3IiPjxtZXRhIGNvbnRlbnQ9Ik1pY3Jvc29mdCBXb3JkIDExIiBuYW1lPSJPcmlnaW5hdG9yIj48bGluayBocmVmPSJmaWxlOi8vL0M6JTVDVXNlcnMlNUNTb255VmFpbyU1Q0FwcERhdGElNUNMb2NhbCU1Q1RlbXAlNUNtc29odG1sMSU1QzAxJTVDY2xpcF9maWxlbGlzdC54bWwiIHJlbD0iRmlsZS1MaXN0IiAvPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+PCEtLXsxMjUxNDQzMTgyNjQxNH0tLT48IS0tezEyNTE0NDMxODI2NDE1fS0tPjwvc3Bhbj48c3R5bGUgdHlwZT0idGV4dC9jc3MiPjwvc3R5bGU+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48IS0tezEyNTE0NDMxODI2NDE3fS0tPjwvc3Bhbj48L21ldGE+PC9tZXRhPjwvbWV0YT48L21ldGE+PC9wPjxwIHN0eWxlPSJ0ZXh0LWFsaWduOiBqdXN0aWZ5OyIgY2xhc3M9Ik1zb05vcm1hbCI+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij4mIzE2MDsmIzE2MDsmIzE2MDsmIzE2MDsmIzE2MDsmIzE2MDsmIzE2MDsmIzE2MDsmIzE2MDsgQuG7mSBwaOG6rW4gVGnhur9wIG5o4bqtbiB2w6AgdHLhuqMga+G6v3QgcXXhuqMgbMOgIMSRxqFuIHbhu4sgdHLhu7FjIHRodeG7mWMgVsSDbiBwaMOybmcgSMSQTkQtVUJORCB0aMOgbmggcGjhu5EgSOG7mWkgQW4sIMSRxrDhu6NjIHRow6BuaCBs4bqtcCB04burIG7Eg20gMjAwMiB0aGVvIFF1eeG6v3QgxJHhu4tuaCBz4buRIDcyNy8yMDAyL1HEkC1VQk5EIG5nw6B5IDI5LzgvMjAwMiBj4bunYSBVQk5EIHRo4buLIHjDoyBI4buZaSBBbiBuYXkgbMOgIFRow6BuaCBwaOG7kSBI4buZaSBBbi48L3NwYW4+PC9wPjxwIHN0eWxlPSJ0ZXh0LWFsaWduOiBqdXN0aWZ5OyIgY2xhc3M9Ik1zb05vcm1hbCI+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij4mIzE2MDsmIzE2MDsmIzE2MDsmIzE2MDsmIzE2MDsmIzE2MDsmIzE2MDsmIzE2MDsmIzE2MDsgTmdheSB04burIGJhbiDEkeG6p3UgbeG7m2kgaMOsbmggdGjDoG5oLCBC4buZIHBo4bqtbiBUaeG6v3Agbmjhuq1uIHbDoCB0cuG6oyBr4bq/dCBxdeG6oyDEkcOjIHRoYW0gbcawdSBjaG8gVUJORCB0aMOgbmggcGjhu5EgY2jhu41uIG3hu5l0IHPhu5EgbMSpbmggduG7sWMgYuG7qWMgdGhp4bq/dCBj4bunYSBuaMOibiBkw6JuIGfhu5NtOjwvc3Bhbj48L3A+PHAgc3R5bGU9InRleHQtYWxpZ246IGp1c3RpZnk7IiBjbGFzcz0iTXNvTm9ybWFsIj48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPiYjMTYwOyYjMTYwOyYjMTYwOyYjMTYwOyYjMTYwOyYjMTYwOyYjMTYwOyYjMTYwOyYjMTYwOyAtIEzEqW5oIHbhu7FjIEPhuqVwIGdp4bqleSBwaMOpcCBjaOG7qW5nIG5o4bqtbiDEkMSDbmcga8O9IGtpbmggZG9hbmguPC9zcGFuPjwvcD48cCBzdHlsZT0idGV4dC1hbGlnbjoganVzdGlmeTsiIGNsYXNzPSJNc29Ob3JtYWwiPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+JiMxNjA7JiMxNjA7JiMxNjA7JiMxNjA7JiMxNjA7JiMxNjA7JiMxNjA7JiMxNjA7JiMxNjA7IC0gTMSpbmggduG7sWMgQ+G6pXAgZ2nhuqV5IHBow6lwIHjDonkgZOG7sW5nIG5ow6Ag4bufLjwvc3Bhbj48L3A+PHAgc3R5bGU9InRleHQtYWxpZ246IGp1c3RpZnk7IiBjbGFzcz0iTXNvTm9ybWFsIj48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPiYjMTYwOyYjMTYwOyYjMTYwOyYjMTYwOyYjMTYwOyYjMTYwOyYjMTYwOyYjMTYwOyYjMTYwOyAtIEzEqW5oIHbhu7FjIMSR4bqldCDEkWFpLjwvc3Bhbj48L3A+PHAgc3R5bGU9InRleHQtYWxpZ246IGp1c3RpZnk7IiBjbGFzcz0iTXNvTm9ybWFsIj48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPiYjMTYwOyYjMTYwOyYjMTYwOyYjMTYwOyYjMTYwOyYjMTYwOyYjMTYwOyYjMTYwOyYjMTYwOyAtIEzEqW5oIHbhu7FjIGvDqiBraGFpIFRodeG6vy48L3NwYW4+PC9wPjxwIHN0eWxlPSJ0ZXh0LWFsaWduOiBqdXN0aWZ5OyIgY2xhc3M9Ik1zb05vcm1hbCI+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij4mIzE2MDsmIzE2MDsmIzE2MDsmIzE2MDsmIzE2MDsmIzE2MDsmIzE2MDsmIzE2MDsmIzE2MDsgVHJvbmcgcXXDoSB0csOsbmggaG/huqF0IMSR4buZbmcsIELhu5kgcGjhuq1uIFRp4bq/cCBuaOG6rW4gdsOgIHRy4bqjIGvhur90IHF14bqjIGtow7RuZyBuZ+G7q25nIGPhu5EgZ+G6r25nIHBo4bulYyB24bulIG5nw6B5IGPDoG5nIHThu5F0IGjGoW4gxJHDoXAg4bupbmcgZOG6p24gbmjhu69uZyBuaHUgY+G6p3UgdGhp4bq/dCB0aOG7sWMgY+G7p2EgbmjDom4gZMOibiwga2jDtG5nIG5n4burbmcgY+G6o2kgdGnhur9uIGzhu4EgbOG7kWkgbMOgbSB2aeG7h2MsIHTDoWMgcGhvbmcgY8O0bmcgdMOhYywgdGhhbSBtxrB1IGNobyBVQk5EIHRow6BuaCBwaOG7kSB0aOG7kW5nIG5o4bqldCBnaeG6o20gZOG6p24gbeG7mXQgc+G7kSB0aOG7pyB04bulYyBraMO0bmcgY+G6p24gdGhp4bq/dCwgZ2nhuqNtIGLhu5t0IHRo4budaSBnaWFuIGdpYW8gdHLhuqMgaOG7kyBzxqEsIGPhuqNpIHRp4bq/biBiaeG7g3UgbeG6q3UgY8OhYyBsb+G6oWkgxJHGoW4gxJHhu4MgY2hvIG5nxrDhu51pIGTDom4gZOG7hSBoaeG7g3UsIGThu4UgZ2hpLiBDw7RuZyBraGFpIGPDoWMgdGjhu6cgdOG7pWMgaOG7kyBzxqEgY8OhYyBsb+G6oWksIGPDtG5nIGtoYWkgdGjhu51pIGdpYW4gZ2nhuqNpIHF1eeG6v3QsIGPDtG5nIGtoYWkgY8OhYyBraG/huqNuIHRodSBwaMOtIHbDoCBs4buHIHBow60gdOG6oW8gxJHGsOG7o2Mgbmnhu4FtIHRpbiBjaG8gbmjDom4gZMOibiBnw7NwIHBo4bqnbiB2w6BvIHZp4buHYyB0aOG7sWMgaGnhu4duIGPhuqNpIGPDoWNoIGjDoG5oIGNow61uaCB0aGVvIGNo4bunIHRyxrDGoW5nIGPhu6dhIENow61uaCBwaOG7py48L3NwYW4+PC9wPjxwIHN0eWxlPSJ0ZXh0LWFsaWduOiBqdXN0aWZ5OyI+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij4mIzE2MDsmIzE2MDsmIzE2MDsmIzE2MDsmIzE2MDsmIzE2MDsmIzE2MDsmIzE2MDsmIzE2MDsgVGjhu7FjIGhp4buHbiBRdXnhur90IMSR4buLbmggOTMvMjAwNy9RxJAtVFRnIG5nw6B5IDIyLzYvMjAwNyBj4bunYSBUaOG7pyB0xrDhu5tuZyBDaMOtbmggcGjhu6cgduG7gSB2aeG7h2MgQmFuIGjDoG5oIFF1eSBjaOG6vyB0aOG7sWMgaGnhu4duIGPGoSBjaOG6vyBt4buZdCBj4butYSwgbeG7mXQgY+G7rWEgbGnDqm4gdGjDtG5nIHThuqFpIGPGoSBxdWFuIGjDoG5oIGNow61uaCBuaMOgIG7GsOG7m2Mg4bufIMSR4buLYSBwaMawxqFuZy4gQ3Xhu5FpIG7Eg20gMjAwNywgVGjGsOG7nW5nIHRy4buxYyBIxJBORCB0aMOgbmggcGjhu5EgxJHDoyB04buVIGNo4bupYyBo4buNcCB2w6AgxJHDoyByYSBOZ2jhu4sgcXV54bq/dCBjaHV5w6puIMSR4buBIHPhu5EgMTMvMjAwNy9OUS1IxJBORCBuZ8OgeSAxMy8xMi8yMDA3IHbhu4EgxJHhuql5IG3huqFuaCBjaMawxqFuZyB0csOsbmggY+G6o2kgY8OhY2ggaMOgbmggY2jDrW5oIHRyw6puIMSR4buLYSBiw6BuIHRow6BuaCBwaOG7kS4gVOG7qyBOZ2jhu4sgcXV54bq/dCBuw6B5LCBVQk5EIHRow6BuaCBwaOG7kSDEkcOjIGNo4buJIMSR4bqhbyBWxINuIHBow7JuZyBIRE5ELVVCTkQgdHJp4buDbiBraGFpIHRo4buxYyBoaeG7h24gbsOibmcgY+G6pXAgbeG7nyBy4buZbmcgaG/huqF0IMSR4buZbmcgY+G7p2EgQuG7mSBwaOG6rW4gVGnhur9wIG5o4bqtbiB2w6AgdHLhuqMga+G6v3QgcXXhuqMgY+G7p2EgdGjDoG5oIHBo4buRIHRoZW8gaMaw4bubbmcgY8O0bmcgbmdo4buHIHRow7RuZyB0aW4sIGhp4buHbiDEkeG6oWkgaMOzYSB2aeG7h2MgdGnhur9wIG5o4bqtbiwgeOG7rSBsw70sIGdp4bqjaSBxdXnhur90IGPDtG5nIHZp4buHYyBjaG8gdOG7lSBjaOG7qWMgdsOgIGPDoSBuaMOibi4gxJDhur9uIG5heSwgQuG7mSBwaOG6rW4gVGnhur9wIG5o4bqtbiB2w6AgdHLhuqMga+G6v3QgcXXhuqMgY+G7p2EgdGjDoG5oIHBo4buRIMSRw6MgxJHhuqd1IHTGsCBuw6JuZyBj4bqlcCBjxqEgc+G7nyB24bqtdCBjaOG6pXQsIHPhuq9tIG3hu5tpIHRyYW5nIHRoaeG6v3QgYuG7iywgeMOieSBk4buxbmcgcGjhuqduIG3hu4FtIHRp4bq/cCBuaOG6rW4gdsOgIGdp4bqjaSBxdXnhur90IGjhu5Mgc8ahIGNobyB04buVIGNo4bupYyB2w6AgY8OhIG5ow6JuLCDEkeG6v24gbmF5IGPGoSBi4bqjbiDEkcOjIGhvw6BuIHRow6BuaCDEkWkgdsOgbyB24bqtbiBow6BuaCBn4buTbSBjw6FjIGzEqW5oIHbhu7FjOjwvc3Bhbj48L3A+PHAgc3R5bGU9InRleHQtYWxpZ246IGp1c3RpZnk7Ij48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjEuIEzEqW5oIHbhu7FjIGPhuqVwIGdp4bqleSBwaMOpcCB4w6J5IGThu7FuZyBuZ2/DoGkga2h1IHBo4buRIGPhu5UgLSBQaMOybmcgUUzEkFQ8L3NwYW4+PC9wPjxwIHN0eWxlPSJ0ZXh0LWFsaWduOiBqdXN0aWZ5OyI+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij4yLiBMxKluaCB24buxYyBj4bqlcCBnaeG6pXkgcGjDqXAgeMOieSBk4buxbmcgdHJvbmcga2h1IHBo4buRIGPhu5UgLSBUVC5RTEJUIGRpIHTDrWNoPC9zcGFuPjwvcD48cCBzdHlsZT0idGV4dC1hbGlnbjoganVzdGlmeTsiPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+My4gTMSpbmggduG7sWMgY+G6pXAgZ2nhuqV5IGNo4bupbmcgbmjhuq1uIMSQxINuZyBrw70ga2luaCBkb2FuaCDigJMgUGjDsm5nIFTDoGkgY2jDrW5oIC0gPGJyIC8+JiMxNjA7JiMxNjA7JiMxNjA7IEvhur8gaG/huqFjaDwvc3Bhbj48L3A+PHAgc3R5bGU9InRleHQtYWxpZ246IGp1c3RpZnk7Ij48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjQuIEzEqW5oIHbhu7FjIFjDonkgZOG7sW5nIGPGoSBi4bqjbiDigJMgUGjDsm5nIFTDoGkgY2jDrW5oIC0gS+G6vyBob+G6oWNoPC9zcGFuPjwvcD48cCBzdHlsZT0idGV4dC1hbGlnbjoganVzdGlmeTsiPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+NS4gTMSpbmggduG7sWMgVGh14bq/IOKAkyBDaGkgY+G7pWMgdGh14bq/PC9zcGFuPjwvcD48cCBzdHlsZT0idGV4dC1hbGlnbjoganVzdGlmeTsiPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+Ni4gTMSpbmggduG7sWMgVGh1IHRp4buBbiB0aHXhur8gbuG7mXAgdsOgbyBuZ8OibiBzw6FjaCDigJMgS2hvIGLhuqFjIE5ow6Agbsaw4bubYzwvc3Bhbj48L3A+PHAgc3R5bGU9InRleHQtYWxpZ246IGp1c3RpZnk7Ij48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjcuIEzEqW5oIHbhu7FjIMSQ4bqldCDEkWFpIOKAkyBQaMOybmcgVMOgaSBuZ3V5w6puIG3DtGkgdHLGsOG7nW5nPC9zcGFuPjwvcD48cCBzdHlsZT0idGV4dC1hbGlnbjoganVzdGlmeTsiPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+OC4gTMSpbmggduG7sWMgxJDEg25nIGvDvSBo4buZIHThu4tjaCDigJMgUGjDsm5nIFTGsCBwaMOhcC48L3NwYW4+PC9wPjxwIHN0eWxlPSJ0ZXh0LWFsaWduOiBqdXN0aWZ5OyIgY2xhc3M9Ik1zb05vcm1hbCI+PG86cD48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPiYjMTYwOzwvc3Bhbj48L286cD48L3A+','PHA+PG1ldGEgY29udGVudD0idGV4dC9odG1sOyBjaGFyc2V0PXV0Zi04IiBodHRwLWVxdWl2PSJDb250ZW50LVR5cGUiPjxtZXRhIGNvbnRlbnQ9IldvcmQuRG9jdW1lbnQiIG5hbWU9IlByb2dJZCI+PG1ldGEgY29udGVudD0iTWljcm9zb2Z0IFdvcmQgMTEiIG5hbWU9IkdlbmVyYXRvciI+PG1ldGEgY29udGVudD0iTWljcm9zb2Z0IFdvcmQgMTEiIG5hbWU9Ik9yaWdpbmF0b3IiPjxsaW5rIGhyZWY9ImZpbGU6Ly8vQzpcRE9DVU1FfjFcdHJ1bmdsdlxMT0NBTFN+MVxUZW1wXG1zb2h0bWwxXDAxXGNsaXBfZmlsZWxpc3QueG1sIiByZWw9IkZpbGUtTGlzdCIgLz48IS0tW2lmIGd0ZSBtc28gOV0+PHhtbD4NCiA8dzpXb3JkRG9jdW1lbnQ+DQogIDx3OlZpZXc+Tm9ybWFsPC93OlZpZXc+DQogIDx3Olpvb20+MDwvdzpab29tPg0KICA8dzpQdW5jdHVhdGlvbktlcm5pbmcgLz4NCiAgPHc6VmFsaWRhdGVBZ2FpbnN0U2NoZW1hcyAvPg0KICA8dzpTYXZlSWZYTUxJbnZhbGlkPmZhbHNlPC93OlNhdmVJZlhNTEludmFsaWQ+DQogIDx3Oklnbm9yZU1peGVkQ29udGVudD5mYWxzZTwvdzpJZ25vcmVNaXhlZENvbnRlbnQ+DQogIDx3OkFsd2F5c1Nob3dQbGFjZWhvbGRlclRleHQ+ZmFsc2U8L3c6QWx3YXlzU2hvd1BsYWNlaG9sZGVyVGV4dD4NCiAgPHc6Q29tcGF0aWJpbGl0eT4NCiAgIDx3OkJyZWFrV3JhcHBlZFRhYmxlcyAvPg0KICAgPHc6U25hcFRvR3JpZEluQ2VsbCAvPg0KICAgPHc6V3JhcFRleHRXaXRoUHVuY3QgLz4NCiAgIDx3OlVzZUFzaWFuQnJlYWtSdWxlcyAvPg0KICAgPHc6RG9udEdyb3dBdXRvZml0IC8+DQogIDwvdzpDb21wYXRpYmlsaXR5Pg0KICA8dzpCcm93c2VyTGV2ZWw+TWljcm9zb2Z0SW50ZXJuZXRFeHBsb3JlcjQ8L3c6QnJvd3NlckxldmVsPg0KIDwvdzpXb3JkRG9jdW1lbnQ+DQo8L3htbD48IVtlbmRpZl0tLT48IS0tW2lmIGd0ZSBtc28gOV0+PHhtbD4NCiA8dzpMYXRlbnRTdHlsZXMgRGVmTG9ja2VkU3RhdGU9ImZhbHNlIiBMYXRlbnRTdHlsZUNvdW50PSIxNTYiPg0KIDwvdzpMYXRlbnRTdHlsZXM+DQo8L3htbD48IVtlbmRpZl0tLT48c3R5bGUgdHlwZT0idGV4dC9jc3MiPg0KPCEtLQ0KIC8qIFN0eWxlIERlZmluaXRpb25zICovDQogcC5Nc29Ob3JtYWwsIGxpLk1zb05vcm1hbCwgZGl2Lk1zb05vcm1hbA0KCXttc28tc3R5bGUtcGFyZW50OiIiOw0KCW1hcmdpbjowaW47DQoJbWFyZ2luLWJvdHRvbTouMDAwMXB0Ow0KCW1zby1wYWdpbmF0aW9uOndpZG93LW9ycGhhbjsNCglmb250LXNpemU6MTQuMHB0Ow0KCWZvbnQtZmFtaWx5OiJUaW1lcyBOZXcgUm9tYW4iOw0KCW1zby1mYXJlYXN0LWZvbnQtZmFtaWx5OiJUaW1lcyBOZXcgUm9tYW4iOw0KCW1zby1iaWRpLWZvbnQtd2VpZ2h0OmJvbGQ7fQ0KQHBhZ2UgU2VjdGlvbjENCgl7c2l6ZTo4LjVpbiAxMS4waW47DQoJbWFyZ2luOjEuMGluIDEuMjVpbiAxLjBpbiAxLjI1aW47DQoJbXNvLWhlYWRlci1tYXJnaW46LjVpbjsNCgltc28tZm9vdGVyLW1hcmdpbjouNWluOw0KCW1zby1wYXBlci1zb3VyY2U6MDt9DQpkaXYuU2VjdGlvbjENCgl7cGFnZTpTZWN0aW9uMTt9DQotLT4NCjwvc3R5bGU+PCEtLVtpZiBndGUgbXNvIDEwXT4NCjxzdHlsZT4NCiAvKiBTdHlsZSBEZWZpbml0aW9ucyAqLw0KIHRhYmxlLk1zb05vcm1hbFRhYmxlDQoJe21zby1zdHlsZS1uYW1lOiJUYWJsZSBOb3JtYWwiOw0KCW1zby10c3R5bGUtcm93YmFuZC1zaXplOjA7DQoJbXNvLXRzdHlsZS1jb2xiYW5kLXNpemU6MDsNCgltc28tc3R5bGUtbm9zaG93OnllczsNCgltc28tc3R5bGUtcGFyZW50OiIiOw0KCW1zby1wYWRkaW5nLWFsdDowaW4gNS40cHQgMGluIDUuNHB0Ow0KCW1zby1wYXJhLW1hcmdpbjowaW47DQoJbXNvLXBhcmEtbWFyZ2luLWJvdHRvbTouMDAwMXB0Ow0KCW1zby1wYWdpbmF0aW9uOndpZG93LW9ycGhhbjsNCglmb250LXNpemU6MTAuMHB0Ow0KCWZvbnQtZmFtaWx5OiJUaW1lcyBOZXcgUm9tYW4iOw0KCW1zby1hbnNpLWxhbmd1YWdlOiMwNDAwOw0KCW1zby1mYXJlYXN0LWxhbmd1YWdlOiMwNDAwOw0KCW1zby1iaWRpLWxhbmd1YWdlOiMwNDAwO30NCjwvc3R5bGU+DQo8IVtlbmRpZl0tLT4gIDwvbWV0YT48L21ldGE+PC9tZXRhPjwvbWV0YT48L3A+PHA+PG1ldGEgY29udGVudD0idGV4dC9odG1sOyBjaGFyc2V0PXV0Zi04IiBodHRwLWVxdWl2PSJDb250ZW50LVR5cGUiPjxtZXRhIGNvbnRlbnQ9IldvcmQuRG9jdW1lbnQiIG5hbWU9IlByb2dJZCI+PG1ldGEgY29udGVudD0iTWljcm9zb2Z0IFdvcmQgMTEiIG5hbWU9IkdlbmVyYXRvciI+PG1ldGEgY29udGVudD0iTWljcm9zb2Z0IFdvcmQgMTEiIG5hbWU9Ik9yaWdpbmF0b3IiPjxsaW5rIGhyZWY9ImZpbGU6Ly8vQzpcVXNlcnNcU29ueVZhaW9cQXBwRGF0YVxMb2NhbFxUZW1wXG1zb2h0bWwxXDAxXGNsaXBfZmlsZWxpc3QueG1sIiByZWw9IkZpbGUtTGlzdCIgLz48IS0tW2lmIGd0ZSBtc28gOV0+PHhtbD4NCiA8dzpXb3JkRG9jdW1lbnQ+DQogIDx3OlZpZXc+Tm9ybWFsPC93OlZpZXc+DQogIDx3Olpvb20+MDwvdzpab29tPg0KICA8dzpQdW5jdHVhdGlvbktlcm5pbmcgLz4NCiAgPHc6VmFsaWRhdGVBZ2FpbnN0U2NoZW1hcyAvPg0KICA8dzpTYXZlSWZYTUxJbnZhbGlkPmZhbHNlPC93OlNhdmVJZlhNTEludmFsaWQ+DQogIDx3Oklnbm9yZU1peGVkQ29udGVudD5mYWxzZTwvdzpJZ25vcmVNaXhlZENvbnRlbnQ+DQogIDx3OkFsd2F5c1Nob3dQbGFjZWhvbGRlclRleHQ+ZmFsc2U8L3c6QWx3YXlzU2hvd1BsYWNlaG9sZGVyVGV4dD4NCiAgPHc6Q29tcGF0aWJpbGl0eT4NCiAgIDx3OkJyZWFrV3JhcHBlZFRhYmxlcyAvPg0KICAgPHc6U25hcFRvR3JpZEluQ2VsbCAvPg0KICAgPHc6V3JhcFRleHRXaXRoUHVuY3QgLz4NCiAgIDx3OlVzZUFzaWFuQnJlYWtSdWxlcyAvPg0KICAgPHc6RG9udEdyb3dBdXRvZml0IC8+DQogIDwvdzpDb21wYXRpYmlsaXR5Pg0KICA8dzpCcm93c2VyTGV2ZWw+TWljcm9zb2Z0SW50ZXJuZXRFeHBsb3JlcjQ8L3c6QnJvd3NlckxldmVsPg0KIDwvdzpXb3JkRG9jdW1lbnQ+DQo8L3htbD48IVtlbmRpZl0tLT48IS0tW2lmIGd0ZSBtc28gOV0+PHhtbD4NCiA8dzpMYXRlbnRTdHlsZXMgRGVmTG9ja2VkU3RhdGU9ImZhbHNlIiBMYXRlbnRTdHlsZUNvdW50PSIxNTYiPg0KIDwvdzpMYXRlbnRTdHlsZXM+DQo8L3htbD48IVtlbmRpZl0tLT48c3R5bGUgdHlwZT0idGV4dC9jc3MiPg0KPCEtLQ0KIC8qIFN0eWxlIERlZmluaXRpb25zICovDQogcC5Nc29Ob3JtYWwsIGxpLk1zb05vcm1hbCwgZGl2Lk1zb05vcm1hbA0KCXttc28tc3R5bGUtcGFyZW50OiIiOw0KCW1hcmdpbjowaW47DQoJbWFyZ2luLWJvdHRvbTouMDAwMXB0Ow0KCW1zby1wYWdpbmF0aW9uOndpZG93LW9ycGhhbjsNCglmb250LXNpemU6MTQuMHB0Ow0KCWZvbnQtZmFtaWx5OiJUaW1lcyBOZXcgUm9tYW4iOw0KCW1zby1mYXJlYXN0LWZvbnQtZmFtaWx5OiJUaW1lcyBOZXcgUm9tYW4iO30NCkBwYWdlIFNlY3Rpb24xDQoJe3NpemU6NTk1LjQ1cHQgODQxLjdwdDsNCgltYXJnaW46MS4waW4gMS4yNWluIDEuMGluIDEuMjVpbjsNCgltc28taGVhZGVyLW1hcmdpbjouNWluOw0KCW1zby1mb290ZXItbWFyZ2luOi41aW47DQoJbXNvLXBhcGVyLXNvdXJjZTowO30NCmRpdi5TZWN0aW9uMQ0KCXtwYWdlOlNlY3Rpb24xO30NCi0tPg0KPC9zdHlsZT48IS0tW2lmIGd0ZSBtc28gMTBdPg0KPHN0eWxlPg0KIC8qIFN0eWxlIERlZmluaXRpb25zICovDQogdGFibGUuTXNvTm9ybWFsVGFibGUNCgl7bXNvLXN0eWxlLW5hbWU6IlRhYmxlIE5vcm1hbCI7DQoJbXNvLXRzdHlsZS1yb3diYW5kLXNpemU6MDsNCgltc28tdHN0eWxlLWNvbGJhbmQtc2l6ZTowOw0KCW1zby1zdHlsZS1ub3Nob3c6eWVzOw0KCW1zby1zdHlsZS1wYXJlbnQ6IiI7DQoJbXNvLXBhZGRpbmctYWx0OjBpbiA1LjRwdCAwaW4gNS40cHQ7DQoJbXNvLXBhcmEtbWFyZ2luOjBpbjsNCgltc28tcGFyYS1tYXJnaW4tYm90dG9tOi4wMDAxcHQ7DQoJbXNvLXBhZ2luYXRpb246d2lkb3ctb3JwaGFuOw0KCWZvbnQtc2l6ZToxMC4wcHQ7DQoJZm9udC1mYW1pbHk6IlRpbWVzIE5ldyBSb21hbiI7DQoJbXNvLWFuc2ktbGFuZ3VhZ2U6IzA0MDA7DQoJbXNvLWZhcmVhc3QtbGFuZ3VhZ2U6IzA0MDA7DQoJbXNvLWJpZGktbGFuZ3VhZ2U6IzA0MDA7fQ0KPC9zdHlsZT4NCjwhW2VuZGlmXS0tPjwvbWV0YT48L21ldGE+PC9tZXRhPjwvbWV0YT48L3A+PHA+PG86cD48YnIgLz48L286cD48L3A+PHAgYWxpZ249ImNlbnRlciIgc3R5bGU9InRleHQtYWxpZ246IGNlbnRlcjsiIGNsYXNzPSJNc29Ob3JtYWwiPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IHgtbGFyZ2U7Ij48c3Ryb25nPktIw4FJIFFVw4FUIELhu5ggUEjhuqxOIE3hu5hUIEPhu6xBPC9zdHJvbmc+PC9zcGFuPjwvcD4gICAgPHAgY2xhc3M9Ik1zb05vcm1hbCI+PG86cD4mIzE2MDs8L286cD48L3A+ICAgIDxwIHN0eWxlPSJ0ZXh0LWFsaWduOiBqdXN0aWZ5OyIgY2xhc3M9Ik1zb05vcm1hbCI+PHNwYW4gc3R5bGU9IiI+JiMxNjA7JiMxNjA7JiMxNjA7JiMxNjA7JiMxNjA7JiMxNjA7JiMxNjA7JiMxNjA7JiMxNjA7IDwvc3Bhbj5C4buZIHBo4bqtbiBUaeG6v3Agbmjhuq1uIHbDoCB0cuG6oyBr4bq/dCBxdeG6oyBsw6AgxJHGoW4gduG7iyB0cuG7sWMgdGh14buZYyBWxINuIHBow7JuZyBIxJBORC1VQk5EIHRow6BuaCBwaOG7kSBI4buZaSBBbiwgxJHGsOG7o2MgdGjDoG5oIGzhuq1wIHThu6sgbsSDbSAyMDAyIHRoZW8gUXV54bq/dCDEkeG7i25oIHPhu5EgNzI3LzIwMDIvUcSQLVVCTkQgbmfDoHkgMjkvOC8yMDAyIGPhu6dhIFVCTkQgdGjhu4sgeMOjIEjhu5lpIEFuIG5heSBsw6AgVGjDoG5oIHBo4buRIEjhu5lpIEFuLjwvcD4gICAgPHAgc3R5bGU9InRleHQtYWxpZ246IGp1c3RpZnk7IiBjbGFzcz0iTXNvTm9ybWFsIj48c3BhbiBzdHlsZT0iIj4mIzE2MDsmIzE2MDsmIzE2MDsmIzE2MDsmIzE2MDsmIzE2MDsmIzE2MDsmIzE2MDsmIzE2MDsgPC9zcGFuPk5nYXkgdOG7qyBiYW4gxJHhuqd1IG3hu5tpIGjDrG5oIHRow6BuaCwgQuG7mSBwaOG6rW4gVGnhur9wIG5o4bqtbiB2w6AgdHLhuqMga+G6v3QgcXXhuqMgxJHDoyB0aGFtIG3GsHUgY2hvIFVCTkQgdGjDoG5oIHBo4buRIGNo4buNbiBt4buZdCBz4buRIGzEqW5oIHbhu7FjIGLhu6ljIHRoaeG6v3QgY+G7p2EgbmjDom4gZMOibiBn4buTbTo8L3A+ICAgIDxwIHN0eWxlPSJ0ZXh0LWFsaWduOiBqdXN0aWZ5OyIgY2xhc3M9Ik1zb05vcm1hbCI+PHNwYW4gc3R5bGU9IiI+JiMxNjA7JiMxNjA7JiMxNjA7JiMxNjA7JiMxNjA7JiMxNjA7JiMxNjA7JiMxNjA7JiMxNjA7IDwvc3Bhbj4tIEzEqW5oIHbhu7FjIEPhuqVwIGdp4bqleSBwaMOpcCBjaOG7qW5nIG5o4bqtbiDEkMSDbmcga8O9IGtpbmggZG9hbmguPC9wPiAgICA8cCBzdHlsZT0idGV4dC1hbGlnbjoganVzdGlmeTsiIGNsYXNzPSJNc29Ob3JtYWwiPjxzcGFuIHN0eWxlPSIiPiYjMTYwOyYjMTYwOyYjMTYwOyYjMTYwOyYjMTYwOyYjMTYwOyYjMTYwOyYjMTYwOyYjMTYwOyA8L3NwYW4+LSBMxKluaCB24buxYyBD4bqlcCBnaeG6pXkgcGjDqXAgeMOieSBk4buxbmcgbmjDoCDhu58uPC9wPiAgICA8cCBzdHlsZT0idGV4dC1hbGlnbjoganVzdGlmeTsiIGNsYXNzPSJNc29Ob3JtYWwiPjxzcGFuIHN0eWxlPSIiPiYjMTYwOyYjMTYwOyYjMTYwOyYjMTYwOyYjMTYwOyYjMTYwOyYjMTYwOyYjMTYwOyYjMTYwOyA8L3NwYW4+LSBMxKluaCB24buxYyDEkeG6pXQgxJFhaS48L3A+ICAgIDxwIHN0eWxlPSJ0ZXh0LWFsaWduOiBqdXN0aWZ5OyIgY2xhc3M9Ik1zb05vcm1hbCI+PHNwYW4gc3R5bGU9IiI+JiMxNjA7JiMxNjA7JiMxNjA7JiMxNjA7JiMxNjA7JiMxNjA7JiMxNjA7JiMxNjA7JiMxNjA7IDwvc3Bhbj4tIEzEqW5oIHbhu7FjIGvDqiBraGFpIFRodeG6vy48L3A+ICAgIDxwIHN0eWxlPSJ0ZXh0LWFsaWduOiBqdXN0aWZ5OyIgY2xhc3M9Ik1zb05vcm1hbCI+PHNwYW4gc3R5bGU9IiI+JiMxNjA7JiMxNjA7JiMxNjA7JiMxNjA7JiMxNjA7JiMxNjA7JiMxNjA7JiMxNjA7JiMxNjA7IDwvc3Bhbj5Ucm9uZyBxdcOhIHRyw6xuaCBob+G6oXQgxJHhu5luZywgQuG7mSBwaOG6rW4gVGnhur9wIG5o4bqtbiB2w6AgdHLhuqMga+G6v3QgcXXhuqMga2jDtG5nIG5n4burbmcgY+G7kSBn4bqvbmcgcGjhu6VjIHbhu6UgbmfDoHkgY8OgbmcgdOG7kXQgaMahbiDEkcOhcCDhu6luZyBk4bqnbiBuaOG7r25nIG5odSBj4bqndSB0aGnhur90IHRo4buxYyBj4bunYSBuaMOibiBkw6JuLCBraMO0bmcgbmfhu6tuZyBj4bqjaSB0aeG6v24gbOG7gSBs4buRaSBsw6BtIHZp4buHYywgdMOhYyBwaG9uZyBjw7RuZyB0w6FjLCB0aGFtIG3GsHUgY2hvIFVCTkQgdGjDoG5oIHBo4buRIHRo4buRbmcgbmjhuqV0IGdp4bqjbSBk4bqnbiBt4buZdCBz4buRIHRo4bunIHThu6VjIGtow7RuZyBj4bqnbiB0aGnhur90LCBnaeG6o20gYuG7m3QgdGjhu51pIGdpYW4gZ2lhbyB0cuG6oyBo4buTIHPGoSwgY+G6o2kgdGnhur9uIGJp4buDdSBt4bqrdSBjw6FjIGxv4bqhaSDEkcahbiDEkeG7gyBjaG8gbmfGsOG7nWkgZMOibiBk4buFIGhp4buDdSwgZOG7hSBnaGkuIEPDtG5nIGtoYWkgY8OhYyB0aOG7pyB04bulYyBo4buTIHPGoSBjw6FjIGxv4bqhaSwgY8O0bmcga2hhaSB0aOG7nWkgZ2lhbiBnaeG6o2kgcXV54bq/dCwgY8O0bmcga2hhaSBjw6FjIGtob+G6o24gdGh1IHBow60gdsOgIGzhu4cgcGjDrSB04bqhbyDEkcaw4bujYyBuaeG7gW0gdGluIGNobyBuaMOibiBkw6JuIGfDs3AgcGjhuqduIHbDoG8gdmnhu4djIHRo4buxYyBoaeG7h24gY+G6o2kgY8OhY2ggaMOgbmggY2jDrW5oIHRoZW8gY2jhu6cgdHLGsMahbmcgY+G7p2EgQ2jDrW5oIHBo4bunLjwvcD4gICAgPHAgc3R5bGU9InRleHQtYWxpZ246IGp1c3RpZnk7IiBjbGFzcz0iTXNvTm9ybWFsIj48c3BhbiBzdHlsZT0iIj4mIzE2MDsmIzE2MDsmIzE2MDsmIzE2MDsmIzE2MDsmIzE2MDsmIzE2MDsmIzE2MDsmIzE2MDsgPC9zcGFuPlRo4buxYyBoaeG7h24gUXV54bq/dCDEkeG7i25oIDkzLzIwMDcvUcSQLVRUZyBuZ8OgeSAyMi82LzIwMDcgY+G7p2EgVGjhu6cgdMaw4bubbmcgQ2jDrW5oIHBo4bunIHbhu4Egdmnhu4djIEJhbiBow6BuaCBRdXkgY2jhur8gdGjhu7FjIGhp4buHbiBjxqEgY2jhur8gbeG7mXQgY+G7rWEsIG3hu5l0IGPhu61hIGxpw6puIHRow7RuZyB04bqhaSBjxqEgcXVhbiBow6BuaCBjaMOtbmggbmjDoCBuxrDhu5tjIOG7nyDEkeG7i2EgcGjGsMahbmcuIEN14buRaSBuxINtIDIwMDcsIFRoxrDhu51uZyB0cuG7sWMgSMSQTkQgdGjDoG5oIHBo4buRIMSRw6MgdOG7lSBjaOG7qWMgaOG7jXAgdsOgIMSRw6MgcmEgTmdo4buLIHF1eeG6v3QgY2h1ecOqbiDEkeG7gSBz4buRIDEzLzIwMDcvTlEtSMSQTkQgbmfDoHkgMTMvMTIvMjAwNyB24buBIMSR4bqpeSBt4bqhbmggY2jGsMahbmcgdHLDrG5oIGPhuqNpIGPDoWNoIGjDoG5oIGNow61uaCB0csOqbiDEkeG7i2EgYsOgbiB0aMOgbmggcGjhu5EuIFThu6sgTmdo4buLIHF1eeG6v3QgbsOgeSwgVUJORCB0aMOgbmggcGjhu5EgxJHDoyBjaOG7iSDEkeG6oW8gVsSDbiBwaMOybmcgSERORC1VQk5EIHRyaeG7g24ga2hhaSB0aOG7sWMgaGnhu4duIG7Dom5nIGPhuqVwIG3hu58gcuG7mW5nIGhv4bqhdCDEkeG7mW5nIGPhu6dhIELhu5kgcGjhuq1uIFRp4bq/cCBuaOG6rW4gdsOgIHRy4bqjIGvhur90IHF14bqjIGPhu6dhIHRow6BuaCBwaOG7kSB0aGVvIGjGsOG7m25nIGPDtG5nIG5naOG7hyB0aMO0bmcgdGluLCBoaeG7h24gxJHhuqFpIGjDs2Egdmnhu4djIHRp4bq/cCBuaOG6rW4sIHjhu60gbMO9LCBnaeG6o2kgcXV54bq/dCBjw7RuZyB2aeG7h2MgY2hvIHThu5UgY2jhu6ljIHbDoCBjw6EgbmjDom4uIMSQ4bq/biBuYXksIELhu5kgcGjhuq1uIFRp4bq/cCBuaOG6rW4gdsOgIHRy4bqjIGvhur90IHF14bqjIGPhu6dhIHRow6BuaCBwaOG7kSDEkcOjIMSR4bqndSB0xrAgbsOibmcgY+G6pXAgY8ahIHPhu58gduG6rXQgY2jhuqV0LCBz4bqvbSBt4bubaSB0cmFuZyB0aGnhur90IGLhu4ssIHjDonkgZOG7sW5nIHBo4bqnbiBt4buBbSB0aeG6v3Agbmjhuq1uIHbDoCBnaeG6o2kgcXV54bq/dCBo4buTIHPGoSBjaG8gdOG7lSBjaOG7qWMgdsOgIGPDoSBuaMOibiwgxJHhur9uIG5heSBjxqEgYuG6o24gxJHDoyBob8OgbiB0aMOgbmggxJFpIHbDoG8gduG6rW4gaMOgbmggZ+G7k20gY8OhYyBsxKluaCB24buxYzo8L3A+ICAgIDxwIHN0eWxlPSJ0ZXh0LWFsaWduOiBqdXN0aWZ5OyB0ZXh0LWluZGVudDogMC41aW47IiBjbGFzcz0iTXNvTm9ybWFsIj4xLiBMxKluaCB24buxYyBj4bqlcCBnaeG6pXkgcGjDqXAgeMOieSBk4buxbmcgbmdvw6BpIGtodSBwaOG7kSBj4buVIC0gUGjDsm5nIFFMxJBUPC9wPiAgICA8cCBzdHlsZT0idGV4dC1hbGlnbjoganVzdGlmeTsiIGNsYXNzPSJNc29Ob3JtYWwiPjxzcGFuIHN0eWxlPSIiPiYjMTYwOyYjMTYwOyYjMTYwOyYjMTYwOyYjMTYwOyYjMTYwOyYjMTYwOyYjMTYwOyYjMTYwOyA8L3NwYW4+Mi4gTMSpbmggduG7sWMgY+G6pXAgZ2nhuqV5IHBow6lwIHjDonkgZOG7sW5nIHRyb25nIGtodSBwaOG7kSBj4buVIC0gVFQuUUxCVCBkaSB0w61jaDwvcD4gICAgPHAgc3R5bGU9InRleHQtYWxpZ246IGp1c3RpZnk7IiBjbGFzcz0iTXNvTm9ybWFsIj48c3BhbiBzdHlsZT0iIj4mIzE2MDsmIzE2MDsmIzE2MDsmIzE2MDsmIzE2MDsmIzE2MDsmIzE2MDsmIzE2MDsmIzE2MDsgPC9zcGFuPjMuIEzEqW5oIHbhu7FjIGPhuqVwIGdp4bqleSBjaOG7qW5nIG5o4bqtbiDEkMSDbmcga8O9IGtpbmggZG9hbmgg4oCTIFBow7JuZyBUw6BpIGNow61uaCAtIEvhur8gaG/huqFjaDwvcD4gICAgPHAgc3R5bGU9InRleHQtYWxpZ246IGp1c3RpZnk7IHRleHQtaW5kZW50OiAwLjVpbjsiIGNsYXNzPSJNc29Ob3JtYWwiPjQuIEzEqW5oIHbhu7FjIFjDonkgZOG7sW5nIGPGoSBi4bqjbiDigJMgUGjDsm5nIFTDoGkgY2jDrW5oIC0gS+G6vyBob+G6oWNoPC9wPiAgICA8cCBzdHlsZT0idGV4dC1hbGlnbjoganVzdGlmeTsiIGNsYXNzPSJNc29Ob3JtYWwiPjxzcGFuIHN0eWxlPSIiPiYjMTYwOyYjMTYwOyYjMTYwOyYjMTYwOyYjMTYwOyYjMTYwOyYjMTYwOyYjMTYwOyYjMTYwOyA8L3NwYW4+NS4gTMSpbmggduG7sWMgVGh14bq/IOKAkyBDaGkgY+G7pWMgdGh14bq/PC9wPiAgICA8cCBzdHlsZT0idGV4dC1hbGlnbjoganVzdGlmeTsiIGNsYXNzPSJNc29Ob3JtYWwiPjxzcGFuIHN0eWxlPSIiPiYjMTYwOyYjMTYwOyYjMTYwOyYjMTYwOyYjMTYwOyYjMTYwOyYjMTYwOyYjMTYwOyYjMTYwOyA8L3NwYW4+Ni4gTMSpbmggduG7sWMgVGh1IHRp4buBbiB0aHXhur8gbuG7mXAgdsOgbyBuZ8OibiBzw6FjaCDigJMgS2hvIGLhuqFjIE5ow6Agbsaw4bubYzwvcD4gICAgPHAgc3R5bGU9InRleHQtYWxpZ246IGp1c3RpZnk7IiBjbGFzcz0iTXNvTm9ybWFsIj48c3BhbiBzdHlsZT0iIj4mIzE2MDsmIzE2MDsmIzE2MDsmIzE2MDsmIzE2MDsmIzE2MDsmIzE2MDsmIzE2MDsmIzE2MDsgPC9zcGFuPjcuIEzEqW5oIHbhu7FjIMSQ4bqldCDEkWFpIOKAkyBQaMOybmcgVMOgaSBuZ3V5w6puIG3DtGkgdHLGsOG7nW5nPC9wPiAgICA8cCBzdHlsZT0idGV4dC1hbGlnbjoganVzdGlmeTsiIGNsYXNzPSJNc29Ob3JtYWwiPjxzcGFuIHN0eWxlPSIiPiYjMTYwOyYjMTYwOyYjMTYwOyYjMTYwOyYjMTYwOyYjMTYwOyYjMTYwOyYjMTYwOyYjMTYwOyA8L3NwYW4+OC4gTMSpbmggduG7sWMgxJDEg25nIGvDvSBo4buZIHThu4tjaCDigJMgUGjDsm5nIFTGsCBwaMOhcC48L3A+ICAgIDxwIGNsYXNzPSJNc29Ob3JtYWwiPjxvOnA+JiMxNjA7PC9vOnA+PC9wPg==','533f8485754de893dae4db5c67b831c6.png');
/*!40000 ALTER TABLE `gioithieu` ENABLE KEYS */;


--
-- Table structure for table `tracuu_mhcu`.`linhvuc`
--

DROP TABLE IF EXISTS `linhvuc`;
CREATE TABLE `linhvuc` (
  `ID_LINHVUC` int(11) NOT NULL auto_increment,
  `TEN` varchar(1024) default NULL,
  `ACTIVE` tinyint(1) default NULL,
  PRIMARY KEY  (`ID_LINHVUC`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tracuu_mhcu`.`linhvuc`
--

/*!40000 ALTER TABLE `linhvuc` DISABLE KEYS */;
INSERT INTO `linhvuc` (`ID_LINHVUC`,`TEN`,`ACTIVE`) VALUES 
 (5,'Äáº¥t Ä‘ai',1),
 (8,'Äáº§u tÆ° xÃ¢y dá»±ng cÆ¡ báº£n',1),
 (11,'ÄÄƒng kÃ½ vÃ  cáº¥p giáº¥y chá»©ng nháº­n Ä‘Äƒng kÃ½ kinh doanh',1),
 (12,'Cáº¥p giáº¥y phÃ©p xÃ¢y dá»±ng nhÃ  á»Ÿ',1),
 (13,'MÃ´i trÆ°á»ng',1),
 (14,'Vá»‡ sinh an toÃ n thá»±c pháº©m',1);
/*!40000 ALTER TABLE `linhvuc` ENABLE KEYS */;


--
-- Table structure for table `tracuu_mhcu`.`loaihoso`
--

DROP TABLE IF EXISTS `loaihoso`;
CREATE TABLE `loaihoso` (
  `ID_LOAIHOSO` int(20) NOT NULL auto_increment,
  `ID_LINHVUC` int(11) default NULL,
  `TEN` varchar(1024) character set latin1 default NULL,
  `LEPHI` varchar(1024) default NULL,
  `SONGAYXULY` int(4) default NULL,
  `GHICHU` mediumtext character set latin1,
  `ACTIVE` tinyint(1) default NULL,
  `CHITIET_HOSO` longtext,
  `IMAGE_QUITRINH` varchar(1024) default NULL,
  PRIMARY KEY  (`ID_LOAIHOSO`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='InnoDB free: 10240 kB';

--
-- Dumping data for table `tracuu_mhcu`.`loaihoso`
--

/*!40000 ALTER TABLE `loaihoso` DISABLE KEYS */;
INSERT INTO `loaihoso` (`ID_LOAIHOSO`,`ID_LINHVUC`,`TEN`,`LEPHI`,`SONGAYXULY`,`GHICHU`,`ACTIVE`,`CHITIET_HOSO`,`IMAGE_QUITRINH`) VALUES 
 (1,5,'Cáº¥p giáº¥y chá»©ng nháº­n quyá»n sá»­ dá»¥ng Ä‘áº¥t, quyá»n sá»Ÿ há»¯u nhÃ  á»Ÿ vÃ  tÃ i sáº£n khÃ¡c gáº¯n liá»n vá»›i Ä‘áº¥t','0',48,'',1,'PGRpdiBzdHlsZT0idGV4dC1pbmRlbnQ6IDAuNWluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyI+PGRpdiBzdHlsZT0idGV4dC1hbGlnbjoganVzdGlmeTsgdGV4dC1pbmRlbnQ6IDAuNWluOyI+PHN0cm9uZz48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogJ1RpbWVzIE5ldyBSb21hbic7Ij4mIzE2MDtJLiBUaMOgbmggcGjhuqduIGjhu5Mgc8ahOjwvc3Bhbj48L3NwYW4+PC9zdHJvbmc+PC9kaXY+PGRpdiBzdHlsZT0ibWFyZ2luOiA0cHQgMGluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyI+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPiYjMTYwOy0gIDwvc3Bhbj48L3NwYW4+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPsSQxqFuIHhpbiBj4bqlcCBnaeG6pXkgY2jhu6luZyBuaOG6rW4uPC9zcGFuPjwvc3Bhbj48L2Rpdj48ZGl2PjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiBUaW1lcyBOZXcgUm9tYW47Ij4mIzE2MDstIEPDoWMgZ2nhuqV5IHThu50gduG7gSBxdXnhu4FuIHPhu58gaOG7r3UgcuG7q25nIGPDonk8L3NwYW4+PC9zcGFuPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiBUaW1lcyBOZXcgUm9tYW47Ij4uPC9zcGFuPjwvc3Bhbj48L2Rpdj48ZGl2PjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij4mIzE2MDstIDAyIFThu50ga2hhaSBs4buHIHBow60gdHLGsOG7m2MgYuG6oS48L3NwYW4+PC9zcGFuPjwvZGl2PjxkaXY+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiBUaW1lcyBOZXcgUm9tYW47Ij48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPiYjMTYwOy0gMDIgVOG7nSBraGFpIG7hu5lwIHRp4buBbiBz4butIGThu6VuZyDEkeG6pXQuPC9zcGFuPjwvc3Bhbj48L2Rpdj48ZGl2PjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij4mIzE2MDstIELhuqNuIHNhbyBjw6FjIGdp4bqleSB04budIGxpw6puIHF1YW4gxJHhur9uIHZp4buHYyB0aOG7sWMgaGnhu4duIG5naMSpYSB24bulIHTDoGkgY2jDrW5oLjwvc3Bhbj48L3NwYW4+PC9kaXY+PGRpdj48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+JiMxNjA7LSBHaeG6pXkgdOG7nSB24buBIHF1eeG7gW4gc+G7rSBk4bulbmcgxJHhuqV0Ljwvc3Bhbj48L3NwYW4+PC9kaXY+PGRpdiBzdHlsZT0ibWFyZ2luOiA0cHQgMGluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyB0ZXh0LWluZGVudDogMC41aW47Ij48c3Ryb25nPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiAnVGltZXMgTmV3IFJvbWFuJzsiPklJLiBT4buRIGzGsOG7o25nIGjhu5Mgc8ahOiAwMSBi4buZPC9zcGFuPjwvc3Bhbj48L3N0cm9uZz48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogJ1RpbWVzIE5ldyBSb21hbic7Ij4uPC9zcGFuPjwvc3Bhbj48L2Rpdj48ZGl2IHN0eWxlPSJtYXJnaW46IDRwdCAwaW47IHRleHQtYWxpZ246IGp1c3RpZnk7IHRleHQtaW5kZW50OiAwLjVpbjsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3Ryb25nPklJSS4gU+G7kSBuZ8OgeSB44butIGzDvSA6IDQ4IDwvc3Ryb25nPm5nw6B5PC9zcGFuPjwvc3Bhbj48L2Rpdj48ZGl2IHN0eWxlPSJtYXJnaW46IDRwdCAwaW47IHRleHQtYWxpZ246IGp1c3RpZnk7IHRleHQtaW5kZW50OiAwLjVpbjsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3Ryb25nPklWLiBM4buHIHBow60gOiA8L3N0cm9uZz48c3Ryb25nPjEwMC4wMDA8L3N0cm9uZz4gxJHhu5NuZzxiciAvPjwvc3Bhbj48L3NwYW4+PC9kaXY+PGRpdiBzdHlsZT0ibWFyZ2luOiA0cHQgMGluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyB0ZXh0LWluZGVudDogMC41aW47Ij48c3Ryb25nPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiAnVGltZXMgTmV3IFJvbWFuJzsiPlYuIFF1aSB0csOsbmggeOG7rSBsw70gOjxiciAvPjwvc3Bhbj48L3NwYW4+PC9zdHJvbmc+PC9kaXY+PGRpdiBzdHlsZT0ibWFyZ2luOiA0cHQgMGluOyB0ZXh0LWFsaWduOiBjZW50ZXI7IHRleHQtaW5kZW50OiAwLjVpbjsiPjxpbWcgd2lkdGg9IjgzMyIgaGVpZ2h0PSIzMzkiIHNyYz0iL3B1YmxpYy91c2VyZmlsZXMvaW1hZ2UvR1RWVC9RTEdURFQvMi5wbmciIGFsdD0iUXV5IHRyw6xuaCBj4bqlcCBnaeG6pXkgcGjDqXAgbMawdSBow6BuaCB4ZSBxdcOhIHThuqNpLCBxdcOhIGto4buVIHRyw6puIMSRxrDhu51uZyBnaWFvIHRow7RuZyBjw7RuZyBj4buZbmcgKDAyIG5nw6B5KSIgLz48L2Rpdj48L2Rpdj48cD4mIzE2MDs8L3A+',''),
 (2,5,'Chuyá»ƒn nhÆ°á»£ng quyá»n sá»­ dá»¥ng Ä‘áº¥t, quyá»n sá»Ÿ há»¯u tÃ i sáº£n gáº¯n liá»n vá»›i Ä‘áº¥t','0',40,'',1,'PGRpdiBzdHlsZT0idGV4dC1pbmRlbnQ6IDAuNWluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyI+PGRpdiBzdHlsZT0idGV4dC1hbGlnbjoganVzdGlmeTsgdGV4dC1pbmRlbnQ6IDAuNWluOyI+PHN0cm9uZz48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogJ1RpbWVzIE5ldyBSb21hbic7Ij4mIzE2MDtJLiBUaMOgbmggcGjhuqduIGjhu5Mgc8ahOjwvc3Bhbj48L3NwYW4+PC9zdHJvbmc+PC9kaXY+PGRpdiBzdHlsZT0ibWFyZ2luOiA0cHQgMGluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyI+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPiYjMTYwOy0gPC9zcGFuPjwvc3Bhbj48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+SOG7o3AgxJHhu5NuZyBjaHV54buDbiBuaMaw4bujbmcuPC9zcGFuPjwvc3Bhbj48L2Rpdj48ZGl2PjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiBUaW1lcyBOZXcgUm9tYW47Ij4mIzE2MDstIEdp4bqleSBjaOG7qW5nIG5o4bqtbiBxdXnhu4FuIHPhu60gZOG7pW5nIMSR4bqldCwgcXV54buBbiBz4bufIGjhu691IG5ow6Ag4bufIHbDoCB0w6BpIHPhuqNuIGfhuq9uIGxp4buBbiB24bubaSDEkeG6pXQ8L3NwYW4+PC9zcGFuPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiBUaW1lcyBOZXcgUm9tYW47Ij4uPC9zcGFuPjwvc3Bhbj48L2Rpdj48ZGl2PjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij4mIzE2MDstIDAyIFThu50ga2hhaSBs4buHIHBow60gdHLGsOG7m2MgYuG6oS48L3NwYW4+PC9zcGFuPjwvZGl2PjxkaXY+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiBUaW1lcyBOZXcgUm9tYW47Ij48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPiYjMTYwOy0gMDIgVOG7nSBraGFpIHRodeG6vyB0aHUgbmjhuq1wIGPDoSBuaMOibi48L3NwYW4+PC9zcGFuPjwvZGl2PjxkaXY+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiBUaW1lcyBOZXcgUm9tYW47Ij48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPiYjMTYwOy0gQuG6o24gc2FvIGPDoWMgZ2nhuqV5IHThu50gbGnDqm4gcXVhbiDEkeG6v24gdmnhu4djIHRo4buxYyBoaeG7h24gbmdoxKlhIHbhu6UgdMOgaSBjaMOtbmguPC9zcGFuPjwvc3Bhbj48L2Rpdj48ZGl2IHN0eWxlPSJtYXJnaW46IDRwdCAwaW47IHRleHQtYWxpZ246IGp1c3RpZnk7IHRleHQtaW5kZW50OiAwLjVpbjsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij4mIzE2MDs8L3NwYW4+PC9zcGFuPjxzdHJvbmc+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6ICdUaW1lcyBOZXcgUm9tYW4nOyI+SUkuIFPhu5EgbMaw4bujbmcgaOG7kyBzxqE6IDAxIGLhu5k8L3NwYW4+PC9zcGFuPjwvc3Ryb25nPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiAnVGltZXMgTmV3IFJvbWFuJzsiPi48L3NwYW4+PC9zcGFuPjwvZGl2PjxkaXYgc3R5bGU9Im1hcmdpbjogNHB0IDBpbjsgdGV4dC1hbGlnbjoganVzdGlmeTsgdGV4dC1pbmRlbnQ6IDAuNWluOyI+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiBUaW1lcyBOZXcgUm9tYW47Ij48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzdHJvbmc+SUlJLiBT4buRIG5nw6B5IHjhu60gbMO9IDogNDAgPC9zdHJvbmc+bmfDoHk8L3NwYW4+PC9zcGFuPjwvZGl2PjxkaXYgc3R5bGU9Im1hcmdpbjogNHB0IDBpbjsgdGV4dC1hbGlnbjoganVzdGlmeTsgdGV4dC1pbmRlbnQ6IDAuNWluOyI+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiBUaW1lcyBOZXcgUm9tYW47Ij48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzdHJvbmc+SVYuIEzhu4cgcGjDrSA6IDwvc3Ryb25nPjxzdHJvbmc+MTAwLjAwMDwvc3Ryb25nPiDEkeG7k25nPGJyIC8+PC9zcGFuPjwvc3Bhbj48L2Rpdj48ZGl2IHN0eWxlPSJtYXJnaW46IDRwdCAwaW47IHRleHQtYWxpZ246IGp1c3RpZnk7IHRleHQtaW5kZW50OiAwLjVpbjsiPjxzdHJvbmc+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6ICdUaW1lcyBOZXcgUm9tYW4nOyI+Vi4gUXVpIHRyw6xuaCB44butIGzDvSA6PGJyIC8+PC9zcGFuPjwvc3Bhbj48L3N0cm9uZz48L2Rpdj48ZGl2IHN0eWxlPSJtYXJnaW46IDRwdCAwaW47IHRleHQtYWxpZ246IGNlbnRlcjsgdGV4dC1pbmRlbnQ6IDAuNWluOyI+JiMxNjA7PC9kaXY+PC9kaXY+PHA+JiMxNjA7PC9wPjxkaXYgc3R5bGU9Im1hcmdpbjogNHB0IDBpbjsgdGV4dC1hbGlnbjogY2VudGVyOyB0ZXh0LWluZGVudDogMC41aW47Ij4mIzE2MDs8L2Rpdj4=','');
INSERT INTO `loaihoso` (`ID_LOAIHOSO`,`ID_LINHVUC`,`TEN`,`LEPHI`,`SONGAYXULY`,`GHICHU`,`ACTIVE`,`CHITIET_HOSO`,`IMAGE_QUITRINH`) VALUES 
 (3,5,'Thá»«a káº¿ quyá»n sá»­ dá»¥ng Ä‘áº¥t, quyá»n sá»Ÿ há»¯u tÃ i sáº£n gáº¯n liá»n vá»›i Ä‘áº¥t','0',40,'',1,'PGRpdiBzdHlsZT0idGV4dC1pbmRlbnQ6IDAuNWluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyI+PGRpdiBzdHlsZT0idGV4dC1hbGlnbjoganVzdGlmeTsgdGV4dC1pbmRlbnQ6IDAuNWluOyI+PHN0cm9uZz48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogJ1RpbWVzIE5ldyBSb21hbic7Ij4mIzE2MDtJLiBUaMOgbmggcGjhuqduIGjhu5Mgc8ahOjwvc3Bhbj48L3NwYW4+PC9zdHJvbmc+PC9kaXY+PGRpdiBzdHlsZT0ibWFyZ2luOiA0cHQgMGluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyI+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPiYjMTYwOy0gPC9zcGFuPjwvc3Bhbj48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+RGkgY2jDumMgaG/hurdjIGJpw6puIGLhuqNuIHBow6JuIGNoaWEgdGjhu6thIGvhur8uPC9zcGFuPjwvc3Bhbj48L2Rpdj48ZGl2PjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiBUaW1lcyBOZXcgUm9tYW47Ij4mIzE2MDstIEdp4bqleSBjaOG7qW5nIG5o4bqtbiBxdXnhu4FuIHPhu60gZOG7pW5nIMSR4bqldCwgcXV54buBbiBz4bufIGjhu691IG5ow6Ag4bufIHbDoCB0w6BpIHPhuqNuIGfhuq9uIGxp4buBbiB24bubaSDEkeG6pXQ8L3NwYW4+PC9zcGFuPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiBUaW1lcyBOZXcgUm9tYW47Ij4uPC9zcGFuPjwvc3Bhbj48L2Rpdj48ZGl2PjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij4mIzE2MDstIDAyIFThu50ga2hhaSBs4buHIHBow60gdHLGsOG7m2MgYuG6oS48L3NwYW4+PC9zcGFuPjwvZGl2PjxkaXY+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiBUaW1lcyBOZXcgUm9tYW47Ij48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPiYjMTYwOy0gMDIgVOG7nSBraGFpIHRodeG6vyB0aHUgbmjhuq1wIGPDoSBuaMOibi48L3NwYW4+PC9zcGFuPjwvZGl2PjxkaXY+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiBUaW1lcyBOZXcgUm9tYW47Ij48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPiYjMTYwOy0gQuG6o24gc2FvIGPDoWMgZ2nhuqV5IHThu50gbGnDqm4gcXVhbiDEkeG6v24gdmnhu4djIHRo4buxYyBoaeG7h24gbmdoxKlhIHbhu6UgdMOgaSBjaMOtbmguPC9zcGFuPjwvc3Bhbj48L2Rpdj48ZGl2IHN0eWxlPSJtYXJnaW46IDRwdCAwaW47IHRleHQtYWxpZ246IGp1c3RpZnk7IHRleHQtaW5kZW50OiAwLjVpbjsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij4mIzE2MDs8L3NwYW4+PC9zcGFuPjxzdHJvbmc+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6ICdUaW1lcyBOZXcgUm9tYW4nOyI+SUkuIFPhu5EgbMaw4bujbmcgaOG7kyBzxqE6IDAxIGLhu5k8L3NwYW4+PC9zcGFuPjwvc3Ryb25nPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiAnVGltZXMgTmV3IFJvbWFuJzsiPi48L3NwYW4+PC9zcGFuPjwvZGl2PjxkaXYgc3R5bGU9Im1hcmdpbjogNHB0IDBpbjsgdGV4dC1hbGlnbjoganVzdGlmeTsgdGV4dC1pbmRlbnQ6IDAuNWluOyI+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiBUaW1lcyBOZXcgUm9tYW47Ij48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzdHJvbmc+SUlJLiBT4buRIG5nw6B5IHjhu60gbMO9IDogNDAgPC9zdHJvbmc+bmfDoHk8L3NwYW4+PC9zcGFuPjwvZGl2PjxkaXYgc3R5bGU9Im1hcmdpbjogNHB0IDBpbjsgdGV4dC1hbGlnbjoganVzdGlmeTsgdGV4dC1pbmRlbnQ6IDAuNWluOyI+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiBUaW1lcyBOZXcgUm9tYW47Ij48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzdHJvbmc+SVYuIEzhu4cgcGjDrSA6IDwvc3Ryb25nPjxzdHJvbmc+MTAwLjAwMDwvc3Ryb25nPiDEkeG7k25nPGJyIC8+PC9zcGFuPjwvc3Bhbj48L2Rpdj48ZGl2IHN0eWxlPSJtYXJnaW46IDRwdCAwaW47IHRleHQtYWxpZ246IGp1c3RpZnk7IHRleHQtaW5kZW50OiAwLjVpbjsiPjxzdHJvbmc+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6ICdUaW1lcyBOZXcgUm9tYW4nOyI+Vi4gUXVpIHRyw6xuaCB44butIGzDvSA6PGJyIC8+PC9zcGFuPjwvc3Bhbj48L3N0cm9uZz48L2Rpdj48ZGl2IHN0eWxlPSJtYXJnaW46IDRwdCAwaW47IHRleHQtYWxpZ246IGNlbnRlcjsgdGV4dC1pbmRlbnQ6IDAuNWluOyI+JiMxNjA7PC9kaXY+PC9kaXY+IDxwPiYjMTYwOzwvcD4gPGRpdiBzdHlsZT0ibWFyZ2luOiA0cHQgMGluOyB0ZXh0LWFsaWduOiBjZW50ZXI7IHRleHQtaW5kZW50OiAwLjVpbjsiPiYjMTYwOzwvZGl2PiA8cD4mIzE2MDs8L3A+',''),
 (4,5,'Táº·ng cho quyá»n sá»­ dá»¥ng Ä‘áº¥t, quyá»n sá»Ÿ há»¯u tÃ i sáº£n gáº¯n liá»n vá»›i Ä‘áº¥t','0',40,'',1,'PGRpdiBzdHlsZT0idGV4dC1pbmRlbnQ6IDAuNWluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyI+PGRpdiBzdHlsZT0idGV4dC1hbGlnbjoganVzdGlmeTsgdGV4dC1pbmRlbnQ6IDAuNWluOyI+PHN0cm9uZz48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogJ1RpbWVzIE5ldyBSb21hbic7Ij4mIzE2MDtJLiBUaMOgbmggcGjhuqduIGjhu5Mgc8ahOjwvc3Bhbj48L3NwYW4+PC9zdHJvbmc+PC9kaXY+PGRpdiBzdHlsZT0ibWFyZ2luOiA0cHQgMGluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyI+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPiYjMTYwOy0gPC9zcGFuPjwvc3Bhbj48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+SOG7o3AgxJHhu5NuZyB04bq3bmcgY2hvLjwvc3Bhbj48L3NwYW4+PC9kaXY+PGRpdj48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+JiMxNjA7LSBHaeG6pXkgY2jhu6luZyBuaOG6rW4gcXV54buBbiBz4butIGThu6VuZyDEkeG6pXQsIHF1eeG7gW4gc+G7nyBo4buvdSBuaMOgIOG7nyB2w6AgdMOgaSBz4bqjbiBn4bqvbiBsaeG7gW4gduG7m2kgxJHhuqV0PC9zcGFuPjwvc3Bhbj48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+Ljwvc3Bhbj48L3NwYW4+PC9kaXY+PGRpdj48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+JiMxNjA7LSAwMiBC4bqjbiBzYW8gY8O0bmcgY2jhu6luZywgY2jhu6luZyB0aOG7sWMgZ2nhuqV5IHThu50gY2jhu6luZyBtaW5oIG3hu5FpIHF1YW4gaOG7hy48L3NwYW4+PC9zcGFuPjwvZGl2PjxkaXY+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiBUaW1lcyBOZXcgUm9tYW47Ij48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPiYjMTYwOy0gMDIgVOG7nSBraGFpIGzhu4cgcGjDrSB0csaw4bubYyBi4bqhLjwvc3Bhbj48L3NwYW4+PC9kaXY+PGRpdj48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+JiMxNjA7LSAwMiBU4budIGtoYWkgdGh14bq/IHRodSBuaOG6rXAgY8OhIG5ow6JuLjwvc3Bhbj48L3NwYW4+PC9kaXY+PGRpdj48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+JiMxNjA7LSBC4bqjbiBzYW8gY8OhYyBnaeG6pXkgdOG7nSBsacOqbiBxdWFuIMSR4bq/biB2aeG7h2MgdGjhu7FjIGhp4buHbiBuZ2jEqWEgduG7pSB0w6BpIGNow61uaC48L3NwYW4+PC9zcGFuPjwvZGl2PjxkaXYgc3R5bGU9Im1hcmdpbjogNHB0IDBpbjsgdGV4dC1hbGlnbjoganVzdGlmeTsgdGV4dC1pbmRlbnQ6IDAuNWluOyI+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiBUaW1lcyBOZXcgUm9tYW47Ij48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPiYjMTYwOzwvc3Bhbj48L3NwYW4+PHN0cm9uZz48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogJ1RpbWVzIE5ldyBSb21hbic7Ij5JSS4gU+G7kSBsxrDhu6NuZyBo4buTIHPGoTogMDEgYuG7mTwvc3Bhbj48L3NwYW4+PC9zdHJvbmc+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6ICdUaW1lcyBOZXcgUm9tYW4nOyI+Ljwvc3Bhbj48L3NwYW4+PC9kaXY+PGRpdiBzdHlsZT0ibWFyZ2luOiA0cHQgMGluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyB0ZXh0LWluZGVudDogMC41aW47Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+PHN0cm9uZz5JSUkuIFPhu5EgbmfDoHkgeOG7rSBsw70gOiA0MCA8L3N0cm9uZz5uZ8OgeTwvc3Bhbj48L3NwYW4+PC9kaXY+PGRpdiBzdHlsZT0ibWFyZ2luOiA0cHQgMGluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyB0ZXh0LWluZGVudDogMC41aW47Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+PHN0cm9uZz5JVi4gTOG7hyBwaMOtIDogPC9zdHJvbmc+PHN0cm9uZz4xMDAuMDAwPC9zdHJvbmc+IMSR4buTbmc8YnIgLz48L3NwYW4+PC9zcGFuPjwvZGl2PjxkaXYgc3R5bGU9Im1hcmdpbjogNHB0IDBpbjsgdGV4dC1hbGlnbjoganVzdGlmeTsgdGV4dC1pbmRlbnQ6IDAuNWluOyI+PHN0cm9uZz48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogJ1RpbWVzIE5ldyBSb21hbic7Ij5WLiBRdWkgdHLDrG5oIHjhu60gbMO9IDo8YnIgLz48L3NwYW4+PC9zcGFuPjwvc3Ryb25nPjwvZGl2PjxkaXYgc3R5bGU9Im1hcmdpbjogNHB0IDBpbjsgdGV4dC1hbGlnbjogY2VudGVyOyB0ZXh0LWluZGVudDogMC41aW47Ij4mIzE2MDs8L2Rpdj48L2Rpdj4gPHA+JiMxNjA7PC9wPiA8ZGl2IHN0eWxlPSJtYXJnaW46IDRwdCAwaW47IHRleHQtYWxpZ246IGNlbnRlcjsgdGV4dC1pbmRlbnQ6IDAuNWluOyI+JiMxNjA7PC9kaXY+IDxwPiYjMTYwOzwvcD4=','');
INSERT INTO `loaihoso` (`ID_LOAIHOSO`,`ID_LINHVUC`,`TEN`,`LEPHI`,`SONGAYXULY`,`GHICHU`,`ACTIVE`,`CHITIET_HOSO`,`IMAGE_QUITRINH`) VALUES 
 (5,5,'Chuyá»ƒn nhÆ°á»£ng quyá»n sá»­ dá»¥ng Ä‘áº¥t, quyá»n sá»Ÿ há»¯u tÃ i sáº£n gáº¯n liá»n vá»›i Ä‘áº¥t Ä‘á»‘i vá»›i toÃ n bá»™ thá»­a Ä‘áº¥t','0',15,'',1,'PGRpdiBzdHlsZT0idGV4dC1pbmRlbnQ6IDAuNWluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyI+PGRpdiBzdHlsZT0idGV4dC1hbGlnbjoganVzdGlmeTsgdGV4dC1pbmRlbnQ6IDAuNWluOyI+PHN0cm9uZz48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogJ1RpbWVzIE5ldyBSb21hbic7Ij4mIzE2MDtJLiBUaMOgbmggcGjhuqduIGjhu5Mgc8ahOjwvc3Bhbj48L3NwYW4+PC9zdHJvbmc+PC9kaXY+PGRpdiBzdHlsZT0ibWFyZ2luOiA0cHQgMGluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyI+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPiYjMTYwOy0gPC9zcGFuPjwvc3Bhbj48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+SOG7o3AgxJHhu5NuZyBjaHV54buDbiBuaMaw4bujbmcuPC9zcGFuPjwvc3Bhbj48L2Rpdj48ZGl2PjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiBUaW1lcyBOZXcgUm9tYW47Ij4mIzE2MDstIEdp4bqleSBjaOG7qW5nIG5o4bqtbiBxdXnhu4FuIHPhu60gZOG7pW5nIMSR4bqldCwgcXV54buBbiBz4bufIGjhu691IG5ow6Ag4bufIHbDoCB0w6BpIHPhuqNuIGfhuq9uIGxp4buBbiB24bubaSDEkeG6pXQ8L3NwYW4+PC9zcGFuPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiBUaW1lcyBOZXcgUm9tYW47Ij4uPC9zcGFuPjwvc3Bhbj48L2Rpdj48ZGl2PjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij4mIzE2MDstIDAyIFThu50ga2hhaSBs4buHIHBow60gdHLGsOG7m2MgYuG6oS48L3NwYW4+PC9zcGFuPjwvZGl2PjxkaXY+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiBUaW1lcyBOZXcgUm9tYW47Ij48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPiYjMTYwOy0gMDIgVOG7nSBraGFpIHRodeG6vyB0aHUgbmjhuq1wIGPDoSBuaMOibi48L3NwYW4+PC9zcGFuPjwvZGl2PjxkaXY+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiBUaW1lcyBOZXcgUm9tYW47Ij48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPiYjMTYwOy0gQuG6o24gc2FvIGPDoWMgZ2nhuqV5IHThu50gbGnDqm4gcXVhbiDEkeG6v24gdmnhu4djIHRo4buxYyBoaeG7h24gbmdoxKlhIHbhu6UgdMOgaSBjaMOtbmguPC9zcGFuPjwvc3Bhbj48L2Rpdj48ZGl2IHN0eWxlPSJtYXJnaW46IDRwdCAwaW47IHRleHQtYWxpZ246IGp1c3RpZnk7IHRleHQtaW5kZW50OiAwLjVpbjsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij4mIzE2MDs8L3NwYW4+PC9zcGFuPjxzdHJvbmc+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6ICdUaW1lcyBOZXcgUm9tYW4nOyI+SUkuIFPhu5EgbMaw4bujbmcgaOG7kyBzxqE6IDAxIGLhu5k8L3NwYW4+PC9zcGFuPjwvc3Ryb25nPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiAnVGltZXMgTmV3IFJvbWFuJzsiPi48L3NwYW4+PC9zcGFuPjwvZGl2PjxkaXYgc3R5bGU9Im1hcmdpbjogNHB0IDBpbjsgdGV4dC1hbGlnbjoganVzdGlmeTsgdGV4dC1pbmRlbnQ6IDAuNWluOyI+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiBUaW1lcyBOZXcgUm9tYW47Ij48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzdHJvbmc+SUlJLiBT4buRIG5nw6B5IHjhu60gbMO9IDogMTU8L3N0cm9uZz4gbmfDoHk8L3NwYW4+PC9zcGFuPjwvZGl2PjxkaXYgc3R5bGU9Im1hcmdpbjogNHB0IDBpbjsgdGV4dC1hbGlnbjoganVzdGlmeTsgdGV4dC1pbmRlbnQ6IDAuNWluOyI+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiBUaW1lcyBOZXcgUm9tYW47Ij48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzdHJvbmc+SVYuIEzhu4cgcGjDrSA6IDwvc3Ryb25nPjxzdHJvbmc+MTAwLjAwMDwvc3Ryb25nPiDEkeG7k25nPGJyIC8+PC9zcGFuPjwvc3Bhbj48L2Rpdj48ZGl2IHN0eWxlPSJtYXJnaW46IDRwdCAwaW47IHRleHQtYWxpZ246IGp1c3RpZnk7IHRleHQtaW5kZW50OiAwLjVpbjsiPjxzdHJvbmc+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6ICdUaW1lcyBOZXcgUm9tYW4nOyI+Vi4gUXVpIHRyw6xuaCB44butIGzDvSA6PGJyIC8+PC9zcGFuPjwvc3Bhbj48L3N0cm9uZz48L2Rpdj48ZGl2IHN0eWxlPSJtYXJnaW46IDRwdCAwaW47IHRleHQtYWxpZ246IGNlbnRlcjsgdGV4dC1pbmRlbnQ6IDAuNWluOyI+JiMxNjA7PC9kaXY+PC9kaXY+ICA8cD4mIzE2MDs8L3A+ICA8ZGl2IHN0eWxlPSJtYXJnaW46IDRwdCAwaW47IHRleHQtYWxpZ246IGNlbnRlcjsgdGV4dC1pbmRlbnQ6IDAuNWluOyI+JiMxNjA7PC9kaXY+ICA8cD4mIzE2MDs8L3A+',''),
 (6,5,'Táº·ng cho quyá»n sá»­ dá»¥ng Ä‘áº¥t, quyá»n sá»Ÿ há»¯u tÃ i sáº£n gáº¯n liá»n vá»›i Ä‘áº¥t Ä‘á»‘i vá»›i toÃ n bá»™ thá»­a Ä‘áº¥t','0',15,'',1,'PGRpdiBzdHlsZT0idGV4dC1pbmRlbnQ6IDAuNWluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyI+PGRpdiBzdHlsZT0idGV4dC1hbGlnbjoganVzdGlmeTsgdGV4dC1pbmRlbnQ6IDAuNWluOyI+PHN0cm9uZz48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogJ1RpbWVzIE5ldyBSb21hbic7Ij4mIzE2MDtJLiBUaMOgbmggcGjhuqduIGjhu5Mgc8ahOjwvc3Bhbj48L3NwYW4+PC9zdHJvbmc+PC9kaXY+PGRpdiBzdHlsZT0ibWFyZ2luOiA0cHQgMGluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyI+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPiYjMTYwOy0gPC9zcGFuPjwvc3Bhbj48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+SOG7o3AgxJHhu5NuZyB04bq3bmcgY2hvLjwvc3Bhbj48L3NwYW4+PC9kaXY+PGRpdj48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+JiMxNjA7LSBHaeG6pXkgY2jhu6luZyBuaOG6rW4gcXV54buBbiBz4butIGThu6VuZyDEkeG6pXQsIHF1eeG7gW4gc+G7nyBo4buvdSBuaMOgIOG7nyB2w6AgdMOgaSBz4bqjbiBn4bqvbiBsaeG7gW4gduG7m2kgxJHhuqV0PC9zcGFuPjwvc3Bhbj48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+Ljwvc3Bhbj48L3NwYW4+PC9kaXY+PGRpdj48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+JiMxNjA7LSAwMiBC4bqjbiBzYW8gY8O0bmcgY2jhu6luZywgY2jhu6luZyB0aOG7sWMgZ2nhuqV5IHThu50gY2jhu6luZyBtaW5oIG3hu5FpIHF1YW4gaOG7hy48L3NwYW4+PC9zcGFuPjwvZGl2PjxkaXY+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiBUaW1lcyBOZXcgUm9tYW47Ij48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPiYjMTYwOy0gMDIgVOG7nSBraGFpIGzhu4cgcGjDrSB0csaw4bubYyBi4bqhLjwvc3Bhbj48L3NwYW4+PC9kaXY+PGRpdj48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+JiMxNjA7LSAwMiBU4budIGtoYWkgdGh14bq/IHRodSBuaOG6rXAgY8OhIG5ow6JuLjwvc3Bhbj48L3NwYW4+PC9kaXY+PGRpdj48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+JiMxNjA7LSBC4bqjbiBzYW8gY8OhYyBnaeG6pXkgdOG7nSBsacOqbiBxdWFuIMSR4bq/biB2aeG7h2MgdGjhu7FjIGhp4buHbiBuZ2jEqWEgduG7pSB0w6BpIGNow61uaC48L3NwYW4+PC9zcGFuPjwvZGl2PjxkaXYgc3R5bGU9Im1hcmdpbjogNHB0IDBpbjsgdGV4dC1hbGlnbjoganVzdGlmeTsgdGV4dC1pbmRlbnQ6IDAuNWluOyI+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiBUaW1lcyBOZXcgUm9tYW47Ij48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPiYjMTYwOzwvc3Bhbj48L3NwYW4+PHN0cm9uZz48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogJ1RpbWVzIE5ldyBSb21hbic7Ij5JSS4gU+G7kSBsxrDhu6NuZyBo4buTIHPGoTogMDEgYuG7mTwvc3Bhbj48L3NwYW4+PC9zdHJvbmc+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6ICdUaW1lcyBOZXcgUm9tYW4nOyI+Ljwvc3Bhbj48L3NwYW4+PC9kaXY+PGRpdiBzdHlsZT0ibWFyZ2luOiA0cHQgMGluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyB0ZXh0LWluZGVudDogMC41aW47Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+PHN0cm9uZz5JSUkuIFPhu5EgbmfDoHkgeOG7rSBsw70gOiAxNSA8L3N0cm9uZz5uZ8OgeTwvc3Bhbj48L3NwYW4+PC9kaXY+PGRpdiBzdHlsZT0ibWFyZ2luOiA0cHQgMGluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyB0ZXh0LWluZGVudDogMC41aW47Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+PHN0cm9uZz5JVi4gTOG7hyBwaMOtIDogPC9zdHJvbmc+PHN0cm9uZz4xMDAuMDAwPC9zdHJvbmc+IMSR4buTbmc8YnIgLz48L3NwYW4+PC9zcGFuPjwvZGl2PjxkaXYgc3R5bGU9Im1hcmdpbjogNHB0IDBpbjsgdGV4dC1hbGlnbjoganVzdGlmeTsgdGV4dC1pbmRlbnQ6IDAuNWluOyI+PHN0cm9uZz48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogJ1RpbWVzIE5ldyBSb21hbic7Ij5WLiBRdWkgdHLDrG5oIHjhu60gbMO9IDo8YnIgLz48L3NwYW4+PC9zcGFuPjwvc3Ryb25nPjwvZGl2PjxkaXYgc3R5bGU9Im1hcmdpbjogNHB0IDBpbjsgdGV4dC1hbGlnbjogY2VudGVyOyB0ZXh0LWluZGVudDogMC41aW47Ij4mIzE2MDs8L2Rpdj48L2Rpdj4gICA8cD4mIzE2MDs8L3A+ICAgPGRpdiBzdHlsZT0ibWFyZ2luOiA0cHQgMGluOyB0ZXh0LWFsaWduOiBjZW50ZXI7IHRleHQtaW5kZW50OiAwLjVpbjsiPiYjMTYwOzwvZGl2PiAgIDxwPiYjMTYwOzwvcD4=','');
INSERT INTO `loaihoso` (`ID_LOAIHOSO`,`ID_LINHVUC`,`TEN`,`LEPHI`,`SONGAYXULY`,`GHICHU`,`ACTIVE`,`CHITIET_HOSO`,`IMAGE_QUITRINH`) VALUES 
 (7,5,'Thá»«a káº¿ quyá»n sá»­ dá»¥ng Ä‘áº¥t, quyá»n sá»Ÿ há»¯u tÃ i sáº£n gáº¯n liá»n vá»›i Ä‘áº¥t Ä‘á»‘i vá»›i toÃ n bá»™ thá»­a Ä‘áº¥t','0',15,'',1,'PGRpdiBzdHlsZT0idGV4dC1pbmRlbnQ6IDAuNWluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyI+PGRpdiBzdHlsZT0idGV4dC1hbGlnbjoganVzdGlmeTsgdGV4dC1pbmRlbnQ6IDAuNWluOyI+PHN0cm9uZz48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogJ1RpbWVzIE5ldyBSb21hbic7Ij4mIzE2MDtJLiBUaMOgbmggcGjhuqduIGjhu5Mgc8ahOjwvc3Bhbj48L3NwYW4+PC9zdHJvbmc+PC9kaXY+PGRpdiBzdHlsZT0ibWFyZ2luOiA0cHQgMGluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyI+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPiYjMTYwOy0gPC9zcGFuPjwvc3Bhbj48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+SOG7o3AgxJHhu5NuZyBjaHV54buDbiBuaMaw4bujbmcuPC9zcGFuPjwvc3Bhbj48L2Rpdj48ZGl2PjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiBUaW1lcyBOZXcgUm9tYW47Ij4mIzE2MDstIEdp4bqleSBjaOG7qW5nIG5o4bqtbiBxdXnhu4FuIHPhu60gZOG7pW5nIMSR4bqldCwgcXV54buBbiBz4bufIGjhu691IG5ow6Ag4bufIHbDoCB0w6BpIHPhuqNuIGfhuq9uIGxp4buBbiB24bubaSDEkeG6pXQ8L3NwYW4+PC9zcGFuPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiBUaW1lcyBOZXcgUm9tYW47Ij4uPC9zcGFuPjwvc3Bhbj48L2Rpdj48ZGl2PjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij4mIzE2MDstIDAyIFThu50ga2hhaSBs4buHIHBow60gdHLGsOG7m2MgYuG6oS48L3NwYW4+PC9zcGFuPjwvZGl2PjxkaXY+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiBUaW1lcyBOZXcgUm9tYW47Ij48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPiYjMTYwOy0gMDIgVOG7nSBraGFpIHRodeG6vyB0aHUgbmjhuq1wIGPDoSBuaMOibi48L3NwYW4+PC9zcGFuPjwvZGl2PjxkaXY+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiBUaW1lcyBOZXcgUm9tYW47Ij48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPiYjMTYwOy0gQuG6o24gc2FvIGPDoWMgZ2nhuqV5IHThu50gbGnDqm4gcXVhbiDEkeG6v24gdmnhu4djIHRo4buxYyBoaeG7h24gbmdoxKlhIHbhu6UgdMOgaSBjaMOtbmguPC9zcGFuPjwvc3Bhbj48L2Rpdj48ZGl2IHN0eWxlPSJtYXJnaW46IDRwdCAwaW47IHRleHQtYWxpZ246IGp1c3RpZnk7IHRleHQtaW5kZW50OiAwLjVpbjsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij4mIzE2MDs8L3NwYW4+PC9zcGFuPjxzdHJvbmc+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6ICdUaW1lcyBOZXcgUm9tYW4nOyI+SUkuIFPhu5EgbMaw4bujbmcgaOG7kyBzxqE6IDAxIGLhu5k8L3NwYW4+PC9zcGFuPjwvc3Ryb25nPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiAnVGltZXMgTmV3IFJvbWFuJzsiPi48L3NwYW4+PC9zcGFuPjwvZGl2PjxkaXYgc3R5bGU9Im1hcmdpbjogNHB0IDBpbjsgdGV4dC1hbGlnbjoganVzdGlmeTsgdGV4dC1pbmRlbnQ6IDAuNWluOyI+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiBUaW1lcyBOZXcgUm9tYW47Ij48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzdHJvbmc+SUlJLiBT4buRIG5nw6B5IHjhu60gbMO9IDogMTU8L3N0cm9uZz4gbmfDoHk8L3NwYW4+PC9zcGFuPjwvZGl2PjxkaXYgc3R5bGU9Im1hcmdpbjogNHB0IDBpbjsgdGV4dC1hbGlnbjoganVzdGlmeTsgdGV4dC1pbmRlbnQ6IDAuNWluOyI+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiBUaW1lcyBOZXcgUm9tYW47Ij48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzdHJvbmc+SVYuIEzhu4cgcGjDrSA6IDwvc3Ryb25nPjxzdHJvbmc+MTAwLjAwMDwvc3Ryb25nPiDEkeG7k25nPGJyIC8+PC9zcGFuPjwvc3Bhbj48L2Rpdj48ZGl2IHN0eWxlPSJtYXJnaW46IDRwdCAwaW47IHRleHQtYWxpZ246IGp1c3RpZnk7IHRleHQtaW5kZW50OiAwLjVpbjsiPjxzdHJvbmc+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6ICdUaW1lcyBOZXcgUm9tYW4nOyI+Vi4gUXVpIHRyw6xuaCB44butIGzDvSA6PGJyIC8+PC9zcGFuPjwvc3Bhbj48L3N0cm9uZz48L2Rpdj48ZGl2IHN0eWxlPSJtYXJnaW46IDRwdCAwaW47IHRleHQtYWxpZ246IGNlbnRlcjsgdGV4dC1pbmRlbnQ6IDAuNWluOyI+JiMxNjA7PC9kaXY+PC9kaXY+ICAgIDxwPiYjMTYwOzwvcD4gICAgPGRpdiBzdHlsZT0ibWFyZ2luOiA0cHQgMGluOyB0ZXh0LWFsaWduOiBjZW50ZXI7IHRleHQtaW5kZW50OiAwLjVpbjsiPiYjMTYwOzwvZGl2PiAgICA8cD4mIzE2MDs8L3A+',''),
 (8,5,'Chuyá»ƒn má»¥c Ä‘Ã­ch sá»­ dá»¥ng Ä‘áº¥t','0',30,'',1,'PGRpdiBzdHlsZT0idGV4dC1pbmRlbnQ6IDAuNWluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyI+PGRpdiBzdHlsZT0idGV4dC1hbGlnbjoganVzdGlmeTsgdGV4dC1pbmRlbnQ6IDAuNWluOyI+PHN0cm9uZz48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogJ1RpbWVzIE5ldyBSb21hbic7Ij4mIzE2MDtJLiBUaMOgbmggcGjhuqduIGjhu5Mgc8ahOjwvc3Bhbj48L3NwYW4+PC9zdHJvbmc+PC9kaXY+PGRpdiBzdHlsZT0ibWFyZ2luOiA0cHQgMGluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyI+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPiYjMTYwOy0gPC9zcGFuPjwvc3Bhbj48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+xJDGoW4geGluIGNodXnhu4NuIG3hu6VjIMSRw61jaCBz4butIGThu6VuZyDEkeG6pXQuPC9zcGFuPjwvc3Bhbj48L2Rpdj48ZGl2PjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiBUaW1lcyBOZXcgUm9tYW47Ij4mIzE2MDstIEdp4bqleSBjaOG7qW5nIG5o4bqtbiBxdXnhu4FuIHPhu60gZOG7pW5nIMSR4bqldCwgcXV54buBbiBz4bufIGjhu691IG5ow6Ag4bufIHbDoCB0w6BpIHPhuqNuIGfhuq9uIGxp4buBbiB24bubaSDEkeG6pXQ8L3NwYW4+PC9zcGFuPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiBUaW1lcyBOZXcgUm9tYW47Ij4uPC9zcGFuPjwvc3Bhbj48L2Rpdj48ZGl2PjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij4mIzE2MDstIDAyIFThu50ga2hhaSBs4buHIHBow60gdHLGsOG7m2MgYuG6oS48L3NwYW4+PC9zcGFuPjwvZGl2PjxkaXY+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiBUaW1lcyBOZXcgUm9tYW47Ij48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPiYjMTYwOy0gMDIgVOG7nSBraGFpIG7hu5lwIHRp4buBbiBz4butIGThu6VuZyDEkeG6pXQuPC9zcGFuPjwvc3Bhbj48L2Rpdj48ZGl2PjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij4mIzE2MDstIELhuqNuIHNhbyBjw6FjIGdp4bqleSB04budIGxpw6puIHF1YW4gxJHhur9uIHZp4buHYyB0aOG7sWMgaGnhu4duIG5naMSpYSB24bulIHTDoGkgY2jDrW5oLjwvc3Bhbj48L3NwYW4+PC9kaXY+PGRpdiBzdHlsZT0ibWFyZ2luOiA0cHQgMGluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyB0ZXh0LWluZGVudDogMC41aW47Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+JiMxNjA7PC9zcGFuPjwvc3Bhbj48c3Ryb25nPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiAnVGltZXMgTmV3IFJvbWFuJzsiPklJLiBT4buRIGzGsOG7o25nIGjhu5Mgc8ahOiAwMSBi4buZPC9zcGFuPjwvc3Bhbj48L3N0cm9uZz48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogJ1RpbWVzIE5ldyBSb21hbic7Ij4uPC9zcGFuPjwvc3Bhbj48L2Rpdj48ZGl2IHN0eWxlPSJtYXJnaW46IDRwdCAwaW47IHRleHQtYWxpZ246IGp1c3RpZnk7IHRleHQtaW5kZW50OiAwLjVpbjsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3Ryb25nPklJSS4gU+G7kSBuZ8OgeSB44butIGzDvSA6IDMwIDwvc3Ryb25nPm5nw6B5PC9zcGFuPjwvc3Bhbj48L2Rpdj48ZGl2IHN0eWxlPSJtYXJnaW46IDRwdCAwaW47IHRleHQtYWxpZ246IGp1c3RpZnk7IHRleHQtaW5kZW50OiAwLjVpbjsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3Ryb25nPklWLiBM4buHIHBow60gOiA8L3N0cm9uZz48c3Ryb25nPjEwMC4wMDA8L3N0cm9uZz4gxJHhu5NuZzxiciAvPjwvc3Bhbj48L3NwYW4+PC9kaXY+PGRpdiBzdHlsZT0ibWFyZ2luOiA0cHQgMGluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyB0ZXh0LWluZGVudDogMC41aW47Ij48c3Ryb25nPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiAnVGltZXMgTmV3IFJvbWFuJzsiPlYuIFF1aSB0csOsbmggeOG7rSBsw70gOjxiciAvPjwvc3Bhbj48L3NwYW4+PC9zdHJvbmc+PC9kaXY+PGRpdiBzdHlsZT0ibWFyZ2luOiA0cHQgMGluOyB0ZXh0LWFsaWduOiBjZW50ZXI7IHRleHQtaW5kZW50OiAwLjVpbjsiPiYjMTYwOzwvZGl2PjwvZGl2PiAgICAgPHA+JiMxNjA7PC9wPiAgICAgPGRpdiBzdHlsZT0ibWFyZ2luOiA0cHQgMGluOyB0ZXh0LWFsaWduOiBjZW50ZXI7IHRleHQtaW5kZW50OiAwLjVpbjsiPiYjMTYwOzwvZGl2PiAgICAgPHA+JiMxNjA7PC9wPg==','');
INSERT INTO `loaihoso` (`ID_LOAIHOSO`,`ID_LINHVUC`,`TEN`,`LEPHI`,`SONGAYXULY`,`GHICHU`,`ACTIVE`,`CHITIET_HOSO`,`IMAGE_QUITRINH`) VALUES 
 (9,5,'Cáº¥p Ä‘á»•i giáº¥y chá»©ng nháº­n quyá»n sá»­ dá»¥ng Ä‘áº¥t, quyá»n sá»Ÿ há»¯u nhÃ  á»Ÿ vÃ  tÃ i sáº£n khÃ¡c gáº¯n liá»n vá»›i Ä‘áº¥t','0',18,'',1,'PGRpdiBzdHlsZT0idGV4dC1pbmRlbnQ6IDAuNWluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyI+PGRpdiBzdHlsZT0idGV4dC1hbGlnbjoganVzdGlmeTsgdGV4dC1pbmRlbnQ6IDAuNWluOyI+PHN0cm9uZz48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogJ1RpbWVzIE5ldyBSb21hbic7Ij4mIzE2MDtJLiBUaMOgbmggcGjhuqduIGjhu5Mgc8ahOjwvc3Bhbj48L3NwYW4+PC9zdHJvbmc+PC9kaXY+PGRpdiBzdHlsZT0ibWFyZ2luOiA0cHQgMGluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyI+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPiYjMTYwOy0gPC9zcGFuPjwvc3Bhbj48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+xJDGoW4gxJHhu4Egbmdo4buLIGPhuqVwIMSR4buVaSBnaeG6pXkgY2jhu6luZyBuaOG6rW4gcXV54buBbiBz4butIGThu6VuZyDEkeG6pXQsIHPhu58gaOG7r3UgbmjDoCDhu58gdsOgIHTDoGkgc+G6o24ga2jDoWMgZ+G6r24gbGnhu4FuIHbhu5tpIMSR4bqldC48L3NwYW4+PC9zcGFuPjwvZGl2PjxkaXY+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPiYjMTYwOy0gR2nhuqV5IGNo4bupbmcgbmjhuq1uIHF1eeG7gW4gc+G7rSBk4bulbmcgxJHhuqV0LCBxdXnhu4FuIHPhu58gaOG7r3UgbmjDoCDhu58gdsOgIHTDoGkgc+G6o24gZ+G6r24gbGnhu4FuIHbhu5tpIMSR4bqldDwvc3Bhbj48L3NwYW4+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPi48L3NwYW4+PC9zcGFuPjwvZGl2PjxkaXYgc3R5bGU9Im1hcmdpbjogNHB0IDBpbjsgdGV4dC1hbGlnbjoganVzdGlmeTsgdGV4dC1pbmRlbnQ6IDAuNWluOyI+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiBUaW1lcyBOZXcgUm9tYW47Ij48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPiYjMTYwOzwvc3Bhbj48L3NwYW4+PHN0cm9uZz48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogJ1RpbWVzIE5ldyBSb21hbic7Ij5JSS4gU+G7kSBsxrDhu6NuZyBo4buTIHPGoTogMDEgYuG7mTwvc3Bhbj48L3NwYW4+PC9zdHJvbmc+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6ICdUaW1lcyBOZXcgUm9tYW4nOyI+Ljwvc3Bhbj48L3NwYW4+PC9kaXY+PGRpdiBzdHlsZT0ibWFyZ2luOiA0cHQgMGluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyB0ZXh0LWluZGVudDogMC41aW47Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+PHN0cm9uZz5JSUkuIFPhu5EgbmfDoHkgeOG7rSBsw70gOiAxOCA8L3N0cm9uZz5uZ8OgeTwvc3Bhbj48L3NwYW4+PC9kaXY+PGRpdiBzdHlsZT0ibWFyZ2luOiA0cHQgMGluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyB0ZXh0LWluZGVudDogMC41aW47Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+PHN0cm9uZz5JVi4gTOG7hyBwaMOtIDogPC9zdHJvbmc+PHN0cm9uZz4xMDAuMDAwPC9zdHJvbmc+IMSR4buTbmc8YnIgLz48L3NwYW4+PC9zcGFuPjwvZGl2PjxkaXYgc3R5bGU9Im1hcmdpbjogNHB0IDBpbjsgdGV4dC1hbGlnbjoganVzdGlmeTsgdGV4dC1pbmRlbnQ6IDAuNWluOyI+PHN0cm9uZz48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogJ1RpbWVzIE5ldyBSb21hbic7Ij5WLiBRdWkgdHLDrG5oIHjhu60gbMO9IDo8YnIgLz48L3NwYW4+PC9zcGFuPjwvc3Ryb25nPjwvZGl2PjxkaXYgc3R5bGU9Im1hcmdpbjogNHB0IDBpbjsgdGV4dC1hbGlnbjogY2VudGVyOyB0ZXh0LWluZGVudDogMC41aW47Ij4mIzE2MDs8L2Rpdj48L2Rpdj4gICAgIDxwPiYjMTYwOzwvcD4gICAgIDxkaXYgc3R5bGU9Im1hcmdpbjogNHB0IDBpbjsgdGV4dC1hbGlnbjogY2VudGVyOyB0ZXh0LWluZGVudDogMC41aW47Ij4mIzE2MDs8L2Rpdj4gICAgIDxwPiYjMTYwOzwvcD4=',''),
 (10,5,'Cáº¥p láº¡i giáº¥y chá»©ng nháº­n do bá»‹ máº¥t, cáº¥p Ä‘á»•i giáº¥y chá»©ng nháº­n','0',30,'',1,'PGRpdiBzdHlsZT0idGV4dC1pbmRlbnQ6IDAuNWluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyI+PGRpdiBzdHlsZT0idGV4dC1hbGlnbjoganVzdGlmeTsgdGV4dC1pbmRlbnQ6IDAuNWluOyI+PHN0cm9uZz48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogJ1RpbWVzIE5ldyBSb21hbic7Ij4mIzE2MDtJLiBUaMOgbmggcGjhuqduIGjhu5Mgc8ahOjwvc3Bhbj48L3NwYW4+PC9zdHJvbmc+PC9kaXY+PGRpdiBzdHlsZT0ibWFyZ2luOiA0cHQgMGluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyI+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPiYjMTYwOy0gxJDGoW4gxJHhu4Egbmdo4buLIGPhuqVwIGzhuqFpIGdp4bqleSBjaOG7qW5nIG5o4bqtbiBxdXnhu4FuIHPhu60gZOG7pW5nIMSR4bqldCwgc+G7nyBo4buvdSBuaMOgIOG7nyB2w6AgdMOgaSBz4bqjbiBraMOhYyBn4bqvbiBsaeG7gW4gduG7m2kgxJHhuqV0PC9zcGFuPjwvc3Bhbj48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+Ljwvc3Bhbj48L3NwYW4+PC9kaXY+PGRpdj48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+JiMxNjA7LSBHaeG6pXkgdOG7nSB4w6FjIG5o4bqtbiB2aeG7h2MgbeG6pXQgZ2nhuqV5IGNo4bupbmcgbmjhuq1uIGPhu6dhIGPDtG5nIGFuIGPhuqVwIHjDoyBuxqFpIG3huqV0IGdp4bqleSA8L3NwYW4+PC9zcGFuPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiBUaW1lcyBOZXcgUm9tYW47Ij4uPC9zcGFuPjwvc3Bhbj48L2Rpdj48ZGl2PjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij4mIzE2MDstIEdp4bqleSB4w6FjIG5o4bqtbiBj4bunYSBVQk5EIGPhuqVwIHjDoyB24buBIHZp4buHYyDEkcOjIG5pw6ptIHnhur90IHRow7RuZyBiw6FvIG3huqV0IEdp4bqleSBjaOG7qW5nIG5o4bqtbi48L3NwYW4+PC9zcGFuPjwvZGl2PjxkaXYgc3R5bGU9Im1hcmdpbjogNHB0IDBpbjsgdGV4dC1hbGlnbjoganVzdGlmeTsgdGV4dC1pbmRlbnQ6IDAuNWluOyI+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiBUaW1lcyBOZXcgUm9tYW47Ij48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPiYjMTYwOzwvc3Bhbj48L3NwYW4+PHN0cm9uZz48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogJ1RpbWVzIE5ldyBSb21hbic7Ij5JSS4gU+G7kSBsxrDhu6NuZyBo4buTIHPGoTogMDEgYuG7mTwvc3Bhbj48L3NwYW4+PC9zdHJvbmc+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6ICdUaW1lcyBOZXcgUm9tYW4nOyI+Ljwvc3Bhbj48L3NwYW4+PC9kaXY+PGRpdiBzdHlsZT0ibWFyZ2luOiA0cHQgMGluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyB0ZXh0LWluZGVudDogMC41aW47Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+PHN0cm9uZz5JSUkuIFPhu5EgbmfDoHkgeOG7rSBsw70gOiAzMCA8L3N0cm9uZz5uZ8OgeTwvc3Bhbj48L3NwYW4+PC9kaXY+PGRpdiBzdHlsZT0ibWFyZ2luOiA0cHQgMGluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyB0ZXh0LWluZGVudDogMC41aW47Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+PHN0cm9uZz5JVi4gTOG7hyBwaMOtIDogPC9zdHJvbmc+PHN0cm9uZz4xMDAuMDAwPC9zdHJvbmc+IMSR4buTbmc8YnIgLz48L3NwYW4+PC9zcGFuPjwvZGl2PjxkaXYgc3R5bGU9Im1hcmdpbjogNHB0IDBpbjsgdGV4dC1hbGlnbjoganVzdGlmeTsgdGV4dC1pbmRlbnQ6IDAuNWluOyI+PHN0cm9uZz48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogJ1RpbWVzIE5ldyBSb21hbic7Ij5WLiBRdWkgdHLDrG5oIHjhu60gbMO9IDo8YnIgLz48L3NwYW4+PC9zcGFuPjwvc3Ryb25nPjwvZGl2PjxkaXYgc3R5bGU9Im1hcmdpbjogNHB0IDBpbjsgdGV4dC1hbGlnbjogY2VudGVyOyB0ZXh0LWluZGVudDogMC41aW47Ij4mIzE2MDs8L2Rpdj48L2Rpdj4gICAgICA8cD4mIzE2MDs8L3A+ICAgICAgPGRpdiBzdHlsZT0ibWFyZ2luOiA0cHQgMGluOyB0ZXh0LWFsaWduOiBjZW50ZXI7IHRleHQtaW5kZW50OiAwLjVpbjsiPiYjMTYwOzwvZGl2PiAgICAgIDxwPiYjMTYwOzwvcD4=','');
INSERT INTO `loaihoso` (`ID_LOAIHOSO`,`ID_LINHVUC`,`TEN`,`LEPHI`,`SONGAYXULY`,`GHICHU`,`ACTIVE`,`CHITIET_HOSO`,`IMAGE_QUITRINH`) VALUES 
 (11,5,'ÄÄƒng kÃ½ biáº¿n Ä‘á»™ng vá» quyá»n sá»­ dá»¥ng Ä‘áº¥t, quyá»n sá»Ÿ há»¯u nhÃ  á»Ÿ vÃ  tÃ i sáº£n khÃ¡c gáº¯n liá»n vá»›i Ä‘áº¥t','0',15,'',1,'PGRpdiBzdHlsZT0idGV4dC1pbmRlbnQ6IDAuNWluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyI+PGRpdiBzdHlsZT0idGV4dC1hbGlnbjoganVzdGlmeTsgdGV4dC1pbmRlbnQ6IDAuNWluOyI+PHN0cm9uZz48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogJ1RpbWVzIE5ldyBSb21hbic7Ij4mIzE2MDtJLiBUaMOgbmggcGjhuqduIGjhu5Mgc8ahOjwvc3Bhbj48L3NwYW4+PC9zdHJvbmc+PC9kaXY+PGRpdiBzdHlsZT0ibWFyZ2luOiA0cHQgMGluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyI+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPiYjMTYwOy0gxJDGoW4gxJHEg25nIGvDvSBiaeG6v24gxJHhu5luZzwvc3Bhbj48L3NwYW4+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPi48L3NwYW4+PC9zcGFuPjwvZGl2PjxkaXY+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPiYjMTYwOy0gR2nhuqV5IGNo4bupbmcgbmjhuq1uIHF1eeG7gW4gc+G7rSBk4bulbmcgxJHhuqV0LCBxdXnhu4FuIHPhu58gaOG7r3UgbmjDoCDhu58gdsOgIHTDoGkgc+G6o24gZ+G6r24gbGnhu4FuIHbhu5tpIMSR4bqldDwvc3Bhbj48L3NwYW4+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPi48L3NwYW4+PC9zcGFuPjwvZGl2PjxkaXY+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiBUaW1lcyBOZXcgUm9tYW47Ij48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPiYjMTYwOy0gR2nhuqV5IHThu50gY2jhu6luZyBtaW5oIGxpw6puIHF1YW4gxJHhur9uIHZp4buHYyDEkcSDbmcga8O9IGJp4bq/biDEkeG7mW5nLjwvc3Bhbj48L3NwYW4+PC9kaXY+PGRpdiBzdHlsZT0ibWFyZ2luOiA0cHQgMGluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyB0ZXh0LWluZGVudDogMC41aW47Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+JiMxNjA7PC9zcGFuPjwvc3Bhbj48c3Ryb25nPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiAnVGltZXMgTmV3IFJvbWFuJzsiPklJLiBT4buRIGzGsOG7o25nIGjhu5Mgc8ahOiAwMSBi4buZPC9zcGFuPjwvc3Bhbj48L3N0cm9uZz48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogJ1RpbWVzIE5ldyBSb21hbic7Ij4uPC9zcGFuPjwvc3Bhbj48L2Rpdj48ZGl2IHN0eWxlPSJtYXJnaW46IDRwdCAwaW47IHRleHQtYWxpZ246IGp1c3RpZnk7IHRleHQtaW5kZW50OiAwLjVpbjsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3Ryb25nPklJSS4gU+G7kSBuZ8OgeSB44butIGzDvSA6IDE1IDwvc3Ryb25nPm5nw6B5PC9zcGFuPjwvc3Bhbj48L2Rpdj48ZGl2IHN0eWxlPSJtYXJnaW46IDRwdCAwaW47IHRleHQtYWxpZ246IGp1c3RpZnk7IHRleHQtaW5kZW50OiAwLjVpbjsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3Ryb25nPklWLiBM4buHIHBow60gOiA8L3N0cm9uZz48c3Ryb25nPjEwMC4wMDA8L3N0cm9uZz4gxJHhu5NuZzxiciAvPjwvc3Bhbj48L3NwYW4+PC9kaXY+PGRpdiBzdHlsZT0ibWFyZ2luOiA0cHQgMGluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyB0ZXh0LWluZGVudDogMC41aW47Ij48c3Ryb25nPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiAnVGltZXMgTmV3IFJvbWFuJzsiPlYuIFF1aSB0csOsbmggeOG7rSBsw70gOjxiciAvPjwvc3Bhbj48L3NwYW4+PC9zdHJvbmc+PC9kaXY+PGRpdiBzdHlsZT0ibWFyZ2luOiA0cHQgMGluOyB0ZXh0LWFsaWduOiBjZW50ZXI7IHRleHQtaW5kZW50OiAwLjVpbjsiPiYjMTYwOzwvZGl2PjwvZGl2PiAgICAgICA8cD4mIzE2MDs8L3A+ICAgICAgIDxkaXYgc3R5bGU9Im1hcmdpbjogNHB0IDBpbjsgdGV4dC1hbGlnbjogY2VudGVyOyB0ZXh0LWluZGVudDogMC41aW47Ij4mIzE2MDs8L2Rpdj4gICAgICAgPHA+JiMxNjA7PC9wPg==',''),
 (12,5,'TÃ¡ch thá»­a hoáº·c há»£p thá»­a','0',33,'',1,'PGRpdiBzdHlsZT0idGV4dC1pbmRlbnQ6IDAuNWluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyI+PGRpdiBzdHlsZT0idGV4dC1hbGlnbjoganVzdGlmeTsgdGV4dC1pbmRlbnQ6IDAuNWluOyI+PHN0cm9uZz48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogJ1RpbWVzIE5ldyBSb21hbic7Ij4mIzE2MDtJLiBUaMOgbmggcGjhuqduIGjhu5Mgc8ahOjwvc3Bhbj48L3NwYW4+PC9zdHJvbmc+PC9kaXY+PGRpdiBzdHlsZT0ibWFyZ2luOiA0cHQgMGluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyI+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPiYjMTYwOy0gPC9zcGFuPjwvc3Bhbj48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+xJDGoW4gdMOhY2ggdGjhu61hIGhv4bq3YyBo4bujcCB0aOG7rWEgY+G7p2EgbmfGsOG7nWkgc+G7rSBk4bulbmcgxJHhuqV0Ljwvc3Bhbj48L3NwYW4+PC9kaXY+PGRpdj48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+JiMxNjA7LSBHaeG6pXkgY2jhu6luZyBuaOG6rW4gcXV54buBbiBz4butIGThu6VuZyDEkeG6pXQsIHPhu58gaOG7r3UgbmjDoCDhu58gdsOgIHTDoGkga2jDoWMgc+G6o24gZ+G6r24gbGnhu4FuIHbhu5tpIMSR4bqldDwvc3Bhbj48L3NwYW4+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPi48L3NwYW4+PC9zcGFuPjwvZGl2PjxkaXYgc3R5bGU9Im1hcmdpbjogNHB0IDBpbjsgdGV4dC1hbGlnbjoganVzdGlmeTsgdGV4dC1pbmRlbnQ6IDAuNWluOyI+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiBUaW1lcyBOZXcgUm9tYW47Ij48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPiYjMTYwOzwvc3Bhbj48L3NwYW4+PHN0cm9uZz48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogJ1RpbWVzIE5ldyBSb21hbic7Ij5JSS4gU+G7kSBsxrDhu6NuZyBo4buTIHPGoTogMDEgYuG7mTwvc3Bhbj48L3NwYW4+PC9zdHJvbmc+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6ICdUaW1lcyBOZXcgUm9tYW4nOyI+Ljwvc3Bhbj48L3NwYW4+PC9kaXY+PGRpdiBzdHlsZT0ibWFyZ2luOiA0cHQgMGluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyB0ZXh0LWluZGVudDogMC41aW47Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+PHN0cm9uZz5JSUkuIFPhu5EgbmfDoHkgeOG7rSBsw70gOiAzMyA8L3N0cm9uZz5uZ8OgeTwvc3Bhbj48L3NwYW4+PC9kaXY+PGRpdiBzdHlsZT0ibWFyZ2luOiA0cHQgMGluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyB0ZXh0LWluZGVudDogMC41aW47Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+PHN0cm9uZz5JVi4gTOG7hyBwaMOtIDogPC9zdHJvbmc+PHN0cm9uZz4xMDAuMDAwPC9zdHJvbmc+IMSR4buTbmc8YnIgLz48L3NwYW4+PC9zcGFuPjwvZGl2PjxkaXYgc3R5bGU9Im1hcmdpbjogNHB0IDBpbjsgdGV4dC1hbGlnbjoganVzdGlmeTsgdGV4dC1pbmRlbnQ6IDAuNWluOyI+PHN0cm9uZz48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogJ1RpbWVzIE5ldyBSb21hbic7Ij5WLiBRdWkgdHLDrG5oIHjhu60gbMO9IDo8YnIgLz48L3NwYW4+PC9zcGFuPjwvc3Ryb25nPjwvZGl2PjxkaXYgc3R5bGU9Im1hcmdpbjogNHB0IDBpbjsgdGV4dC1hbGlnbjogY2VudGVyOyB0ZXh0LWluZGVudDogMC41aW47Ij4mIzE2MDs8L2Rpdj48L2Rpdj4gICAgICAgIDxwPiYjMTYwOzwvcD4gICAgICAgIDxkaXYgc3R5bGU9Im1hcmdpbjogNHB0IDBpbjsgdGV4dC1hbGlnbjogY2VudGVyOyB0ZXh0LWluZGVudDogMC41aW47Ij4mIzE2MDs8L2Rpdj4gICAgICAgIDxwPiYjMTYwOzwvcD4=','');
INSERT INTO `loaihoso` (`ID_LOAIHOSO`,`ID_LINHVUC`,`TEN`,`LEPHI`,`SONGAYXULY`,`GHICHU`,`ACTIVE`,`CHITIET_HOSO`,`IMAGE_QUITRINH`) VALUES 
 (13,5,'TrÃ¬nh tá»± thá»§ tá»¥c Ä‘Äƒng kÃ½ tháº¿ cháº¥p, báº£o lÃ£nh báº±ng quyá»n sá»­ dá»¥ng Ä‘áº¥t, quyá»n sá»Ÿ há»¯u nhÃ  á»Ÿ vÃ  tÃ i sáº£n gáº¯n liá»n vá»›i Ä‘áº¥t','0',1,'',1,'PGRpdiBzdHlsZT0idGV4dC1pbmRlbnQ6IDAuNWluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyI+PGRpdiBzdHlsZT0idGV4dC1hbGlnbjoganVzdGlmeTsgdGV4dC1pbmRlbnQ6IDAuNWluOyI+PHN0cm9uZz48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogJ1RpbWVzIE5ldyBSb21hbic7Ij4mIzE2MDtJLiBUaMOgbmggcGjhuqduIGjhu5Mgc8ahOjwvc3Bhbj48L3NwYW4+PC9zdHJvbmc+PC9kaXY+PGRpdiBzdHlsZT0ibWFyZ2luOiA0cHQgMGluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyI+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPiYjMTYwOy0gPC9zcGFuPjwvc3Bhbj48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+SOG7o3AgxJHhu5NuZyB0aOG6vyBjaOG6pXAsIGjhu6NwIMSR4buTbmcgYsOjbyBsw6NuaCBi4bqxbmcgcXV54buBbiBz4butIGThu6VuZyDEkeG6pXQuPC9zcGFuPjwvc3Bhbj48L2Rpdj48ZGl2PjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiBUaW1lcyBOZXcgUm9tYW47Ij4mIzE2MDstIEdp4bqleSBjaOG7qW5nIG5o4bqtbiBxdXnhu4FuIHPhu60gZOG7pW5nIMSR4bqldCwgc+G7nyBo4buvdSBuaMOgIOG7nyB2w6AgdMOgaSBz4bqjbiBraMOhYyBn4bqvbiBsaeG7gW4gduG7m2kgxJHhuqV0PC9zcGFuPjwvc3Bhbj48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+Ljwvc3Bhbj48L3NwYW4+PC9kaXY+PGRpdj48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+JiMxNjA7LSDEkMahbiB5w6p1IGPhuqd1IMSRxINuZyBrw70gdGjhur8gY2jhuqVwIHF1eeG7gW4gYuG6o28gbMOjbmggcXV54buBbiBz4butIGThu6VuZyDEkeG6pXQsIHTDoGkgc+G6o24gZ+G6r24gbGnhu4FuIHbhu5tpIMSR4bqldC48L3NwYW4+PC9zcGFuPjwvZGl2PjxkaXYgc3R5bGU9Im1hcmdpbjogNHB0IDBpbjsgdGV4dC1hbGlnbjoganVzdGlmeTsgdGV4dC1pbmRlbnQ6IDAuNWluOyI+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiBUaW1lcyBOZXcgUm9tYW47Ij48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPiYjMTYwOzwvc3Bhbj48L3NwYW4+PHN0cm9uZz48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogJ1RpbWVzIE5ldyBSb21hbic7Ij5JSS4gU+G7kSBsxrDhu6NuZyBo4buTIHPGoTogMDEgYuG7mTwvc3Bhbj48L3NwYW4+PC9zdHJvbmc+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6ICdUaW1lcyBOZXcgUm9tYW4nOyI+Ljwvc3Bhbj48L3NwYW4+PC9kaXY+PGRpdiBzdHlsZT0ibWFyZ2luOiA0cHQgMGluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyB0ZXh0LWluZGVudDogMC41aW47Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+PHN0cm9uZz5JSUkuIFPhu5EgbmfDoHkgeOG7rSBsw70gOiAxIDwvc3Ryb25nPm5nw6B5PC9zcGFuPjwvc3Bhbj48L2Rpdj48ZGl2IHN0eWxlPSJtYXJnaW46IDRwdCAwaW47IHRleHQtYWxpZ246IGp1c3RpZnk7IHRleHQtaW5kZW50OiAwLjVpbjsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3Ryb25nPklWLiBM4buHIHBow60gOiA8L3N0cm9uZz48c3Ryb25nPjEwMC4wMDA8L3N0cm9uZz4gxJHhu5NuZzxiciAvPjwvc3Bhbj48L3NwYW4+PC9kaXY+PGRpdiBzdHlsZT0ibWFyZ2luOiA0cHQgMGluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyB0ZXh0LWluZGVudDogMC41aW47Ij48c3Ryb25nPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiAnVGltZXMgTmV3IFJvbWFuJzsiPlYuIFF1aSB0csOsbmggeOG7rSBsw70gOjxiciAvPjwvc3Bhbj48L3NwYW4+PC9zdHJvbmc+PC9kaXY+PGRpdiBzdHlsZT0ibWFyZ2luOiA0cHQgMGluOyB0ZXh0LWFsaWduOiBjZW50ZXI7IHRleHQtaW5kZW50OiAwLjVpbjsiPiYjMTYwOzwvZGl2PjwvZGl2PiAgICAgICAgIDxwPiYjMTYwOzwvcD4gICAgICAgICA8ZGl2IHN0eWxlPSJtYXJnaW46IDRwdCAwaW47IHRleHQtYWxpZ246IGNlbnRlcjsgdGV4dC1pbmRlbnQ6IDAuNWluOyI+JiMxNjA7PC9kaXY+ICAgICAgICAgPHA+JiMxNjA7PC9wPg==',''),
 (14,5,'TrÃ¬nh tá»± thá»§ tá»¥c Ä‘Äƒng kÃ½ xÃ³a tháº¿ cháº¥p, báº£o lÃ£nh báº±ng quyá»n sá»­ dá»¥ng Ä‘áº¥t, quyá»n sá»Ÿ há»¯u nhÃ  á»Ÿ vÃ  tÃ i sáº£n gáº¯n liá»n vá»›i Ä‘áº¥t','0',1,'',1,'PGRpdiBzdHlsZT0idGV4dC1pbmRlbnQ6IDAuNWluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyI+PGRpdiBzdHlsZT0idGV4dC1hbGlnbjoganVzdGlmeTsgdGV4dC1pbmRlbnQ6IDAuNWluOyI+PHN0cm9uZz48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogJ1RpbWVzIE5ldyBSb21hbic7Ij4mIzE2MDtJLiBUaMOgbmggcGjhuqduIGjhu5Mgc8ahOjwvc3Bhbj48L3NwYW4+PC9zdHJvbmc+PC9kaXY+PGRpdj48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+JiMxNjA7LSBHaeG6pXkgY2jhu6luZyBuaOG6rW4gcXV54buBbiBz4butIGThu6VuZyDEkeG6pXQsIHPhu58gaOG7r3UgbmjDoCDhu58gdsOgIHTDoGkgc+G6o24ga2jDoWMgZ+G6r24gbGnhu4FuIHbhu5tpIMSR4bqldDwvc3Bhbj48L3NwYW4+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPi48L3NwYW4+PC9zcGFuPjwvZGl2PjxkaXY+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiBUaW1lcyBOZXcgUm9tYW47Ij48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPiYjMTYwOy0gxJDGoW4gecOqdSBj4bqndSB4w7NhIMSRxINuZyBrw70gdGjhur8gY2jhuqVwIHF1eeG7gW4gYuG6o28gbMOjbmggcXV54buBbiBz4butIGThu6VuZyDEkeG6pXQsIHTDoGkgc+G6o24gZ+G6r24gbGnhu4FuIHbhu5tpIMSR4bqldC48L3NwYW4+PC9zcGFuPjwvZGl2PjxkaXYgc3R5bGU9Im1hcmdpbjogNHB0IDBpbjsgdGV4dC1hbGlnbjoganVzdGlmeTsgdGV4dC1pbmRlbnQ6IDAuNWluOyI+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiBUaW1lcyBOZXcgUm9tYW47Ij48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPiYjMTYwOzwvc3Bhbj48L3NwYW4+PHN0cm9uZz48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogJ1RpbWVzIE5ldyBSb21hbic7Ij5JSS4gU+G7kSBsxrDhu6NuZyBo4buTIHPGoTogMDEgYuG7mTwvc3Bhbj48L3NwYW4+PC9zdHJvbmc+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6ICdUaW1lcyBOZXcgUm9tYW4nOyI+Ljwvc3Bhbj48L3NwYW4+PC9kaXY+PGRpdiBzdHlsZT0ibWFyZ2luOiA0cHQgMGluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyB0ZXh0LWluZGVudDogMC41aW47Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+PHN0cm9uZz5JSUkuIFPhu5EgbmfDoHkgeOG7rSBsw70gOiAxIDwvc3Ryb25nPm5nw6B5PC9zcGFuPjwvc3Bhbj48L2Rpdj48ZGl2IHN0eWxlPSJtYXJnaW46IDRwdCAwaW47IHRleHQtYWxpZ246IGp1c3RpZnk7IHRleHQtaW5kZW50OiAwLjVpbjsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3Ryb25nPklWLiBM4buHIHBow60gOiA8L3N0cm9uZz48c3Ryb25nPjEwMC4wMDA8L3N0cm9uZz4gxJHhu5NuZzxiciAvPjwvc3Bhbj48L3NwYW4+PC9kaXY+PGRpdiBzdHlsZT0ibWFyZ2luOiA0cHQgMGluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyB0ZXh0LWluZGVudDogMC41aW47Ij48c3Ryb25nPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiAnVGltZXMgTmV3IFJvbWFuJzsiPlYuIFF1aSB0csOsbmggeOG7rSBsw70gOjxiciAvPjwvc3Bhbj48L3NwYW4+PC9zdHJvbmc+PC9kaXY+PGRpdiBzdHlsZT0ibWFyZ2luOiA0cHQgMGluOyB0ZXh0LWFsaWduOiBjZW50ZXI7IHRleHQtaW5kZW50OiAwLjVpbjsiPiYjMTYwOzwvZGl2PjwvZGl2PiAgICAgICAgICA8cD4mIzE2MDs8L3A+ICAgICAgICAgIDxkaXYgc3R5bGU9Im1hcmdpbjogNHB0IDBpbjsgdGV4dC1hbGlnbjogY2VudGVyOyB0ZXh0LWluZGVudDogMC41aW47Ij4mIzE2MDs8L2Rpdj4gICAgICAgICAgPHA+JiMxNjA7PC9wPg==','');
INSERT INTO `loaihoso` (`ID_LOAIHOSO`,`ID_LINHVUC`,`TEN`,`LEPHI`,`SONGAYXULY`,`GHICHU`,`ACTIVE`,`CHITIET_HOSO`,`IMAGE_QUITRINH`) VALUES 
 (15,13,'Cáº¥p giáº¥y xÃ¡c nháº­n báº£n cam káº¿t báº£o vá»‡ mÃ´i trÆ°á»ng','0',5,'',1,'PGRpdiBzdHlsZT0idGV4dC1pbmRlbnQ6IDAuNWluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyI+PGRpdiBzdHlsZT0idGV4dC1hbGlnbjoganVzdGlmeTsgdGV4dC1pbmRlbnQ6IDAuNWluOyI+PHN0cm9uZz48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogJ1RpbWVzIE5ldyBSb21hbic7Ij4mIzE2MDtJLiBUaMOgbmggcGjhuqduIGjhu5Mgc8ahOjwvc3Bhbj48L3NwYW4+PC9zdHJvbmc+PC9kaXY+PGRpdiBzdHlsZT0ibWFyZ2luOiA0cHQgMGluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyI+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPiYjMTYwOy0gPC9zcGFuPjwvc3Bhbj48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+VsSDbiBi4bqjbiDEkeG7gSBuZ2jhu4sgeMOhYyBuaOG6rW4gxJHEg25nIGvDvSBi4bqjbiBjYW0ga+G6v3QgYuG6o28gduG7hyBtw7RpIHRyxrDhu51uZy48L3NwYW4+PC9zcGFuPjwvZGl2PjxkaXY+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPiYjMTYwOy0gQuG6o24gY2FtIGvhur90IGLhuqNvIHbhu4cgbcO0aSB0csaw4budbmc8L3NwYW4+PC9zcGFuPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiBUaW1lcyBOZXcgUm9tYW47Ij4uPC9zcGFuPjwvc3Bhbj48L2Rpdj48ZGl2PjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij4mIzE2MDstIELhuqNuIHNhbyBjw7RuZyBjaOG7qW5nIGdp4bqleSBDTU5EIGPhu6dhIGNo4bunIGThu7Egw6FuLjwvc3Bhbj48L3NwYW4+PC9kaXY+PGRpdj48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+JiMxNjA7LSBC4bqjbiBzYW8gY8O0bmcgY2jhu6luZyB0csOtY2ggbOG7pWMgdGjhu61hIMSR4bqldCBz4butIGThu6VuZyDEkeG7gyBz4bqjbiB4deG6pXQga2luaCBkb2FuaCwgZOG7i2NoIHbhu6UuPC9zcGFuPjwvc3Bhbj48L2Rpdj48ZGl2PjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij4mIzE2MDstIEjhu5Mgc8ahIHRoaeG6v3Qga+G6vyBk4buxIMOhbi48L3NwYW4+PC9zcGFuPjwvZGl2PjxkaXYgc3R5bGU9Im1hcmdpbjogNHB0IDBpbjsgdGV4dC1hbGlnbjoganVzdGlmeTsgdGV4dC1pbmRlbnQ6IDAuNWluOyI+PHN0cm9uZz48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogJ1RpbWVzIE5ldyBSb21hbic7Ij5JSS4gU+G7kSBsxrDhu6NuZyBo4buTIHPGoTogMDEgYuG7mTwvc3Bhbj48L3NwYW4+PC9zdHJvbmc+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6ICdUaW1lcyBOZXcgUm9tYW4nOyI+Ljwvc3Bhbj48L3NwYW4+PC9kaXY+PGRpdiBzdHlsZT0ibWFyZ2luOiA0cHQgMGluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyB0ZXh0LWluZGVudDogMC41aW47Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+PHN0cm9uZz5JSUkuIFPhu5EgbmfDoHkgeOG7rSBsw70gOiA1IDwvc3Ryb25nPm5nw6B5PC9zcGFuPjwvc3Bhbj48L2Rpdj48ZGl2IHN0eWxlPSJtYXJnaW46IDRwdCAwaW47IHRleHQtYWxpZ246IGp1c3RpZnk7IHRleHQtaW5kZW50OiAwLjVpbjsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3Ryb25nPklWLiBM4buHIHBow60gOiA8L3N0cm9uZz48c3Ryb25nPjEwMC4wMDA8L3N0cm9uZz4gxJHhu5NuZzxiciAvPjwvc3Bhbj48L3NwYW4+PC9kaXY+PGRpdiBzdHlsZT0ibWFyZ2luOiA0cHQgMGluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyB0ZXh0LWluZGVudDogMC41aW47Ij48c3Ryb25nPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiAnVGltZXMgTmV3IFJvbWFuJzsiPlYuIFF1aSB0csOsbmggeOG7rSBsw70gOjxiciAvPjwvc3Bhbj48L3NwYW4+PC9zdHJvbmc+PC9kaXY+PGRpdiBzdHlsZT0ibWFyZ2luOiA0cHQgMGluOyB0ZXh0LWFsaWduOiBjZW50ZXI7IHRleHQtaW5kZW50OiAwLjVpbjsiPiYjMTYwOzwvZGl2PjwvZGl2PiA8cD4mIzE2MDs8L3A+',''),
 (16,14,'Cáº¥p giáº¥y chá»©ng nháº­n Ä‘á»§ Ä‘iá»u kiá»‡n Vá»‡ sinh an toÃ n thá»±c pháº©m','0',10,'',1,'PGRpdiBzdHlsZT0idGV4dC1pbmRlbnQ6IDAuNWluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyI+PGRpdiBzdHlsZT0idGV4dC1hbGlnbjoganVzdGlmeTsgdGV4dC1pbmRlbnQ6IDAuNWluOyI+PHN0cm9uZz48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogJ1RpbWVzIE5ldyBSb21hbic7Ij4mIzE2MDtJLiBUaMOgbmggcGjhuqduIGjhu5Mgc8ahOjwvc3Bhbj48L3NwYW4+PC9zdHJvbmc+PC9kaXY+PGRpdiBzdHlsZT0ibWFyZ2luOiA0cHQgMGluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyI+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPiYjMTYwOy0gPC9zcGFuPjwvc3Bhbj48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+xJDGoW4gxJHhu4Egbmdo4buLIGPhuqVwIGdp4bqleSBjaOG7qW5nIG5o4bqtbi48L3NwYW4+PC9zcGFuPjwvZGl2PjxkaXY+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPiYjMTYwOy0gQuG6o24gc2FvIGPDtG5nIGNo4bupbmcgZ2nhuqV5IMSRxINuZyBrw70ga2luaCBkb2FuaDwvc3Bhbj48L3NwYW4+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPi48L3NwYW4+PC9zcGFuPjwvZGl2PjxkaXY+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiBUaW1lcyBOZXcgUm9tYW47Ij48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPiYjMTYwOy0gQuG6o24gc2FvIHRodXnhur90IG1pbmggduG7gSBjxqEgc+G7nyB24bqtdCBjaOG6pXQuPC9zcGFuPjwvc3Bhbj48L2Rpdj48ZGl2PjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij4mIzE2MDstIELhuqNuIHNhbyBjw7RuZyBjaOG7qW5nIGdp4bqleSBjaOG7qW5nIG5o4bqtbiBHTVAsIFNTT1AsIEhBQ0NQLjwvc3Bhbj48L3NwYW4+PC9kaXY+PGRpdj48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+JiMxNjA7LSBC4bqjbiBjYW0ga+G6v3QgYuG6o28gxJHhuqNtIHbhu4cgc2luaCwgYW4gdG/DoG4gdGjhu7FjIHBo4bqpbS48L3NwYW4+PC9zcGFuPjwvZGl2PjxkaXY+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiBUaW1lcyBOZXcgUm9tYW47Ij48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPiYjMTYwOy0gR2nhuqV5IGNo4bupbmcgbmjhuq1uIMSR4bunIMSRaeG7gXUga2nhu4duIHPhu6ljIGto4buPZSBj4bunYSBjaOG7pyBjxqEgc+G7nyB2w6AgbmfGsOG7nWkgdHLhu7FjIHRp4bq/cCB0aGFtIGdpYSBz4bqjbiB4deG6pXQga2luaCBkb2FuaC48L3NwYW4+PC9zcGFuPjwvZGl2PjxkaXY+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiBUaW1lcyBOZXcgUm9tYW47Ij48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPiYjMTYwOy0gQuG6o24gc2FvIEdp4bqleSBjaOG7qW5nIG5o4bqtbiDEkcOjIMSRxrDhu6NjIHThuq1wIGh14bqlbiBraeG6v24gdGjhu6ljIHbhu4EgduG7hyBzaW5oIGFuIHRvw6BuIHRo4buxYyBwaOG6qW0uPC9zcGFuPjwvc3Bhbj48L2Rpdj48ZGl2IHN0eWxlPSJtYXJnaW46IDRwdCAwaW47IHRleHQtYWxpZ246IGp1c3RpZnk7IHRleHQtaW5kZW50OiAwLjVpbjsiPjxzdHJvbmc+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6ICdUaW1lcyBOZXcgUm9tYW4nOyI+SUkuIFPhu5EgbMaw4bujbmcgaOG7kyBzxqE6IDAxIGLhu5k8L3NwYW4+PC9zcGFuPjwvc3Ryb25nPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiAnVGltZXMgTmV3IFJvbWFuJzsiPi48L3NwYW4+PC9zcGFuPjwvZGl2PjxkaXYgc3R5bGU9Im1hcmdpbjogNHB0IDBpbjsgdGV4dC1hbGlnbjoganVzdGlmeTsgdGV4dC1pbmRlbnQ6IDAuNWluOyI+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiBUaW1lcyBOZXcgUm9tYW47Ij48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzdHJvbmc+SUlJLiBT4buRIG5nw6B5IHjhu60gbMO9IDogMTAgPC9zdHJvbmc+bmfDoHk8L3NwYW4+PC9zcGFuPjwvZGl2PjxkaXYgc3R5bGU9Im1hcmdpbjogNHB0IDBpbjsgdGV4dC1hbGlnbjoganVzdGlmeTsgdGV4dC1pbmRlbnQ6IDAuNWluOyI+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiBUaW1lcyBOZXcgUm9tYW47Ij48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzdHJvbmc+SVYuIEzhu4cgcGjDrSA6IDwvc3Ryb25nPjxzdHJvbmc+MTAwLjAwMDwvc3Ryb25nPiDEkeG7k25nPGJyIC8+PC9zcGFuPjwvc3Bhbj48L2Rpdj48ZGl2IHN0eWxlPSJtYXJnaW46IDRwdCAwaW47IHRleHQtYWxpZ246IGp1c3RpZnk7IHRleHQtaW5kZW50OiAwLjVpbjsiPjxzdHJvbmc+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6ICdUaW1lcyBOZXcgUm9tYW4nOyI+Vi4gUXVpIHRyw6xuaCB44butIGzDvSA6PGJyIC8+PC9zcGFuPjwvc3Bhbj48L3N0cm9uZz48L2Rpdj48ZGl2IHN0eWxlPSJtYXJnaW46IDRwdCAwaW47IHRleHQtYWxpZ246IGNlbnRlcjsgdGV4dC1pbmRlbnQ6IDAuNWluOyI+JiMxNjA7PC9kaXY+PC9kaXY+IDxwPiYjMTYwOzwvcD4=','');
INSERT INTO `loaihoso` (`ID_LOAIHOSO`,`ID_LINHVUC`,`TEN`,`LEPHI`,`SONGAYXULY`,`GHICHU`,`ACTIVE`,`CHITIET_HOSO`,`IMAGE_QUITRINH`) VALUES 
 (17,8,'PhÃª duyá»‡t bÃ¡o cÃ¡o kinh táº¿ ká»¹ thuáº­t vÃ  káº¿ hoáº¡ch Ä‘áº¥u tháº§u','0',15,'',1,'PGRpdiBzdHlsZT0idGV4dC1pbmRlbnQ6IDAuNWluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyI+PGRpdiBzdHlsZT0idGV4dC1hbGlnbjoganVzdGlmeTsgdGV4dC1pbmRlbnQ6IDAuNWluOyI+PHN0cm9uZz48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogJ1RpbWVzIE5ldyBSb21hbic7Ij4mIzE2MDtJLiBUaMOgbmggcGjhuqduIGjhu5Mgc8ahOjwvc3Bhbj48L3NwYW4+PC9zdHJvbmc+PC9kaXY+PGRpdiBzdHlsZT0ibWFyZ2luOiA0cHQgMGluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyI+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPiYjMTYwOy0gPC9zcGFuPjwvc3Bhbj48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+VOG7nSB0csOsbmggxJHhu4Egbmdo4buLIGLDoW8gY8OhbyBraW5oIHThur8ga+G7uSB0aHXhuq10IHbDoCBr4bq/IGhv4bqhY2ggxJHhuqV1IHRo4bqndS48L3NwYW4+PC9zcGFuPjwvZGl2PjxkaXY+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPiYjMTYwOy0gQsOhbyBjw6FvIGtpbmggdOG6vyBrxKkgdGh14bqtdDwvc3Bhbj48L3NwYW4+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPi48L3NwYW4+PC9zcGFuPjwvZGl2PjxkaXY+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiBUaW1lcyBOZXcgUm9tYW47Ij48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPiYjMTYwOy0gS+G6vyBob+G6oWNoIMSR4bqldSB0aOG6p3UuPC9zcGFuPjwvc3Bhbj48L2Rpdj48ZGl2PjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij4mIzE2MDstIEvhur90IHF14bqjIHRo4bqpbSDEkeG7i25oICh0aOG6qW0gdHJhKS48L3NwYW4+PC9zcGFuPjwvZGl2PjxkaXYgc3R5bGU9Im1hcmdpbjogNHB0IDBpbjsgdGV4dC1hbGlnbjoganVzdGlmeTsgdGV4dC1pbmRlbnQ6IDAuNWluOyI+PHN0cm9uZz48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogJ1RpbWVzIE5ldyBSb21hbic7Ij5JSS4gU+G7kSBsxrDhu6NuZyBo4buTIHPGoTogMDEgYuG7mTwvc3Bhbj48L3NwYW4+PC9zdHJvbmc+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6ICdUaW1lcyBOZXcgUm9tYW4nOyI+Ljwvc3Bhbj48L3NwYW4+PC9kaXY+PGRpdiBzdHlsZT0ibWFyZ2luOiA0cHQgMGluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyB0ZXh0LWluZGVudDogMC41aW47Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+PHN0cm9uZz5JSUkuIFPhu5EgbmfDoHkgeOG7rSBsw70gOiAxNSA8L3N0cm9uZz5uZ8OgeTwvc3Bhbj48L3NwYW4+PC9kaXY+PGRpdiBzdHlsZT0ibWFyZ2luOiA0cHQgMGluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyB0ZXh0LWluZGVudDogMC41aW47Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+PHN0cm9uZz5JVi4gTOG7hyBwaMOtIDogPC9zdHJvbmc+PHN0cm9uZz4xMDAuMDAwPC9zdHJvbmc+IMSR4buTbmc8YnIgLz48L3NwYW4+PC9zcGFuPjwvZGl2PjxkaXYgc3R5bGU9Im1hcmdpbjogNHB0IDBpbjsgdGV4dC1hbGlnbjoganVzdGlmeTsgdGV4dC1pbmRlbnQ6IDAuNWluOyI+PHN0cm9uZz48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogJ1RpbWVzIE5ldyBSb21hbic7Ij5WLiBRdWkgdHLDrG5oIHjhu60gbMO9IDo8YnIgLz48L3NwYW4+PC9zcGFuPjwvc3Ryb25nPjwvZGl2PjxkaXYgc3R5bGU9Im1hcmdpbjogNHB0IDBpbjsgdGV4dC1hbGlnbjogY2VudGVyOyB0ZXh0LWluZGVudDogMC41aW47Ij4mIzE2MDs8L2Rpdj48L2Rpdj48cD4mIzE2MDs8L3A+',''),
 (18,8,'Äiá»u chá»‰nh tá»•ng dá»± toÃ¡n Ä‘áº§u tÆ° xÃ¢y dá»±ng cÃ´ng trÃ¬nh','0',7,'',1,'PGRpdiBzdHlsZT0idGV4dC1pbmRlbnQ6IDAuNWluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyI+PGRpdiBzdHlsZT0idGV4dC1hbGlnbjoganVzdGlmeTsgdGV4dC1pbmRlbnQ6IDAuNWluOyI+PHN0cm9uZz48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogJ1RpbWVzIE5ldyBSb21hbic7Ij4mIzE2MDtJLiBUaMOgbmggcGjhuqduIGjhu5Mgc8ahOjwvc3Bhbj48L3NwYW4+PC9zdHJvbmc+PC9kaXY+PGRpdiBzdHlsZT0ibWFyZ2luOiA0cHQgMGluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyI+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPiYjMTYwOy0gPC9zcGFuPjwvc3Bhbj48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+VOG7nSB0csOsbmggdGjhuqltIMSR4buLbmggxJHhu4Egbmdo4buLIMSRaeG7gXUgY2jhu4luaCB04buVbmcgZOG7sSB0b8Ohbi48L3NwYW4+PC9zcGFuPjwvZGl2PjxkaXY+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPiYjMTYwOy0gSOG7kyBzxqEgZOG7sSB0b8OhbiDEkWnhu4F1IGNo4buJbmg8L3NwYW4+PC9zcGFuPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiBUaW1lcyBOZXcgUm9tYW47Ij4uPC9zcGFuPjwvc3Bhbj48L2Rpdj48ZGl2PjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij4mIzE2MDstIEvhur90IHF14bqjIHRo4bqpbSDEkeG7i25oICh0aOG6qW0gdHJhKSDEkWnhu4F1IGNo4buJbmguPC9zcGFuPjwvc3Bhbj48L2Rpdj48ZGl2IHN0eWxlPSJtYXJnaW46IDRwdCAwaW47IHRleHQtYWxpZ246IGp1c3RpZnk7IHRleHQtaW5kZW50OiAwLjVpbjsiPjxzdHJvbmc+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6ICdUaW1lcyBOZXcgUm9tYW4nOyI+SUkuIFPhu5EgbMaw4bujbmcgaOG7kyBzxqE6IDAxIGLhu5k8L3NwYW4+PC9zcGFuPjwvc3Ryb25nPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiAnVGltZXMgTmV3IFJvbWFuJzsiPi48L3NwYW4+PC9zcGFuPjwvZGl2PjxkaXYgc3R5bGU9Im1hcmdpbjogNHB0IDBpbjsgdGV4dC1hbGlnbjoganVzdGlmeTsgdGV4dC1pbmRlbnQ6IDAuNWluOyI+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiBUaW1lcyBOZXcgUm9tYW47Ij48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzdHJvbmc+SUlJLiBT4buRIG5nw6B5IHjhu60gbMO9IDogNyA8L3N0cm9uZz5uZ8OgeTwvc3Bhbj48L3NwYW4+PC9kaXY+PGRpdiBzdHlsZT0ibWFyZ2luOiA0cHQgMGluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyB0ZXh0LWluZGVudDogMC41aW47Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+PHN0cm9uZz5JVi4gTOG7hyBwaMOtIDogPC9zdHJvbmc+PHN0cm9uZz4xMDAuMDAwPC9zdHJvbmc+IMSR4buTbmc8YnIgLz48L3NwYW4+PC9zcGFuPjwvZGl2PjxkaXYgc3R5bGU9Im1hcmdpbjogNHB0IDBpbjsgdGV4dC1hbGlnbjoganVzdGlmeTsgdGV4dC1pbmRlbnQ6IDAuNWluOyI+PHN0cm9uZz48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogJ1RpbWVzIE5ldyBSb21hbic7Ij5WLiBRdWkgdHLDrG5oIHjhu60gbMO9IDo8YnIgLz48L3NwYW4+PC9zcGFuPjwvc3Ryb25nPjwvZGl2PjxkaXYgc3R5bGU9Im1hcmdpbjogNHB0IDBpbjsgdGV4dC1hbGlnbjogY2VudGVyOyB0ZXh0LWluZGVudDogMC41aW47Ij4mIzE2MDs8L2Rpdj48L2Rpdj48cD4mIzE2MDs8L3A+','');
INSERT INTO `loaihoso` (`ID_LOAIHOSO`,`ID_LINHVUC`,`TEN`,`LEPHI`,`SONGAYXULY`,`GHICHU`,`ACTIVE`,`CHITIET_HOSO`,`IMAGE_QUITRINH`) VALUES 
 (19,8,'PhÃª duyá»‡t chá»‰ Ä‘á»‹nh tháº§u tÆ° váº¥n','0',7,'',1,'PGRpdiBzdHlsZT0idGV4dC1pbmRlbnQ6IDAuNWluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyI+PGRpdiBzdHlsZT0idGV4dC1hbGlnbjoganVzdGlmeTsgdGV4dC1pbmRlbnQ6IDAuNWluOyI+PHN0cm9uZz48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogJ1RpbWVzIE5ldyBSb21hbic7Ij4mIzE2MDtJLiBUaMOgbmggcGjhuqduIGjhu5Mgc8ahOjwvc3Bhbj48L3NwYW4+PC9zdHJvbmc+PC9kaXY+PGRpdiBzdHlsZT0ibWFyZ2luOiA0cHQgMGluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyI+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPiYjMTYwOy0gPC9zcGFuPjwvc3Bhbj48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+xJDGoW4geGluIG5o4bqtbiB0aOG6p3UgY+G7p2EgxJHGoW4gduG7iyB0xrAgduG6pW4gdsOgIGjhu5Mgc8ahIG7Eg25nIGzhu7FjLjwvc3Bhbj48L3NwYW4+PC9kaXY+PGRpdj48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+JiMxNjA7LSBU4budIHRyw6xuaCBwaMOqIGR1eeG7h3QgY2jhu4kgxJHhu4tuaCB0aOG6p3UgdMawIHbhuqVuPC9zcGFuPjwvc3Bhbj48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+Ljwvc3Bhbj48L3NwYW4+PC9kaXY+PGRpdiBzdHlsZT0ibWFyZ2luOiA0cHQgMGluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyB0ZXh0LWluZGVudDogMC41aW47Ij48c3Ryb25nPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiAnVGltZXMgTmV3IFJvbWFuJzsiPklJLiBT4buRIGzGsOG7o25nIGjhu5Mgc8ahOiAwMSBi4buZPC9zcGFuPjwvc3Bhbj48L3N0cm9uZz48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogJ1RpbWVzIE5ldyBSb21hbic7Ij4uPC9zcGFuPjwvc3Bhbj48L2Rpdj48ZGl2IHN0eWxlPSJtYXJnaW46IDRwdCAwaW47IHRleHQtYWxpZ246IGp1c3RpZnk7IHRleHQtaW5kZW50OiAwLjVpbjsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3Ryb25nPklJSS4gU+G7kSBuZ8OgeSB44butIGzDvSA6IDcgPC9zdHJvbmc+bmfDoHk8L3NwYW4+PC9zcGFuPjwvZGl2PjxkaXYgc3R5bGU9Im1hcmdpbjogNHB0IDBpbjsgdGV4dC1hbGlnbjoganVzdGlmeTsgdGV4dC1pbmRlbnQ6IDAuNWluOyI+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiBUaW1lcyBOZXcgUm9tYW47Ij48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzdHJvbmc+SVYuIEzhu4cgcGjDrSA6IDwvc3Ryb25nPjxzdHJvbmc+MTAwLjAwMDwvc3Ryb25nPiDEkeG7k25nPGJyIC8+PC9zcGFuPjwvc3Bhbj48L2Rpdj48ZGl2IHN0eWxlPSJtYXJnaW46IDRwdCAwaW47IHRleHQtYWxpZ246IGp1c3RpZnk7IHRleHQtaW5kZW50OiAwLjVpbjsiPjxzdHJvbmc+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6ICdUaW1lcyBOZXcgUm9tYW4nOyI+Vi4gUXVpIHRyw6xuaCB44butIGzDvSA6PGJyIC8+PC9zcGFuPjwvc3Bhbj48L3N0cm9uZz48L2Rpdj48ZGl2IHN0eWxlPSJtYXJnaW46IDRwdCAwaW47IHRleHQtYWxpZ246IGNlbnRlcjsgdGV4dC1pbmRlbnQ6IDAuNWluOyI+JiMxNjA7PC9kaXY+PC9kaXY+IDxwPiYjMTYwOzwvcD4=',''),
 (20,8,'Tháº©m Ä‘á»‹nh há»“ sÆ¡ má»i tháº§u cÃ´ng trÃ¬nh','0',7,'',1,'PGRpdiBzdHlsZT0idGV4dC1pbmRlbnQ6IDAuNWluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyI+PGRpdiBzdHlsZT0idGV4dC1hbGlnbjoganVzdGlmeTsgdGV4dC1pbmRlbnQ6IDAuNWluOyI+PHN0cm9uZz48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogJ1RpbWVzIE5ldyBSb21hbic7Ij4mIzE2MDtJLiBUaMOgbmggcGjhuqduIGjhu5Mgc8ahOjwvc3Bhbj48L3NwYW4+PC9zdHJvbmc+PC9kaXY+PGRpdiBzdHlsZT0ibWFyZ2luOiA0cHQgMGluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyI+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPiYjMTYwOy0gPC9zcGFuPjwvc3Bhbj48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+VOG7nSB0csOsbmggxJHhu4Egbmdo4buLIHBow6ogZHV54buHdCBo4buTIHPGoSBt4budaSB0aOG6p3UuPC9zcGFuPjwvc3Bhbj48L2Rpdj48ZGl2PjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiBUaW1lcyBOZXcgUm9tYW47Ij4mIzE2MDstIFF1eeG6v3QgxJHhu4tuaCBwaMOqIGR1eeG7h3QgZOG7sSDDoW48L3NwYW4+PC9zcGFuPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiBUaW1lcyBOZXcgUm9tYW47Ij4uPC9zcGFuPjwvc3Bhbj48L2Rpdj48ZGl2PjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij4mIzE2MDstIFF1eeG6v3QgxJHhu4tuaCBwaMOqIGR1eeG7h3Qga+G6vyBob+G6oWNoIMSR4bqldSB0aOG6p3UuPC9zcGFuPjwvc3Bhbj48L2Rpdj48ZGl2PjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij4mIzE2MDstIEjhu5Mgc8ahIG3hu51pIHRo4bqndS48L3NwYW4+PC9zcGFuPjwvZGl2PjxkaXYgc3R5bGU9Im1hcmdpbjogNHB0IDBpbjsgdGV4dC1hbGlnbjoganVzdGlmeTsgdGV4dC1pbmRlbnQ6IDAuNWluOyI+PHN0cm9uZz48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogJ1RpbWVzIE5ldyBSb21hbic7Ij5JSS4gU+G7kSBsxrDhu6NuZyBo4buTIHPGoTogMDEgYuG7mTwvc3Bhbj48L3NwYW4+PC9zdHJvbmc+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6ICdUaW1lcyBOZXcgUm9tYW4nOyI+Ljwvc3Bhbj48L3NwYW4+PC9kaXY+PGRpdiBzdHlsZT0ibWFyZ2luOiA0cHQgMGluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyB0ZXh0LWluZGVudDogMC41aW47Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+PHN0cm9uZz5JSUkuIFPhu5EgbmfDoHkgeOG7rSBsw70gOiA3IDwvc3Ryb25nPm5nw6B5PC9zcGFuPjwvc3Bhbj48L2Rpdj48ZGl2IHN0eWxlPSJtYXJnaW46IDRwdCAwaW47IHRleHQtYWxpZ246IGp1c3RpZnk7IHRleHQtaW5kZW50OiAwLjVpbjsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3Ryb25nPklWLiBM4buHIHBow60gOiA8L3N0cm9uZz48c3Ryb25nPjEwMC4wMDA8L3N0cm9uZz4gxJHhu5NuZzxiciAvPjwvc3Bhbj48L3NwYW4+PC9kaXY+PGRpdiBzdHlsZT0ibWFyZ2luOiA0cHQgMGluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyB0ZXh0LWluZGVudDogMC41aW47Ij48c3Ryb25nPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiAnVGltZXMgTmV3IFJvbWFuJzsiPlYuIFF1aSB0csOsbmggeOG7rSBsw70gOjxiciAvPjwvc3Bhbj48L3NwYW4+PC9zdHJvbmc+PC9kaXY+PGRpdiBzdHlsZT0ibWFyZ2luOiA0cHQgMGluOyB0ZXh0LWFsaWduOiBjZW50ZXI7IHRleHQtaW5kZW50OiAwLjVpbjsiPiYjMTYwOzwvZGl2PjwvZGl2PiAgPHA+JiMxNjA7PC9wPg==','');
INSERT INTO `loaihoso` (`ID_LOAIHOSO`,`ID_LINHVUC`,`TEN`,`LEPHI`,`SONGAYXULY`,`GHICHU`,`ACTIVE`,`CHITIET_HOSO`,`IMAGE_QUITRINH`) VALUES 
 (21,8,'PhÃª duyá»‡t káº¿t quáº£ lá»±a chá»n nhÃ  tháº§u','0',10,'',1,'PGRpdiBzdHlsZT0idGV4dC1pbmRlbnQ6IDAuNWluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyI+PGRpdiBzdHlsZT0idGV4dC1hbGlnbjoganVzdGlmeTsgdGV4dC1pbmRlbnQ6IDAuNWluOyI+PHN0cm9uZz48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogJ1RpbWVzIE5ldyBSb21hbic7Ij4mIzE2MDtJLiBUaMOgbmggcGjhuqduIGjhu5Mgc8ahOjwvc3Bhbj48L3NwYW4+PC9zdHJvbmc+PC9kaXY+PGRpdiBzdHlsZT0ibWFyZ2luOiA0cHQgMGluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyI+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPiYjMTYwOy0gPC9zcGFuPjwvc3Bhbj48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+UXV54bq/dCDEkeG7i25oIHBow6ogZHV54buHdCBk4buxIMOhbi48L3NwYW4+PC9zcGFuPjwvZGl2PjxkaXY+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPiYjMTYwOy0gUXV54bq/dCDEkeG7i25oIHBow6ogZHV54buHdCBo4buTIHPGoSBt4budaSB0aOG6p3UgKGjhu5Mgc8ahIHnDqnUgY+G6p3UpPC9zcGFuPjwvc3Bhbj48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+Ljwvc3Bhbj48L3NwYW4+PC9kaXY+PGRpdj48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+JiMxNjA7LSBU4budIHRyw6xuaCBwaMOqIGR1eeG7h3Qga+G6v3QgcXXhuqMgbOG7sWEgY2jhu41uIG5ow6AgdGjhuqd1Ljwvc3Bhbj48L3NwYW4+PC9kaXY+PGRpdj48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+JiMxNjA7LSBCw6FvIGPDoW8ga+G6v3QgcXXhuqMgbOG7sWEgY2jhu41uIG5ow6AgdGjhuqd1IGPhu6dhIFThu5UgY2h1ecOqbiBnaWEgeMOpdCB0aOG6p3UgaG/hurdjIGJpw6puIGLhuqNuIMSRw6FuaCBnacOhIGPhu6dhIFThu5UgxJHDoW5oIGdpw6EuPC9zcGFuPjwvc3Bhbj48L2Rpdj48ZGl2PjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij4mIzE2MDstIEPDoWMgaOG7kyBzxqEgZOG7sSB0aOG6p3UuPC9zcGFuPjwvc3Bhbj48L2Rpdj48ZGl2PjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij4mIzE2MDstIELDoW8gxJHEg25nIHRow7RuZyB0aW4gxJHhuqV1IHRo4bqndS48L3NwYW4+PC9zcGFuPjwvZGl2PjxkaXY+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiBUaW1lcyBOZXcgUm9tYW47Ij48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPiYjMTYwOy0gQmnDqm4gYuG6o24gbeG7nyB0aOG6p3UuPC9zcGFuPjwvc3Bhbj48L2Rpdj48ZGl2IHN0eWxlPSJtYXJnaW46IDRwdCAwaW47IHRleHQtYWxpZ246IGp1c3RpZnk7IHRleHQtaW5kZW50OiAwLjVpbjsiPjxzdHJvbmc+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6ICdUaW1lcyBOZXcgUm9tYW4nOyI+SUkuIFPhu5EgbMaw4bujbmcgaOG7kyBzxqE6IDAxIGLhu5k8L3NwYW4+PC9zcGFuPjwvc3Ryb25nPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiAnVGltZXMgTmV3IFJvbWFuJzsiPi48L3NwYW4+PC9zcGFuPjwvZGl2PjxkaXYgc3R5bGU9Im1hcmdpbjogNHB0IDBpbjsgdGV4dC1hbGlnbjoganVzdGlmeTsgdGV4dC1pbmRlbnQ6IDAuNWluOyI+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiBUaW1lcyBOZXcgUm9tYW47Ij48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzdHJvbmc+SUlJLiBT4buRIG5nw6B5IHjhu60gbMO9IDogMTAgPC9zdHJvbmc+bmfDoHk8L3NwYW4+PC9zcGFuPjwvZGl2PjxkaXYgc3R5bGU9Im1hcmdpbjogNHB0IDBpbjsgdGV4dC1hbGlnbjoganVzdGlmeTsgdGV4dC1pbmRlbnQ6IDAuNWluOyI+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiBUaW1lcyBOZXcgUm9tYW47Ij48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzdHJvbmc+SVYuIEzhu4cgcGjDrSA6IDwvc3Ryb25nPjxzdHJvbmc+MTAwLjAwMDwvc3Ryb25nPiDEkeG7k25nPGJyIC8+PC9zcGFuPjwvc3Bhbj48L2Rpdj48ZGl2IHN0eWxlPSJtYXJnaW46IDRwdCAwaW47IHRleHQtYWxpZ246IGp1c3RpZnk7IHRleHQtaW5kZW50OiAwLjVpbjsiPjxzdHJvbmc+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6ICdUaW1lcyBOZXcgUm9tYW4nOyI+Vi4gUXVpIHRyw6xuaCB44butIGzDvSA6PGJyIC8+PC9zcGFuPjwvc3Bhbj48L3N0cm9uZz48L2Rpdj48ZGl2IHN0eWxlPSJtYXJnaW46IDRwdCAwaW47IHRleHQtYWxpZ246IGNlbnRlcjsgdGV4dC1pbmRlbnQ6IDAuNWluOyI+JiMxNjA7PC9kaXY+PC9kaXY+ICAgPHA+JiMxNjA7PC9wPg==',''),
 (22,8,'Tháº©m tra quyáº¿t toÃ¡n vá»‘n Ä‘áº§u tÆ° dá»± Ã¡n hoÃ n thÃ nh','0',60,'',1,'PGRpdiBzdHlsZT0idGV4dC1pbmRlbnQ6IDAuNWluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyI+PGRpdiBzdHlsZT0idGV4dC1hbGlnbjoganVzdGlmeTsgdGV4dC1pbmRlbnQ6IDAuNWluOyI+PHN0cm9uZz48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogJ1RpbWVzIE5ldyBSb21hbic7Ij4mIzE2MDtJLiBUaMOgbmggcGjhuqduIGjhu5Mgc8ahOjwvc3Bhbj48L3NwYW4+PC9zdHJvbmc+PC9kaXY+PGRpdiBzdHlsZT0ibWFyZ2luOiA0cHQgMGluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyI+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPiYjMTYwOy0gPC9zcGFuPjwvc3Bhbj48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+VOG7nSB0csOsbmggcGjDqiBkdXnhu4d0IHF1eeG6v3QgdG/DoW4gY+G7p2EgY2jhu6cgxJHhuqd1IHTGsC48L3NwYW4+PC9zcGFuPjwvZGl2PjxkaXY+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPiYjMTYwOy0gQsOhbyBjw6FvIHF1eeG6v3QgdG/DoW4gZOG7sSDDoW4gaG/DoG4gdGjDoG5oPC9zcGFuPjwvc3Bhbj48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+Ljwvc3Bhbj48L3NwYW4+PC9kaXY+PGRpdj48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+JiMxNjA7LSBRdXnhur90IMSR4buLbmggcGjDqiBkdXnhu4d0IGThu7Egw6FuLCB0aGnhur90IGvhur8gZOG7sSB0b8Ohbiwga+G6vyBob+G6oWNoIMSR4bqldSB0aOG6p3UsIGjhu5Mgc8ahIG3hu51pIHRo4bqndS48L3NwYW4+PC9zcGFuPjwvZGl2PjxkaXY+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiBUaW1lcyBOZXcgUm9tYW47Ij48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPiYjMTYwOy0gSOG7o3AgxJHhu5NuZyBraW5oIHThur8sIGJpw6puIGLhuqNuIHRoYW5oIGzDvSBo4bujcCDEkeG7k25nLjwvc3Bhbj48L3NwYW4+PC9kaXY+PGRpdj48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+JiMxNjA7LSBCacOqbiBi4bqjbiBuZ2hp4buHbSB0aHUgaG/DoG4gdGjDoG5oIGPDtG5nIHRyw6xuaC48L3NwYW4+PC9zcGFuPjwvZGl2PjxkaXY+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiBUaW1lcyBOZXcgUm9tYW47Ij48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPiYjMTYwOy0gR2nhuqV5IMSR4buBIG5naOG7iyB0aGFuaCB0b8OhbiB24buRbiDEkeG6p3UgdMawLCBnaeG6pXkgcsO6dCB24buRbi48L3NwYW4+PC9zcGFuPjwvZGl2PjxkaXY+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiBUaW1lcyBOZXcgUm9tYW47Ij48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPiYjMTYwOy0gSOG7kyBzxqEgaG/DoG4gY8O0bmcsIG5o4bqtdCBrw70sIGNo4bqldCBsxrDhu6NuZy48L3NwYW4+PC9zcGFuPjwvZGl2PjxkaXY+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiBUaW1lcyBOZXcgUm9tYW47Ij48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPiYjMTYwOy0gQsOhbyBjw6FvIGvhur90IHF14bqjIGtp4buDbSB0b8OhbiBxdXnhur90IHRvw6FuIGThu7Egw6FuIGhvw6BuIHRow6BuaC48L3NwYW4+PC9zcGFuPjwvZGl2PjxkaXY+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiBUaW1lcyBOZXcgUm9tYW47Ij48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPiYjMTYwOy0gS+G6v3QgbHXhuq1uIHRoYW5oIHRyYSwgYmnDqm4gYuG6o24ga2nhu4NtIHRyYSwgYsOhbyBjw6FvIGtp4buDbSB0b8Ohbi48L3NwYW4+PC9zcGFuPjwvZGl2PjxkaXYgc3R5bGU9Im1hcmdpbjogNHB0IDBpbjsgdGV4dC1hbGlnbjoganVzdGlmeTsgdGV4dC1pbmRlbnQ6IDAuNWluOyI+PHN0cm9uZz48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogJ1RpbWVzIE5ldyBSb21hbic7Ij5JSS4gU+G7kSBsxrDhu6NuZyBo4buTIHPGoTogMDEgYuG7mTwvc3Bhbj48L3NwYW4+PC9zdHJvbmc+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6ICdUaW1lcyBOZXcgUm9tYW4nOyI+Ljwvc3Bhbj48L3NwYW4+PC9kaXY+PGRpdiBzdHlsZT0ibWFyZ2luOiA0cHQgMGluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyB0ZXh0LWluZGVudDogMC41aW47Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+PHN0cm9uZz5JSUkuIFPhu5EgbmfDoHkgeOG7rSBsw70gOiA2MCA8L3N0cm9uZz5uZ8OgeTwvc3Bhbj48L3NwYW4+PC9kaXY+PGRpdiBzdHlsZT0ibWFyZ2luOiA0cHQgMGluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyB0ZXh0LWluZGVudDogMC41aW47Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+PHN0cm9uZz5JVi4gTOG7hyBwaMOtIDogPC9zdHJvbmc+PHN0cm9uZz4xMDAuMDAwPC9zdHJvbmc+IMSR4buTbmc8YnIgLz48L3NwYW4+PC9zcGFuPjwvZGl2PjxkaXYgc3R5bGU9Im1hcmdpbjogNHB0IDBpbjsgdGV4dC1hbGlnbjoganVzdGlmeTsgdGV4dC1pbmRlbnQ6IDAuNWluOyI+PHN0cm9uZz48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogJ1RpbWVzIE5ldyBSb21hbic7Ij5WLiBRdWkgdHLDrG5oIHjhu60gbMO9IDo8YnIgLz48L3NwYW4+PC9zcGFuPjwvc3Ryb25nPjwvZGl2PjxkaXYgc3R5bGU9Im1hcmdpbjogNHB0IDBpbjsgdGV4dC1hbGlnbjogY2VudGVyOyB0ZXh0LWluZGVudDogMC41aW47Ij4mIzE2MDs8L2Rpdj48L2Rpdj4gICAgPHA+JiMxNjA7PC9wPg==','');
INSERT INTO `loaihoso` (`ID_LOAIHOSO`,`ID_LINHVUC`,`TEN`,`LEPHI`,`SONGAYXULY`,`GHICHU`,`ACTIVE`,`CHITIET_HOSO`,`IMAGE_QUITRINH`) VALUES 
 (23,8,'Há»“ sÆ¡ má»i tháº§u mua sáº¯m hÃ ng hÃ³a','0',7,'',1,'PGRpdiBzdHlsZT0idGV4dC1pbmRlbnQ6IDAuNWluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyI+PGRpdiBzdHlsZT0idGV4dC1hbGlnbjoganVzdGlmeTsgdGV4dC1pbmRlbnQ6IDAuNWluOyI+PHN0cm9uZz48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogJ1RpbWVzIE5ldyBSb21hbic7Ij4mIzE2MDtJLiBUaMOgbmggcGjhuqduIGjhu5Mgc8ahOjwvc3Bhbj48L3NwYW4+PC9zdHJvbmc+PC9kaXY+PGRpdiBzdHlsZT0ibWFyZ2luOiA0cHQgMGluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyI+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPiYjMTYwOy0gPC9zcGFuPjwvc3Bhbj48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+VOG7nSB0csOsbmggxJHhu4Egbmdo4buLIHBow6ogZHV54buHdCBo4buTIHPGoSBt4budaSB0aOG6p3UuPC9zcGFuPjwvc3Bhbj48L2Rpdj48ZGl2PjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiBUaW1lcyBOZXcgUm9tYW47Ij4mIzE2MDstIFF1eeG6v3QgxJHhu4tuaCBwaMOqIGR1eeG7h3Qga+G6vyBob+G6oWNoIMSR4bqldSB0aOG6p3U8L3NwYW4+PC9zcGFuPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiBUaW1lcyBOZXcgUm9tYW47Ij4uPC9zcGFuPjwvc3Bhbj48L2Rpdj48ZGl2PjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij4mIzE2MDstIEjhu5Mgc8ahIG3hu51pIHRo4bqndSAoaOG7kyBzxqEgecOqdSBj4bqndSkuPC9zcGFuPjwvc3Bhbj48L2Rpdj48ZGl2IHN0eWxlPSJtYXJnaW46IDRwdCAwaW47IHRleHQtYWxpZ246IGp1c3RpZnk7IHRleHQtaW5kZW50OiAwLjVpbjsiPjxzdHJvbmc+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6ICdUaW1lcyBOZXcgUm9tYW4nOyI+SUkuIFPhu5EgbMaw4bujbmcgaOG7kyBzxqE6IDAxIGLhu5k8L3NwYW4+PC9zcGFuPjwvc3Ryb25nPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiAnVGltZXMgTmV3IFJvbWFuJzsiPi48L3NwYW4+PC9zcGFuPjwvZGl2PjxkaXYgc3R5bGU9Im1hcmdpbjogNHB0IDBpbjsgdGV4dC1hbGlnbjoganVzdGlmeTsgdGV4dC1pbmRlbnQ6IDAuNWluOyI+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiBUaW1lcyBOZXcgUm9tYW47Ij48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzdHJvbmc+SUlJLiBT4buRIG5nw6B5IHjhu60gbMO9IDogNyA8L3N0cm9uZz5uZ8OgeTwvc3Bhbj48L3NwYW4+PC9kaXY+PGRpdiBzdHlsZT0ibWFyZ2luOiA0cHQgMGluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyB0ZXh0LWluZGVudDogMC41aW47Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+PHN0cm9uZz5JVi4gTOG7hyBwaMOtIDogPC9zdHJvbmc+PHN0cm9uZz4xMDAuMDAwPC9zdHJvbmc+IMSR4buTbmc8YnIgLz48L3NwYW4+PC9zcGFuPjwvZGl2PjxkaXYgc3R5bGU9Im1hcmdpbjogNHB0IDBpbjsgdGV4dC1hbGlnbjoganVzdGlmeTsgdGV4dC1pbmRlbnQ6IDAuNWluOyI+PHN0cm9uZz48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogJ1RpbWVzIE5ldyBSb21hbic7Ij5WLiBRdWkgdHLDrG5oIHjhu60gbMO9IDo8YnIgLz48L3NwYW4+PC9zcGFuPjwvc3Ryb25nPjwvZGl2PjxkaXYgc3R5bGU9Im1hcmdpbjogNHB0IDBpbjsgdGV4dC1hbGlnbjogY2VudGVyOyB0ZXh0LWluZGVudDogMC41aW47Ij4mIzE2MDs8L2Rpdj48L2Rpdj4gICAgIDxwPiYjMTYwOzwvcD4=',''),
 (24,11,'ÄÄƒng kÃ½ kinh doanh há»™ cÃ¡ thá»ƒ','0',5,'',1,'PGRpdiBzdHlsZT0idGV4dC1pbmRlbnQ6IDAuNWluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyI+PGRpdiBzdHlsZT0idGV4dC1hbGlnbjoganVzdGlmeTsgdGV4dC1pbmRlbnQ6IDAuNWluOyI+PHN0cm9uZz48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogJ1RpbWVzIE5ldyBSb21hbic7Ij4mIzE2MDtJLiBUaMOgbmggcGjhuqduIGjhu5Mgc8ahOjwvc3Bhbj48L3NwYW4+PC9zdHJvbmc+PC9kaXY+PGRpdiBzdHlsZT0ibWFyZ2luOiA0cHQgMGluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyI+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPiYjMTYwOy0gPC9zcGFuPjwvc3Bhbj48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+xJDGoW4gxJHEg25nIGvDvSBraW5oIGRvYW5oLjwvc3Bhbj48L3NwYW4+PC9kaXY+PGRpdj48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+JiMxNjA7LSBC4bqjbiBzYW8gaOG7o3AgbOG7hyBjaOG7qW5nIGNo4buJIGjDoG5oIG5naOG7gTwvc3Bhbj48L3NwYW4+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPi48L3NwYW4+PC9zcGFuPjwvZGl2PjxkaXY+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiBUaW1lcyBOZXcgUm9tYW47Ij48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPiYjMTYwOy0gR2nhuqV5IHjDoWMgbmjhuq1uIG3DtGkgdHLGsOG7nW5nIHRodeG7mWMgbmfDoG5oIG5naOG7gSBraW5oIGRvYW5oLjwvc3Bhbj48L3NwYW4+PC9kaXY+PGRpdj48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+JiMxNjA7LSBC4bqjbiBzYW8gaOG7o3AgbOG7hyBjw6FjIGdp4bqleSB04budIGxpw6puIHF1YW4gxJHhur9uIHF1eeG7gW4gc+G7rSBk4bulbmcgxJHhu4thIMSRaeG7g20ga2luaCBkb2FuaCBo4bujcCBwaMOhcC48L3NwYW4+PC9zcGFuPjwvZGl2PjxkaXYgc3R5bGU9Im1hcmdpbjogNHB0IDBpbjsgdGV4dC1hbGlnbjoganVzdGlmeTsgdGV4dC1pbmRlbnQ6IDAuNWluOyI+PHN0cm9uZz48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogJ1RpbWVzIE5ldyBSb21hbic7Ij5JSS4gU+G7kSBsxrDhu6NuZyBo4buTIHPGoTogMDEgYuG7mTwvc3Bhbj48L3NwYW4+PC9zdHJvbmc+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6ICdUaW1lcyBOZXcgUm9tYW4nOyI+Ljwvc3Bhbj48L3NwYW4+PC9kaXY+PGRpdiBzdHlsZT0ibWFyZ2luOiA0cHQgMGluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyB0ZXh0LWluZGVudDogMC41aW47Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+PHN0cm9uZz5JSUkuIFPhu5EgbmfDoHkgeOG7rSBsw70gOiA1IDwvc3Ryb25nPm5nw6B5PC9zcGFuPjwvc3Bhbj48L2Rpdj48ZGl2IHN0eWxlPSJtYXJnaW46IDRwdCAwaW47IHRleHQtYWxpZ246IGp1c3RpZnk7IHRleHQtaW5kZW50OiAwLjVpbjsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3Ryb25nPklWLiBM4buHIHBow60gOiA8L3N0cm9uZz48c3Ryb25nPjEwMC4wMDA8L3N0cm9uZz4gxJHhu5NuZzxiciAvPjwvc3Bhbj48L3NwYW4+PC9kaXY+PGRpdiBzdHlsZT0ibWFyZ2luOiA0cHQgMGluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyB0ZXh0LWluZGVudDogMC41aW47Ij48c3Ryb25nPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiAnVGltZXMgTmV3IFJvbWFuJzsiPlYuIFF1aSB0csOsbmggeOG7rSBsw70gOjxiciAvPjwvc3Bhbj48L3NwYW4+PC9zdHJvbmc+PC9kaXY+PGRpdiBzdHlsZT0ibWFyZ2luOiA0cHQgMGluOyB0ZXh0LWFsaWduOiBjZW50ZXI7IHRleHQtaW5kZW50OiAwLjVpbjsiPiYjMTYwOzwvZGl2PjwvZGl2PiAgICAgIDxwPiYjMTYwOzwvcD4=','');
INSERT INTO `loaihoso` (`ID_LOAIHOSO`,`ID_LINHVUC`,`TEN`,`LEPHI`,`SONGAYXULY`,`GHICHU`,`ACTIVE`,`CHITIET_HOSO`,`IMAGE_QUITRINH`) VALUES 
 (25,11,'ÄÄƒng kÃ½ thay Ä‘á»•i ná»™i dung Ä‘Äƒng kÃ½ kinh doanh','0',5,'',1,'PGRpdiBzdHlsZT0idGV4dC1pbmRlbnQ6IDAuNWluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyI+PGRpdiBzdHlsZT0idGV4dC1hbGlnbjoganVzdGlmeTsgdGV4dC1pbmRlbnQ6IDAuNWluOyI+PHN0cm9uZz48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogJ1RpbWVzIE5ldyBSb21hbic7Ij4mIzE2MDtJLiBUaMOgbmggcGjhuqduIGjhu5Mgc8ahOjwvc3Bhbj48L3NwYW4+PC9zdHJvbmc+PC9kaXY+PGRpdiBzdHlsZT0ibWFyZ2luOiA0cHQgMGluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyI+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPiYjMTYwOy0gPC9zcGFuPjwvc3Bhbj48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+VGjDtG5nIGLDoW8gdGhheSDEkeG7lWkgbuG7mWkgZHVuZyBj4bunYSDEkcSDbmcga8O9IGtpbmggZG9hbmguPC9zcGFuPjwvc3Bhbj48L2Rpdj48ZGl2PjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiBUaW1lcyBOZXcgUm9tYW47Ij4mIzE2MDstIELhuqNuIHNhbyBo4bujcCBs4buHIGNo4bupbmcgY2jhu4kgaMOgbmggbmdo4buBIGPhu6dhIGPDoSBuaMOibiB2w6AgxJHhuqFpIGRp4buHbiBo4buZIGdpYSDEkcOsbmg8L3NwYW4+PC9zcGFuPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiBUaW1lcyBOZXcgUm9tYW47Ij4uPC9zcGFuPjwvc3Bhbj48L2Rpdj48ZGl2PjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij4mIzE2MDstIE7hu5lwIGdp4bqleSBjaOG7qW5nIG5o4bqtbiDEkcSDbmcga8O9IGtpbmggZG9hbmggxJHDoyBrw70uPC9zcGFuPjwvc3Bhbj48L2Rpdj48ZGl2IHN0eWxlPSJtYXJnaW46IDRwdCAwaW47IHRleHQtYWxpZ246IGp1c3RpZnk7IHRleHQtaW5kZW50OiAwLjVpbjsiPjxzdHJvbmc+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6ICdUaW1lcyBOZXcgUm9tYW4nOyI+SUkuIFPhu5EgbMaw4bujbmcgaOG7kyBzxqE6IDAxIGLhu5k8L3NwYW4+PC9zcGFuPjwvc3Ryb25nPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiAnVGltZXMgTmV3IFJvbWFuJzsiPi48L3NwYW4+PC9zcGFuPjwvZGl2PjxkaXYgc3R5bGU9Im1hcmdpbjogNHB0IDBpbjsgdGV4dC1hbGlnbjoganVzdGlmeTsgdGV4dC1pbmRlbnQ6IDAuNWluOyI+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiBUaW1lcyBOZXcgUm9tYW47Ij48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzdHJvbmc+SUlJLiBT4buRIG5nw6B5IHjhu60gbMO9IDogNSA8L3N0cm9uZz5uZ8OgeTwvc3Bhbj48L3NwYW4+PC9kaXY+PGRpdiBzdHlsZT0ibWFyZ2luOiA0cHQgMGluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyB0ZXh0LWluZGVudDogMC41aW47Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+PHN0cm9uZz5JVi4gTOG7hyBwaMOtIDogPC9zdHJvbmc+PHN0cm9uZz4xMDAuMDAwPC9zdHJvbmc+IMSR4buTbmc8YnIgLz48L3NwYW4+PC9zcGFuPjwvZGl2PjxkaXYgc3R5bGU9Im1hcmdpbjogNHB0IDBpbjsgdGV4dC1hbGlnbjoganVzdGlmeTsgdGV4dC1pbmRlbnQ6IDAuNWluOyI+PHN0cm9uZz48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogJ1RpbWVzIE5ldyBSb21hbic7Ij5WLiBRdWkgdHLDrG5oIHjhu60gbMO9IDo8YnIgLz48L3NwYW4+PC9zcGFuPjwvc3Ryb25nPjwvZGl2PjxkaXYgc3R5bGU9Im1hcmdpbjogNHB0IDBpbjsgdGV4dC1hbGlnbjogY2VudGVyOyB0ZXh0LWluZGVudDogMC41aW47Ij4mIzE2MDs8L2Rpdj48L2Rpdj4gICAgICAgPHA+JiMxNjA7PC9wPg==',''),
 (26,11,'ÄÄƒng kÃ½ kinh doanh Ä‘á»‘i vá»›i Há»£p tÃ¡c xÃ£','0',15,'',1,'PGRpdiBzdHlsZT0idGV4dC1pbmRlbnQ6IDAuNWluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyI+PGRpdiBzdHlsZT0idGV4dC1hbGlnbjoganVzdGlmeTsgdGV4dC1pbmRlbnQ6IDAuNWluOyI+PHN0cm9uZz48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogJ1RpbWVzIE5ldyBSb21hbic7Ij4mIzE2MDtJLiBUaMOgbmggcGjhuqduIGjhu5Mgc8ahOjwvc3Bhbj48L3NwYW4+PC9zdHJvbmc+PC9kaXY+PGRpdiBzdHlsZT0ibWFyZ2luOiA0cHQgMGluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyI+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPiYjMTYwOy0gPC9zcGFuPjwvc3Bhbj48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+xJDGoW4gxJHEg25nIGvDvSBraW5oIGRvYW5oLjwvc3Bhbj48L3NwYW4+PC9kaXY+PGRpdj48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+JiMxNjA7LSDEkGnhu4F1IGzhu4cgSOG7o3AgdMOhYyB4w6M8L3NwYW4+PC9zcGFuPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiBUaW1lcyBOZXcgUm9tYW47Ij4uPC9zcGFuPjwvc3Bhbj48L2Rpdj48ZGl2PjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij4mIzE2MDstIFPhu5EgbMaw4bujbmcgeMOjIHZpw6puLCBkYW5oIHPDoWNoIEJhbiBRdeG6o24gdHLhu4ssIELhuqNuIGtp4buDbSBzb8OhdCBI4bujcCB0w6FjIHjDoy4uPC9zcGFuPjwvc3Bhbj48L2Rpdj48ZGl2PjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij4mIzE2MDstIEJpw6puIGLhuqNuIHRow7RuZyBxdWEgSOG7mWkgbmdo4buLIHRow6BuaCBs4bqtcCBI4bujcCB0w6FjIHjDoy48L3NwYW4+PC9zcGFuPjwvZGl2PjxkaXY+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiBUaW1lcyBOZXcgUm9tYW47Ij48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPiYjMTYwOy0gxJDhu4thIGNo4buJIHRy4bulIHPhu58gbMOgbSB2aeG7h2MgY+G7p2EgSOG7o3AgdMOhYyB4w6MuPC9zcGFuPjwvc3Bhbj48L2Rpdj48ZGl2IHN0eWxlPSJtYXJnaW46IDRwdCAwaW47IHRleHQtYWxpZ246IGp1c3RpZnk7IHRleHQtaW5kZW50OiAwLjVpbjsiPjxzdHJvbmc+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6ICdUaW1lcyBOZXcgUm9tYW4nOyI+SUkuIFPhu5EgbMaw4bujbmcgaOG7kyBzxqE6IDAxIGLhu5k8L3NwYW4+PC9zcGFuPjwvc3Ryb25nPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiAnVGltZXMgTmV3IFJvbWFuJzsiPi48L3NwYW4+PC9zcGFuPjwvZGl2PjxkaXYgc3R5bGU9Im1hcmdpbjogNHB0IDBpbjsgdGV4dC1hbGlnbjoganVzdGlmeTsgdGV4dC1pbmRlbnQ6IDAuNWluOyI+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiBUaW1lcyBOZXcgUm9tYW47Ij48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzdHJvbmc+SUlJLiBT4buRIG5nw6B5IHjhu60gbMO9IDogMTUgPC9zdHJvbmc+bmfDoHk8L3NwYW4+PC9zcGFuPjwvZGl2PjxkaXYgc3R5bGU9Im1hcmdpbjogNHB0IDBpbjsgdGV4dC1hbGlnbjoganVzdGlmeTsgdGV4dC1pbmRlbnQ6IDAuNWluOyI+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiBUaW1lcyBOZXcgUm9tYW47Ij48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzdHJvbmc+SVYuIEzhu4cgcGjDrSA6IDwvc3Ryb25nPjxzdHJvbmc+MTAwLjAwMDwvc3Ryb25nPiDEkeG7k25nPGJyIC8+PC9zcGFuPjwvc3Bhbj48L2Rpdj48ZGl2IHN0eWxlPSJtYXJnaW46IDRwdCAwaW47IHRleHQtYWxpZ246IGp1c3RpZnk7IHRleHQtaW5kZW50OiAwLjVpbjsiPjxzdHJvbmc+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6ICdUaW1lcyBOZXcgUm9tYW4nOyI+Vi4gUXVpIHRyw6xuaCB44butIGzDvSA6PGJyIC8+PC9zcGFuPjwvc3Bhbj48L3N0cm9uZz48L2Rpdj48ZGl2IHN0eWxlPSJtYXJnaW46IDRwdCAwaW47IHRleHQtYWxpZ246IGNlbnRlcjsgdGV4dC1pbmRlbnQ6IDAuNWluOyI+JiMxNjA7PC9kaXY+PC9kaXY+ICAgICAgICA8cD4mIzE2MDs8L3A+','');
INSERT INTO `loaihoso` (`ID_LOAIHOSO`,`ID_LINHVUC`,`TEN`,`LEPHI`,`SONGAYXULY`,`GHICHU`,`ACTIVE`,`CHITIET_HOSO`,`IMAGE_QUITRINH`) VALUES 
 (27,12,'Thá»§ tá»¥c cáº¥p giáº¥y phÃ©p xÃ¢y dá»±ng nhÃ  á»Ÿ cho há»™ gia Ä‘Ã¬nh cÃ¡ nhÃ¢n','0',10,'',1,'PGRpdiBzdHlsZT0idGV4dC1pbmRlbnQ6IDAuNWluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyI+PGRpdiBzdHlsZT0idGV4dC1hbGlnbjoganVzdGlmeTsgdGV4dC1pbmRlbnQ6IDAuNWluOyI+PHN0cm9uZz48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogJ1RpbWVzIE5ldyBSb21hbic7Ij4mIzE2MDtJLiBUaMOgbmggcGjhuqduIGjhu5Mgc8ahOjwvc3Bhbj48L3NwYW4+PC9zdHJvbmc+PC9kaXY+PGRpdiBzdHlsZT0ibWFyZ2luOiA0cHQgMGluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyI+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPiYjMTYwOy0gPC9zcGFuPjwvc3Bhbj48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+xJDGoW4gY+G6pXAgZ2nhuqV5IHBow6lwIHjDonkgZOG7sW5nLjwvc3Bhbj48L3NwYW4+PC9kaXY+PGRpdj48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+JiMxNjA7LSBC4bqjbiBzYW8gY8O0bmcgY2jhu6luZyBt4buZdCB0cm9uZyBjw6FjIGdp4bqleSB04budIHbhu4EgcXV54buBbiBz4butIGThu6VuZyDEkeG6pXQ8L3NwYW4+PC9zcGFuPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiBUaW1lcyBOZXcgUm9tYW47Ij4uPC9zcGFuPjwvc3Bhbj48L2Rpdj48ZGl2PjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij4mIzE2MDstIELhuqNuIHbhur0gdGhp4bq/dCBr4bq/IHRo4buDIGhp4buHbiDEkcaw4bujYyB24buLIHRyw60gbeG6t3QgYuG6sW5nLCBt4bq3dCBj4bqvdCwgbeG6t3QgxJHhu6luZyBiaeG7g24gaMOsbmgsIG3hurd0IGLhurFuZyBtw7NuZyBjw7RuZyB0csOsbmgsIHPGoSDEkeG7kyB24buLIHRyw60gaG/hurdjIHR1eeG6v24gY8O0bmcgdHLDrG5oLCBzxqEgxJHhu5MgaOG7hyB0aOG7kW5nIHbDoCDEkWnhu4NtIMSR4bqldSBu4buRaSBo4buHIHRo4buRbmcgY+G6pXAgxJFp4buHbiwgY+G6pXAgbsaw4bubYyB0aG/DoXQgbsaw4bubYy48L3NwYW4+PC9zcGFuPjwvZGl2PjxkaXYgc3R5bGU9Im1hcmdpbjogNHB0IDBpbjsgdGV4dC1hbGlnbjoganVzdGlmeTsgdGV4dC1pbmRlbnQ6IDAuNWluOyI+PHN0cm9uZz48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogJ1RpbWVzIE5ldyBSb21hbic7Ij5JSS4gU+G7kSBsxrDhu6NuZyBo4buTIHPGoTogMDEgYuG7mTwvc3Bhbj48L3NwYW4+PC9zdHJvbmc+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6ICdUaW1lcyBOZXcgUm9tYW4nOyI+Ljwvc3Bhbj48L3NwYW4+PC9kaXY+PGRpdiBzdHlsZT0ibWFyZ2luOiA0cHQgMGluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyB0ZXh0LWluZGVudDogMC41aW47Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+PHN0cm9uZz5JSUkuIFPhu5EgbmfDoHkgeOG7rSBsw70gOiAxMCA8L3N0cm9uZz5uZ8OgeTwvc3Bhbj48L3NwYW4+PC9kaXY+PGRpdiBzdHlsZT0ibWFyZ2luOiA0cHQgMGluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyB0ZXh0LWluZGVudDogMC41aW47Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+PHN0cm9uZz5JVi4gTOG7hyBwaMOtIDogPC9zdHJvbmc+PHN0cm9uZz4xMDAuMDAwPC9zdHJvbmc+IMSR4buTbmc8YnIgLz48L3NwYW4+PC9zcGFuPjwvZGl2PjxkaXYgc3R5bGU9Im1hcmdpbjogNHB0IDBpbjsgdGV4dC1hbGlnbjoganVzdGlmeTsgdGV4dC1pbmRlbnQ6IDAuNWluOyI+PHN0cm9uZz48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogJ1RpbWVzIE5ldyBSb21hbic7Ij5WLiBRdWkgdHLDrG5oIHjhu60gbMO9IDo8YnIgLz48L3NwYW4+PC9zcGFuPjwvc3Ryb25nPjwvZGl2PjxkaXYgc3R5bGU9Im1hcmdpbjogNHB0IDBpbjsgdGV4dC1hbGlnbjogY2VudGVyOyB0ZXh0LWluZGVudDogMC41aW47Ij4mIzE2MDs8L2Rpdj48L2Rpdj4gICAgICAgICA8cD4mIzE2MDs8L3A+',''),
 (28,12,'Thá»§ tá»¥c cáº¥p giáº¥y phÃ©p cáº£i táº¡o, sá»­a chá»¯a, má»Ÿ rá»™ng nhÃ  á»Ÿ cho há»™ gia Ä‘Ã¬nh cÃ¡ nhÃ¢n','0',10,'',1,'PGRpdiBzdHlsZT0idGV4dC1pbmRlbnQ6IDAuNWluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyI+PGRpdiBzdHlsZT0idGV4dC1hbGlnbjoganVzdGlmeTsgdGV4dC1pbmRlbnQ6IDAuNWluOyI+PHN0cm9uZz48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogJ1RpbWVzIE5ldyBSb21hbic7Ij4mIzE2MDtJLiBUaMOgbmggcGjhuqduIGjhu5Mgc8ahOjwvc3Bhbj48L3NwYW4+PC9zdHJvbmc+PC9kaXY+PGRpdiBzdHlsZT0ibWFyZ2luOiA0cHQgMGluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyI+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPiYjMTYwOy0gPC9zcGFuPjwvc3Bhbj48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+xJDGoW4gY+G6pXAgZ2nhuqV5IHBow6lwIHjDonkgZOG7sW5nLjwvc3Bhbj48L3NwYW4+PC9kaXY+PGRpdj48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+JiMxNjA7LSBC4bqjbiBzYW8gY8O0bmcgY2jhu6luZyBt4buZdCB0cm9uZyBjw6FjIGdp4bqleSB04budIHbhu4EgcXV54buBbiBz4butIGThu6VuZyDEkeG6pXQ8L3NwYW4+PC9zcGFuPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiBUaW1lcyBOZXcgUm9tYW47Ij4uPC9zcGFuPjwvc3Bhbj48L2Rpdj48ZGl2PjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij4mIzE2MDstICBC4bqjbiB24bq9IHRoaeG6v3Qga+G6vyB0aOG7gyBoaeG7h24gxJHGsOG7o2MgduG7iyB0csOtIG3hurd0IGLhurFuZywgbeG6t3QgY+G6r3QsIG3hurd0IMSR4bupbmcgYmnhu4NuICBow6xuaCwgbeG6t3QgYuG6sW5nIG3Ds25nIGPDtG5nIHRyw6xuaCwgc8ahIMSR4buTIHbhu4sgdHLDrSBob+G6t2MgdHV54bq/biBjw7RuZyB0csOsbmgsIHPGoSAgxJHhu5MgaOG7hyB0aOG7kW5nIHbDoCDEkWnhu4NtIMSR4bqldSBu4buRaSBo4buHIHRo4buRbmcgY+G6pXAgxJFp4buHbiwgY+G6pXAgbsaw4bubYyB0aG/DoXQgbsaw4bubYy48L3NwYW4+PC9zcGFuPjwvZGl2PjxkaXY+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3BhbiBzdHlsZT0iZm9udC1mYW1pbHk6IFRpbWVzIE5ldyBSb21hbjsiPiYjMTYwOy0gPC9zcGFuPjwvc3Bhbj48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+4bqibmggY2jhu6VwIGhp4buHbiB0cuG6oW4gdHLGsOG7m2Mga2hpIGPhuqNpIHThuqFvLjwvc3Bhbj48L3NwYW4+PC9kaXY+PGRpdiBzdHlsZT0ibWFyZ2luOiA0cHQgMGluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyB0ZXh0LWluZGVudDogMC41aW47Ij48c3Ryb25nPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiAnVGltZXMgTmV3IFJvbWFuJzsiPklJLiBT4buRIGzGsOG7o25nIGjhu5Mgc8ahOiAwMSBi4buZPC9zcGFuPjwvc3Bhbj48L3N0cm9uZz48c3BhbiBzdHlsZT0iZm9udC1zaXplOiBsYXJnZTsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogJ1RpbWVzIE5ldyBSb21hbic7Ij4uPC9zcGFuPjwvc3Bhbj48L2Rpdj48ZGl2IHN0eWxlPSJtYXJnaW46IDRwdCAwaW47IHRleHQtYWxpZ246IGp1c3RpZnk7IHRleHQtaW5kZW50OiAwLjVpbjsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3Ryb25nPklJSS4gU+G7kSBuZ8OgeSB44butIGzDvSA6IDEwIDwvc3Ryb25nPm5nw6B5PC9zcGFuPjwvc3Bhbj48L2Rpdj48ZGl2IHN0eWxlPSJtYXJnaW46IDRwdCAwaW47IHRleHQtYWxpZ246IGp1c3RpZnk7IHRleHQtaW5kZW50OiAwLjVpbjsiPjxzcGFuIHN0eWxlPSJmb250LWZhbWlseTogVGltZXMgTmV3IFJvbWFuOyI+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogbGFyZ2U7Ij48c3Ryb25nPklWLiBM4buHIHBow60gOiA8L3N0cm9uZz48c3Ryb25nPjEwMC4wMDA8L3N0cm9uZz4gxJHhu5NuZzxiciAvPjwvc3Bhbj48L3NwYW4+PC9kaXY+PGRpdiBzdHlsZT0ibWFyZ2luOiA0cHQgMGluOyB0ZXh0LWFsaWduOiBqdXN0aWZ5OyB0ZXh0LWluZGVudDogMC41aW47Ij48c3Ryb25nPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IGxhcmdlOyI+PHNwYW4gc3R5bGU9ImZvbnQtZmFtaWx5OiAnVGltZXMgTmV3IFJvbWFuJzsiPlYuIFF1aSB0csOsbmggeOG7rSBsw70gOjxiciAvPjwvc3Bhbj48L3NwYW4+PC9zdHJvbmc+PC9kaXY+PGRpdiBzdHlsZT0ibWFyZ2luOiA0cHQgMGluOyB0ZXh0LWFsaWduOiBjZW50ZXI7IHRleHQtaW5kZW50OiAwLjVpbjsiPiYjMTYwOzwvZGl2PjwvZGl2PiAgICAgICAgICA8cD4mIzE2MDs8L3A+','');
/*!40000 ALTER TABLE `loaihoso` ENABLE KEYS */;


--
-- Table structure for table `tracuu_mhcu`.`qtht_actions`
--

DROP TABLE IF EXISTS `qtht_actions`;
CREATE TABLE `qtht_actions` (
  `ID_ACT` int(11) NOT NULL auto_increment,
  `ID_MOD` int(11) NOT NULL,
  `NAME` varchar(128) NOT NULL,
  `ISPUBLIC` tinyint(1) unsigned default '0',
  `ACTIVE` tinyint(1) default NULL,
  `DESCRIPTION` varchar(128) default NULL,
  PRIMARY KEY  (`ID_ACT`),
  KEY `FK_MODUELS_ACTIONS_FK` (`ID_MOD`),
  CONSTRAINT `qtht_actions_fk` FOREIGN KEY (`ID_MOD`) REFERENCES `qtht_modules` (`ID_MOD`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tracuu_mhcu`.`qtht_actions`
--

/*!40000 ALTER TABLE `qtht_actions` DISABLE KEYS */;
INSERT INTO `qtht_actions` (`ID_ACT`,`ID_MOD`,`NAME`,`ISPUBLIC`,`ACTIVE`,`DESCRIPTION`) VALUES 
 (29,23,'index',0,1,'Danh s&#225;ch'),
 (30,23,'input',0,1,'Nh&#7853;p li&#7879;u'),
 (34,33,'index',0,1,'Danh s&#225;ch'),
 (35,33,'input',0,1,'Nh&#7853;p li&#7879;u'),
 (36,33,'save',0,1,'C&#226;p nh&#7853;t'),
 (37,33,'add',NULL,1,'ThÃªm má»›i'),
 (38,33,'edit',NULL,1,'Cáº­p nháº­t'),
 (39,33,'delete',0,1,'Xo&#225;'),
 (48,42,'index',1,1,'Danh s&#225;ch'),
 (49,42,'save',1,1,'C&#226;p nh&#7853;t'),
 (52,42,'addfile',1,1,'ThÃªm má»›i file Ä‘Ã­nh kÃ¨m'),
 (53,42,'update',1,1,'Cáº­p nháº­t file Ä‘Ã­nh kÃ¨m'),
 (55,42,'input',1,1,'Nh&#7853;p li&#7879;u'),
 (57,42,'delete',1,1,'Xo&#225;'),
 (59,42,'download',1,1,'download'),
 (136,23,'save',0,1,'C&#226;p nh&#7853;t'),
 (139,23,'delete',0,1,'Xo&#225;'),
 (184,34,'index',0,1,'Danh s&#225;ch'),
 (185,34,'input',0,1,'Nh&#7853;p li&#7879;u'),
 (187,34,'save',0,1,'C&#226;p nh&#7853;t'),
 (189,34,'delete',0,1,'Xo&#225;'),
 (207,66,'changeyear',1,1,'Äá»•i nÄƒm');
INSERT INTO `qtht_actions` (`ID_ACT`,`ID_MOD`,`NAME`,`ISPUBLIC`,`ACTIVE`,`DESCRIPTION`) VALUES 
 (209,66,'delay',1,1,'Láº¥y trá»… háº¡n'),
 (211,66,'getnextstep',1,1,'Láº¥y bÆ°á»›c tiáº¿p theo trong WF'),
 (220,68,'viewbussdate',NULL,1,'Xem lá»‹ch lÃ m viá»‡c'),
 (222,68,'index',NULL,1,'Danh sÃ¡ch'),
 (223,68,'save',NULL,1,'LÆ°u cáº¥u hÃ¬nh'),
 (276,42,'attachment',1,1,'File Ä‘Ã­nh kÃ¨m cho trao Ä‘á»•i ná»™i bá»™'),
 (278,66,'changepassword',1,1,'Äá»•i máº­t kháº¥u'),
 (279,68,'nextyear',NULL,1,'NÄƒm lÃ m viá»‡c'),
 (281,66,'alarm',1,1,'alarm'),
 (282,66,'nomessage',1,1,'No Mesage'),
 (312,42,'vanthu',NULL,1,'DÃ nh cho vÄƒn thÆ°'),
 (335,66,'adddate',1,1,'ThÃªm ngÃ y'),
 (340,68,'checkdatabase',1,1,'Kiá»ƒm tra káº¿t ná»‘i database'),
 (346,66,'checksendable',1,1,'kiá»ƒm tra send'),
 (350,66,'changeacc',1,1,'Äá»•i acc'),
 (373,66,'help',1,1,'Trá»£ giÃºp'),
 (376,68,'getattactment',1,1,'Láº¥y file Ä‘Ã­nh kÃ¨m'),
 (390,66,'convert',NULL,1,'Convert');
INSERT INTO `qtht_actions` (`ID_ACT`,`ID_MOD`,`NAME`,`ISPUBLIC`,`ACTIVE`,`DESCRIPTION`) VALUES 
 (1008,68,'checkdatabaseldap',1,1,'kiem tra ket noi ldap'),
 (1020,95,'index',1,1,'Trang chá»§'),
 (1021,38,'index',1,1,'ThoÃ¡t'),
 (1022,96,'index',1,1,'Danh sÃ¡ch lÄ©nh vá»±c'),
 (1023,96,'input',1,1,'Nháº­p liá»‡u'),
 (1024,96,'save',1,1,'LÆ°u'),
 (1025,96,'delete',1,1,'XÃ³a'),
 (1026,97,'index',1,1,'Danh sÃ¡ch'),
 (1027,97,'input',1,1,'Nháº­p liá»‡u'),
 (1028,97,'save',1,1,'LÆ°u'),
 (1029,97,'delete',1,1,'XÃ³a'),
 (1030,98,'index',1,1,'Danh sÃ¡ch thá»§ tá»¥c'),
 (1031,98,'input',1,1,'Nháº­p liá»‡u thá»§ tá»¥c'),
 (1032,98,'save',1,1,'LÆ°u thá»§ tá»¥c'),
 (1033,98,'delete',1,1,'XÃ³a thá»§ tá»¥c'),
 (1034,99,'input',1,1,'Nháº­p liá»‡u resource'),
 (1035,99,'save',1,1,'LÆ°u resource'),
 (1036,96,'index',NULL,1,'Danh sÃ¡ch lÄ©nh vá»±c'),
 (1037,96,'index',NULL,1,'Danh sÃ¡ch lÄ©nh vá»±c'),
 (1038,101,'index',1,1,'Danh sÃ¡ch'),
 (1039,101,'input',1,1,'Nháº­p má»›i');
INSERT INTO `qtht_actions` (`ID_ACT`,`ID_MOD`,`NAME`,`ISPUBLIC`,`ACTIVE`,`DESCRIPTION`) VALUES 
 (1040,101,'save',1,1,'LÆ°u Láº¡i'),
 (1043,101,'delete',1,1,'XÃ³a'),
 (1044,102,'index',1,1,'danh sÃ¡ch'),
 (1045,102,'input',1,1,'Nháº­p liá»‡u'),
 (1046,102,'save',1,1,'lÆ°u'),
 (1047,99,'index',NULL,1,'Danh sÃ¡ch'),
 (1048,99,'input',NULL,1,'Nháº­p liá»‡u'),
 (1049,99,'delete',NULL,1,'XÃ³a'),
 (1050,99,'save',NULL,1,'LÆ°u');
/*!40000 ALTER TABLE `qtht_actions` ENABLE KEYS */;


--
-- Table structure for table `tracuu_mhcu`.`qtht_groupmodules`
--

DROP TABLE IF EXISTS `qtht_groupmodules`;
CREATE TABLE `qtht_groupmodules` (
  `ID_GMOD` int(11) NOT NULL auto_increment,
  `NAME` varchar(128) default NULL,
  PRIMARY KEY  (`ID_GMOD`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tracuu_mhcu`.`qtht_groupmodules`
--

/*!40000 ALTER TABLE `qtht_groupmodules` DISABLE KEYS */;
INSERT INTO `qtht_groupmodules` (`ID_GMOD`,`NAME`) VALUES 
 (1,'H&#7879; th&#7889;ng'),
 (2,'C&#244;ng vi&#7879;c'),
 (3,'H&#7891; s&#417; c&#244;ng vi&#7879;c'),
 (4,'V&#259;n b&#7843;n &#273;&#7871;n'),
 (5,'V&#259;n b&#7843;n &#273;i'),
 (6,'H&#7891; s&#417; m&#7897;t c&#7917;a'),
 (7,'B&#225;o c&#225;o'),
 (8,'L&#7883;ch c&#244;ng t&#225;c'),
 (9,'Quy tr&#236;nh &#273;i&#7879;n t&#7917;'),
 (10,'L&#7883;c c&#244;ng t&#225;c (v2)'),
 (11,'Van ban mail'),
 (12,'T&#224;i li&#7879;u ISO');
/*!40000 ALTER TABLE `qtht_groupmodules` ENABLE KEYS */;


--
-- Table structure for table `tracuu_mhcu`.`qtht_groups`
--

DROP TABLE IF EXISTS `qtht_groups`;
CREATE TABLE `qtht_groups` (
  `ID_G` int(11) NOT NULL auto_increment,
  `NAME` varchar(128) default NULL,
  `ACTIVE` tinyint(1) default NULL,
  `ID_U_DAIDIEN` int(11) default NULL,
  `CODE` varchar(3) default NULL,
  `ORDERS` int(11) default NULL,
  PRIMARY KEY  (`ID_G`),
  KEY `ID_U_DAIDIEN` (`ID_U_DAIDIEN`),
  KEY `CODE` (`CODE`),
  KEY `ACTIVE` (`ACTIVE`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tracuu_mhcu`.`qtht_groups`
--

/*!40000 ALTER TABLE `qtht_groups` DISABLE KEYS */;
INSERT INTO `qtht_groups` (`ID_G`,`NAME`,`ACTIVE`,`ID_U_DAIDIEN`,`CODE`,`ORDERS`) VALUES 
 (1,'Tá»• tiáº¿p nháº­n vÃ  tráº£ há»“ sÆ¡',1,NULL,'NMC',6),
 (2,'LÃ£nh Ä‘áº¡o cÆ¡ quan',1,NULL,'NLD',1),
 (3,'Quáº£n trá»‹ há»‡ thá»‘ng',1,NULL,'NQT',8),
 (6,'ChuyÃªn viÃªn',1,NULL,'NCV',4),
 (7,'VÄƒn thÆ°',1,NULL,'NVT',5),
 (8,'LÃ£nh Ä‘áº¡o phÃ²ng',1,NULL,'LDP',3),
 (9,'LÃ£nh Ä‘áº¡o vÄƒn phÃ²ng',1,NULL,'LVP',2),
 (10,'LÆ°u trá»¯',1,NULL,NULL,7);
/*!40000 ALTER TABLE `qtht_groups` ENABLE KEYS */;


--
-- Table structure for table `tracuu_mhcu`.`qtht_menus`
--

DROP TABLE IF EXISTS `qtht_menus`;
CREATE TABLE `qtht_menus` (
  `ID_MNU` int(11) NOT NULL auto_increment,
  `ID_MNU_PARENT` int(11) default NULL,
  `NAME` varchar(128) default NULL,
  `SCRIPT` varchar(100) default NULL,
  `POPUP` tinyint(1) default NULL,
  `WIDTH` int(11) default NULL,
  `HEIGHT` int(11) default NULL,
  `TOP` int(11) default NULL,
  `XLEFT` int(11) default NULL,
  `ISCENTER` tinyint(1) default NULL,
  `URL` varchar(100) default NULL,
  `ACTID` int(11) default NULL,
  `ISLASTMENU` tinyint(1) default NULL,
  `ICON` varchar(128) default NULL,
  `ORDERS` int(11) default NULL,
  PRIMARY KEY  (`ID_MNU`),
  KEY `FK_ACTIONS_MENUS_FK` (`ACTID`),
  KEY `FK_MENUS_OWNER_FK` (`ID_MNU_PARENT`),
  CONSTRAINT `qtht_menus_fk` FOREIGN KEY (`ID_MNU_PARENT`) REFERENCES `qtht_menus` (`ID_MNU`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `qtht_menus_fk1` FOREIGN KEY (`ACTID`) REFERENCES `qtht_actions` (`ID_ACT`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tracuu_mhcu`.`qtht_menus`
--

/*!40000 ALTER TABLE `qtht_menus` DISABLE KEYS */;
INSERT INTO `qtht_menus` (`ID_MNU`,`ID_MNU_PARENT`,`NAME`,`SCRIPT`,`POPUP`,`WIDTH`,`HEIGHT`,`TOP`,`XLEFT`,`ISCENTER`,`URL`,`ACTID`,`ISLASTMENU`,`ICON`,`ORDERS`) VALUES 
 (1,NULL,'Root',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 (63,1,'Há»‡ thá»‘ng','',0,NULL,NULL,NULL,NULL,0,'',NULL,0,'',2),
 (101,63,'Cáº¥u hÃ¬nh há»‡ thá»‘ng','',0,NULL,NULL,NULL,NULL,0,'',222,0,'',1),
 (115,1,'Danh má»¥c','',0,NULL,NULL,NULL,NULL,0,'',NULL,0,'',3),
 (134,1,'Trang chá»§','',0,NULL,NULL,NULL,NULL,0,'',1020,0,'',1),
 (135,115,'Danh má»¥c lÄ©nh vá»±c','',0,NULL,NULL,NULL,NULL,0,'',1022,0,'',1),
 (136,115,'Danh má»¥c loáº¡i há»“ sÆ¡','',0,NULL,NULL,NULL,NULL,0,'',1026,0,'',2),
 (137,115,'Lá»i giá»›i thiá»‡u','',0,NULL,NULL,NULL,NULL,0,'',1045,0,'',3);
/*!40000 ALTER TABLE `qtht_menus` ENABLE KEYS */;


--
-- Table structure for table `tracuu_mhcu`.`qtht_modules`
--

DROP TABLE IF EXISTS `qtht_modules`;
CREATE TABLE `qtht_modules` (
  `ID_MOD` int(11) NOT NULL auto_increment,
  `NAME` varchar(128) default NULL,
  `ACTIVE` tinyint(1) default NULL,
  `ISPUBLIC` tinyint(1) default NULL,
  `URL` varchar(128) default NULL,
  `ID_GMOD` int(11) default NULL,
  PRIMARY KEY  (`ID_MOD`),
  KEY `ID_GMOD` (`ID_GMOD`),
  CONSTRAINT `qtht_modules_fk` FOREIGN KEY (`ID_GMOD`) REFERENCES `qtht_groupmodules` (`ID_GMOD`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tracuu_mhcu`.`qtht_modules`
--

/*!40000 ALTER TABLE `qtht_modules` DISABLE KEYS */;
INSERT INTO `qtht_modules` (`ID_MOD`,`NAME`,`ACTIVE`,`ISPUBLIC`,`URL`,`ID_GMOD`) VALUES 
 (23,'Quáº£n trá»‹ action',1,0,'/qtht/actions/',1),
 (32,'Danh m&#7909;c nh&#243;m',1,0,'/qtht/groups/',1),
 (33,'Danh m&#7909;c menu',1,0,'/qtht/menus/',1),
 (34,'Danh m&#7909;c module',1,0,'/qtht/modules/',1),
 (35,'Danh m&#7909;c ng&#432;&#7901;i d&#249;ng',1,0,'/qtht/danhmucnguoidung/',1),
 (36,'Nh&#7853;t k&#253; h&#7879; th&#7889;ng',1,0,'/auth/log/',1),
 (37,'&#272;&#259;ng nh&#7853;p',1,1,'/auth/login/',1),
 (38,'Tho&#225;t',1,1,'/auth/logout/',1),
 (39,'Th&#244;ng b&#225;o l&#7895;i',1,1,'/default/error/',1),
 (42,'File &#273;&#237;nh k&#232;m',1,1,'/hscv/file/',3),
 (66,'Auth Public',1,1,'/auth/user/',1),
 (68,'Cáº¥u hÃ¬nh há»‡ thá»‘ng',1,0,'/qtht/config/',1),
 (95,'Trang chá»§',1,1,'/default/index/',NULL),
 (96,'Danh má»¥c lÄ©nh vá»±c',1,1,'/danhmuc/linhvuc/',NULL),
 (97,'Danh má»¥c loáº¡i há»“ sÆ¡',1,1,'/danhmuc/loaihoso/',NULL),
 (98,'Danh má»¥c thá»§ tá»¥c',1,1,'/danhmuc/thutuc/',NULL),
 (99,'Quáº£n lÃ½ Resource',1,1,'/danhmuc/resource/',NULL);
INSERT INTO `qtht_modules` (`ID_MOD`,`NAME`,`ACTIVE`,`ISPUBLIC`,`URL`,`ID_GMOD`) VALUES 
 (101,'Danh Má»¥c Qui TrÃ¬nh',1,1,'/danhmuc/quitrinh/',NULL),
 (102,'Giá»›i thiá»‡u',1,1,'/danhmuc/gioithieu/',NULL);
/*!40000 ALTER TABLE `qtht_modules` ENABLE KEYS */;


--
-- Table structure for table `tracuu_mhcu`.`qtht_multiaccount`
--

DROP TABLE IF EXISTS `qtht_multiaccount`;
CREATE TABLE `qtht_multiaccount` (
  `ID_MTACC` int(11) NOT NULL auto_increment,
  `ID_U` int(11) default NULL,
  `ID_U_UQ` int(11) default NULL,
  PRIMARY KEY  (`ID_MTACC`),
  KEY `ID_U` (`ID_U`),
  KEY `ID_U_UQ` (`ID_U_UQ`),
  CONSTRAINT `qtht_multiaccount_fk` FOREIGN KEY (`ID_U`) REFERENCES `qtht_users` (`ID_U`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `qtht_multiaccount_fk1` FOREIGN KEY (`ID_U_UQ`) REFERENCES `qtht_users` (`ID_U`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tracuu_mhcu`.`qtht_multiaccount`
--

/*!40000 ALTER TABLE `qtht_multiaccount` DISABLE KEYS */;
/*!40000 ALTER TABLE `qtht_multiaccount` ENABLE KEYS */;


--
-- Table structure for table `tracuu_mhcu`.`qtht_users`
--

DROP TABLE IF EXISTS `qtht_users`;
CREATE TABLE `qtht_users` (
  `ID_U` int(11) NOT NULL auto_increment,
  `ID_EMP` int(11) default NULL,
  `USERNAME` varchar(30) default NULL,
  `PASSWORD` varchar(50) default NULL,
  `ACTIVE` tinyint(1) default NULL,
  `ISLEADER` tinyint(4) default NULL,
  `ORDERS` int(11) default NULL,
  `TYPE_LDAPPASS` varchar(10) NOT NULL default 'MD5',
  `PHONE` varchar(20) default NULL,
  PRIMARY KEY  (`ID_U`),
  UNIQUE KEY `USERNAME` (`USERNAME`),
  KEY `ID_EMP` (`ID_EMP`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tracuu_mhcu`.`qtht_users`
--

/*!40000 ALTER TABLE `qtht_users` DISABLE KEYS */;
INSERT INTO `qtht_users` (`ID_U`,`ID_EMP`,`USERNAME`,`PASSWORD`,`ACTIVE`,`ISLEADER`,`ORDERS`,`TYPE_LDAPPASS`,`PHONE`) VALUES 
 (139,162,'administrator','e10adc3949ba59abbe56e057f20f883e',1,1,8,'MD5',NULL);
/*!40000 ALTER TABLE `qtht_users` ENABLE KEYS */;


--
-- Table structure for table `tracuu_mhcu`.`quitrinh`
--

DROP TABLE IF EXISTS `quitrinh`;
CREATE TABLE `quitrinh` (
  `ID_QUITRINH` int(11) NOT NULL auto_increment,
  `TEN` varchar(1024) default NULL,
  `ID_RESOURCE` int(11) default NULL,
  `GHICHU` text,
  `ACTIVE` tinyint(1) default NULL,
  PRIMARY KEY  (`ID_QUITRINH`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tracuu_mhcu`.`quitrinh`
--

/*!40000 ALTER TABLE `quitrinh` DISABLE KEYS */;
/*!40000 ALTER TABLE `quitrinh` ENABLE KEYS */;


--
-- Table structure for table `tracuu_mhcu`.`resource`
--

DROP TABLE IF EXISTS `resource`;
CREATE TABLE `resource` (
  `ID_RESOURCE` int(11) NOT NULL auto_increment,
  `TEN` varchar(1024) default NULL,
  `ID_FILE` int(11) default NULL,
  `DATA` blob,
  `DATA_CONTENT` varchar(1024) default NULL,
  PRIMARY KEY  (`ID_RESOURCE`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tracuu_mhcu`.`resource`
--

/*!40000 ALTER TABLE `resource` DISABLE KEYS */;
/*!40000 ALTER TABLE `resource` ENABLE KEYS */;


--
-- Table structure for table `tracuu_mhcu`.`thutuc`
--

DROP TABLE IF EXISTS `thutuc`;
CREATE TABLE `thutuc` (
  `ID_THUTUC` int(11) NOT NULL auto_increment,
  `TEN` varchar(1024) default NULL,
  `ACTIVE` tinyint(1) default NULL,
  `GHICHU` text,
  `ID_RESOURCE` int(11) default NULL,
  PRIMARY KEY  (`ID_THUTUC`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tracuu_mhcu`.`thutuc`
--

/*!40000 ALTER TABLE `thutuc` DISABLE KEYS */;
/*!40000 ALTER TABLE `thutuc` ENABLE KEYS */;


--
-- Table structure for table `tracuu_mhcu`.`vanbanlienquan`
--

DROP TABLE IF EXISTS `vanbanlienquan`;
CREATE TABLE `vanbanlienquan` (
  `ID_VANBANLIENQUAN` int(11) NOT NULL auto_increment,
  `TEN` varchar(1024) default NULL,
  `ID_RESOURCE` int(11) default NULL,
  PRIMARY KEY  (`ID_VANBANLIENQUAN`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tracuu_mhcu`.`vanbanlienquan`
--

/*!40000 ALTER TABLE `vanbanlienquan` DISABLE KEYS */;
/*!40000 ALTER TABLE `vanbanlienquan` ENABLE KEYS */;


--
-- View structure for view `tracuu_mhcu`.`view_menus`
--

DROP VIEW IF EXISTS `view_menus`;
CREATE VIEW `tracuu_mhcu`.`view_menus` AS select `mn`.`ID_MNU` AS `ID_MNU`,`mn`.`ID_MNU_PARENT` AS `ID_MNU_PARENT`,`mn`.`NAME` AS `NAME`,`mn`.`SCRIPT` AS `SCRIPT`,`mn`.`POPUP` AS `POPUP`,`mn`.`WIDTH` AS `WIDTH`,`mn`.`HEIGHT` AS `HEIGHT`,`mn`.`TOP` AS `TOP`,`mn`.`XLEFT` AS `XLEFT`,`mn`.`ISCENTER` AS `ISCENTER`,`mn`.`URL` AS `URL`,`mn`.`ACTID` AS `ACTID`,`mn`.`ISLASTMENU` AS `ISLASTMENU`,`mn`.`ICON` AS `ICON`,concat(`md`.`URL`,`ac`.`NAME`) AS `URL_ACTION`,`mn`.`ORDERS` AS `ORDERS` from ((`tracuu_mhcu`.`qtht_menus` `mn` left join `tracuu_mhcu`.`qtht_actions` `ac` on((`ac`.`ID_ACT` = `mn`.`ACTID`))) left join `tracuu_mhcu`.`qtht_modules` `md` on((`md`.`ID_MOD` = `ac`.`ID_MOD`))) order by `mn`.`ORDERS`;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
