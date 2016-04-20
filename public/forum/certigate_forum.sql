-- MySQL dump 10.13  Distrib 5.5.46, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: certigate
-- ------------------------------------------------------
-- Server version	5.5.46-0ubuntu0.14.04.2

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `b8_wordlist`
--

DROP TABLE IF EXISTS `b8_wordlist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `b8_wordlist` (
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `count_ham` int(11) DEFAULT NULL,
  `count_spam` int(11) DEFAULT NULL,
  PRIMARY KEY (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `b8_wordlist`
--

LOCK TABLES `b8_wordlist` WRITE;
/*!40000 ALTER TABLE `b8_wordlist` DISABLE KEYS */;
INSERT INTO `b8_wordlist` VALUES ('b8*dbversion',3,NULL),('b8*texts',0,0);
/*!40000 ALTER TABLE `b8_wordlist` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `codo_bans`
--

DROP TABLE IF EXISTS `codo_bans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `codo_bans` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `ban_type` enum('name','mail','ip') COLLATE utf8_unicode_ci NOT NULL,
  `ban_by` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `ban_on` int(11) NOT NULL,
  `ban_reason` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `ban_expires` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `codo_bans`
--

LOCK TABLES `codo_bans` WRITE;
/*!40000 ALTER TABLE `codo_bans` DISABLE KEYS */;
/*!40000 ALTER TABLE `codo_bans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `codo_block_roles`
--

DROP TABLE IF EXISTS `codo_block_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `codo_block_roles` (
  `bid` int(11) NOT NULL,
  `rid` int(11) NOT NULL,
  KEY `codo_block_roles_rid_index` (`rid`),
  KEY `codo_block_roles_bid_index` (`bid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `codo_block_roles`
--

LOCK TABLES `codo_block_roles` WRITE;
/*!40000 ALTER TABLE `codo_block_roles` DISABLE KEYS */;
/*!40000 ALTER TABLE `codo_block_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `codo_blocks`
--

DROP TABLE IF EXISTS `codo_blocks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `codo_blocks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `module` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `theme` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `weight` int(11) NOT NULL,
  `region` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `visibility` int(11) NOT NULL,
  `pages` text COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `cache` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `codo_blocks`
--

LOCK TABLES `codo_blocks` WRITE;
/*!40000 ALTER TABLE `codo_blocks` DISABLE KEYS */;
INSERT INTO `codo_blocks` VALUES (1,'html','blue',0,0,'block_main_menu','<li class=\'dropdown codo_dropdown\'><span class=\'codo_menu_dropdown\' data-toggle=\'dropdown\'>Link<i class=\'caret\'></i></span>\r\n          <ul class=\'dropdown-menu\' role=\'menu\'>\r\n            <li><a href=\'#\'>Action</a></li>\r\n            <li><a href=\'#\'>Another action</a></li>\r\n            <li><a href=\'#\'>Something else here</a></li>\r\n           <li><a href=\'#\'>Separated link</a></li>\r\n            <li><a href=\'#\'>One more separated link</a></li>\r\n          </ul>\r\n</li>',0,'','Main Menu',1),(2,'html','blue',0,0,'block_footer_right','<small>\r\n   \r\n<a href=\"https://facebook.com/codologic\"><i class=\"icon-facebook\"> </i></a> \r\n <a href=\"https://twitter.com/codologic\"><i class=\"icon-twitter\"> </i></a>\r\n <a href=\"https://plus.google.com/+codologic\"><i class=\"icon-google-plus-square\"> </i></a>\r\n\r\n        <br>\r\n        <a href=\"index.php?u=page/6\">Terms of Service</a> |\r\n        <a href=\"index.php?u=page/7\">Privacy</a> |\r\n        <a href=\"#\">About us</a> \r\n</small>',0,'','Footer Right',1);
/*!40000 ALTER TABLE `codo_blocks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `codo_categories`
--

DROP TABLE IF EXISTS `codo_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `codo_categories` (
  `cat_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cat_pid` int(11) NOT NULL,
  `cat_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cat_alias` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `cat_description` varchar(400) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cat_img` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `no_topics` int(11) NOT NULL,
  `no_posts` int(11) NOT NULL,
  `cat_order` int(11) NOT NULL,
  PRIMARY KEY (`cat_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `codo_categories`
--

LOCK TABLES `codo_categories` WRITE;
/*!40000 ALTER TABLE `codo_categories` DISABLE KEYS */;
INSERT INTO `codo_categories` VALUES (3,0,'General Discussions','general-discussions','For anything and everything that doesnt fit in other categories.','bubbles.png',1,1,0),(10,0,'News and Announcements','news-and-announcements','this is where all the latest news will be posted','bullhorn.png',0,0,0),(11,0,'Support Forums','support-forums','Have any problem? Report it here and we will be glad to help.','support.png',0,0,2),(12,0,'Let us know','let-us-know','We encourage new members to post a short description about themselves','envelope.png',0,0,2),(13,0,'Bug Reports','bug-reports','Found a bug? why not report it here?','bug.png',0,0,2),(14,0,'Feature Requests','feature-requests','You have a cool idea? post them here!','wand.png',0,0,2);
/*!40000 ALTER TABLE `codo_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `codo_config`
--

DROP TABLE IF EXISTS `codo_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `codo_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `option_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `option_value` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `codo_config`
--

LOCK TABLES `codo_config` WRITE;
/*!40000 ALTER TABLE `codo_config` DISABLE KEYS */;
INSERT INTO `codo_config` VALUES (1,'site_url',''),(2,'site_title','Certigate'),(3,'site_description',''),(4,'admin_email','admin@certigate.com'),(5,'theme','blue'),(6,'captcha_public_key',''),(7,'captcha_private_key',''),(8,'register_pass_min','8'),(9,'num_posts_all_topics','30'),(10,'num_posts_cat_topics','20'),(11,'num_posts_per_topic','20'),(12,'forum_attachments_path','assets/img/attachments'),(13,'forum_attachments_exts','jpg,jpeg,png,gif,pjpeg,bmp,txt'),(14,'forum_attachments_size','3'),(15,'forum_attachments_mimetypes','image/*,text/plain'),(16,'forum_attachments_multiple','true'),(17,'forum_attachments_parallel','4'),(18,'forum_attachments_max','10'),(19,'reply_min_chars','10'),(20,'subcategory_dropdown','hidden'),(21,'captcha','disabled'),(22,'await_approval_message','Dear [user:username],\n\nThank you for registering at [option:site_title]. Before we can activate your account one last step must be taken to complete your registration.\n\nTo complete your registration, please visit this URL: [this:confirm_url]\n\nYour Username is: [user:username] \n\nIf you are still having problems signing up please contact a member of our support staff at [option:admin_email]\n\nRegards,\n[option:site_title]'),(23,'await_approval_subject','Confirm your email for [user:username] at [option:site_title]'),(24,'mail_type','mail'),(25,'smtp_protocol','ssl'),(26,'smtp_server','smtp.gmail.com'),(27,'smtp_port','465'),(28,'smtp_username','admin@codologic.com'),(29,'smtp_password','your_smtp_pass'),(30,'register_username_min','3'),(31,'signature_char_lim','255'),(32,'sso_client_id','certigateforum'),(33,'sso_secret','C371i9@13Xe24!rf'),(34,'sso_get_user_path','http://local-certigate.com/users/details'),(35,'sso_login_user_path','http://local-certigate.com/sign/in?redirectBackUrl=/forum'),(36,'sso_logout_user_path','http://local-certigate.com/sign/out'),(37,'sso_register_user_path','http://local-certigate.com/users/new'),(38,'sso_name','certigate'),(39,'post_notify_message','Hi, \n\n[user:username] has replied to the topic: [post:title]\n\n----\n[post:imessage]\n----\n\nYou can view the reply at the following url\n[post:url]\n\nRegards,\n[option:site_title] team\n'),(40,'post_notify_subject','[post:title] - new reply'),(41,'password_reset_message','Hi,\r\n\r\nA request has been made to reset your account password. \r\n\r\\To reset your password, please follow the below link:\n[user:link]\r\n\rPassword reset token: [user:token]\r\n\r\nRegards,\r\n[option:site_title] team\r\n'),(42,'password_reset_subject','Your [option:site_title] password reset request'),(43,'topic_notify_message','Hi [post:username],\r\n\r\n[user:username] has created a new topic: [post:title]\r\nin category [post:category]\r\n\r\nYou can view the topic by clicking [post:url]\r\n\r\nRegards,\r\n[option:site_title] team'),(44,'topic_notify_subject','[post:category] - new topic'),(45,'version','3.4'),(46,'brand_img','http://codoforum.com/img/favicon-32x32.png'),(47,'reg_req_admin','no'),(48,'max_rep_per_day','100'),(49,'rep_req_to_inc','0'),(50,'posts_req_to_inc','0'),(51,'rep_req_to_dec','0'),(52,'posts_req_to_dec','0'),(53,'rep_times_same_user','5'),(54,'rep_hours_same_user','24'),(55,'enable_reputation','yes');
/*!40000 ALTER TABLE `codo_config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `codo_crons`
--

DROP TABLE IF EXISTS `codo_crons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `codo_crons` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cron_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `cron_type` enum('once','recurrence') COLLATE utf8_unicode_ci NOT NULL,
  `cron_interval` int(11) NOT NULL,
  `cron_started` int(11) NOT NULL,
  `cron_last_run` int(11) NOT NULL,
  `cron_status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `codo_crons_cron_name_index` (`cron_name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `codo_crons`
--

LOCK TABLES `codo_crons` WRITE;
/*!40000 ALTER TABLE `codo_crons` DISABLE KEYS */;
INSERT INTO `codo_crons` VALUES (1,'core','recurrence',86400,1458824682,1458824683,0),(2,'daily_digest','recurrence',86400,1458824682,1458824683,0),(3,'weekly_digest','recurrence',604800,1458824682,1458824683,0),(4,'mail_notify_send','recurrence',1800,1458827755,1458827755,0);
/*!40000 ALTER TABLE `codo_crons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `codo_daily_rep_log`
--

DROP TABLE IF EXISTS `codo_daily_rep_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `codo_daily_rep_log` (
  `uid` int(11) NOT NULL,
  `rep_count` int(11) NOT NULL,
  `start_rep_time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `codo_daily_rep_log`
--

LOCK TABLES `codo_daily_rep_log` WRITE;
/*!40000 ALTER TABLE `codo_daily_rep_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `codo_daily_rep_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `codo_edits`
--

DROP TABLE IF EXISTS `codo_edits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `codo_edits` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `codo_edits`
--

LOCK TABLES `codo_edits` WRITE;
/*!40000 ALTER TABLE `codo_edits` DISABLE KEYS */;
/*!40000 ALTER TABLE `codo_edits` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `codo_logs`
--

DROP TABLE IF EXISTS `codo_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `codo_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `log_type` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `message` text COLLATE utf8_unicode_ci NOT NULL,
  `severity` int(11) NOT NULL,
  `trace` text COLLATE utf8_unicode_ci NOT NULL,
  `log_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `codo_logs`
--

LOCK TABLES `codo_logs` WRITE;
/*!40000 ALTER TABLE `codo_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `codo_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `codo_mail_queue`
--

DROP TABLE IF EXISTS `codo_mail_queue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `codo_mail_queue` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mail_type` int(11) DEFAULT '1',
  `mail_status` int(11) NOT NULL,
  `from_address` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `to_address` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `mail_subject` varchar(400) COLLATE utf8_unicode_ci NOT NULL,
  `body` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `codo_mail_queue`
--

LOCK TABLES `codo_mail_queue` WRITE;
/*!40000 ALTER TABLE `codo_mail_queue` DISABLE KEYS */;
/*!40000 ALTER TABLE `codo_mail_queue` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `codo_notify`
--

DROP TABLE IF EXISTS `codo_notify`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `codo_notify` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `uid` int(11) NOT NULL,
  `nid` int(11) NOT NULL,
  `created` int(11) NOT NULL,
  `is_read` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `codo_notify_is_read_index` (`is_read`),
  KEY `codo_notify_nid_index` (`nid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `codo_notify`
--

LOCK TABLES `codo_notify` WRITE;
/*!40000 ALTER TABLE `codo_notify` DISABLE KEYS */;
/*!40000 ALTER TABLE `codo_notify` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `codo_notify_queue`
--

DROP TABLE IF EXISTS `codo_notify_queue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `codo_notify_queue` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `nid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `codo_notify_queue`
--

LOCK TABLES `codo_notify_queue` WRITE;
/*!40000 ALTER TABLE `codo_notify_queue` DISABLE KEYS */;
/*!40000 ALTER TABLE `codo_notify_queue` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `codo_notify_subscribers`
--

DROP TABLE IF EXISTS `codo_notify_subscribers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `codo_notify_subscribers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cid` int(11) DEFAULT NULL,
  `tid` int(11) DEFAULT NULL,
  `uid` int(11) NOT NULL,
  `type` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `codo_notify_subscribers_cid_index` (`cid`),
  KEY `codo_notify_subscribers_tid_index` (`tid`),
  KEY `codo_notify_subscribers_uid_index` (`uid`),
  KEY `codo_notify_subscribers_type_index` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `codo_notify_subscribers`
--

LOCK TABLES `codo_notify_subscribers` WRITE;
/*!40000 ALTER TABLE `codo_notify_subscribers` DISABLE KEYS */;
/*!40000 ALTER TABLE `codo_notify_subscribers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `codo_notify_text`
--

DROP TABLE IF EXISTS `codo_notify_text`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `codo_notify_text` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `data` varchar(1024) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `codo_notify_text`
--

LOCK TABLES `codo_notify_text` WRITE;
/*!40000 ALTER TABLE `codo_notify_text` DISABLE KEYS */;
/*!40000 ALTER TABLE `codo_notify_text` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `codo_page_roles`
--

DROP TABLE IF EXISTS `codo_page_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `codo_page_roles` (
  `pid` int(11) NOT NULL,
  `rid` int(11) NOT NULL,
  KEY `codo_page_roles_pid_index` (`pid`),
  KEY `codo_page_roles_rid_index` (`rid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `codo_page_roles`
--

LOCK TABLES `codo_page_roles` WRITE;
/*!40000 ALTER TABLE `codo_page_roles` DISABLE KEYS */;
/*!40000 ALTER TABLE `codo_page_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `codo_pages`
--

DROP TABLE IF EXISTS `codo_pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `codo_pages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `codo_pages`
--

LOCK TABLES `codo_pages` WRITE;
/*!40000 ALTER TABLE `codo_pages` DISABLE KEYS */;
INSERT INTO `codo_pages` VALUES (6,'Terms Of Service','terms-of-service','<h1>Terms and Conditions</h1>\r\n<hr>\r\n<p>By using and accessing this website, <a href=\"http://codoforum.com\">codoforum.com</a> a part of <a href=\"http://codologic.com\">Codologic</a> (collectively referred to as the \"Site\" or \"Codoforum\" in these Terms of Service), you (\"you\", \"user\" or, \"end user\") agree to these Terms of Service (collectively, the \"Terms of Service\" or \"Agreement\").</p>\r\n<p>IF YOU DO NOT AGREE TO THE TERMS OF THIS AGREEMENT, IMMEDIATELY STOP ACCESSING THIS SITE.</p>\r\n<p>You agree not to modify, copy, distribute, transmit, display, perform, reproduce, publish, license, transfer, create derivate work from, sell or re-sell any content or information obtained from or through the Site.\r\n<br><br><strong>Third-party Sites.</strong></p>\r\n<p>The Site may contain links to other websites maintained by third-parties. These links are provided solely as a convenience and does not imply endorsement of, or association with, the party by Codologic.\r\n<br><br><strong>Modifications to this Agreement.</strong></p>\r\n<p>Codologic reserves the right to change or modify any of the terms and conditions contained in this Agreement at any time. You acknowledge and agree that it is your responsibility to review the Site and these Terms of Service from time to time. Your continued use of the Site after such modifications to this Agreement will constitute acknowledgment of the modified Terms of Service and agreement to abide and be bound by the modified Terms of Service.\r\n<br><br><strong>Termination of Use.</strong></p>\r\n<p>Codologic shall have the right to immediately terminate or suspend, in its discretion, your access to all or part of the Site with or without notice for any reason.\r\n<br><br><strong>Limitation of Liability.</strong></p>\r\n<p>In no event shall Codologic or its affiliates be liable for any indirect, incidental, special, punitive damages or consequential damages of any kind, or any damages whatsoever arising out of or related to your use of the Site, the content and other information obtained therein.</p>\r\n<p>Certain jurisdictions prohibit the exclusion or limitation of liability for consequential or incidental damages, thus the above limitations may not apply to you.\r\n<br><br><strong>Indemnity</strong></p>\r\n<p>You agree to indemnify and hold us, and our subsidiaries, affiliates, directors, officers, agents, vendors or other partners and employees harmless from any claim or demand, including attorneys’ fees, made by any third party due to or arising out of any material or information posted, provided, transmitted or otherwise made available by you on this Site or through Codologic.com’s services, or by your violation of these Terms, or by your violation of the rights of another.\r\n<br><br><strong>Disclaimers and Limitation of Liability </strong></p>\r\n<p>You understand and agree that this Site is provided \"AS-IS\" and that we assume no responsibility for your ability to (or any costs or fees associated with your ability to) obtain access to Codologic.com. Nor do we assume any liability for the failure to store or maintain any user communications or personal settings.</p>\r\n<p>NO ADVICE OR INFORMATION, WHETHER ORAL OR WRITTEN, OBTAINED BY YOU FROM CODOLOGIC.COM OR THROUGH OR FROM ITS SERVICES SHALL CREATE ANY WARRANTY NOT EXPRESSLY STATED IN THESE TERMS AND CONDITIONS. IN NO EVENT SHALL CODOLOGIC.COM OR ITS OWNER BE LIABLE TO YOU OR ANY THIRD PARTY FOR ANY INDIRECT, CONSEQUENTIAL, EXEMPLARY, INCIDENTAL, SPECIAL OR PUNITIVE DAMAGES, INCLUDING LOST PROFIT DAMAGES ARISING FROM YOUR USE OF CODOLOGIC.COM OR ITS SERVICES EVEN IF WE HAVE BEEN ADVISED OF THE POSSIBILITY OF SUCH DAMAGES.\r\n<br><br><strong>Jurisdiction</strong></p>\r\n<p>This agreement is governed and construed in accordance with the Laws of Union of India. You hereby irrevocably consent to the exclusive jurisdiction and venue of courts in Mumbai, Maharashtra, India, in all disputes arising out of or relating to the use of Codologic site/services. Use of the Codologic site/services is unauthorized in any jurisdiction that does not give effect to all provisions of these terms and conditions, including without limitation this paragraph. You agree to indemnify and hold Codologic.com, its subsidiaries, affiliates, officers, directors, employees, and representatives harmless from any claim, demand, or damage, including reasonable attorneys\' fees, asserted by any third party due to or arising out of your use of or conduct on the Codologic site/services.</p>\r\n<p>The section titles and other headings in these Terms are for convenience only and have no legal or contractual effect. Our failure to exercise or enforce any right or provision of these Terms will not operate as a waiver of such right or provision. If any provision of these Terms is unlawful, void or unenforceable, that provision is deemed severable and does not affect the validity and enforceability of any remaining provisions.</p>\r\n<p><br><br><strong>Date of Last Update.</strong></p>\r\n<p>This agreement was last updated on May 15, 2014.</p>\r\n'),(7,'Privacy Policy','privacy-policy','\r\n        \r\n <h1>Privacy policy</h1>\r\n <hr>\r\n <br>       \r\n        \r\n<p><strong>Privacy policy for Codoforum:</strong></p>\r\n<p>Your use of any information or materials on this website is entirely at your own decision, for which we shall not be liable. </p>\r\n<p>It shall be your own responsibility to ensure that any products, services or information available through this website meet your specific requirements.</p>\r\n<p>This website contains material which is owned by or licensed to us. This material includes, but is not limited to, the design, layout, look, appearance and graphics. Reproduction is prohibited other than in accordance with the copyright notice, which forms part of these terms and conditions.</p>\r\n<p>All trademarks reproduced in this website which are not the property of, or licensed to, the operator are acknowledged on the website.\r\nUnauthorized use of this website may give rise to a claim for damages and/or be a criminal offence.</p>\r\n<p>From time to time this website may also include links to other websites. These links are provided for your convenience to provide further information. They do not signify that we endorse the website(s). </p>\r\n<p>We have no responsibility for the content of the linked website(s).\r\nYour use of this website and any dispute arising out of such use of the website is subject to the laws of India or other regulatory authority.</p>\r\n<br>\r\n<br>\r\n<p><strong>Log Files:</strong></p>\r\n<p>Codoforum makes use of log files (which includes IP addresses, type of browser, internet service providers, referrer, number of clicks etc) to understand user movements and demographics. Such information is not linked to any information that is personally identifiable.</p>\r\n\r\n<br>\r\n<br>\r\n<p><strong>Cookies:</strong></p>\r\n<p>Codoforum uses cookies to store information about visitors preferences, record user-specific information on which pages the user access or visit, customize Web page content based on visitors browser type or other information that the visitor sends via their browser.</p>\r\n<p>We will not sell, disseminate, disclose, trade, transmit, transfer, share, lease or rent any personally identifiable information to any third party not specifically authorized by you to receive your information except as we have disclosed to you in this Privacy Policy. However we may use such information to update you about promotional offers and updates from us.</p>\r\n<br>\r\n<br>\r\n<p><strong>Changes in Our Privacy Policy:</strong></p>\r\n<p>We reserve the right to change this Privacy Policy without providing you with advance notice of our intent to make the changes.</p>\r\n<p>If you believe that any information we are holding on you is incorrect or incomplete, please contact us as soon as possible.</p>\r\n\r\n');
/*!40000 ALTER TABLE `codo_pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `codo_permission_list`
--

DROP TABLE IF EXISTS `codo_permission_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `codo_permission_list` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `permission` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `codo_permission_list`
--

LOCK TABLES `codo_permission_list` WRITE;
/*!40000 ALTER TABLE `codo_permission_list` DISABLE KEYS */;
INSERT INTO `codo_permission_list` VALUES (1,'view category','forum'),(2,'view user profiles','general'),(3,'use search','general'),(4,'view all topics','forum'),(5,'view my topics','forum'),(6,'create new topic','forum'),(7,'reply to all topics','forum'),(8,'edit my topics','forum'),(9,'edit all topics','forum'),(10,'delete my topics','forum'),(11,'delete all topics','forum'),(12,'view forum','general'),(13,'move topics','forum'),(14,'moderate topics','forum'),(15,'moderate posts','forum'),(16,'make sticky','forum'),(17,'edit profile','general'),(18,'see history','forum'),(19,'rep up','forum'),(20,'rep down','forum'),(21,'merge topics','forum'),(22,'add tags','forum');
/*!40000 ALTER TABLE `codo_permission_list` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `codo_permissions`
--

DROP TABLE IF EXISTS `codo_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `codo_permissions` (
  `pid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(11) NOT NULL,
  `cid` int(11) NOT NULL,
  `tid` int(11) NOT NULL,
  `permission` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `granted` int(11) NOT NULL,
  `inherited` int(11) NOT NULL,
  PRIMARY KEY (`pid`),
  KEY `codo_permissions_rid_index` (`rid`)
) ENGINE=InnoDB AUTO_INCREMENT=1328 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `codo_permissions`
--

LOCK TABLES `codo_permissions` WRITE;
/*!40000 ALTER TABLE `codo_permissions` DISABLE KEYS */;
INSERT INTO `codo_permissions` VALUES (296,1,0,0,'view user profiles',1,-1),(297,1,0,0,'use search',1,-1),(298,1,0,0,'view all topics',1,-1),(299,1,0,0,'view my topics',0,-1),(300,1,0,0,'create new topic',0,-1),(301,1,0,0,'reply to all topics',0,-1),(302,1,0,0,'edit my topics',0,-1),(303,1,0,0,'edit all topics',0,-1),(304,1,0,0,'delete my topics',0,-1),(305,1,0,0,'delete all topics',0,-1),(306,1,0,0,'view forum',1,-1),(355,1,0,0,'edit my posts',0,-1),(356,1,0,0,'edit all posts',0,-1),(357,1,0,0,'delete my posts',0,-1),(358,1,0,0,'delete all posts',0,-1),(359,2,0,0,'view user profiles',1,-1),(360,2,0,0,'use search',1,-1),(361,2,0,0,'view all topics',1,-1),(362,2,0,0,'view my topics',0,-1),(363,2,0,0,'create new topic',1,-1),(364,2,0,0,'reply to all topics',1,-1),(365,2,0,0,'edit my topics',1,-1),(366,2,0,0,'edit all topics',0,-1),(367,2,0,0,'delete my topics',1,-1),(368,2,0,0,'delete all topics',0,-1),(369,2,0,0,'view forum',0,-1),(370,2,0,0,'edit my posts',1,-1),(371,2,0,0,'edit all posts',0,-1),(372,2,0,0,'delete my posts',1,-1),(373,2,0,0,'delete all posts',0,-1),(374,3,0,0,'view user profiles',1,-1),(375,3,0,0,'use search',1,-1),(376,3,0,0,'view all topics',1,-1),(377,3,0,0,'view my topics',0,-1),(378,3,0,0,'create new topic',1,-1),(379,3,0,0,'reply to all topics',1,-1),(380,3,0,0,'edit my topics',1,-1),(381,3,0,0,'edit all topics',1,-1),(382,3,0,0,'delete my topics',1,-1),(383,3,0,0,'delete all topics',1,-1),(384,3,0,0,'view forum',0,-1),(385,3,0,0,'edit my posts',1,-1),(386,3,0,0,'edit all posts',1,-1),(387,3,0,0,'delete my posts',1,-1),(388,3,0,0,'delete all posts',1,-1),(389,4,0,0,'view user profiles',1,-1),(390,4,0,0,'use search',1,-1),(391,4,0,0,'view all topics',1,-1),(392,4,0,0,'view my topics',0,-1),(393,4,0,0,'create new topic',1,-1),(394,4,0,0,'reply to all topics',1,-1),(395,4,0,0,'edit my topics',1,-1),(396,4,0,0,'edit all topics',1,-1),(397,4,0,0,'delete my topics',1,-1),(398,4,0,0,'delete all topics',1,-1),(399,4,0,0,'view forum',0,-1),(400,4,0,0,'edit my posts',1,-1),(401,4,0,0,'edit all posts',1,-1),(402,4,0,0,'delete my posts',1,-1),(403,4,0,0,'delete all posts',1,-1),(404,6,0,0,'view user profiles',1,-1),(405,6,0,0,'use search',1,-1),(406,6,0,0,'view all topics',0,-1),(407,6,0,0,'view my topics',0,-1),(408,6,0,0,'create new topic',0,-1),(409,6,0,0,'reply to all topics',0,-1),(410,6,0,0,'edit my topics',0,-1),(411,6,0,0,'edit all topics',0,-1),(412,6,0,0,'delete my topics',0,-1),(413,6,0,0,'delete all topics',0,-1),(414,6,0,0,'view forum',0,-1),(415,6,0,0,'edit my posts',0,-1),(416,6,0,0,'edit all posts',0,-1),(417,6,0,0,'delete my posts',0,-1),(418,6,0,0,'delete all posts',0,-1),(419,5,0,0,'view user profiles',1,-1),(420,5,0,0,'use search',1,-1),(421,5,0,0,'view all topics',1,-1),(422,5,0,0,'view my topics',0,-1),(423,5,0,0,'create new topic',0,-1),(424,5,0,0,'reply to all topics',0,-1),(425,5,0,0,'edit my topics',0,-1),(426,5,0,0,'edit all topics',0,-1),(427,5,0,0,'delete my topics',0,-1),(428,5,0,0,'delete all topics',0,-1),(429,5,0,0,'view forum',1,-1),(430,5,0,0,'edit my posts',0,-1),(431,5,0,0,'edit all posts',0,-1),(432,5,0,0,'delete my posts',0,-1),(433,5,0,0,'delete all posts',0,-1),(434,6,3,0,'view all topics',0,1),(435,6,3,0,'view my topics',0,1),(436,6,3,0,'create new topic',0,1),(437,6,3,0,'reply to all topics',0,1),(438,6,3,0,'edit my topics',0,1),(439,6,3,0,'edit all topics',0,1),(440,6,3,0,'delete my topics',0,1),(441,6,3,0,'delete all topics',0,1),(442,6,3,0,'edit my posts',0,1),(443,6,3,0,'edit all posts',0,1),(444,6,3,0,'delete my posts',0,1),(445,6,3,0,'delete all posts',0,1),(446,5,3,0,'view all topics',1,1),(447,5,3,0,'view my topics',0,1),(448,5,3,0,'create new topic',0,1),(449,5,3,0,'reply to all topics',0,1),(450,5,3,0,'edit my topics',0,1),(451,5,3,0,'edit all topics',0,1),(452,5,3,0,'delete my topics',0,1),(453,5,3,0,'delete all topics',0,1),(454,5,3,0,'edit my posts',0,1),(455,5,3,0,'edit all posts',0,1),(456,5,3,0,'delete my posts',0,1),(457,5,3,0,'delete all posts',0,1),(458,4,3,0,'view all topics',1,1),(459,4,3,0,'view my topics',0,1),(460,4,3,0,'create new topic',1,1),(461,4,3,0,'reply to all topics',1,1),(462,4,3,0,'edit my topics',1,1),(463,4,3,0,'edit all topics',1,1),(464,4,3,0,'delete my topics',1,1),(465,4,3,0,'delete all topics',1,1),(466,4,3,0,'edit my posts',1,1),(467,4,3,0,'edit all posts',1,1),(468,4,3,0,'delete my posts',1,1),(469,4,3,0,'delete all posts',1,1),(470,3,3,0,'view all topics',1,1),(471,3,3,0,'view my topics',0,1),(472,3,3,0,'create new topic',1,1),(473,3,3,0,'reply to all topics',1,1),(474,3,3,0,'edit my topics',1,1),(475,3,3,0,'edit all topics',1,1),(476,3,3,0,'delete my topics',1,1),(477,3,3,0,'delete all topics',1,1),(478,3,3,0,'edit my posts',1,1),(479,3,3,0,'edit all posts',1,1),(480,3,3,0,'delete my posts',1,1),(481,3,3,0,'delete all posts',1,1),(482,2,3,0,'view all topics',1,1),(483,2,3,0,'view my topics',0,1),(484,2,3,0,'create new topic',1,1),(485,2,3,0,'reply to all topics',1,1),(486,2,3,0,'edit my topics',1,1),(487,2,3,0,'edit all topics',0,1),(488,2,3,0,'delete my topics',1,1),(489,2,3,0,'delete all topics',0,1),(490,2,3,0,'edit my posts',1,1),(491,2,3,0,'edit all posts',0,1),(492,2,3,0,'delete my posts',1,1),(493,2,3,0,'delete all posts',0,1),(494,1,3,0,'view all topics',1,1),(495,1,3,0,'view my topics',0,1),(496,1,3,0,'create new topic',0,1),(497,1,3,0,'reply to all topics',0,1),(498,1,3,0,'edit my topics',0,1),(499,1,3,0,'edit all topics',0,1),(500,1,3,0,'delete my topics',0,1),(501,1,3,0,'delete all topics',0,1),(502,1,3,0,'edit my posts',0,1),(503,1,3,0,'edit all posts',0,1),(504,1,3,0,'delete my posts',0,1),(505,1,3,0,'delete all posts',0,1),(506,6,10,0,'view all topics',0,1),(507,6,10,0,'view my topics',0,1),(508,6,10,0,'create new topic',0,1),(509,6,10,0,'reply to all topics',0,1),(510,6,10,0,'edit my topics',0,1),(511,6,10,0,'edit all topics',0,1),(512,6,10,0,'delete my topics',0,1),(513,6,10,0,'delete all topics',0,1),(514,6,10,0,'edit my posts',0,1),(515,6,10,0,'edit all posts',0,1),(516,6,10,0,'delete my posts',0,1),(517,6,10,0,'delete all posts',0,1),(518,5,10,0,'view all topics',1,1),(519,5,10,0,'view my topics',0,1),(520,5,10,0,'create new topic',0,1),(521,5,10,0,'reply to all topics',0,1),(522,5,10,0,'edit my topics',0,1),(523,5,10,0,'edit all topics',0,1),(524,5,10,0,'delete my topics',0,1),(525,5,10,0,'delete all topics',0,1),(526,5,10,0,'edit my posts',0,1),(527,5,10,0,'edit all posts',0,1),(528,5,10,0,'delete my posts',0,1),(529,5,10,0,'delete all posts',0,1),(530,4,10,0,'view all topics',1,1),(531,4,10,0,'view my topics',0,1),(532,4,10,0,'create new topic',1,1),(533,4,10,0,'reply to all topics',1,1),(534,4,10,0,'edit my topics',1,1),(535,4,10,0,'edit all topics',1,1),(536,4,10,0,'delete my topics',1,1),(537,4,10,0,'delete all topics',1,1),(538,4,10,0,'edit my posts',1,1),(539,4,10,0,'edit all posts',1,1),(540,4,10,0,'delete my posts',1,1),(541,4,10,0,'delete all posts',1,1),(542,3,10,0,'view all topics',1,1),(543,3,10,0,'view my topics',0,1),(544,3,10,0,'create new topic',1,1),(545,3,10,0,'reply to all topics',1,1),(546,3,10,0,'edit my topics',1,1),(547,3,10,0,'edit all topics',1,1),(548,3,10,0,'delete my topics',1,1),(549,3,10,0,'delete all topics',1,1),(550,3,10,0,'edit my posts',1,1),(551,3,10,0,'edit all posts',1,1),(552,3,10,0,'delete my posts',1,1),(553,3,10,0,'delete all posts',1,1),(554,2,10,0,'view all topics',1,1),(555,2,10,0,'view my topics',0,1),(556,2,10,0,'create new topic',1,1),(557,2,10,0,'reply to all topics',1,1),(558,2,10,0,'edit my topics',1,1),(559,2,10,0,'edit all topics',0,1),(560,2,10,0,'delete my topics',1,1),(561,2,10,0,'delete all topics',0,1),(562,2,10,0,'edit my posts',1,1),(563,2,10,0,'edit all posts',0,1),(564,2,10,0,'delete my posts',1,1),(565,2,10,0,'delete all posts',0,1),(566,1,10,0,'view all topics',1,1),(567,1,10,0,'view my topics',0,1),(568,1,10,0,'create new topic',0,1),(569,1,10,0,'reply to all topics',0,1),(570,1,10,0,'edit my topics',0,1),(571,1,10,0,'edit all topics',0,1),(572,1,10,0,'delete my topics',0,1),(573,1,10,0,'delete all topics',0,1),(574,1,10,0,'edit my posts',0,1),(575,1,10,0,'edit all posts',0,1),(576,1,10,0,'delete my posts',0,1),(577,1,10,0,'delete all posts',0,1),(578,6,11,0,'view all topics',0,1),(579,6,11,0,'view my topics',0,1),(580,6,11,0,'create new topic',0,1),(581,6,11,0,'reply to all topics',0,1),(582,6,11,0,'edit my topics',0,1),(583,6,11,0,'edit all topics',0,1),(584,6,11,0,'delete my topics',0,1),(585,6,11,0,'delete all topics',0,1),(586,6,11,0,'edit my posts',0,1),(587,6,11,0,'edit all posts',0,1),(588,6,11,0,'delete my posts',0,1),(589,6,11,0,'delete all posts',0,1),(590,5,11,0,'view all topics',1,1),(591,5,11,0,'view my topics',0,1),(592,5,11,0,'create new topic',0,1),(593,5,11,0,'reply to all topics',0,1),(594,5,11,0,'edit my topics',0,1),(595,5,11,0,'edit all topics',0,1),(596,5,11,0,'delete my topics',0,1),(597,5,11,0,'delete all topics',0,1),(598,5,11,0,'edit my posts',0,1),(599,5,11,0,'edit all posts',0,1),(600,5,11,0,'delete my posts',0,1),(601,5,11,0,'delete all posts',0,1),(602,4,11,0,'view all topics',1,1),(603,4,11,0,'view my topics',0,1),(604,4,11,0,'create new topic',1,1),(605,4,11,0,'reply to all topics',1,1),(606,4,11,0,'edit my topics',1,1),(607,4,11,0,'edit all topics',1,1),(608,4,11,0,'delete my topics',1,1),(609,4,11,0,'delete all topics',1,1),(610,4,11,0,'edit my posts',1,1),(611,4,11,0,'edit all posts',1,1),(612,4,11,0,'delete my posts',1,1),(613,4,11,0,'delete all posts',1,1),(614,3,11,0,'view all topics',1,1),(615,3,11,0,'view my topics',0,1),(616,3,11,0,'create new topic',1,1),(617,3,11,0,'reply to all topics',1,1),(618,3,11,0,'edit my topics',1,1),(619,3,11,0,'edit all topics',1,1),(620,3,11,0,'delete my topics',1,1),(621,3,11,0,'delete all topics',1,1),(622,3,11,0,'edit my posts',1,1),(623,3,11,0,'edit all posts',1,1),(624,3,11,0,'delete my posts',1,1),(625,3,11,0,'delete all posts',1,1),(626,2,11,0,'view all topics',1,1),(627,2,11,0,'view my topics',0,1),(628,2,11,0,'create new topic',1,1),(629,2,11,0,'reply to all topics',1,1),(630,2,11,0,'edit my topics',1,1),(631,2,11,0,'edit all topics',0,1),(632,2,11,0,'delete my topics',1,1),(633,2,11,0,'delete all topics',0,1),(634,2,11,0,'edit my posts',1,1),(635,2,11,0,'edit all posts',0,1),(636,2,11,0,'delete my posts',1,1),(637,2,11,0,'delete all posts',0,1),(638,1,11,0,'view all topics',1,1),(639,1,11,0,'view my topics',0,1),(640,1,11,0,'create new topic',0,1),(641,1,11,0,'reply to all topics',0,1),(642,1,11,0,'edit my topics',0,1),(643,1,11,0,'edit all topics',0,1),(644,1,11,0,'delete my topics',0,1),(645,1,11,0,'delete all topics',0,1),(646,1,11,0,'edit my posts',0,1),(647,1,11,0,'edit all posts',0,1),(648,1,11,0,'delete my posts',0,1),(649,1,11,0,'delete all posts',0,1),(650,6,12,0,'view all topics',0,1),(651,6,12,0,'view my topics',0,1),(652,6,12,0,'create new topic',0,1),(653,6,12,0,'reply to all topics',0,1),(654,6,12,0,'edit my topics',0,1),(655,6,12,0,'edit all topics',0,1),(656,6,12,0,'delete my topics',0,1),(657,6,12,0,'delete all topics',0,1),(658,6,12,0,'edit my posts',0,1),(659,6,12,0,'edit all posts',0,1),(660,6,12,0,'delete my posts',0,1),(661,6,12,0,'delete all posts',0,1),(662,5,12,0,'view all topics',1,1),(663,5,12,0,'view my topics',0,1),(664,5,12,0,'create new topic',0,1),(665,5,12,0,'reply to all topics',0,1),(666,5,12,0,'edit my topics',0,1),(667,5,12,0,'edit all topics',0,1),(668,5,12,0,'delete my topics',0,1),(669,5,12,0,'delete all topics',0,1),(670,5,12,0,'edit my posts',0,1),(671,5,12,0,'edit all posts',0,1),(672,5,12,0,'delete my posts',0,1),(673,5,12,0,'delete all posts',0,1),(674,4,12,0,'view all topics',1,1),(675,4,12,0,'view my topics',0,1),(676,4,12,0,'create new topic',1,1),(677,4,12,0,'reply to all topics',1,1),(678,4,12,0,'edit my topics',1,1),(679,4,12,0,'edit all topics',1,1),(680,4,12,0,'delete my topics',1,1),(681,4,12,0,'delete all topics',1,1),(682,4,12,0,'edit my posts',1,1),(683,4,12,0,'edit all posts',1,1),(684,4,12,0,'delete my posts',1,1),(685,4,12,0,'delete all posts',1,1),(686,3,12,0,'view all topics',1,1),(687,3,12,0,'view my topics',0,1),(688,3,12,0,'create new topic',1,1),(689,3,12,0,'reply to all topics',1,1),(690,3,12,0,'edit my topics',1,1),(691,3,12,0,'edit all topics',1,1),(692,3,12,0,'delete my topics',1,1),(693,3,12,0,'delete all topics',1,1),(694,3,12,0,'edit my posts',1,1),(695,3,12,0,'edit all posts',1,1),(696,3,12,0,'delete my posts',1,1),(697,3,12,0,'delete all posts',1,1),(698,2,12,0,'view all topics',1,1),(699,2,12,0,'view my topics',0,1),(700,2,12,0,'create new topic',1,1),(701,2,12,0,'reply to all topics',1,1),(702,2,12,0,'edit my topics',1,1),(703,2,12,0,'edit all topics',0,1),(704,2,12,0,'delete my topics',1,1),(705,2,12,0,'delete all topics',0,1),(706,2,12,0,'edit my posts',1,1),(707,2,12,0,'edit all posts',0,1),(708,2,12,0,'delete my posts',1,1),(709,2,12,0,'delete all posts',0,1),(710,1,12,0,'view all topics',1,1),(711,1,12,0,'view my topics',0,1),(712,1,12,0,'create new topic',0,1),(713,1,12,0,'reply to all topics',0,1),(714,1,12,0,'edit my topics',0,1),(715,1,12,0,'edit all topics',0,1),(716,1,12,0,'delete my topics',0,1),(717,1,12,0,'delete all topics',0,1),(718,1,12,0,'edit my posts',0,1),(719,1,12,0,'edit all posts',0,1),(720,1,12,0,'delete my posts',0,1),(721,1,12,0,'delete all posts',0,1),(722,6,13,0,'view all topics',0,1),(723,6,13,0,'view my topics',0,1),(724,6,13,0,'create new topic',0,1),(725,6,13,0,'reply to all topics',0,1),(726,6,13,0,'edit my topics',0,1),(727,6,13,0,'edit all topics',0,1),(728,6,13,0,'delete my topics',0,1),(729,6,13,0,'delete all topics',0,1),(730,6,13,0,'edit my posts',0,1),(731,6,13,0,'edit all posts',0,1),(732,6,13,0,'delete my posts',0,1),(733,6,13,0,'delete all posts',0,1),(734,5,13,0,'view all topics',1,1),(735,5,13,0,'view my topics',0,1),(736,5,13,0,'create new topic',0,1),(737,5,13,0,'reply to all topics',0,1),(738,5,13,0,'edit my topics',0,1),(739,5,13,0,'edit all topics',0,1),(740,5,13,0,'delete my topics',0,1),(741,5,13,0,'delete all topics',0,1),(742,5,13,0,'edit my posts',0,1),(743,5,13,0,'edit all posts',0,1),(744,5,13,0,'delete my posts',0,1),(745,5,13,0,'delete all posts',0,1),(746,4,13,0,'view all topics',1,1),(747,4,13,0,'view my topics',0,1),(748,4,13,0,'create new topic',1,1),(749,4,13,0,'reply to all topics',1,1),(750,4,13,0,'edit my topics',1,1),(751,4,13,0,'edit all topics',1,1),(752,4,13,0,'delete my topics',1,1),(753,4,13,0,'delete all topics',1,1),(754,4,13,0,'edit my posts',1,1),(755,4,13,0,'edit all posts',1,1),(756,4,13,0,'delete my posts',1,1),(757,4,13,0,'delete all posts',1,1),(758,3,13,0,'view all topics',1,1),(759,3,13,0,'view my topics',0,1),(760,3,13,0,'create new topic',1,1),(761,3,13,0,'reply to all topics',1,1),(762,3,13,0,'edit my topics',1,1),(763,3,13,0,'edit all topics',1,1),(764,3,13,0,'delete my topics',1,1),(765,3,13,0,'delete all topics',1,1),(766,3,13,0,'edit my posts',1,1),(767,3,13,0,'edit all posts',1,1),(768,3,13,0,'delete my posts',1,1),(769,3,13,0,'delete all posts',1,1),(770,2,13,0,'view all topics',1,1),(771,2,13,0,'view my topics',0,1),(772,2,13,0,'create new topic',1,1),(773,2,13,0,'reply to all topics',1,1),(774,2,13,0,'edit my topics',1,1),(775,2,13,0,'edit all topics',0,1),(776,2,13,0,'delete my topics',1,1),(777,2,13,0,'delete all topics',0,1),(778,2,13,0,'edit my posts',1,1),(779,2,13,0,'edit all posts',0,1),(780,2,13,0,'delete my posts',1,1),(781,2,13,0,'delete all posts',0,1),(782,1,13,0,'view all topics',1,1),(783,1,13,0,'view my topics',0,1),(784,1,13,0,'create new topic',0,1),(785,1,13,0,'reply to all topics',0,1),(786,1,13,0,'edit my topics',0,1),(787,1,13,0,'edit all topics',0,1),(788,1,13,0,'delete my topics',0,1),(789,1,13,0,'delete all topics',0,1),(790,1,13,0,'edit my posts',0,1),(791,1,13,0,'edit all posts',0,1),(792,1,13,0,'delete my posts',0,1),(793,1,13,0,'delete all posts',0,1),(794,6,14,0,'view all topics',0,1),(795,6,14,0,'view my topics',0,1),(796,6,14,0,'create new topic',0,1),(797,6,14,0,'reply to all topics',0,1),(798,6,14,0,'edit my topics',0,1),(799,6,14,0,'edit all topics',0,1),(800,6,14,0,'delete my topics',0,1),(801,6,14,0,'delete all topics',0,1),(802,6,14,0,'edit my posts',0,1),(803,6,14,0,'edit all posts',0,1),(804,6,14,0,'delete my posts',0,1),(805,6,14,0,'delete all posts',0,1),(806,5,14,0,'view all topics',1,1),(807,5,14,0,'view my topics',0,1),(808,5,14,0,'create new topic',0,1),(809,5,14,0,'reply to all topics',0,1),(810,5,14,0,'edit my topics',0,1),(811,5,14,0,'edit all topics',0,1),(812,5,14,0,'delete my topics',0,1),(813,5,14,0,'delete all topics',0,1),(814,5,14,0,'edit my posts',0,1),(815,5,14,0,'edit all posts',0,1),(816,5,14,0,'delete my posts',0,1),(817,5,14,0,'delete all posts',0,1),(818,4,14,0,'view all topics',1,1),(819,4,14,0,'view my topics',0,1),(820,4,14,0,'create new topic',1,1),(821,4,14,0,'reply to all topics',1,1),(822,4,14,0,'edit my topics',1,1),(823,4,14,0,'edit all topics',1,1),(824,4,14,0,'delete my topics',1,1),(825,4,14,0,'delete all topics',1,1),(826,4,14,0,'edit my posts',1,1),(827,4,14,0,'edit all posts',1,1),(828,4,14,0,'delete my posts',1,1),(829,4,14,0,'delete all posts',1,1),(830,3,14,0,'view all topics',1,1),(831,3,14,0,'view my topics',0,1),(832,3,14,0,'create new topic',1,1),(833,3,14,0,'reply to all topics',1,1),(834,3,14,0,'edit my topics',1,1),(835,3,14,0,'edit all topics',1,1),(836,3,14,0,'delete my topics',1,1),(837,3,14,0,'delete all topics',1,1),(838,3,14,0,'edit my posts',1,1),(839,3,14,0,'edit all posts',1,1),(840,3,14,0,'delete my posts',1,1),(841,3,14,0,'delete all posts',1,1),(842,2,14,0,'view all topics',1,1),(843,2,14,0,'view my topics',0,1),(844,2,14,0,'create new topic',1,1),(845,2,14,0,'reply to all topics',1,1),(846,2,14,0,'edit my topics',1,1),(847,2,14,0,'edit all topics',0,1),(848,2,14,0,'delete my topics',1,1),(849,2,14,0,'delete all topics',0,1),(850,2,14,0,'edit my posts',1,1),(851,2,14,0,'edit all posts',0,1),(852,2,14,0,'delete my posts',1,1),(853,2,14,0,'delete all posts',0,1),(854,1,14,0,'view all topics',1,1),(855,1,14,0,'view my topics',0,1),(856,1,14,0,'create new topic',0,1),(857,1,14,0,'reply to all topics',0,1),(858,1,14,0,'edit my topics',0,1),(859,1,14,0,'edit all topics',0,1),(860,1,14,0,'delete my topics',0,1),(861,1,14,0,'delete all topics',0,1),(862,1,14,0,'edit my posts',0,1),(863,1,14,0,'edit all posts',0,1),(864,1,14,0,'delete my posts',0,1),(865,1,14,0,'delete all posts',0,1),(866,1,0,0,'view category',1,-1),(867,1,3,0,'view category',1,1),(868,1,10,0,'view category',1,1),(869,1,11,0,'view category',1,1),(870,1,12,0,'view category',1,1),(871,1,13,0,'view category',1,1),(872,1,14,0,'view category',1,1),(873,2,0,0,'view category',1,-1),(874,2,3,0,'view category',1,1),(875,2,10,0,'view category',1,1),(876,2,11,0,'view category',1,1),(877,2,12,0,'view category',1,1),(878,2,13,0,'view category',1,1),(879,2,14,0,'view category',1,1),(880,3,0,0,'view category',1,-1),(881,3,3,0,'view category',1,1),(882,3,10,0,'view category',1,1),(883,3,11,0,'view category',1,1),(884,3,12,0,'view category',1,1),(885,3,13,0,'view category',1,1),(886,3,14,0,'view category',1,1),(887,4,0,0,'view category',1,-1),(888,4,3,0,'view category',1,1),(889,4,10,0,'view category',1,1),(890,4,11,0,'view category',1,1),(891,4,12,0,'view category',1,1),(892,4,13,0,'view category',1,1),(893,4,14,0,'view category',1,1),(894,5,0,0,'view category',1,-1),(895,5,3,0,'view category',1,1),(896,5,10,0,'view category',1,1),(897,5,11,0,'view category',1,1),(898,5,12,0,'view category',1,1),(899,5,13,0,'view category',1,1),(900,5,14,0,'view category',1,1),(901,6,0,0,'view category',1,-1),(902,6,3,0,'view category',1,1),(903,6,10,0,'view category',1,1),(904,6,11,0,'view category',1,1),(905,6,12,0,'view category',1,1),(906,6,13,0,'view category',1,1),(907,6,14,0,'view category',1,1),(908,1,0,0,'move topics',0,-1),(909,1,3,0,'move topics',0,1),(910,1,10,0,'move topics',0,1),(911,1,11,0,'move topics',0,1),(912,1,12,0,'move topics',0,1),(913,1,13,0,'move topics',0,1),(914,1,14,0,'move topics',0,1),(915,2,0,0,'move topics',0,-1),(916,2,3,0,'move topics',0,1),(917,2,10,0,'move topics',0,1),(918,2,11,0,'move topics',0,1),(919,2,12,0,'move topics',0,1),(920,2,13,0,'move topics',0,1),(921,2,14,0,'move topics',0,1),(922,3,0,0,'move topics',1,-1),(923,3,3,0,'move topics',1,1),(924,3,10,0,'move topics',1,1),(925,3,11,0,'move topics',1,1),(926,3,12,0,'move topics',1,1),(927,3,13,0,'move topics',1,1),(928,3,14,0,'move topics',1,1),(929,4,0,0,'move topics',1,-1),(930,4,3,0,'move topics',1,1),(931,4,10,0,'move topics',1,1),(932,4,11,0,'move topics',1,1),(933,4,12,0,'move topics',1,1),(934,4,13,0,'move topics',1,1),(935,4,14,0,'move topics',1,1),(936,5,0,0,'move topics',0,-1),(937,5,3,0,'move topics',0,1),(938,5,10,0,'move topics',0,1),(939,5,11,0,'move topics',0,1),(940,5,12,0,'move topics',0,1),(941,5,13,0,'move topics',0,1),(942,5,14,0,'move topics',0,1),(943,6,0,0,'move topics',0,-1),(944,6,3,0,'move topics',0,1),(945,6,10,0,'move topics',0,1),(946,6,11,0,'move topics',0,1),(947,6,12,0,'move topics',0,1),(948,6,13,0,'move topics',0,1),(949,6,14,0,'move topics',0,1),(950,1,0,0,'moderate topics',0,-1),(951,1,3,0,'moderate topics',0,1),(952,1,10,0,'moderate topics',0,1),(953,1,11,0,'moderate topics',0,1),(954,1,12,0,'moderate topics',0,1),(955,1,13,0,'moderate topics',0,1),(956,1,14,0,'moderate topics',0,1),(957,2,0,0,'moderate topics',0,-1),(958,2,3,0,'moderate topics',0,1),(959,2,10,0,'moderate topics',0,1),(960,2,11,0,'moderate topics',0,1),(961,2,12,0,'moderate topics',0,1),(962,2,13,0,'moderate topics',0,1),(963,2,14,0,'moderate topics',0,1),(964,3,0,0,'moderate topics',1,-1),(965,3,3,0,'moderate topics',1,1),(966,3,10,0,'moderate topics',1,1),(967,3,11,0,'moderate topics',1,1),(968,3,12,0,'moderate topics',1,1),(969,3,13,0,'moderate topics',1,1),(970,3,14,0,'moderate topics',1,1),(971,4,0,0,'moderate topics',1,-1),(972,4,3,0,'moderate topics',1,1),(973,4,10,0,'moderate topics',1,1),(974,4,11,0,'moderate topics',1,1),(975,4,12,0,'moderate topics',1,1),(976,4,13,0,'moderate topics',1,1),(977,4,14,0,'moderate topics',1,1),(978,5,0,0,'moderate topics',0,-1),(979,5,3,0,'moderate topics',0,1),(980,5,10,0,'moderate topics',0,1),(981,5,11,0,'moderate topics',0,1),(982,5,12,0,'moderate topics',0,1),(983,5,13,0,'moderate topics',0,1),(984,5,14,0,'moderate topics',0,1),(985,6,0,0,'moderate topics',0,-1),(986,6,3,0,'moderate topics',0,1),(987,6,10,0,'moderate topics',0,1),(988,6,11,0,'moderate topics',0,1),(989,6,12,0,'moderate topics',0,1),(990,6,13,0,'moderate topics',0,1),(991,6,14,0,'moderate topics',0,1),(992,1,0,0,'moderate posts',0,-1),(993,1,3,0,'moderate posts',0,1),(994,1,10,0,'moderate posts',0,1),(995,1,11,0,'moderate posts',0,1),(996,1,12,0,'moderate posts',0,1),(997,1,13,0,'moderate posts',0,1),(998,1,14,0,'moderate posts',0,1),(999,2,0,0,'moderate posts',0,-1),(1000,2,3,0,'moderate posts',0,1),(1001,2,10,0,'moderate posts',0,1),(1002,2,11,0,'moderate posts',0,1),(1003,2,12,0,'moderate posts',0,1),(1004,2,13,0,'moderate posts',0,1),(1005,2,14,0,'moderate posts',0,1),(1006,3,0,0,'moderate posts',1,-1),(1007,3,3,0,'moderate posts',1,1),(1008,3,10,0,'moderate posts',1,1),(1009,3,11,0,'moderate posts',1,1),(1010,3,12,0,'moderate posts',1,1),(1011,3,13,0,'moderate posts',1,1),(1012,3,14,0,'moderate posts',1,1),(1013,4,0,0,'moderate posts',1,-1),(1014,4,3,0,'moderate posts',1,1),(1015,4,10,0,'moderate posts',1,1),(1016,4,11,0,'moderate posts',1,1),(1017,4,12,0,'moderate posts',1,1),(1018,4,13,0,'moderate posts',1,1),(1019,4,14,0,'moderate posts',1,1),(1020,5,0,0,'moderate posts',0,-1),(1021,5,3,0,'moderate posts',0,1),(1022,5,10,0,'moderate posts',0,1),(1023,5,11,0,'moderate posts',0,1),(1024,5,12,0,'moderate posts',0,1),(1025,5,13,0,'moderate posts',0,1),(1026,5,14,0,'moderate posts',0,1),(1027,6,0,0,'moderate posts',0,-1),(1028,6,3,0,'moderate posts',0,1),(1029,6,10,0,'moderate posts',0,1),(1030,6,11,0,'moderate posts',0,1),(1031,6,12,0,'moderate posts',0,1),(1032,6,13,0,'moderate posts',0,1),(1033,6,14,0,'moderate posts',0,1),(1034,1,0,0,'make sticky',0,-1),(1035,1,3,0,'make sticky',0,1),(1036,1,10,0,'make sticky',0,1),(1037,1,11,0,'make sticky',0,1),(1038,1,12,0,'make sticky',0,1),(1039,1,13,0,'make sticky',0,1),(1040,1,14,0,'make sticky',0,1),(1041,2,0,0,'make sticky',0,-1),(1042,2,3,0,'make sticky',0,1),(1043,2,10,0,'make sticky',0,1),(1044,2,11,0,'make sticky',0,1),(1045,2,12,0,'make sticky',0,1),(1046,2,13,0,'make sticky',0,1),(1047,2,14,0,'make sticky',0,1),(1048,3,0,0,'make sticky',1,-1),(1049,3,3,0,'make sticky',1,1),(1050,3,10,0,'make sticky',1,1),(1051,3,11,0,'make sticky',1,1),(1052,3,12,0,'make sticky',1,1),(1053,3,13,0,'make sticky',1,1),(1054,3,14,0,'make sticky',1,1),(1055,4,0,0,'make sticky',1,-1),(1056,4,3,0,'make sticky',1,1),(1057,4,10,0,'make sticky',1,1),(1058,4,11,0,'make sticky',1,1),(1059,4,12,0,'make sticky',1,1),(1060,4,13,0,'make sticky',1,1),(1061,4,14,0,'make sticky',1,1),(1062,5,0,0,'make sticky',0,-1),(1063,5,3,0,'make sticky',0,1),(1064,5,10,0,'make sticky',0,1),(1065,5,11,0,'make sticky',0,1),(1066,5,12,0,'make sticky',0,1),(1067,5,13,0,'make sticky',0,1),(1068,5,14,0,'make sticky',0,1),(1069,6,0,0,'make sticky',0,-1),(1070,6,3,0,'make sticky',0,1),(1071,6,10,0,'make sticky',0,1),(1072,6,11,0,'make sticky',0,1),(1073,6,12,0,'make sticky',0,1),(1074,6,13,0,'make sticky',0,1),(1075,6,14,0,'make sticky',0,1),(1076,1,0,0,'edit profile',1,-1),(1077,1,3,0,'edit profile',1,1),(1078,1,10,0,'edit profile',1,1),(1079,1,11,0,'edit profile',1,1),(1080,1,12,0,'edit profile',1,1),(1081,1,13,0,'edit profile',1,1),(1082,1,14,0,'edit profile',1,1),(1083,2,0,0,'edit profile',1,-1),(1084,2,3,0,'edit profile',1,1),(1085,2,10,0,'edit profile',1,1),(1086,2,11,0,'edit profile',1,1),(1087,2,12,0,'edit profile',1,1),(1088,2,13,0,'edit profile',1,1),(1089,2,14,0,'edit profile',1,1),(1090,3,0,0,'edit profile',1,-1),(1091,3,3,0,'edit profile',1,1),(1092,3,10,0,'edit profile',1,1),(1093,3,11,0,'edit profile',1,1),(1094,3,12,0,'edit profile',1,1),(1095,3,13,0,'edit profile',1,1),(1096,3,14,0,'edit profile',1,1),(1097,4,0,0,'edit profile',1,-1),(1098,4,3,0,'edit profile',1,1),(1099,4,10,0,'edit profile',1,1),(1100,4,11,0,'edit profile',1,1),(1101,4,12,0,'edit profile',1,1),(1102,4,13,0,'edit profile',1,1),(1103,4,14,0,'edit profile',1,1),(1104,5,0,0,'edit profile',1,-1),(1105,5,3,0,'edit profile',1,1),(1106,5,10,0,'edit profile',1,1),(1107,5,11,0,'edit profile',1,1),(1108,5,12,0,'edit profile',1,1),(1109,5,13,0,'edit profile',1,1),(1110,5,14,0,'edit profile',1,1),(1111,6,0,0,'edit profile',1,-1),(1112,6,3,0,'edit profile',1,1),(1113,6,10,0,'edit profile',1,1),(1114,6,11,0,'edit profile',1,1),(1115,6,12,0,'edit profile',1,1),(1116,6,13,0,'edit profile',1,1),(1117,6,14,0,'edit profile',1,1),(1118,1,0,0,'see history',0,-1),(1119,1,3,0,'see history',0,1),(1120,1,10,0,'see history',0,1),(1121,1,11,0,'see history',0,1),(1122,1,12,0,'see history',0,1),(1123,1,13,0,'see history',0,1),(1124,1,14,0,'see history',0,1),(1125,2,0,0,'see history',1,-1),(1126,2,3,0,'see history',1,1),(1127,2,10,0,'see history',1,1),(1128,2,11,0,'see history',1,1),(1129,2,12,0,'see history',1,1),(1130,2,13,0,'see history',1,1),(1131,2,14,0,'see history',1,1),(1132,3,0,0,'see history',1,-1),(1133,3,3,0,'see history',1,1),(1134,3,10,0,'see history',1,1),(1135,3,11,0,'see history',1,1),(1136,3,12,0,'see history',1,1),(1137,3,13,0,'see history',1,1),(1138,3,14,0,'see history',1,1),(1139,4,0,0,'see history',1,-1),(1140,4,3,0,'see history',1,1),(1141,4,10,0,'see history',1,1),(1142,4,11,0,'see history',1,1),(1143,4,12,0,'see history',1,1),(1144,4,13,0,'see history',1,1),(1145,4,14,0,'see history',1,1),(1146,5,0,0,'see history',0,-1),(1147,5,3,0,'see history',0,1),(1148,5,10,0,'see history',0,1),(1149,5,11,0,'see history',0,1),(1150,5,12,0,'see history',0,1),(1151,5,13,0,'see history',0,1),(1152,5,14,0,'see history',0,1),(1153,6,0,0,'see history',0,-1),(1154,6,3,0,'see history',0,1),(1155,6,10,0,'see history',0,1),(1156,6,11,0,'see history',0,1),(1157,6,12,0,'see history',0,1),(1158,6,13,0,'see history',0,1),(1159,6,14,0,'see history',0,1),(1160,1,0,0,'rep up',0,-1),(1161,1,3,0,'rep up',0,1),(1162,1,10,0,'rep up',0,1),(1163,1,11,0,'rep up',0,1),(1164,1,12,0,'rep up',0,1),(1165,1,13,0,'rep up',0,1),(1166,1,14,0,'rep up',0,1),(1167,2,0,0,'rep up',1,-1),(1168,2,3,0,'rep up',1,1),(1169,2,10,0,'rep up',1,1),(1170,2,11,0,'rep up',1,1),(1171,2,12,0,'rep up',1,1),(1172,2,13,0,'rep up',1,1),(1173,2,14,0,'rep up',1,1),(1174,3,0,0,'rep up',1,-1),(1175,3,3,0,'rep up',1,1),(1176,3,10,0,'rep up',1,1),(1177,3,11,0,'rep up',1,1),(1178,3,12,0,'rep up',1,1),(1179,3,13,0,'rep up',1,1),(1180,3,14,0,'rep up',1,1),(1181,4,0,0,'rep up',1,-1),(1182,4,3,0,'rep up',1,1),(1183,4,10,0,'rep up',1,1),(1184,4,11,0,'rep up',1,1),(1185,4,12,0,'rep up',1,1),(1186,4,13,0,'rep up',1,1),(1187,4,14,0,'rep up',1,1),(1188,5,0,0,'rep up',0,-1),(1189,5,3,0,'rep up',0,1),(1190,5,10,0,'rep up',0,1),(1191,5,11,0,'rep up',0,1),(1192,5,12,0,'rep up',0,1),(1193,5,13,0,'rep up',0,1),(1194,5,14,0,'rep up',0,1),(1195,6,0,0,'rep up',0,-1),(1196,6,3,0,'rep up',0,1),(1197,6,10,0,'rep up',0,1),(1198,6,11,0,'rep up',0,1),(1199,6,12,0,'rep up',0,1),(1200,6,13,0,'rep up',0,1),(1201,6,14,0,'rep up',0,1),(1202,1,0,0,'rep down',0,-1),(1203,1,3,0,'rep down',0,1),(1204,1,10,0,'rep down',0,1),(1205,1,11,0,'rep down',0,1),(1206,1,12,0,'rep down',0,1),(1207,1,13,0,'rep down',0,1),(1208,1,14,0,'rep down',0,1),(1209,2,0,0,'rep down',1,-1),(1210,2,3,0,'rep down',1,1),(1211,2,10,0,'rep down',1,1),(1212,2,11,0,'rep down',1,1),(1213,2,12,0,'rep down',1,1),(1214,2,13,0,'rep down',1,1),(1215,2,14,0,'rep down',1,1),(1216,3,0,0,'rep down',1,-1),(1217,3,3,0,'rep down',1,1),(1218,3,10,0,'rep down',1,1),(1219,3,11,0,'rep down',1,1),(1220,3,12,0,'rep down',1,1),(1221,3,13,0,'rep down',1,1),(1222,3,14,0,'rep down',1,1),(1223,4,0,0,'rep down',1,-1),(1224,4,3,0,'rep down',1,1),(1225,4,10,0,'rep down',1,1),(1226,4,11,0,'rep down',1,1),(1227,4,12,0,'rep down',1,1),(1228,4,13,0,'rep down',1,1),(1229,4,14,0,'rep down',1,1),(1230,5,0,0,'rep down',0,-1),(1231,5,3,0,'rep down',0,1),(1232,5,10,0,'rep down',0,1),(1233,5,11,0,'rep down',0,1),(1234,5,12,0,'rep down',0,1),(1235,5,13,0,'rep down',0,1),(1236,5,14,0,'rep down',0,1),(1237,6,0,0,'rep down',0,-1),(1238,6,3,0,'rep down',0,1),(1239,6,10,0,'rep down',0,1),(1240,6,11,0,'rep down',0,1),(1241,6,12,0,'rep down',0,1),(1242,6,13,0,'rep down',0,1),(1243,6,14,0,'rep down',0,1),(1244,1,0,0,'merge topics',0,-1),(1245,1,3,0,'merge topics',0,1),(1246,1,10,0,'merge topics',0,1),(1247,1,11,0,'merge topics',0,1),(1248,1,12,0,'merge topics',0,1),(1249,1,13,0,'merge topics',0,1),(1250,1,14,0,'merge topics',0,1),(1251,2,0,0,'merge topics',0,-1),(1252,2,3,0,'merge topics',0,1),(1253,2,10,0,'merge topics',0,1),(1254,2,11,0,'merge topics',0,1),(1255,2,12,0,'merge topics',0,1),(1256,2,13,0,'merge topics',0,1),(1257,2,14,0,'merge topics',0,1),(1258,3,0,0,'merge topics',1,-1),(1259,3,3,0,'merge topics',1,1),(1260,3,10,0,'merge topics',1,1),(1261,3,11,0,'merge topics',1,1),(1262,3,12,0,'merge topics',1,1),(1263,3,13,0,'merge topics',1,1),(1264,3,14,0,'merge topics',1,1),(1265,4,0,0,'merge topics',1,-1),(1266,4,3,0,'merge topics',1,1),(1267,4,10,0,'merge topics',1,1),(1268,4,11,0,'merge topics',1,1),(1269,4,12,0,'merge topics',1,1),(1270,4,13,0,'merge topics',1,1),(1271,4,14,0,'merge topics',1,1),(1272,5,0,0,'merge topics',0,-1),(1273,5,3,0,'merge topics',0,1),(1274,5,10,0,'merge topics',0,1),(1275,5,11,0,'merge topics',0,1),(1276,5,12,0,'merge topics',0,1),(1277,5,13,0,'merge topics',0,1),(1278,5,14,0,'merge topics',0,1),(1279,6,0,0,'merge topics',0,-1),(1280,6,3,0,'merge topics',0,1),(1281,6,10,0,'merge topics',0,1),(1282,6,11,0,'merge topics',0,1),(1283,6,12,0,'merge topics',0,1),(1284,6,13,0,'merge topics',0,1),(1285,6,14,0,'merge topics',0,1),(1286,1,0,0,'add tags',0,-1),(1287,1,3,0,'add tags',0,1),(1288,1,10,0,'add tags',0,1),(1289,1,11,0,'add tags',0,1),(1290,1,12,0,'add tags',0,1),(1291,1,13,0,'add tags',0,1),(1292,1,14,0,'add tags',0,1),(1293,2,0,0,'add tags',1,-1),(1294,2,3,0,'add tags',1,1),(1295,2,10,0,'add tags',1,1),(1296,2,11,0,'add tags',1,1),(1297,2,12,0,'add tags',1,1),(1298,2,13,0,'add tags',1,1),(1299,2,14,0,'add tags',1,1),(1300,3,0,0,'add tags',1,-1),(1301,3,3,0,'add tags',1,1),(1302,3,10,0,'add tags',1,1),(1303,3,11,0,'add tags',1,1),(1304,3,12,0,'add tags',1,1),(1305,3,13,0,'add tags',1,1),(1306,3,14,0,'add tags',1,1),(1307,4,0,0,'add tags',1,-1),(1308,4,3,0,'add tags',1,1),(1309,4,10,0,'add tags',1,1),(1310,4,11,0,'add tags',1,1),(1311,4,12,0,'add tags',1,1),(1312,4,13,0,'add tags',1,1),(1313,4,14,0,'add tags',1,1),(1314,5,0,0,'add tags',0,-1),(1315,5,3,0,'add tags',0,1),(1316,5,10,0,'add tags',0,1),(1317,5,11,0,'add tags',0,1),(1318,5,12,0,'add tags',0,1),(1319,5,13,0,'add tags',0,1),(1320,5,14,0,'add tags',0,1),(1321,6,0,0,'add tags',0,-1),(1322,6,3,0,'add tags',0,1),(1323,6,10,0,'add tags',0,1),(1324,6,11,0,'add tags',0,1),(1325,6,12,0,'add tags',0,1),(1326,6,13,0,'add tags',0,1),(1327,6,14,0,'add tags',0,1);
/*!40000 ALTER TABLE `codo_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `codo_plugins`
--

DROP TABLE IF EXISTS `codo_plugins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `codo_plugins` (
  `plg_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `plg_type` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `plg_status` int(11) NOT NULL,
  `plg_weight` int(11) NOT NULL,
  `plg_schema_ver` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  UNIQUE KEY `codo_plugins_plg_name_unique` (`plg_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `codo_plugins`
--

LOCK TABLES `codo_plugins` WRITE;
/*!40000 ALTER TABLE `codo_plugins` DISABLE KEYS */;
INSERT INTO `codo_plugins` VALUES ('sso','plugin',1,0,'2.x'),('uni_login','plugin',0,0,'1'),('autologin','plugin',1,0,'1.x');
/*!40000 ALTER TABLE `codo_plugins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `codo_posts`
--

DROP TABLE IF EXISTS `codo_posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `codo_posts` (
  `post_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `topic_id` int(11) NOT NULL,
  `cat_id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `imessage` text COLLATE utf8_unicode_ci NOT NULL,
  `omessage` text COLLATE utf8_unicode_ci NOT NULL,
  `post_created` int(11) NOT NULL,
  `post_modified` int(11) DEFAULT NULL,
  `post_status` int(11) NOT NULL DEFAULT '1',
  `reputation` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`post_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `codo_posts`
--

LOCK TABLES `codo_posts` WRITE;
/*!40000 ALTER TABLE `codo_posts` DISABLE KEYS */;
INSERT INTO `codo_posts` VALUES (1,1,3,1,'Hi,  \n  \nThis is an example post in your codoforum installation.   \nYou can create/modify/delete all forum categories from the forum backend.  \n  \nPlease edit the forum title and description from the backend.   \n  \nThe only user available to login in the front-end is admin with the password that you set during the installation.\n \nYou may delete this post . \n  \nRegards,   \nCodologic Team','<p>Hi,  </p>\n<p>This is an example post in your codoforum installation.<br>You can create/modify/delete all forum categories from the forum backend.  </p>\n<p>Please edit the forum title and description from the backend.   </p>\n<p>The only user available to login in the front-end is admin with the password that you set during the installation.</p>\n<p>You may delete this post . </p>\n<p>Regards,<br>Codologic Team</p>',1401549322,NULL,1,0);
/*!40000 ALTER TABLE `codo_posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `codo_promotion_rules`
--

DROP TABLE IF EXISTS `codo_promotion_rules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `codo_promotion_rules` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `reputation` int(11) NOT NULL,
  `posts` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `rid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `codo_promotion_rules`
--

LOCK TABLES `codo_promotion_rules` WRITE;
/*!40000 ALTER TABLE `codo_promotion_rules` DISABLE KEYS */;
/*!40000 ALTER TABLE `codo_promotion_rules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `codo_reputation`
--

DROP TABLE IF EXISTS `codo_reputation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `codo_reputation` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `from_id` int(11) NOT NULL,
  `to_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `points` int(11) NOT NULL,
  `rep_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `codo_reputation`
--

LOCK TABLES `codo_reputation` WRITE;
/*!40000 ALTER TABLE `codo_reputation` DISABLE KEYS */;
/*!40000 ALTER TABLE `codo_reputation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `codo_roles`
--

DROP TABLE IF EXISTS `codo_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `codo_roles` (
  `rid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rname` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`rid`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `codo_roles`
--

LOCK TABLES `codo_roles` WRITE;
/*!40000 ALTER TABLE `codo_roles` DISABLE KEYS */;
INSERT INTO `codo_roles` VALUES (1,'guest'),(2,'user'),(3,'moderator'),(4,'administrator'),(5,'unverified user'),(6,'banned');
/*!40000 ALTER TABLE `codo_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `codo_sessions`
--

DROP TABLE IF EXISTS `codo_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `codo_sessions` (
  `sid` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_active` int(11) NOT NULL,
  `session_data` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`sid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `codo_sessions`
--

LOCK TABLES `codo_sessions` WRITE;
/*!40000 ALTER TABLE `codo_sessions` DISABLE KEYS */;
INSERT INTO `codo_sessions` VALUES ('759t7s3561vhj44f2fsmmdgqh4',1458828000,'56f3e113d78a2_csrf|s:32:\"74a3aa2120625a2c321a97e526bc60f3\";56f3e113d7830A_loggedin_created|s:14:\"March 24, 2016\";56f3e113d7830A_loggedin_avatar|s:85:\"http://local-certigate.com/forum/sites/default/assets/img/profiles/icons/A_7f2020.png\";56f3e113d7830A_loggedin_username|s:5:\"admin\";56f3e113d7830A_loggedin|s:5:\"admin\";codo_zombie_crons_cleanup|b:1;56f3e113d7830USER|a:1:{s:2:\"id\";s:1:\"1\";}');
/*!40000 ALTER TABLE `codo_sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `codo_signups`
--

DROP TABLE IF EXISTS `codo_signups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `codo_signups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `codo_signups`
--

LOCK TABLES `codo_signups` WRITE;
/*!40000 ALTER TABLE `codo_signups` DISABLE KEYS */;
/*!40000 ALTER TABLE `codo_signups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `codo_smileys`
--

DROP TABLE IF EXISTS `codo_smileys`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `codo_smileys` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `symbol` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `image_name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `weight` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `codo_smileys`
--

LOCK TABLES `codo_smileys` WRITE;
/*!40000 ALTER TABLE `codo_smileys` DISABLE KEYS */;
INSERT INTO `codo_smileys` VALUES (1,':S','worried.gif',NULL),(2,'(wasntme)','itwasntme.gif',NULL),(3,'x(','angry.gif',NULL),(4,'(doh)','doh.gif',NULL),(5,'|-()','yawn.gif',NULL),(6,']:)','evilgrin.gif',NULL),(7,'|(','dull.gif',NULL),(8,'|-)','sleepy.gif',NULL),(9,'(blush)','blush.gif',NULL),(10,':P','tongueout.gif',NULL),(11,'(:|','sweat.gif',NULL),(12,';(','crying.gif',NULL),(13,':)','smile.gif',NULL),(14,':(','sad.gif',NULL),(15,':D','bigsmile.gif',NULL),(16,'8)','cool.gif',NULL),(17,';)','wink.gif',NULL),(18,'(mm)','mmm.gif',NULL),(19,':x','lipssealed.gif',NULL);
/*!40000 ALTER TABLE `codo_smileys` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `codo_tags`
--

DROP TABLE IF EXISTS `codo_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `codo_tags` (
  `tag_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tag_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `topic_id` int(11) NOT NULL,
  PRIMARY KEY (`tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `codo_tags`
--

LOCK TABLES `codo_tags` WRITE;
/*!40000 ALTER TABLE `codo_tags` DISABLE KEYS */;
/*!40000 ALTER TABLE `codo_tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `codo_tags_allowed`
--

DROP TABLE IF EXISTS `codo_tags_allowed`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `codo_tags_allowed` (
  `tag_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tag_text` text COLLATE utf8_unicode_ci NOT NULL,
  `cat_id` int(11) NOT NULL,
  PRIMARY KEY (`tag_id`),
  KEY `codo_tags_allowed_cat_id_index` (`cat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `codo_tags_allowed`
--

LOCK TABLES `codo_tags_allowed` WRITE;
/*!40000 ALTER TABLE `codo_tags_allowed` DISABLE KEYS */;
/*!40000 ALTER TABLE `codo_tags_allowed` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `codo_topics`
--

DROP TABLE IF EXISTS `codo_topics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `codo_topics` (
  `topic_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cat_id` smallint(6) NOT NULL,
  `post_id` int(11) DEFAULT NULL,
  `uid` int(11) NOT NULL,
  `last_post_id` int(11) NOT NULL DEFAULT '0',
  `last_post_uid` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_post_name` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `topic_created` int(11) NOT NULL,
  `topic_updated` int(11) NOT NULL DEFAULT '0',
  `last_post_time` int(11) NOT NULL,
  `no_posts` int(11) NOT NULL DEFAULT '0',
  `no_views` int(11) NOT NULL DEFAULT '0',
  `topic_status` int(11) NOT NULL DEFAULT '1',
  `redirect_to` int(11) DEFAULT NULL,
  PRIMARY KEY (`topic_id`),
  KEY `codo_topics_last_post_time_index` (`last_post_time`),
  KEY `codo_topics_cat_id_uid_topic_created_index` (`cat_id`,`uid`,`topic_created`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `codo_topics`
--

LOCK TABLES `codo_topics` WRITE;
/*!40000 ALTER TABLE `codo_topics` DISABLE KEYS */;
INSERT INTO `codo_topics` VALUES (1,'Welcome to Codoforum',3,1,1,0,NULL,NULL,1401549322,0,1401549322,1,0,1,NULL);
/*!40000 ALTER TABLE `codo_topics` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `codo_unread_categories`
--

DROP TABLE IF EXISTS `codo_unread_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `codo_unread_categories` (
  `cat_id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `read_time` int(11) NOT NULL,
  PRIMARY KEY (`cat_id`,`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `codo_unread_categories`
--

LOCK TABLES `codo_unread_categories` WRITE;
/*!40000 ALTER TABLE `codo_unread_categories` DISABLE KEYS */;
/*!40000 ALTER TABLE `codo_unread_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `codo_unread_topics`
--

DROP TABLE IF EXISTS `codo_unread_topics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `codo_unread_topics` (
  `cat_id` int(11) NOT NULL,
  `topic_id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `read_time` int(11) NOT NULL,
  PRIMARY KEY (`topic_id`,`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `codo_unread_topics`
--

LOCK TABLES `codo_unread_topics` WRITE;
/*!40000 ALTER TABLE `codo_unread_topics` DISABLE KEYS */;
/*!40000 ALTER TABLE `codo_unread_topics` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `codo_user_preferences`
--

DROP TABLE IF EXISTS `codo_user_preferences`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `codo_user_preferences` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `preference` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `value` varchar(400) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `codo_user_preferences_uid_index` (`uid`),
  KEY `codo_user_preferences_preference_index` (`preference`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `codo_user_preferences`
--

LOCK TABLES `codo_user_preferences` WRITE;
/*!40000 ALTER TABLE `codo_user_preferences` DISABLE KEYS */;
INSERT INTO `codo_user_preferences` VALUES (1,0,'notification_frequency','immediate'),(2,0,'real_time_notifications','yes'),(3,0,'desktop_notifications','yes'),(4,0,'send_emails_when_online','no'),(5,0,'notification_type_on_create_topic','4'),(6,0,'notification_type_on_reply_topic','3');
/*!40000 ALTER TABLE `codo_user_preferences` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `codo_user_roles`
--

DROP TABLE IF EXISTS `codo_user_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `codo_user_roles` (
  `uid` int(11) NOT NULL,
  `rid` int(11) NOT NULL,
  `is_primary` int(11) NOT NULL,
  `is_promoted` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`,`rid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `codo_user_roles`
--

LOCK TABLES `codo_user_roles` WRITE;
/*!40000 ALTER TABLE `codo_user_roles` DISABLE KEYS */;
INSERT INTO `codo_user_roles` VALUES (1,4,1,0),(2,1,1,0);
/*!40000 ALTER TABLE `codo_user_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `codo_users`
--

DROP TABLE IF EXISTS `codo_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `codo_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pass` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mail` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `created` int(11) NOT NULL,
  `last_access` int(11) NOT NULL DEFAULT '0',
  `read_time` int(11) NOT NULL DEFAULT '0',
  `user_status` int(11) NOT NULL DEFAULT '1',
  `avatar` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `signature` text COLLATE utf8_unicode_ci,
  `no_posts` int(11) NOT NULL DEFAULT '0',
  `profile_views` int(11) NOT NULL DEFAULT '0',
  `oauth_id` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `reputation` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `codo_users_mail_unique` (`mail`),
  KEY `codo_users_username_index` (`username`),
  KEY `codo_users_mail_index` (`mail`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `codo_users`
--

LOCK TABLES `codo_users` WRITE;
/*!40000 ALTER TABLE `codo_users` DISABLE KEYS */;
INSERT INTO `codo_users` VALUES (1,'admin','admin','$2a$08$8YY0R6D.98JyPt1xcPcqO.8Dl/ICdzrQxbVE8JRuh6tu586QSEbzS',NULL,'admin@certigate.com',1458823444,1458827613,0,1,'A_7f2020.png',NULL,1,0,'0',0),(2,'anonymous','Anonymous','youJustCantCrackThis','','anonymous@localhost',1458823444,1458823444,0,0,'','',0,0,'0',0);
/*!40000 ALTER TABLE `codo_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `codo_views`
--

DROP TABLE IF EXISTS `codo_views`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `codo_views` (
  `date` date NOT NULL,
  `views` int(11) NOT NULL,
  PRIMARY KEY (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `codo_views`
--

LOCK TABLES `codo_views` WRITE;
/*!40000 ALTER TABLE `codo_views` DISABLE KEYS */;
INSERT INTO `codo_views` VALUES ('2016-03-24',25);
/*!40000 ALTER TABLE `codo_views` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-03-24 14:00:33
