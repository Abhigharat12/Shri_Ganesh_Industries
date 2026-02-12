-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: jaiganesh_industries
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

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
-- Table structure for table `brands`
--

DROP TABLE IF EXISTS `brands`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `brands` (
  `brand_id` int(11) NOT NULL AUTO_INCREMENT,
  `brand_name` varchar(255) NOT NULL,
  `brand_active` int(11) NOT NULL DEFAULT 0,
  `brand_status` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`brand_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `brands`
--

LOCK TABLES `brands` WRITE;
/*!40000 ALTER TABLE `brands` DISABLE KEYS */;
INSERT INTO `brands` VALUES (2,'Public Company',1,1),(3,'Sole Proprietorship',1,1),(4,'One Person Company',1,1),(5,'Partnership',1,1),(6,'Limited Liability Partnership',1,1),(7,'Limited Company',1,1);
/*!40000 ALTER TABLE `brands` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `categories_id` int(11) NOT NULL AUTO_INCREMENT,
  `categories_name` varchar(255) NOT NULL,
  `categories_active` int(11) NOT NULL DEFAULT 0,
  `categories_status` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`categories_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Direct',1,1),(2,'By Reference',1,1);
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lead`
--

DROP TABLE IF EXISTS `lead`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lead` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lead_name` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `city` varchar(50) NOT NULL,
  `interest` varchar(100) NOT NULL,
  `source` varchar(50) NOT NULL,
  `status` int(5) NOT NULL,
  `lead_status` tinyint(4) NOT NULL DEFAULT 1,
  `contact_person` varchar(255) DEFAULT NULL,
  `creation_date` datetime DEFAULT current_timestamp(),
  `last_interaction` datetime DEFAULT NULL,
  `interest_probability` enum('25%','50%','75%','100%') DEFAULT '25%',
  `follow_up_date` date DEFAULT NULL,
  `next_step` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lead`
--

LOCK TABLES `lead` WRITE;
/*!40000 ALTER TABLE `lead` DISABLE KEYS */;
INSERT INTO `lead` VALUES (2,'pvt ltd','5255255858','pvt@gmail.com','Mumbai','SEO','Website',2,2,NULL,'2026-02-08 12:51:28',NULL,'25%',NULL,NULL),(3,'Meghal','5255252525','meghal@gmail.com','Nashik','SEO','Website',3,2,'Amey','2026-02-08 12:51:28','2026-02-08 13:13:18','50%','2026-02-18','contact them\r\n'),(4,'Real Estates','8080808080','mailabhishekgharat@gmail.com','Pune','Digital Marketing','Organic',6,2,NULL,'2026-02-08 12:51:28',NULL,'25%',NULL,NULL),(5,'sumit','7028467920','mailabhishekgharat@gmail.com','Pune City','sales','Call',1,2,NULL,'2026-02-08 12:51:28',NULL,'25%',NULL,NULL),(6,'Sales','08788808982','mailabhishekgharat@gmail.com','Pune City','sales','SocialMedia',3,2,'','2026-02-08 12:51:28',NULL,'25%',NULL,NULL),(7,'santosh','1234567890','san@gmail.com','pune','SEO','SocialMedia',2,2,'','2026-02-08 17:35:24',NULL,'25%',NULL,NULL),(8,'New lead','1234567890','new@gmail.com','Dehu','Job work','SocialMedia',2,2,NULL,'2026-02-08 19:31:43',NULL,'25%',NULL,NULL),(9,'Exide Industries','123456789','exide@gmail.com','PCMC','Lithium','Website',2,2,NULL,'2026-02-08 19:35:55',NULL,'25%',NULL,NULL),(10,'Hundai','1234567890','hu@gmail.com','Chakan','Laser Works','Call',1,2,NULL,'2026-02-08 19:37:17',NULL,'25%',NULL,NULL),(11,'Meghal','1234567890','mailabhishekgharat@gmail.com','Pune City','Scan','Organic',2,1,NULL,'2026-02-08 23:06:09',NULL,'25%',NULL,NULL),(12,'rohit shetty','1234567890','rohit@gmail.com','Dehu','Job work','Website',2,1,NULL,'2026-02-08 23:32:54',NULL,'25%',NULL,NULL),(13,'Anna','08788808982','mailabhishekgharat@gmail.com','Pune City','Scan','SocialMedia',3,1,NULL,'2026-02-09 22:45:06',NULL,'25%',NULL,NULL),(14,'Nikhi','1234567890','nikhil@gmail.com','Pune','Lithium','SocialMedia',2,1,'','2026-02-12 00:06:34',NULL,'25%',NULL,NULL);
/*!40000 ALTER TABLE `lead` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lead_history`
--

DROP TABLE IF EXISTS `lead_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lead_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lead_id` int(11) NOT NULL,
  `creation_date` datetime DEFAULT current_timestamp(),
  `last_interaction` datetime DEFAULT NULL,
  `interaction_type` enum('Call','WhatsApp','Email','Meeting','Visit') DEFAULT 'Call',
  `interaction_notes` text DEFAULT '',
  `interest_probability` enum('25%','50%','75%','100%') DEFAULT '25%',
  `follow_up_date` date DEFAULT NULL,
  `follow_up_status` enum('Pending','Done','Missed','Rescheduled') DEFAULT 'Pending',
  `updated_by` int(11) DEFAULT NULL,
  `next_step` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_updated_by` (`updated_by`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lead_history`
--

LOCK TABLES `lead_history` WRITE;
/*!40000 ALTER TABLE `lead_history` DISABLE KEYS */;
INSERT INTO `lead_history` VALUES (3,1,'2026-02-08 14:34:55','2026-02-08 17:39:58','Call','','25%','0000-00-00','Done',1,'hii'),(4,1,'2026-02-08 14:35:27','2026-02-08 19:30:41','Call','Interested','100%','2026-02-10','Done',1,'Call tomorrow'),(6,6,'2026-02-08 14:49:59','2026-02-08 15:26:20','WhatsApp','no','25%','0000-00-00','Pending',1,'done'),(8,6,'2026-02-08 15:30:47','2026-02-08 15:31:16','Email','na','25%','2026-02-25','Pending',1,'fine'),(9,3,'2026-02-08 15:33:07','2026-02-08 16:33:46','Call','','100%','0000-00-00','Done',1,'yyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyy'),(10,7,'2026-02-08 17:36:04','2026-02-08 17:36:29','Call','follwed me','75%','2026-02-24','Done',1,'NA'),(11,8,'2026-02-08 19:31:55','2026-02-08 19:32:31','Call','New Admitted','100%','2026-02-08','Pending',1,'Monitor'),(12,2,'2026-02-08 19:33:34','2026-02-08 19:34:19','Call','CallAgain','100%','2026-02-07','Pending',1,'MAail them'),(13,1,'2026-02-08 21:05:36','2026-02-08 21:05:57','Email','','25%','0000-00-00','Done',1,''),(14,9,'2026-02-08 21:11:23','2026-02-08 21:11:45','Visit','','100%','2026-02-08','Pending',1,''),(15,12,'2026-02-08 23:33:04','2026-02-08 23:33:44','Call','NA','100%','2026-02-08','Missed',1,'NA'),(16,14,'2026-02-12 00:07:29','2026-02-12 00:15:15','Call','','100%','2026-02-11','Pending',1,'');
/*!40000 ALTER TABLE `lead_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_item`
--

DROP TABLE IF EXISTS `order_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_item` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `order_id` int(10) NOT NULL,
  `productName` int(100) NOT NULL,
  `rate` varchar(255) NOT NULL,
  `total` varchar(255) NOT NULL,
  `added_date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_item`
--

LOCK TABLES `order_item` WRITE;
/*!40000 ALTER TABLE `order_item` DISABLE KEYS */;
INSERT INTO `order_item` VALUES (1,1,1,'500','590.00','2022-10-12'),(3,3,4,'150','167.00','2022-10-12'),(4,4,1,'500','585.00','2022-11-02'),(5,5,4,'150','177.00','2022-11-04'),(6,6,1,'500','567.00','2026-02-04');
/*!40000 ALTER TABLE `order_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_date` date NOT NULL,
  `client_name` varchar(255) NOT NULL,
  `client_contact` varchar(255) NOT NULL,
  `sub_total` varchar(255) NOT NULL,
  `vat` varchar(255) NOT NULL,
  `total_amount` varchar(255) NOT NULL,
  `discount` varchar(255) NOT NULL,
  `grand_total` varchar(255) NOT NULL,
  `paid` varchar(255) NOT NULL,
  `due` varchar(255) NOT NULL,
  `payment_type` int(11) NOT NULL,
  `payment_status` int(11) NOT NULL,
  `payment_place` int(11) NOT NULL,
  `gstn` varchar(255) NOT NULL,
  `order_status` int(11) NOT NULL DEFAULT 0,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,'2022-10-12','1','2345678989','500.00','90.00','590.00','5','585.00','400','185.00',0,1,1,'90.00',1,1),(2,'2022-10-12','2','5255255858','150.00','27.00','177.00','10','167.00','150','17.00',1,2,1,'27.00',1,1),(3,'2022-10-12','3','5255252525','150.00','27.00','177.00','10','167.00','150','17.00',2,1,1,'27.00',1,1),(4,'2022-11-02','1','2345678989','500.00','90.00','590.00','5','585.00','585','0.00',2,1,1,'90.00',1,1),(5,'2022-11-04','4','8080808080','150.00','27.00','177.00','','177.00','177','0.00',2,1,1,'27.00',1,1),(6,'2026-02-04','2','5255255858','500.00','90.00','590.00','23','567.00','','567.00',2,1,1,'90.00',1,1);
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product`
--

DROP TABLE IF EXISTS `product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product` (
  `product_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_name` varchar(255) NOT NULL,
  `brand_id` int(11) NOT NULL,
  `categories_id` int(11) NOT NULL,
  `rate` varchar(255) NOT NULL,
  `mrp` int(100) NOT NULL,
  `added_date` date NOT NULL,
  `active` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product`
--

LOCK TABLES `product` WRITE;
/*!40000 ALTER TABLE `product` DISABLE KEYS */;
INSERT INTO `product` VALUES (1,'Inverter service',1,2,'500',620,'2022-02-28',1,1),(2,'Amlokind-Beta 50 Tablet PR',2,1,'150',200,'2022-02-28',1,1),(3,'Clarinova 500 Tablet',2,3,'200',300,'2022-02-28',2,2),(4,'battery service',1,1,'150',250,'2022-10-05',1,1),(5,'wwde',2,2,'222',345,'2022-10-09',1,1);
/*!40000 ALTER TABLE `product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_client`
--

DROP TABLE IF EXISTS `tbl_client`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_client` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `gender` varchar(150) NOT NULL,
  `mob_no` varchar(150) NOT NULL,
  `reffering` varchar(150) NOT NULL,
  `address` varchar(250) NOT NULL,
  `created_date_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `delete_status` int(11) NOT NULL,
  `image` varchar(150) NOT NULL,
  `connection_no` varchar(50) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_client`
--

LOCK TABLES `tbl_client` WRITE;
/*!40000 ALTER TABLE `tbl_client` DISABLE KEYS */;
INSERT INTO `tbl_client` VALUES (1,'Jagdish Bhosale','Male','9090809080','5788534234','Shop No. 2, Krushna Kamal Complex, New Adgaon Naka, Om Nagar, Panchavati, Nashik, Maharashtra 422003','2022-08-02 13:32:38',0,'pexels-italo-melo-2379005.jpg','115',1),(2,'Ashoka Chauvan','Male','5667566756','23442432434','Plot no 2, behind hotel seven heaven Sonawane mala, Chetana nagar, Rane Nagar, Nashik, Maharashtra 422009','2022-08-02 14:31:29',0,'male.jfif','113',2);
/*!40000 ALTER TABLE `tbl_client` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` varchar(50) DEFAULT 'user',
  `status` varchar(20) DEFAULT 'active',
  `created_date` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'abhishek','$2y$10$Qu7wNQ8uojuwYpoM8Z800uyge3eAaEMPUFOObZ0Iyl0Udw3bOxaEy','mailabhishekgharat@gmail.com','admin','active','2026-02-09 18:49:25'),(3,'anna','$2y$10$b3PbC7.YUD7QAS7coqbrW.EYa/n2tOf6PfMtLDyeK63XhO/I8XxcS','anna@gmail.com','user','active','2026-02-09 18:49:25'),(7,'maulizambre','$2y$10$rtcGiWmN.9388efogc4j5.zeHjU/01aMNhCvXbGVo6xvNBcKvQvwm','mauli@gmail.com','user','active','2026-02-09 18:49:25'),(8,'rohit','$2y$10$fZR2Ku/FKi7HujQafLTjB.TiDwLu.BgxTq2y55lAkPyy6HUNklTjG','rohit@gmail.com','user','active','2026-02-09 18:53:53');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-02-12 22:09:44
