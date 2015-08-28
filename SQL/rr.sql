/*
Navicat MySQL Data Transfer

Source Server         : reztek
Source Server Version : 50544
Source Host           : reztek.net:3306
Source Database       : rr

Target Server Type    : MYSQL
Target Server Version : 50544
File Encoding         : 65001

Date: 2015-08-28 09:37:18
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for rolls
-- ----------------------------
DROP TABLE IF EXISTS `rolls`;
CREATE TABLE `rolls` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `against` varchar(255) DEFAULT NULL,
  `against_roll` varchar(255) DEFAULT NULL,
  `roll` varchar(255) DEFAULT NULL,
  `against_included` enum('N','Y') DEFAULT NULL,
  `roll_complete` enum('Y','N') DEFAULT NULL,
  `gen_date` varchar(255) DEFAULT NULL,
  `roll_date` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;
