/*
Navicat MySQL Data Transfer

Source Server         : Local
Source Server Version : 50612
Source Host           : localhost:3306
Source Database       : wechat_demo

Target Server Type    : MYSQL
Target Server Version : 50612
File Encoding         : 65001

Date: 2014-07-21 17:17:45
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
-- Table structure for keywords
-- ----------------------------
DROP TABLE IF EXISTS `keywords`;
CREATE TABLE `keywords` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `keyword` varchar(255) NOT NULL,
  `responseMsgId` int(255) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for login_attempts
-- ----------------------------
DROP TABLE IF EXISTS `login_attempts`;
CREATE TABLE `login_attempts` (
  `ID` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `time` varchar(30) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for members
-- ----------------------------
DROP TABLE IF EXISTS `members`;
CREATE TABLE `members` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` char(128) NOT NULL,
  `salt` char(128) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for menu
-- ----------------------------
DROP TABLE IF EXISTS `menu`;
CREATE TABLE `menu` (
  `menuId` int(11) NOT NULL AUTO_INCREMENT COMMENT '菜单Id',
  `appId` varchar(50) NOT NULL COMMENT '公众号唯一标示',
  `level` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1：一级菜单 2：二级菜单',
  `parMenuId` int(11) DEFAULT NULL COMMENT 'level为2时，父菜单的Id',
  `menuName` varchar(50) NOT NULL COMMENT '菜单显示名称',
  `menuType` tinyint(4) DEFAULT NULL COMMENT '1：一级菜单 2：VIEW 3：CLICK',
  `key` varchar(255) DEFAULT NULL COMMENT 'menuType为2时，此处为Url的地址。menuType为3时，此处为Key的值',
  PRIMARY KEY (`menuId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for message
-- ----------------------------
DROP TABLE IF EXISTS `message`;
CREATE TABLE `message` (
  `ID` varchar(50) NOT NULL,
  `contentType` varchar(255) NOT NULL,
  `msgContent` text NOT NULL,
  `msgType` bit(1) NOT NULL DEFAULT b'0' COMMENT '0：用户发来的 1：回复用户的',
  `subscriberId` varchar(50) NOT NULL COMMENT '订阅者对应公众号的唯一ID',
  `createTime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for responsemsg
-- ----------------------------
DROP TABLE IF EXISTS `responsemsg`;
CREATE TABLE `responsemsg` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `msgType` varchar(255) NOT NULL DEFAULT 'text' COMMENT 'text=>文本; news=>图文; voice=>声音; music=>歌曲; video=>视频',
  `msgContent` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for subscriber
-- ----------------------------
DROP TABLE IF EXISTS `subscriber`;
CREATE TABLE `subscriber` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `appId` varchar(50) NOT NULL COMMENT '公众号唯一标示，用户关注的是哪个公众号',
  `subscriberId` varchar(50) NOT NULL COMMENT '订阅者微信对于该公众号的OpenId',
  `userId` varchar(255) DEFAULT NULL COMMENT '用户绑定的Id',
  `mode` tinyint(4) NOT NULL DEFAULT '1' COMMENT '普通模式：1',
  `active` bit(1) NOT NULL DEFAULT b'1' COMMENT '默认为1,用户正常，取消订阅，active为0',
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
