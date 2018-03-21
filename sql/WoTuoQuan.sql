/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : wotuoquan

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2017-09-18 14:42:12
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for wtq_address
-- ----------------------------
DROP TABLE IF EXISTS `wtq_address`;
CREATE TABLE `wtq_address` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '地址ID',
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `user_name` varchar(30) DEFAULT NULL COMMENT '收货人的姓名',
  `user_phone` varchar(255) DEFAULT NULL COMMENT '收货人的电话',
  `user_address` varchar(255) DEFAULT NULL COMMENT '收货人的地址',
  `address_type` tinyint(1) DEFAULT '0' COMMENT '设置默认地址 1 ',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COMMENT='用户头像';

-- ----------------------------
-- Records of wtq_address
-- ----------------------------
INSERT INTO `wtq_address` VALUES ('1', '2', 'rachin', '13688369875', '四川省攀枝花市米易县 ,太阳村 ', '0');
INSERT INTO `wtq_address` VALUES ('3', '2', 'liangdong', '13688369875', '广西壮族自治区崇左市龙州县 ,沈家巷2号20栋1单元', '1');
INSERT INTO `wtq_address` VALUES ('4', '11', '王伟', '13340889696', '四川省绵阳市游仙区,长虹国际', '1');
INSERT INTO `wtq_address` VALUES ('5', '10', '梁栋', '13688369875', '四川省绵阳市游仙区,沈家坝北街', '1');
INSERT INTO `wtq_address` VALUES ('6', '12', '许', '18215584091', '四川省绵阳市涪城区,高水小区', '0');
INSERT INTO `wtq_address` VALUES ('7', '2', 'rachin', '13688369875', '广东省汕头市金平区,梁栋啊', '0');
INSERT INTO `wtq_address` VALUES ('8', '16', '梁栋', '13688399875', '四川省绵阳市游仙区,沈家坝北街沈家巷2号', '1');
INSERT INTO `wtq_address` VALUES ('9', '15', '绵阳市毅德商贸A区27幢3号', '18382453236', ',', '0');
INSERT INTO `wtq_address` VALUES ('10', '15', '绵阳市毅德商贸A区27幢3号', '18382453236', ',', '0');
INSERT INTO `wtq_address` VALUES ('11', '18', '姜敏', '18382453236', ',绵阳市毅德商贸城A区27幢3号', '0');

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
INSERT INTO `wtq_admin` VALUES ('1', 'supadmin', '0192023a7bbd73250516f069df18b500', '1', '1', '218.6.214.251', '1499823918', '1505711756');
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
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COMMENT='首页轮播图';

-- ----------------------------
-- Records of wtq_home_lunbo
-- ----------------------------
INSERT INTO `wtq_home_lunbo` VALUES ('13', null, '窝陀泉首页图片', '20170727/d694a3646b4b350e09f3b26e38e9f668.png', '1', '1');
INSERT INTO `wtq_home_lunbo` VALUES ('15', null, '首页第三张图', '20170727/43c2e8c509ef15f094b9766a01689622.png', '1', '1');
INSERT INTO `wtq_home_lunbo` VALUES ('16', null, '首页第四张图', '20170727/33a0dd764456f49b7c70b72a3aa74acd.JPG', '1', '1');
INSERT INTO `wtq_home_lunbo` VALUES ('17', null, '推荐图', '20170830/8ad90cb92db37dca0d13accab27545be.jpg', '1', '2');
INSERT INTO `wtq_home_lunbo` VALUES ('18', null, '推荐2', '20170830/405765affc6c14b4d62c88c2e195446e.jpg', '1', '2');
INSERT INTO `wtq_home_lunbo` VALUES ('19', null, '推荐3', '20170830/817953e47ab1196d0f9c977a4319c823.jpg', '1', '2');
INSERT INTO `wtq_home_lunbo` VALUES ('21', null, '', '20170809/fa0c60c532e4a9576210dce84cbb00a5.jpg', '1', '1');
INSERT INTO `wtq_home_lunbo` VALUES ('22', null, '推荐4', '20170830/ce92b5bb6cc2a3638adf305d883ceccb.jpg', '0', '2');

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
  `address_id` int(11) NOT NULL DEFAULT '0',
  `pay_state` tinyint(1) DEFAULT '0' COMMENT '0未支付 1微信支付 2支付宝支付',
  `payid` varchar(255) DEFAULT NULL COMMENT '支付唯一标示',
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=utf8 COMMENT='用户订单详情';

