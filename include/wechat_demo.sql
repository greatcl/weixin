/*
Navicat MySQL Data Transfer

Source Server         : Local
Source Server Version : 50612
Source Host           : localhost:3306
Source Database       : wechat_demo

Target Server Type    : MYSQL
Target Server Version : 50612
File Encoding         : 65001

Date: 2014-07-08 17:20:48
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for app_info
-- ----------------------------
DROP TABLE IF EXISTS `app_info`;
CREATE TABLE `app_info` (
  `appId` varchar(255) NOT NULL COMMENT '公众号的唯一标示',
  `appSecret` varchar(255) NOT NULL COMMENT '第三方用户唯一凭证密钥',
  `appDescription` varchar(255) DEFAULT NULL COMMENT '公众号描述',
  `accessToken` varchar(512) DEFAULT NULL COMMENT '公众号的accessToken',
  `accTokenExpireTime` int(11) DEFAULT NULL COMMENT 'accessToken过期时间',
  PRIMARY KEY (`appId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用于保存公众号的基本信息';

-- ----------------------------
-- Table structure for subscriber
-- ----------------------------
DROP TABLE IF EXISTS `subscriber`;
CREATE TABLE `subscriber` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `appId` varchar(50) NOT NULL COMMENT '公众号唯一标示，用户关注的是哪个公众号',
  `subscriberId` varchar(50) NOT NULL COMMENT '订阅者微信对于该公众号的OpenId',
  `userId` varchar(255) DEFAULT NULL COMMENT '用户绑定的Id',
  `mode` tinyint(4) DEFAULT '1' COMMENT '普通模式：1',
  `active` bit(1) NOT NULL DEFAULT b'1' COMMENT '默认为1,用户正常，取消订阅，active为0',
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
