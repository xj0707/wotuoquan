/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : wotuoquan

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2017-07-21 17:16:02
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for wtq_address
-- ----------------------------
DROP TABLE IF EXISTS `wtq_address`;
CREATE TABLE `wtq_address` (
  `id` int(11) NOT NULL COMMENT '地址ID',
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `user_name` varchar(30) DEFAULT NULL COMMENT '收货人的姓名',
  `user_phone` varchar(255) DEFAULT NULL COMMENT '收货人的电话',
  `user_address` varchar(255) DEFAULT NULL COMMENT '收货人的地址',
  `address_type` tinyint(1) DEFAULT '0' COMMENT '设置默认地址 1 ',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户头像';

-- ----------------------------
-- Records of wtq_address
-- ----------------------------
INSERT INTO `wtq_address` VALUES ('1', '1', null, null, '四川省成都市中和镇黄金时代', '1');
INSERT INTO `wtq_address` VALUES ('2', '2', null, null, '四川省绵阳市立新镇', '1');

-- ----------------------------
-- Table structure for wtq_admin
-- ----------------------------
DROP TABLE IF EXISTS `wtq_admin`;
CREATE TABLE `wtq_admin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `admin_user` varchar(30) NOT NULL COMMENT '账号',
  `admin_pwd` char(32) NOT NULL COMMENT '密码',
  `admin_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1是启用0是禁用',
  `admin_level` tinyint(1) NOT NULL COMMENT '管理员级别1',
  `admin_ip` varchar(20) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of wtq_admin
-- ----------------------------
INSERT INTO `wtq_admin` VALUES ('1', 'supadmin', '0192023a7bbd73250516f069df18b500', '1', '1', '127.0.0.1', '1499823918', '1500601613');
INSERT INTO `wtq_admin` VALUES ('2', 'admin123', 'admin123', '1', '2', null, '1499841938', null);

-- ----------------------------
-- Table structure for wtq_home_lunbo
-- ----------------------------
DROP TABLE IF EXISTS `wtq_home_lunbo`;
CREATE TABLE `wtq_home_lunbo` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL COMMENT '分类下的ID，也就是产品的父ID',
  `lunbo_describe` varchar(255) DEFAULT NULL COMMENT '轮播图的简要描述',
  `product_url` varchar(255) NOT NULL COMMENT '这个父产品的图片',
  `product_state` tinyint(1) DEFAULT '0' COMMENT '状态 是否启用该图片',
  `lunbo_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '轮播类型 1为 首页轮播 2为推荐轮播',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COMMENT='首页轮播图';

-- ----------------------------
-- Records of wtq_home_lunbo
-- ----------------------------
INSERT INTO `wtq_home_lunbo` VALUES ('1', '28', '我勒个去', '20170718\\d7af4b93c825407b07a31a3638a4b48a.jpg', '1', '1');
INSERT INTO `wtq_home_lunbo` VALUES ('3', '33', '第三方了电视剧', '20170718\\d85374dadcd9aea09e00d163d5b48a29.jpg', '1', '1');
INSERT INTO `wtq_home_lunbo` VALUES ('5', null, 'hell哦', '20170718\\1a02b0dd8fbc74d46d3fa82b3587b589.jpg', '1', '1');
INSERT INTO `wtq_home_lunbo` VALUES ('6', null, '马甸123', '20170718\\0423cddda5614c91a92ed5dd69579fbc.jpg', '0', '1');
INSERT INTO `wtq_home_lunbo` VALUES ('9', null, '我是推荐', '20170719\\27ceaa39f5b8500e004b727c870d04c4.jpg', '1', '2');
INSERT INTO `wtq_home_lunbo` VALUES ('10', null, '发打个电话', '20170719\\66c6c7fb48b8e5af2a0b5186bf9f6b17.jpg', '1', '2');
INSERT INTO `wtq_home_lunbo` VALUES ('11', null, '', '20170720/0ad61479a309e6d0a35d15df5a045849.jpg', '0', '1');

-- ----------------------------
-- Table structure for wtq_order
-- ----------------------------
DROP TABLE IF EXISTS `wtq_order`;
CREATE TABLE `wtq_order` (
  `order_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `buy_num` int(11) NOT NULL,
  `product_totalprice` decimal(10,2) NOT NULL COMMENT '订单总价格',
  `order_state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '订单的状态 0未收货 1未收货',
  `order_time` int(11) DEFAULT NULL COMMENT '下单时间',
  `order_overtime` int(11) DEFAULT NULL COMMENT '用户收货时间',
  `address_id` int(11) DEFAULT NULL COMMENT '收货的地址ID',
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='用户订单详情';

-- ----------------------------
-- Records of wtq_order
-- ----------------------------
INSERT INTO `wtq_order` VALUES ('3', '1', '25', '10', '50.00', '0', '1500621307', null, '1');

-- ----------------------------
-- Table structure for wtq_product
-- ----------------------------
DROP TABLE IF EXISTS `wtq_product`;
CREATE TABLE `wtq_product` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `product_name` varchar(20) NOT NULL COMMENT '产品名',
  `parent_id` int(11) NOT NULL COMMENT '父ID',
  `describe` varchar(50) DEFAULT NULL COMMENT '产品的描述',
  `product_url` varchar(255) DEFAULT NULL COMMENT '类别图片',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of wtq_product
-- ----------------------------
INSERT INTO `wtq_product` VALUES ('1', 'W', '0', '水', '');
INSERT INTO `wtq_product` VALUES ('28', 'D', '0', '引用器皿', '');
INSERT INTO `wtq_product` VALUES ('30', 'D1', '28', '饮水机', '');
INSERT INTO `wtq_product` VALUES ('33', 'F', '0', '农产品', '');
INSERT INTO `wtq_product` VALUES ('34', 'D2', '28', '茶壶', '');
INSERT INTO `wtq_product` VALUES ('35', 'F1', '33', '蔬菜', '');
INSERT INTO `wtq_product` VALUES ('40', 'F2', '33', '水果', '');
INSERT INTO `wtq_product` VALUES ('42', 'F3', '33', '肉', '');
INSERT INTO `wtq_product` VALUES ('43', 'D3', '28', '碗', '20170719\\c3aafd93d69653e68feaca38a34b0d17.jpg');

-- ----------------------------
-- Table structure for wtq_product_list
-- ----------------------------
DROP TABLE IF EXISTS `wtq_product_list`;
CREATE TABLE `wtq_product_list` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `product_parentid` int(11) NOT NULL COMMENT '产品父ID',
  `product_name` varchar(30) NOT NULL COMMENT '产品名称',
  `product_url` varchar(255) NOT NULL COMMENT '产品图片URL',
  `product_describe` varchar(50) NOT NULL COMMENT '产品描述',
  `product_label` varchar(15) DEFAULT NULL COMMENT '产品标签 特惠装',
  `product_norms` varchar(20) DEFAULT NULL COMMENT '产品规格如ml',
  `product_price` decimal(10,2) DEFAULT NULL COMMENT '价格',
  `product_num` int(11) NOT NULL DEFAULT '0' COMMENT '产品数量',
  `is_suggest` tinyint(1) DEFAULT '0' COMMENT '设为推荐 1为推荐 0 默认',
  `s_describe` varchar(255) DEFAULT NULL COMMENT '成为推荐商品的描述',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of wtq_product_list
-- ----------------------------
INSERT INTO `wtq_product_list` VALUES ('25', '1', '窝驼泉', '20170721/c46a2c12ba2ead1c0da719d1c4d67d6c.jpg;20170721/e4b8cad3049f87a875aaaee07bfa54a2.jpg;20170721/b035d986331602784de9579d8faa7f9e.jpg', '来自阿尔卑斯山脉的天然纯净水', '特惠装', '500/ml', '5.00', '10000', '1', '哈啊哈哈');
INSERT INTO `wtq_product_list` VALUES ('26', '1', '窝驼泉2', '20170721/0c1590f7edd507d048db04dc6b8f022b.jpg;20170721/55bf7ea731bc2a720f9d015e49ee1980.jpg', '来自于阿尔卑斯山脉的天然纯净水', '特惠装', '200/ml', '2.00', '8888', '1', '不需要理由');
INSERT INTO `wtq_product_list` VALUES ('27', '30', '这是饮水机', '20170721/91cc1d9ce35dd0adbf87af2ff620f917.jpg;20170721/59705badc88591f56576e6d8d6e9f10c.jpg', '干净好喝', '特惠装', '1/个', '100.00', '99999', '1', '要撒理由');

-- ----------------------------
-- Table structure for wtq_user
-- ----------------------------
DROP TABLE IF EXISTS `wtq_user`;
CREATE TABLE `wtq_user` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_phone` varchar(11) NOT NULL COMMENT '用户电话号码',
  `user_name` varchar(30) DEFAULT NULL COMMENT '用户名或昵称',
  `user_pwd` char(32) NOT NULL,
  `user_avatar` varchar(255) DEFAULT NULL COMMENT '用户头像',
  `user_integral` int(11) DEFAULT '0' COMMENT '用户积分',
  `user_type` tinyint(1) NOT NULL COMMENT '用户类别，1为APP注册的用户，2为第三方登录的',
  `user_line` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1在线 0不在线',
  `user_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1为激活状态 0为禁用状态',
  `user_ip` varchar(20) DEFAULT NULL COMMENT '登录的ip',
  `create_time` int(10) DEFAULT NULL,
  `update_time` int(10) DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='用户表';

-- ----------------------------
-- Records of wtq_user
-- ----------------------------
INSERT INTO `wtq_user` VALUES ('1', '13076050636', 'xxjun', '202cb962ac59075b964b07152d234b70', null, '0', '1', '1', '1', '0.0.0.0', '1499677689', '1500621301');
INSERT INTO `wtq_user` VALUES ('2', '13688369875', 'ld123', '202cb962ac59075b964b07152d234b70', null, '0', '1', '1', '1', '0.0.0.0', '1499677689', '1499827557');
INSERT INTO `wtq_user` VALUES ('3', '13076050635', null, 'c4ca4238a0b923820dcc509a6f75849b', null, '0', '1', '0', '1', null, '1500456898', null);