-- ----------------------------
-- Records of wtq_order
-- ----------------------------
INSERT INTO `wtq_order` VALUES ('73', '16', '34', '1', '0.01', '0', '1505701604', null, '8', '1', '4005062001201709182771055754');
INSERT INTO `wtq_order` VALUES ('74', '16', '34', '1', '0.01', '0', '1505701623', null, '8', '2', '2017091821001004790204984814');
INSERT INTO `wtq_order` VALUES ('75', '18', '35', '1', '2.50', '0', '1505705536', null, '11', '1', '4007952001201709182779872726');

-- ----------------------------
-- Table structure for wtq_payinfo
-- ----------------------------
DROP TABLE IF EXISTS `wtq_payinfo`;
CREATE TABLE `wtq_payinfo` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `payid` varchar(255) NOT NULL,
  `create_time` int(11) DEFAULT NULL COMMENT '生成的时间',
  `paystate` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1微信 2支付宝',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of wtq_payinfo
-- ----------------------------
INSERT INTO `wtq_payinfo` VALUES ('41', '73', '16', '4005062001201709182771055754', '1505701604', '1');
INSERT INTO `wtq_payinfo` VALUES ('42', '74', '16', '2017091821001004790204984814', '1505701623', '2');
INSERT INTO `wtq_payinfo` VALUES ('43', '75', '18', '4007952001201709182779872726', '1505705536', '1');

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
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of wtq_product
-- ----------------------------
INSERT INTO `wtq_product` VALUES ('1', 'W', '0', '水', '');
INSERT INTO `wtq_product` VALUES ('28', 'D', '0', '引用器皿', '');
INSERT INTO `wtq_product` VALUES ('33', 'F', '0', '农产品', '');
INSERT INTO `wtq_product` VALUES ('51', 'D1', '28', '饮水机', '20170727/d32f1614c0b36d7be7ceb719d3f3982a.png');
INSERT INTO `wtq_product` VALUES ('56', 'D3', '28', '水杯', '20170727/54df0e5e21b271fb202450285b4e3b2a.png');
INSERT INTO `wtq_product` VALUES ('57', 'D2', '28', '茶壶', '20170727/4be9b11c25d873061103e656604ad547.jpg');
INSERT INTO `wtq_product` VALUES ('58', 'D4', '28', '水壶', '20170727/398a2a1db507ef5851e9538a7a468a75.jpg');
INSERT INTO `wtq_product` VALUES ('59', 'D5', '28', '酒杯', '20170727/424c7e88b68a0b63aedc653632613207.jpg');
INSERT INTO `wtq_product` VALUES ('60', 'D6', '28', '碗', '20170727/e5e5588100267758b33e429c91baf003.jpg');
INSERT INTO `wtq_product` VALUES ('61', 'F1', '33', '天麻', '20170830/c94fd1c8c549d9a01a8cf98ce824ee32.png');
INSERT INTO `wtq_product` VALUES ('62', 'F2', '33', '牛肝菌', '20170830/38bc1c11c066aff1304200afa435a893.png');
INSERT INTO `wtq_product` VALUES ('63', 'F3', '33', '木耳', '20170830/aa23cb0257b8383a327c763676939a35.png');
INSERT INTO `wtq_product` VALUES ('64', 'F4', '33', '姬松茸', '20170830/143f170be0b0d3305ba5bcf41ecca13b.png');
INSERT INTO `wtq_product` VALUES ('65', 'F5', '33', '茶叶', '20170830/b325e20f5080cce2d9ff1fd2f4595a27.png');
INSERT INTO `wtq_product` VALUES ('66', 'F6', '33', '其他', '20170830/532de9fca7e7a0337a0e819148f0ee9e.png');

