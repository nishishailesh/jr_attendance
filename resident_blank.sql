-- MariaDB dump 10.19  Distrib 10.5.15-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: 127.0.0.1    Database: resident
-- ------------------------------------------------------
-- Server version	10.5.15-MariaDB-0+deb11u1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `attendance`
--

DROP TABLE IF EXISTS `attendance`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `attendance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `resident_id` int(11) DEFAULT NULL,
  `month` int(11) DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  `present_days` int(11) DEFAULT NULL,
  `leave_without_pay` int(11) DEFAULT NULL,
  `sick_leave` int(11) DEFAULT NULL,
  `casual_leave` int(11) DEFAULT NULL,
  `weekly_off` int(11) DEFAULT NULL,
  `other_paid_leave` int(11) DEFAULT NULL,
  `remark` varchar(100) DEFAULT NULL,
  `final_submit` int(11) DEFAULT NULL,
  `recorded_by` varchar(50) DEFAULT NULL,
  `recording_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `resident_id_month_year` (`resident_id`,`month`,`year`),
  KEY `resident_id` (`resident_id`),
  CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`resident_id`) REFERENCES `resident` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=366 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `attendance`
--

LOCK TABLES `attendance` WRITE;
/*!40000 ALTER TABLE `attendance` DISABLE KEYS */;
INSERT INTO `attendance` VALUES (362,1,9,2022,26,0,0,0,4,0,'',1,'anaesthesiology','2022-09-16 12:51:26'),(364,2,9,2022,25,0,0,1,4,0,'',1,'anaesthesiology','2022-09-16 13:00:49'),(365,4,9,2022,0,0,0,0,0,0,'',1,'anaesthesiology','2022-09-16 13:06:07');
/*!40000 ALTER TABLE `attendance` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `record_tables`
--

DROP TABLE IF EXISTS `record_tables`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `record_tables` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `table_name` varchar(100) DEFAULT NULL,
  `level` int(11) DEFAULT NULL,
  `recording_time` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `recorded_by` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `record_tables`
--

LOCK TABLES `record_tables` WRITE;
/*!40000 ALTER TABLE `record_tables` DISABLE KEYS */;
INSERT INTO `record_tables` VALUES (62,'resident',1,'2022-08-27 00:10:05',NULL),(64,'attendance',0,'2022-08-26 23:09:20',NULL),(65,'month',1,'2022-08-26 23:09:20',NULL),(66,'year',1,'2022-08-26 23:09:20',NULL);
/*!40000 ALTER TABLE `record_tables` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `resident`
--

DROP TABLE IF EXISTS `resident`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `resident` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `aadhar` bigint(20) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `department` varchar(100) DEFAULT NULL,
  `date_of_joining` date DEFAULT NULL,
  `duration` int(11) DEFAULT NULL,
  `recorded_by` varchar(50) DEFAULT NULL,
  `recording_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `aadhar` (`aadhar`)
) ENGINE=InnoDB AUTO_INCREMENT=18446744073709551615 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `resident`
--

LOCK TABLES `resident` WRITE;
/*!40000 ALTER TABLE `resident` DISABLE KEYS */;
INSERT INTO `resident` VALUES (1,652201283694,'DR. ANDREWS M','anaesthesiology','2022-05-17',1,'3','2022-09-16 12:04:14'),(2,653858719298,'SRIDHAR M','anaesthesiology','2022-05-17',1,'3','2022-09-16 12:04:14'),(3,342948129643,'DR. KAKDIYA KAJALBEN BHARATBHAI','anaesthesiology','2022-05-14',1,'3','2022-09-16 12:04:14'),(4,323776763799,'DR. RIYA YOHANNAN','anaesthesiology','2022-05-14',1,'3','2022-09-16 12:04:14'),(5,515153849811,'DR. SHAMBHAVI VERMA','community_medicine','2022-05-13',1,'3','2022-09-16 12:04:14'),(6,394226531599,'DR. ABHISHEK MUKHERJEE','community_medicine','2022-05-17',1,'3','2022-09-16 12:04:14'),(7,789258116367,'DR. CHAUHAN DHWANI NAVIN','community_medicine','2022-05-14',1,'3','2022-09-16 12:04:14'),(8,358536457423,'DR. ENGTI RAKTIM','community_medicine','2022-05-13',1,'3','2022-09-16 12:04:14'),(9,707672567397,'DR. BODAR KAVITABEN GHANSHYAMBHAI','community_medicine','2022-05-13',1,'3','2022-09-16 12:04:14'),(10,924062214350,'DR. HADIYA KHUSHBOO PRAVINBHAI','community_medicine','2022-05-14',1,'3','2022-09-16 12:04:14'),(11,258566740391,'DR. PATEL GEETABEN KANJIBHAI','community_medicine','2022-05-13',1,'3','2022-09-16 12:04:14'),(12,402158593151,'DR. BHAVSAR KHUSHI NARENDRABHAI','ent','2022-05-16',1,'3','2022-09-16 12:04:14'),(13,544950298767,'DR. CHAUHAN JAHANVIKA MANUBHAI','ent','2022-05-12',1,'3','2022-09-16 12:04:14'),(14,570756299915,'DR. TANK PARTH HEMRAJBHAI','general_medicine','2022-05-19',1,'3','2022-09-16 12:04:14'),(15,479161602523,'DR. PATEL CHIRAG DEVJIBHAI','general_medicine','2022-05-13',1,'3','2022-09-16 12:04:14'),(16,701935912519,'DR. DILAN DAVIS','general_medicine','2022-05-19',1,'3','2022-09-16 12:04:14'),(17,624925473317,'DR. CHAUDHARI TVISHA PIYUSHBHAI','general_medicine','2022-05-13',1,'3','2022-09-16 12:04:14'),(18,374044703684,'DR. KISHAN','general_medicine','2022-05-13',1,'3','2022-09-16 12:04:14'),(19,690229075941,'DR. NAVEEN K.','general_medicine','2022-05-21',1,'3','2022-09-16 12:04:14'),(20,204214916759,'DR. SANDIPKUMAR MANILAL PATEL','general_medicine','2022-08-26',1,'3','2022-09-16 12:04:14'),(21,689368703655,'DR. AKSH KALPESHBHAI PATEL','general_medicine','2022-08-27',1,'3','2022-09-16 12:04:14'),(22,551759017321,'DR. NEHA VERMA','general_medicine','2022-08-30',1,'3','2022-09-16 12:04:14'),(23,910772774177,'DR. VASAVA HIRENKUMAR DINESHBHAI','general_surgery','2022-05-12',1,'3','2022-09-16 12:04:14'),(24,660633782875,'DR. VASAVADA DHAIRAV GAURANG','general_surgery','2022-05-12',1,'3','2022-09-16 12:04:14'),(25,693036100265,'DR. PATEL YASH DURLABHJIBHAI','general_surgery','2022-05-12',1,'3','2022-09-16 12:04:14'),(26,641620102304,'DR. AHIR TUSHARKUMAR MANJIBHAI','general_surgery','2022-05-12',1,'3','2022-09-16 12:04:14'),(27,634791266543,'DR. PULLURU BALA SUSHEETH KUMAR','general_surgery','2022-05-12',1,'3','2022-09-16 12:04:14'),(28,577406645183,'DR. BHARGAV D PATEL','general_surgery','2022-05-18',1,'3','2022-09-16 12:04:14'),(29,996477982816,'DR. BADAMI KHUSHBU ARVINDLAL','general_surgery','2022-05-12',1,'3','2022-09-16 12:04:14'),(30,757379802484,'DR. MEHTA ANUJ RANJIT','general_surgery','2022-05-12',1,'3','2022-09-16 12:04:14'),(31,633152706514,'DR. PADVI TUSHARKUMAR BALUBHAI','general_surgery','2022-05-12',1,'3','2022-09-16 12:04:14'),(32,977981980956,'DR. MODH RUTVI RAJESHKUMAR','ihbt','2022-05-12',1,'3','2022-09-16 12:04:14'),(33,522019041352,'DR. SUTARIYA MIRAL RAMESHBHAI','obst._and_gynae','2022-05-16',1,'3','2022-09-16 12:04:14'),(34,299815622803,'DR. MODI SAKSHI SANJAY','obst._and_gynae','2022-05-16',1,'3','2022-09-16 12:04:14'),(35,819932309220,'DR. TAILOR SHRUTI KISHORKUMAR','obst._and_gynae','2022-05-16',1,'3','2022-09-16 12:04:14'),(36,970695727893,'DR. GANDHI VRUNDABEN VIJAYKUMAR','obst._and_gynae','2022-05-16',1,'3','2022-09-16 12:04:14'),(37,393157590726,'DR. BHOYA DIPIKAKUMARI NAVINBHAI','obst._and_gynae','2022-05-19',1,'3','2022-09-16 12:04:14'),(38,612100273150,'DR. THAKOR RUTVIBEN RAJESHKUMAR','obst._and_gynae','2022-05-16',1,'3','2022-09-16 12:04:14'),(39,274914373253,'DR. PRAJAPATI NEHA VINODBHAI','obst._and_gynae','2022-05-19',1,'3','2022-09-16 12:04:14'),(40,916941013258,'DR. SATANI CHANDNIBEN SURESHBHAI','ophthalmology','2022-05-19',1,'3','2022-09-16 12:04:14'),(41,230909879966,'RAHUL NATWARLAL PATEL','orthopedics','2021-10-28',1,'3','2022-09-16 12:04:14'),(42,377186419254,'DIPNESH RATHWA','orthopedics','2021-10-28',1,'3','2022-09-16 12:04:14'),(43,876364279022,'DR. LOVISH GULBADHAR','orthopedics','2022-05-18',1,'3','2022-09-16 12:04:14'),(44,482600473153,'DR. MEHTA YASH MANISH','orthopedics','2022-05-16',1,'3','2022-09-16 12:04:14'),(45,942176480004,'DR. PATEL RISHI PARESH','orthopedics','2022-05-16',1,'3','2022-09-16 12:04:14'),(46,457471462285,'DR. PATEL ANIKETKUMAR RAMANBHAI','orthopedics','2022-05-16',1,'3','2022-09-16 12:04:14'),(47,387590130254,'DR. UMRETHIUA VIREN ATULBHAI','orthopedics','2022-05-16',1,'3','2022-09-16 12:04:14'),(48,464547204560,'DR. UNIYA MISSAMRAZA SADIQALI','orthopedics','2022-05-16',1,'3','2022-09-16 12:04:14'),(49,717368525992,'DR. SHASTRI ZARNA MAYANKKUMAR','paediatric','2022-05-17',1,'3','2022-09-16 12:04:14'),(50,614980389610,'DR. PATEL KHUSHBHUKUMARI GIRISHCHANDRA','paediatric','2022-08-17',1,'3','2022-09-16 12:04:14'),(51,678006661397,'DR. PATEL KINJAL CHANDRAKANTBHAI','paediatric','2022-08-12',1,'3','2022-09-16 12:04:14'),(52,846537487289,'DR. BHATARKAR SHUCHI RAJIV','paediatric','2022-08-13',1,'3','2022-09-16 12:04:14'),(53,989221776199,'DR. MAKWANA ANITA HARDIK','paediatric','2022-08-10',1,'3','2022-09-16 12:04:14'),(54,873994509396,'CHHOTALA RAKESHKUMAR BABUBHAI','paediatric','2022-09-07',1,'3','2022-09-16 12:04:14'),(55,999999999999,'PATEL CHARMY GIRISHBHAI','paediatric','2022-09-06',1,'3','2022-09-16 12:04:14'),(56,600453155982,'RATHOD ASHA NARSHIBHAI','paediatric','2022-09-07',1,'3','2022-09-16 12:04:14'),(57,968194139385,'DR. GUJRATI SHEETALBEN YOGESHKUMAR','pathology','2022-05-16',1,'3','2022-09-16 12:04:14'),(58,233910413578,'DR.SUTARIYA SHWETA ASHOKBHAI','pathology','2022-05-13',1,'3','2022-09-16 12:04:14'),(59,781969244089,'DR. SUKHADIA RIDHAM DAKSHESHKUMAR','pathology','2022-05-13',1,'3','2022-09-16 12:04:14'),(60,929895710691,'DR. ANAJWALA KRISHNA KISHORBHAI','pathology','2022-05-14',1,'3','2022-09-16 12:04:14'),(61,855007140761,'DR. SAYANTANI DAS','pathology','2022-05-13',1,'3','2022-09-16 12:04:14'),(62,639600470644,'Kukadiya Harshal Harsukhbhai','pathology','2022-08-26',1,'3','2022-09-16 12:04:14'),(63,340087829543,'Vaniya Sarasvatiben Bhupatbhai','pathology','2022-08-26',1,'3','2022-09-16 12:04:14'),(64,681299684698,'Moradiya Kirti Sureshbhai','pathology','2022-08-26',1,'3','2022-09-16 12:04:14'),(65,842330202965,'DR. RATHOD KHUSHBU PREMJIBHAI','pharmacology','2022-05-12',1,'3','2022-09-16 12:04:14'),(66,988388947569,'DR. PATEL JINALBEN MAFATLAL','pharmacology','2022-05-12',1,'3','2022-09-16 12:04:14'),(67,905069890367,'DR. CHOTALIYA JIGAR KAUSHIKBHAI','pharmacology','2022-05-12',1,'3','2022-09-16 12:04:14'),(68,750519416254,'DR. SHAIKH TANVI YUSUFBHAI','psychiatry','2022-05-19',1,'3','2022-09-16 12:04:14'),(69,963038271438,'DR. JOSHI TEJAS LALITBHAI','psychiatry','2022-05-14',1,'3','2022-09-16 12:04:14'),(70,837297205036,'JIKADARA PRIYANKABEN VINUBHAI','radiology','2021-08-03',1,'3','2022-09-16 12:04:14'),(71,972189233918,'DR. PATEL MAHAMMED WASIM MAKBULBHAI','radiology','2022-05-12',1,'3','2022-09-16 12:04:14'),(72,573652095207,'DR. KOTHA HARI PRIYANKA','radiology','2022-05-19',1,'3','2022-09-16 12:04:14'),(73,433014965320,'DR. SAURABH DESHPANDE','radiology','2022-05-12',1,'3','2022-09-16 12:04:14'),(74,388425192816,'DR. PATOLIA RAJ VITTHALBHAI','skin_and_v.d._','2022-05-13',1,'3','2022-09-16 12:04:14'),(75,217681401660,'DR. GENGADIYA JIGNESH KARAMSHIBHAI','tb_and_cheast','2022-05-19',1,'3','2022-09-16 12:04:14');
/*!40000 ALTER TABLE `resident` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `table_field_specification`
--

DROP TABLE IF EXISTS `table_field_specification`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `table_field_specification` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tname` varchar(100) DEFAULT NULL,
  `fname` varchar(100) DEFAULT NULL,
  `ftype` varchar(50) DEFAULT NULL,
  `table` varchar(50) DEFAULT NULL,
  `field` varchar(50) DEFAULT NULL,
  `field_description` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tname_fname` (`tname`,`fname`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `table_field_specification`
--

LOCK TABLES `table_field_specification` WRITE;
/*!40000 ALTER TABLE `table_field_specification` DISABLE KEYS */;
INSERT INTO `table_field_specification` VALUES (51,'attendance','resident_id','dtable','resident','id','id,name,department,date_of_joining'),(52,'attendance','month','table','month','month',''),(53,'attendance','year','table','year','year','');
/*!40000 ALTER TABLE `table_field_specification` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` varchar(50) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `department` varchar(100) DEFAULT NULL,
  `expirydate` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES ('admin','admin','hZQY2R9.sREVc','Admin','2022-11-29'),('anaesthesiology','anaesthesiology','Q8UO3wqRcyfk.','Anaesthesiology','2023-09-16'),('biochemistry','biochemistry','/8MEJLfjvc9Jk','Biochemistry','2022-11-29'),('medicine','medicine','/8MEJLfjvc9Jk','Medicine','2022-11-29');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-09-16 13:11:46
