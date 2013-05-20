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
-- Create schema qlvbdh_adapter
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ qlvbdh_adapter;
USE qlvbdh_adapter;

--
-- Table structure for table `qlvbdh_adapter`.`adapter_files`
--

DROP TABLE IF EXISTS `adapter_files`;
CREATE TABLE `adapter_files` (
  `ID_FILE` int(11) NOT NULL auto_increment,
  `ID_LUUTRU` int(11) default NULL,
  `ID_PART` varchar(64) collate utf8_unicode_ci default NULL,
  `PART_NUMBER` int(11) default NULL,
  `MAHOSO` varchar(100) collate utf8_unicode_ci default NULL,
  `CONTENT` longtext collate utf8_unicode_ci,
  `IS_GET` tinyint(4) default NULL,
  `NAME` varchar(128) collate utf8_unicode_ci default NULL,
  `MIME` varchar(100) collate utf8_unicode_ci default NULL,
  `SIZE` varchar(128) collate utf8_unicode_ci default NULL,
  `SEND_DATE` datetime default NULL,
  PRIMARY KEY  (`ID_FILE`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `qlvbdh_adapter`.`adapter_files`
--

/*!40000 ALTER TABLE `adapter_files` DISABLE KEYS */;
/*!40000 ALTER TABLE `adapter_files` ENABLE KEYS */;


--
-- Table structure for table `qlvbdh_adapter`.`adapter_loaihoso`
--

DROP TABLE IF EXISTS `adapter_loaihoso`;
CREATE TABLE `adapter_loaihoso` (
  `ID_LOAIHOSO` int(11) NOT NULL auto_increment,
  `TENLOAIHOSO` varchar(512) character set utf8 collate utf8_unicode_ci default NULL,
  `MALOAIHOSO` varchar(20) default NULL,
  PRIMARY KEY  (`ID_LOAIHOSO`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `qlvbdh_adapter`.`adapter_loaihoso`
--

/*!40000 ALTER TABLE `adapter_loaihoso` DISABLE KEYS */;
/*!40000 ALTER TABLE `adapter_loaihoso` ENABLE KEYS */;


--
-- Table structure for table `qlvbdh_adapter`.`adapter_luutru`
--

DROP TABLE IF EXISTS `adapter_luutru`;
CREATE TABLE `adapter_luutru` (
  `ID_LUUTRU` int(11) NOT NULL auto_increment,
  `SOURCEXML` text,
  `MADICHVU` varchar(50) default NULL,
  `MAHOSO` varchar(50) default NULL,
  `IS_GET` int(1) default NULL,
  `MAHETHONG` varchar(50) default NULL,
  PRIMARY KEY  (`ID_LUUTRU`),
  KEY `MAHOSO` (`MAHOSO`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `qlvbdh_adapter`.`adapter_luutru`
--

/*!40000 ALTER TABLE `adapter_luutru` DISABLE KEYS */;
/*!40000 ALTER TABLE `adapter_luutru` ENABLE KEYS */;


--
-- Table structure for table `qlvbdh_adapter`.`adapter_luutrudanhmuc`
--

DROP TABLE IF EXISTS `adapter_luutrudanhmuc`;
CREATE TABLE `adapter_luutrudanhmuc` (
  `ID_LTDM` int(11) NOT NULL auto_increment,
  `CODE` varchar(128) default NULL,
  `DATA` longtext,
  `IS_SYNCHRONOUS` int(1) default NULL,
  PRIMARY KEY  (`ID_LTDM`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `qlvbdh_adapter`.`adapter_luutrudanhmuc`
--

/*!40000 ALTER TABLE `adapter_luutrudanhmuc` DISABLE KEYS */;
/*!40000 ALTER TABLE `adapter_luutrudanhmuc` ENABLE KEYS */;


--
-- Table structure for table `qlvbdh_adapter`.`adapter_trangthaihoso`
--

DROP TABLE IF EXISTS `adapter_trangthaihoso`;
CREATE TABLE `adapter_trangthaihoso` (
  `ID_TT` int(11) NOT NULL auto_increment,
  `MASOHOSO` varchar(20) default NULL,
  `TENTOCHUCCANHAN` varchar(256) default NULL,
  `TENHOSO` varchar(1024) default NULL,
  `TRANGTHAI` tinyint(4) default NULL,
  `GHICHU` varchar(1024) default NULL,
  `DIENTHOAI` varchar(20) default NULL,
  `BARCODE` varchar(10) default NULL,
  `NGAYNOP` datetime default NULL,
  `NGAYNHAN` datetime default NULL,
  `PHONG` varchar(256) character set utf8 collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (`ID_TT`),
  KEY `MASOHOSO` (`MASOHOSO`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `qlvbdh_adapter`.`adapter_trangthaihoso`
--

/*!40000 ALTER TABLE `adapter_trangthaihoso` DISABLE KEYS */;
INSERT INTO `adapter_trangthaihoso` (`ID_TT`,`MASOHOSO`,`TENTOCHUCCANHAN`,`TENHOSO`,`TRANGTHAI`,`GHICHU`,`DIENTHOAI`,`BARCODE`,`NGAYNOP`,`PHONG`) VALUES 
 (1,'000112000005','CONG TY A','TEN HO SO 3A',3,'ghichu C C','090986786','','2012-09-29 00:00:00','VÄƒn phÃ²ng');
/*!40000 ALTER TABLE `adapter_trangthaihoso` ENABLE KEYS */;

--
-- Table structure for table `qlvbdh_adapter`.`adapter_loai_hoso`
--

CREATE TABLE `adapter_loai_hoso` (
  `ID_ALH` int(11) NOT NULL auto_increment,
  `ID_LOAIHOSO` int(11) default NULL,
  `TENLOAI` varchar(1024) NOT NULL,
  `SONGAYXULY` int(11) NOT NULL,
  `LEPHI` int(11) default NULL,
  `CHUTHICH` varchar(2024) default NULL,
  `CODE` varchar(20) default NULL,
  `ID_LOAIHSCV` int(11) default NULL,
  `ID_LV_MC` int(11) default NULL,
  `IS_DONGBO` tinyint(1) default NULL,
  `ID_ONWEBSITE` int(11) default NULL,
  `IS_UPDATE` tinyint(1) default NULL,
  `SAPTRE` tinyint(2) default '3',
  PRIMARY KEY  (`ID_ALH`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for table `qlvbdh_adapter`.`adapter_users`
--

DROP TABLE IF EXISTS `adapter_users`;
CREATE TABLE `adapter_users` (
  `ID_U` int(11) NOT NULL auto_increment,
  `USERNAME` varchar(20) default NULL,
  `PASSWORD` varchar(512) default NULL,
  `SYSTEMNAME` varchar(1024) default NULL,
  `ROLE` varchar(64) default NULL,
  PRIMARY KEY  (`ID_U`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `qlvbdh_adapter`.`adapter_users`
--

/*!40000 ALTER TABLE `adapter_users` DISABLE KEYS */;
INSERT INTO `adapter_users` (`ID_U`,`USERNAME`,`PASSWORD`,`SYSTEMNAME`,`ROLE`) VALUES 
 (1,'qlvbdh','a22e32f1c3a16391e05e50391d7ad912',NULL,'QLVBDH@f1'),
 (2,'client','a22e32f1c3a16391e05e50391d7ad912',NULL,'client1');
/*!40000 ALTER TABLE `adapter_users` ENABLE KEYS */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