-- ----------------------------
-- Table structure for wtq_product_list
-- ----------------------------
DROP TABLE IF EXISTS `wtq_product_list`;
CREATE TABLE `wtq_product_list` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `product_parentid` int(11) NOT NULL COMMENT '产品父ID',
  `product_name` varchar(30) NOT NULL COMMENT '产品名称',
  `product_url` varchar(255) NOT NULL COMMENT '产品图片URL',
  `product_describe` varchar(255) NOT NULL COMMENT '产品描述',
  `product_label` varchar(15) DEFAULT NULL COMMENT '产品标签 特惠装',
  `product_norms` varchar(20) DEFAULT NULL COMMENT '产品规格如ml',
  `product_price` decimal(10,2) DEFAULT NULL COMMENT '价格',
  `product_num` int(11) NOT NULL DEFAULT '0' COMMENT '产品数量',
  `is_suggest` tinyint(1) DEFAULT '0' COMMENT '设为推荐 1为推荐 0 默认',
  `s_describe` varchar(255) NOT NULL DEFAULT 'NULL' COMMENT '成为推荐理由',
  `contact` varchar(50) DEFAULT NULL,
  `qrcode_url` varchar(255) DEFAULT NULL COMMENT '二维码URL',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of wtq_product_list
-- ----------------------------
INSERT INTO `wtq_product_list` VALUES ('39', '61', '野生天麻', '20170830/3ef6e9a7b69e47dd4d94fd18355e78aa.jpg;20170830/888847ee18f212bd1bb79d7efddbc0b6.jpg;20170830/81b08a9942d0e5e925f52bfb3784e69e.jpg', '采摘自深山生长成熟的天麻。可用于治疗高血压、头痛眩晕、口眼歪斜、肢体麻木、小儿惊厥等症，效果优于人工种植天麻。', '顺丰包邮', '500g', '880.00', '10000', '1', '价格实惠', '08166388081', 'qrcode/39.png');
INSERT INTO `wtq_product_list` VALUES ('40', '61', '野生天麻', '20170830/0d1caf807328159a150355684b7ed051.jpg;20170830/b6f2f9e115a2bbcd01867813a45e1e9b.jpg;20170830/dee7633fd1bf1eefcbd736bffb1458a7.jpg', '采摘自深山生长成熟的天麻，切片处理，无任何添加成分。可用于治疗高血压、头痛眩晕、口眼歪斜、肢体麻木、小儿惊厥等症，效果优于人工种植天麻。', '顺丰包邮', '250g', '480.00', '10000', '1', '入药养生', '08166388081', 'qrcode/40.png');
INSERT INTO `wtq_product_list` VALUES ('41', '61', '天麻', '20170830/bf93f6c0fd8177a63b49ed740b509c14.jpg;20170830/c0bd5a6a76eaf5d1ade935828a82badf.png;20170830/c0bd5a6a76eaf5d1ade935828a82badf.jpg;20170830/73df9a6dfb18726482ee441f7e027b17.jpg', '人工种植天麻。可用于治疗高血压、头痛眩晕、口眼歪斜、肢体麻木、小儿惊厥等症。也可用于蒸煮类食材，色香俱全，滋补养生。', '顺丰包邮', '250g', '316.00', '10000', '0', 'NULL', '08166388081', 'qrcode/41.png');
INSERT INTO `wtq_product_list` VALUES ('42', '62', '美味牛肝菌', '20170830/404d67013f662a42c172c943ff7406d6.jpg;20170830/c726caeb497d6b7cce567a36a11f5e05.jpg;20170830/752d12d12ad502d8ef797a718919f3ee.jpg;20170830/64496e2705797355e0be10a5a86f2b84.jpg', '牛肝菌因肉质肥厚，极似牛肝而得名，是名贵稀有的野生食用菌，为“四大菌王”之一。用来配制汤料或做成酱油浸膏，也有制成盐腌品食用。', '顺丰包邮', '100g', '108.00', '9999', '1', '美妙回味', '08166388081', 'qrcode/42.png');
INSERT INTO `wtq_product_list` VALUES ('43', '63', '冬木耳', '20170830/f8205117fa0bf58c01e60916e6d2b246.jpg;20170830/156ecb08d5bdb31defd2c983ebca08af.jpg;20170830/33fda51345fff678f8ea16bec83b52d1.jpg;20170830/588cd766e1665fdd5bed1c7410cb85bd.jpg', '味道鲜美，可素可荤，营养丰富。能益气强身，令人肌肤红润，容光焕发，能够疏通肠胃，润滑肠道，同时对高血压患者也有一定帮助。', '顺丰包邮', '100g', '68.00', '10000', '0', 'NULL', '08166388081', 'qrcode/43.png');
INSERT INTO `wtq_product_list` VALUES ('44', '64', '姬松茸', '20170830/3de10aa6b6c32351c39a9813386ee881.jpg;20170830/54b5e1d7078d8315cb13888cbbdf4b4f.jpg;20170830/fb383f132da61646099eb4633de7334e.jpg;20170830/8f7163bacddd4d4ecdda37c4872ba283.JPG', '味道带甜，有杏仁的芳香。姬松茸常见于营养保健食品中，并声称有活化免疫系统、预防恶性肿瘤（防癌）的功效。可用于菜品食材。', '顺丰包邮', '180g', '108.00', '10000', '1', '入食入药', '08166388081', 'qrcode/44.png');
INSERT INTO `wtq_product_list` VALUES ('45', '1', '窝托泉小瓶', '20170918/969d6ae739882cd05904989f81aafc48.jpg', '含有对人体有益的矿物质锶，达国家矿泉水标准；窝托泉山泉水取于自然，胜于自然。天然弱碱性水，它可以帮我们清除掉血液和身体里的多余脂肪。', '特惠装', '350ml', '2.50', '9997', '1', '销量领先', '08166388081', null);
INSERT INTO `wtq_product_list` VALUES ('46', '1', '窝托泉大瓶', '20170918/8b4c4831cfaa990e8e4e9be33b70421f.jpg', '含有对人体有益的矿物质锶，达国家矿泉水标准；窝托泉山泉水取于自然，胜于自然。天然弱碱性水，它可以帮我们清除掉血液和身体里的多余脂肪。', '特惠装', '4L', '12.00', '9999', '1', '夏季热销', '08166388081', null);
INSERT INTO `wtq_product_list` VALUES ('47', '1', '窝托泉中瓶', '20170918/58c3c0dfe9e8bc1d243050e3c33dd89b.jpg', '含有对人体有益的矿物质锶，达国家矿泉水标准；窝托泉山泉水取于自然，胜于自然。天然弱碱性水，它可以帮我们清除掉血液和身体里的多余脂肪。', '特惠装', '518ml', '3.00', '9999', '0', '热卖推荐', '08166388081', null);
INSERT INTO `wtq_product_list` VALUES ('48', '1', '窝托泉大瓶（件）', '20170918/0f8012592571017eff8d46c13ba12e50.jpg', '含有对人体有益的矿物质锶，达国家矿泉水标准；窝托泉山泉水取于自然，胜于自然。天然弱碱性水，它可以帮我们清除掉血液和身体里的多余脂肪。', '特惠装', '4L', '48.00', '9999', '0', 'NULL', '08166638081', 'qrcode/48.png');
INSERT INTO `wtq_product_list` VALUES ('49', '1', '窝托泉中瓶（件）', '20170918/c8d484b81c1ee6a34f2ff03435996c10.jpg', '含有对人体有益的矿物质锶，达国家矿泉水标准；窝托泉山泉水取于自然，胜于自然。天然弱碱性水，它可以帮我们清除掉血液和身体里的多余脂肪。', '特惠装', '518ml', '72.00', '9999', '1', '砖石水518ml', '08166638081', 'qrcode/49.png');

-- ----------------------------
-- Table structure for wtq_score_product
-- ----------------------------
DROP TABLE IF EXISTS `wtq_score_product`;
CREATE TABLE `wtq_score_product` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `score_product` varchar(255) DEFAULT NULL COMMENT '积分产品名',
  `score_url` varchar(255) DEFAULT NULL COMMENT '图片URL',
  `score_describe` varchar(255) DEFAULT NULL COMMENT '奖品的描述',
  `score_norms` varchar(255) DEFAULT NULL COMMENT '规格',
  `consume_score` int(11) DEFAULT NULL COMMENT '所需积分',
  `score_num` int(11) DEFAULT NULL COMMENT '剩余的数量',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of wtq_score_product
-- ----------------------------
INSERT INTO `wtq_score_product` VALUES ('4', '皮皮虾', '20170726/eb5a2b1e817fc1f49bbd2c6ced763f47.png', '极品皮皮虾', '500g', '400', '1');
INSERT INTO `wtq_score_product` VALUES ('6', '皮皮鳝', '20170726/b07f09c2931452a25e9d0a41f7c746cd.png', '大自然的味道', '500g', '600', '1');
INSERT INTO `wtq_score_product` VALUES ('7', '皮皮鳝', '20170726/1f0a3ad100994a6ce496bcfacf56e149.png', '大自然的味道', '500g', '600', '1');
INSERT INTO `wtq_score_product` VALUES ('8', '皮皮鳝', '20170726/485453f1304dcd0284187cc930c7bb1a.png', '大自然的味道', '500g', '600', '1');
INSERT INTO `wtq_score_product` VALUES ('13', 'xxx', '20170726/6f597f46ea37219dd48601ac3acc7c7d.png', 'ddsf', '500g', '300', '1');

-- ----------------------------
-- Table structure for wtq_user
-- ----------------------------
DROP TABLE IF EXISTS `wtq_user`;
CREATE TABLE `wtq_user` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_phone` varchar(11) NOT NULL DEFAULT '0' COMMENT '用户电话号码',
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
  `openid` varchar(255) DEFAULT NULL COMMENT '第三方ID值',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COMMENT='用户表';

-- ----------------------------
-- Records of wtq_user
-- ----------------------------
INSERT INTO `wtq_user` VALUES ('1', '13076050636', 'xxjun', '202cb962ac59075b964b07152d234b70', null, '0', '1', '1', '1', '125.70.76.12', '1499677689', '1505704589', null);
INSERT INTO `wtq_user` VALUES ('2', '13688369875', 'RachinLd', '202cb962ac59075b964b07152d234b70', '20170905/b4a3b726d95edb9fc745a6cdae1f31b6.jpg', '170', '1', '1', '1', '183.221.144.218', '1499677689', '1505355168', null);
INSERT INTO `wtq_user` VALUES ('3', '13076050635', 'oo', 'c4ca4238a0b923820dcc509a6f75849b', null, '0', '1', '0', '1', null, '1500456898', null, null);
INSERT INTO `wtq_user` VALUES ('4', '13688369877', '71896', '88b095aecbba91cc91c945df2a00c5a6', 'avatar.jpg', '0', '1', '1', '1', '183.221.144.175', '1500878878', '1500878912', null);
INSERT INTO `wtq_user` VALUES ('5', '18048193223', '12568', '204c8336f1d6739e9975a286f4de7481', 'avatar.jpg', '0', '1', '1', '1', '171.212.112.96', '1501130100', '1501131862', null);
INSERT INTO `wtq_user` VALUES ('6', '13108100810', '62193', '202cb962ac59075b964b07152d234b70', 'avatar.jpg', '0', '1', '1', '1', '111.9.68.192', '1501130209', '1501130450', null);
INSERT INTO `wtq_user` VALUES ('7', '13108100819', '32730', '202cb962ac59075b964b07152d234b70', 'avatar.jpg', '0', '1', '0', '1', null, '1501130316', null, null);
INSERT INTO `wtq_user` VALUES ('8', '13108100814', '18429', '202cb962ac59075b964b07152d234b70', 'avatar.jpg', '0', '1', '0', '1', null, '1501130329', null, null);
INSERT INTO `wtq_user` VALUES ('9', '13608100810', '78164', '202cb962ac59075b964b07152d234b70', 'avatar.jpg', '0', '1', '0', '1', null, '1501130423', null, null);
INSERT INTO `wtq_user` VALUES ('10', '13212121212', 'rachin', '202cb962ac59075b964b07152d234b70', '20170727/e43b86d5901a4a755cb3015d07112b09.jpg', '0', '1', '1', '1', '111.9.68.192', '1501130750', '1501209696', null);
INSERT INTO `wtq_user` VALUES ('11', '13340889696', '87167', '8e93c29aed4466dfd739c179aca3862f', 'avatar.jpg', '67', '1', '0', '1', '171.210.59.159', '1501131261', '1502166238', null);
INSERT INTO `wtq_user` VALUES ('12', '18215584091', '81446', '1a656139e244effc9a6aaa5663dc3717', '20170729/f74fe0232e33bb3cfa339927ecc1d5f3.jpg', '0', '1', '1', '1', '220.166.29.30', '1501300299', '1501343252', null);
INSERT INTO `wtq_user` VALUES ('13', '15681172084', '48334', 'e3029a19b84573d90fac942eecfc4d14', 'avatar.jpg', '0', '1', '1', '1', '182.136.16.79', '1502184216', '1502184228', null);
INSERT INTO `wtq_user` VALUES ('14', '13458437402', '99439', 'cb47db346da3f85b698fabe0901980e6', 'avatar.jpg', '0', '1', '1', '1', '182.136.16.79', '1502185117', '1502185454', null);
INSERT INTO `wtq_user` VALUES ('15', '18382453236', '37822', '015853a8a1600c7f91cfd80558c796fa', 'avatar.jpg', '0', '1', '1', '1', '218.6.214.251', '1502240939', '1505712625', null);
INSERT INTO `wtq_user` VALUES ('16', '18085028429', '测试', '87c1a90ecbb7e980886f3fa315865c22', '20170918/4b82c7087c6849305df439f318c5abb3.jpg', '150', '2', '1', '1', '183.221.150.234', '1505398473', '1505708952', 'oMCawxG07j1_bqhP1wUMjGev_fSY');
INSERT INTO `wtq_user` VALUES ('17', '18987221827', 'wtq4122', '87c1a90ecbb7e980886f3fa315865c22', null, '0', '2', '1', '1', '111.9.68.171', '1505398595', null, 'undefined');
INSERT INTO `wtq_user` VALUES ('18', '14545852475', 'wtq2481', '87c1a90ecbb7e980886f3fa315865c22', null, '0', '2', '0', '1', '223.86.85.77', '1505566952', null, 'oMCawxDTjm9F7DmBpKSGC-SYdO7s');
INSERT INTO `wtq_user` VALUES ('19', '18601466302', 'wtq956', '87c1a90ecbb7e980886f3fa315865c22', null, '0', '2', '1', '1', '182.136.100.95', '1505704766', '1505710168', 'oMCawxFKwRkByDua3BYDOeU6R_Gs');

-- ----------------------------
-- Table structure for wtq_use_score
-- ----------------------------
DROP TABLE IF EXISTS `wtq_use_score`;
CREATE TABLE `wtq_use_score` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `sp_id` int(11) DEFAULT NULL COMMENT '积分产品ID',
  `u_addressid` int(11) DEFAULT NULL COMMENT '用户收货地址ID',
  `o_state` tinyint(4) DEFAULT '0' COMMENT '这个订单状态是否收货',
  `usescore` int(11) DEFAULT NULL COMMENT '使用了多少积分',
  `create_time` int(11) DEFAULT NULL COMMENT '生成的时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of wtq_use_score
-- ----------------------------
INSERT INTO `wtq_use_score` VALUES ('1', '1', '2', '1', '0', '100', '1501043353');
INSERT INTO `wtq_use_score` VALUES ('2', '1', '3', '1', '0', '200', '1501043562');
INSERT INTO `wtq_use_score` VALUES ('3', '1', '3', '1', '0', '200', '1501050672');

-- ----------------------------
-- Table structure for wtq_version
-- ----------------------------
DROP TABLE IF EXISTS `wtq_version`;
CREATE TABLE `wtq_version` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `v_number` varchar(50) DEFAULT NULL COMMENT '版本号',
  `v_info` text COMMENT '版本信息介绍',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of wtq_version
-- ----------------------------
INSERT INTO `wtq_version` VALUES ('5', 'V00.00.0014', '1.优化界面 2.增加模糊搜索功能 3.新增扫码查询产品功能 4.一大波积分来袭');
