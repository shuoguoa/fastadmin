/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : qwadmin

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2017-05-24 17:49:44
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for banner_image
-- ----------------------------
DROP TABLE IF EXISTS `banner_image`;
CREATE TABLE `banner_image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) DEFAULT NULL,
  `img` varchar(255) DEFAULT NULL,
  `sort` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of banner_image
-- ----------------------------
INSERT INTO `banner_image` VALUES ('7', 'http://www.baidu.com', 'http://localhost/qwadmin/Public/attached/2017/05/24/5925415fca55b.jpeg', '2');
INSERT INTO `banner_image` VALUES ('6', 'http://www.baidu.com', 'http://localhost/qwadmin/Public/attached/2017/05/24/5925413d9cef7.png', '1');
INSERT INTO `banner_image` VALUES ('9', 'http://www.baidu.com', 'http://liufuqin.test.htgames.cn/qwadmin/Public/attached/2017/05/24/5925445d37105.jpg', '3');
INSERT INTO `banner_image` VALUES ('17', 'http://www.baidu.com', 'http://liufuqin.test.htgames.cn/qwadmin/Public/attached/2017/05/24/592549368fee7.jpg', '8');
INSERT INTO `banner_image` VALUES ('20', 'http://www.baidu.com', 'http://liufuqin.test.htgames.cn/qwadmin/Public/attached/2017/05/24/59254d38655f1.jpg', '23');
INSERT INTO `banner_image` VALUES ('19', 'http://www.baidu.com', 'http://liufuqin.test.htgames.cn/qwadmin/Public/attached/2017/05/24/5925496af1d4c.jpg', '22');

-- ----------------------------
-- Table structure for qw_article
-- ----------------------------
DROP TABLE IF EXISTS `qw_article`;
CREATE TABLE `qw_article` (
  `aid` int(11) NOT NULL AUTO_INCREMENT,
  `sid` int(11) NOT NULL COMMENT '分类id',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `seotitle` varchar(255) DEFAULT NULL COMMENT 'SEO标题',
  `keywords` varchar(255) NOT NULL COMMENT '关键词',
  `description` varchar(255) NOT NULL COMMENT '摘要',
  `thumbnail` varchar(255) NOT NULL COMMENT '缩略图',
  `content` text NOT NULL COMMENT '内容',
  `t` int(10) unsigned NOT NULL COMMENT '时间',
  `n` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '点击',
  PRIMARY KEY (`aid`),
  KEY `sid` (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of qw_article
-- ----------------------------

-- ----------------------------
-- Table structure for qw_auth_group
-- ----------------------------
DROP TABLE IF EXISTS `qw_auth_group`;
CREATE TABLE `qw_auth_group` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `title` char(100) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `rules` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of qw_auth_group
-- ----------------------------
INSERT INTO `qw_auth_group` VALUES ('1', '超级管理员', '1', '1,2,58,65,59,60,61,62,3,56,4,6,5,7,8,9,10,51,52,53,57,11,54,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,29,30,31,32,33,34,36,37,38,39,40,41,42,43,44,45,46,47,63,48,49,50,55');
INSERT INTO `qw_auth_group` VALUES ('2', '管理员', '1', '13,14,23,22,21,20,19,18,17,16,15,24,36,34,33,32,31,30,29,27,26,25,1');
INSERT INTO `qw_auth_group` VALUES ('3', '普通用户', '1', '1');
INSERT INTO `qw_auth_group` VALUES ('6', '333', '0', '1,2');

-- ----------------------------
-- Table structure for qw_auth_group_access
-- ----------------------------
DROP TABLE IF EXISTS `qw_auth_group_access`;
CREATE TABLE `qw_auth_group_access` (
  `uid` mediumint(8) unsigned NOT NULL,
  `group_id` mediumint(8) unsigned NOT NULL,
  UNIQUE KEY `uid_group_id` (`uid`,`group_id`),
  KEY `uid` (`uid`),
  KEY `group_id` (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of qw_auth_group_access
-- ----------------------------
INSERT INTO `qw_auth_group_access` VALUES ('1', '1');

-- ----------------------------
-- Table structure for qw_auth_rule
-- ----------------------------
DROP TABLE IF EXISTS `qw_auth_rule`;
CREATE TABLE `qw_auth_rule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL,
  `name` char(80) NOT NULL DEFAULT '',
  `title` char(20) NOT NULL DEFAULT '',
  `icon` varchar(255) NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '1',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `condition` char(100) NOT NULL DEFAULT '',
  `islink` tinyint(1) NOT NULL DEFAULT '1',
  `o` int(11) NOT NULL COMMENT '排序',
  `tips` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=92 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of qw_auth_rule
-- ----------------------------
INSERT INTO `qw_auth_rule` VALUES ('1', '2', 'Test/index', '控制台', 'menu-icon fa fa-tachometer', '1', '1', '', '0', '1', '友情提示：经常查看操作日志，发现异常以便及时追查原因。test 功能');
INSERT INTO `qw_auth_rule` VALUES ('2', '0', '', '系统设置', 'menu-icon fa fa-cog', '1', '1', '', '1', '2', '');
INSERT INTO `qw_auth_rule` VALUES ('3', '2', 'Setting/setting', '网站设置', 'menu-icon fa fa-caret-right', '1', '1', '', '1', '3', '这是网站设置的提示。');
INSERT INTO `qw_auth_rule` VALUES ('4', '2', 'Menu/index', '后台菜单', 'menu-icon fa fa-caret-right', '1', '1', '', '1', '4', '');
INSERT INTO `qw_auth_rule` VALUES ('5', '2', 'Menu/add', '新增菜单', 'menu-icon fa fa-caret-right', '1', '1', '', '1', '5', '');
INSERT INTO `qw_auth_rule` VALUES ('6', '4', 'Menu/edit', '编辑菜单', '', '1', '1', '', '0', '6', '');
INSERT INTO `qw_auth_rule` VALUES ('7', '2', 'Menu/update', '保存菜单', 'menu-icon fa fa-caret-right', '1', '1', '', '0', '7', '');
INSERT INTO `qw_auth_rule` VALUES ('8', '2', 'Menu/del', '删除菜单', 'menu-icon fa fa-caret-right', '1', '1', '', '0', '8', '');
INSERT INTO `qw_auth_rule` VALUES ('9', '2', 'Database/backup', '数据库备份', 'menu-icon fa fa-caret-right', '1', '1', '', '1', '9', '');
INSERT INTO `qw_auth_rule` VALUES ('10', '9', 'Database/recovery', '数据库还原', '', '1', '1', '', '0', '10', '');
INSERT INTO `qw_auth_rule` VALUES ('11', '2', 'Update/update', '在线升级', 'menu-icon fa fa-caret-right', '1', '1', '', '1', '11', '');
INSERT INTO `qw_auth_rule` VALUES ('12', '2', 'Update/devlog', '开发日志', 'menu-icon fa fa-caret-right', '1', '1', '', '1', '12', '');
INSERT INTO `qw_auth_rule` VALUES ('13', '0', '', '用户及组', 'menu-icon fa fa-users', '1', '1', '', '1', '13', '');
INSERT INTO `qw_auth_rule` VALUES ('14', '13', 'Member/index', '用户管理', 'menu-icon fa fa-caret-right', '1', '1', '', '1', '14', '');
INSERT INTO `qw_auth_rule` VALUES ('15', '13', 'Member/add', '新增用户', 'menu-icon fa fa-caret-right', '1', '1', '', '1', '15', '');
INSERT INTO `qw_auth_rule` VALUES ('16', '13', 'Member/edit', '编辑用户', 'menu-icon fa fa-caret-right', '1', '1', '', '0', '16', '');
INSERT INTO `qw_auth_rule` VALUES ('17', '13', 'Member/update', '保存用户', 'menu-icon fa fa-caret-right', '1', '1', '', '0', '17', '');
INSERT INTO `qw_auth_rule` VALUES ('18', '13', 'Member/del', '删除用户', '', '1', '1', '', '0', '18', '');
INSERT INTO `qw_auth_rule` VALUES ('19', '13', 'Group/index', '用户组管理', 'menu-icon fa fa-caret-right', '1', '1', '', '1', '19', '');
INSERT INTO `qw_auth_rule` VALUES ('20', '13', 'Group/add', '新增用户组', 'menu-icon fa fa-caret-right', '1', '1', '', '1', '20', '');
INSERT INTO `qw_auth_rule` VALUES ('21', '13', 'Group/edit', '编辑用户组', 'menu-icon fa fa-caret-right', '1', '1', '', '0', '21', '');
INSERT INTO `qw_auth_rule` VALUES ('22', '13', 'Group/update', '保存用户组', 'menu-icon fa fa-caret-right', '1', '1', '', '0', '22', '');
INSERT INTO `qw_auth_rule` VALUES ('23', '13', 'Group/del', '删除用户组', '', '1', '1', '', '0', '23', '');
INSERT INTO `qw_auth_rule` VALUES ('24', '0', '', '网站内容', 'menu-icon fa fa-desktop', '1', '1', '', '0', '24', '');
INSERT INTO `qw_auth_rule` VALUES ('25', '24', 'Article/index', '文章管理', 'menu-icon fa fa-caret-right', '1', '1', '', '1', '25', '网站虽然重要，身体更重要，出去走走吧。');
INSERT INTO `qw_auth_rule` VALUES ('26', '24', 'Article/add', '新增文章', 'menu-icon fa fa-caret-right', '1', '1', '', '1', '26', '');
INSERT INTO `qw_auth_rule` VALUES ('27', '24', 'Article/edit', '编辑文章', 'menu-icon fa fa-caret-right', '1', '1', '', '0', '27', '');
INSERT INTO `qw_auth_rule` VALUES ('29', '24', 'Article/update', '保存文章', 'menu-icon fa fa-caret-right', '1', '1', '', '0', '29', '');
INSERT INTO `qw_auth_rule` VALUES ('30', '24', 'Article/del', '删除文章', '', '1', '1', '', '0', '30', '');
INSERT INTO `qw_auth_rule` VALUES ('31', '24', 'Category/index', '分类管理', 'menu-icon fa fa-caret-right', '1', '1', '', '1', '31', '');
INSERT INTO `qw_auth_rule` VALUES ('32', '24', 'Category/add', '新增分类', 'menu-icon fa fa-caret-right', '1', '1', '', '1', '32', '');
INSERT INTO `qw_auth_rule` VALUES ('33', '24', 'Category/edit', '编辑分类', 'menu-icon fa fa-caret-right', '1', '1', '', '0', '33', '');
INSERT INTO `qw_auth_rule` VALUES ('34', '24', 'Category/update', '保存分类', 'menu-icon fa fa-caret-right', '1', '1', '', '0', '34', '');
INSERT INTO `qw_auth_rule` VALUES ('36', '24', 'Category/del', '删除分类', '', '1', '1', '', '0', '36', '');
INSERT INTO `qw_auth_rule` VALUES ('37', '0', '', '其它功能', '', '1', '1', '', '1', '50', '');
INSERT INTO `qw_auth_rule` VALUES ('38', '37', 'Link/index', '友情链接', 'menu-icon fa fa-caret-right', '1', '1', '', '1', '38', '');
INSERT INTO `qw_auth_rule` VALUES ('39', '37', 'Link/add', '增加链接', '', '1', '1', '', '1', '39', '');
INSERT INTO `qw_auth_rule` VALUES ('40', '37', 'Link/edit', '编辑链接', '', '1', '1', '', '0', '40', '');
INSERT INTO `qw_auth_rule` VALUES ('41', '37', 'Link/update', '保存链接', '', '1', '1', '', '0', '41', '');
INSERT INTO `qw_auth_rule` VALUES ('42', '37', 'Link/del', '删除链接', '', '1', '1', '', '0', '42', '');
INSERT INTO `qw_auth_rule` VALUES ('43', '37', 'Flash/index', '焦点图', 'menu-icon fa fa-desktop', '1', '1', '', '1', '43', '');
INSERT INTO `qw_auth_rule` VALUES ('44', '37', 'Flash/add', '新增焦点图', '', '1', '1', '', '1', '44', '');
INSERT INTO `qw_auth_rule` VALUES ('45', '37', 'Flash/update', '保存焦点图', '', '1', '1', '', '0', '45', '');
INSERT INTO `qw_auth_rule` VALUES ('46', '37', 'Flash/edit', '编辑焦点图', '', '1', '1', '', '0', '46', '');
INSERT INTO `qw_auth_rule` VALUES ('47', '37', 'Flash/del', '删除焦点图', '', '1', '1', '', '0', '47', '');
INSERT INTO `qw_auth_rule` VALUES ('48', '0', 'Personal/index', '个人中心', 'menu-icon fa fa-user', '1', '1', '', '1', '48', '');
INSERT INTO `qw_auth_rule` VALUES ('49', '48', 'Personal/profile', '个人资料', 'menu-icon fa fa-user', '1', '1', '', '1', '49', '');
INSERT INTO `qw_auth_rule` VALUES ('50', '48', 'Logout/index', '退出', '', '1', '1', '', '1', '50', '');
INSERT INTO `qw_auth_rule` VALUES ('51', '9', 'Database/export', '备份', '', '1', '1', '', '0', '51', '');
INSERT INTO `qw_auth_rule` VALUES ('52', '9', 'Database/optimize', '数据优化', '', '1', '1', '', '0', '52', '');
INSERT INTO `qw_auth_rule` VALUES ('53', '9', 'Database/repair', '修复表', '', '1', '1', '', '0', '53', '');
INSERT INTO `qw_auth_rule` VALUES ('54', '11', 'Update/updating', '升级安装', '', '1', '1', '', '0', '54', '');
INSERT INTO `qw_auth_rule` VALUES ('55', '48', 'Personal/update', '资料保存', '', '1', '1', '', '0', '55', '');
INSERT INTO `qw_auth_rule` VALUES ('56', '3', 'Setting/update', '设置保存', '', '1', '1', '', '0', '56', '');
INSERT INTO `qw_auth_rule` VALUES ('57', '9', 'Database/del', '备份删除', '', '1', '1', '', '0', '57', '');
INSERT INTO `qw_auth_rule` VALUES ('58', '2', 'variable/index', '自定义变量', '', '1', '1', '', '1', '0', '');
INSERT INTO `qw_auth_rule` VALUES ('59', '58', 'variable/add', '新增变量', '', '1', '1', '', '0', '0', '');
INSERT INTO `qw_auth_rule` VALUES ('60', '58', 'variable/edit', '编辑变量', '', '1', '1', '', '0', '0', '');
INSERT INTO `qw_auth_rule` VALUES ('61', '58', 'variable/update', '保存变量', '', '1', '1', '', '0', '0', '');
INSERT INTO `qw_auth_rule` VALUES ('62', '58', 'variable/del', '删除变量', '', '1', '1', '', '0', '0', '');
INSERT INTO `qw_auth_rule` VALUES ('63', '37', 'Facebook/add', '用户反馈', '', '1', '1', '', '1', '63', '');
INSERT INTO `qw_auth_rule` VALUES ('67', '0', 'PushMessage/index_BETA', '消息推送', 'menu-icon fa fa-legal', '1', '1', '', '1', '25', '');
INSERT INTO `qw_auth_rule` VALUES ('68', '25', 'PushMessage/pushMessageToList', '推送个人', 'menu-icon fa fa-user', '1', '1', '', '1', '26', '');
INSERT INTO `qw_auth_rule` VALUES ('69', '67', 'PushMessage/index_CN', 'CN 推送', '', '1', '1', '', '0', '0', '');
INSERT INTO `qw_auth_rule` VALUES ('71', '67', 'PushMessage/index_CN_IOS', 'CN_IOS 推送', '', '1', '1', '', '0', '0', '');
INSERT INTO `qw_auth_rule` VALUES ('70', '67', 'PushMessage/index_TW', 'TW 推送', '', '1', '1', '', '0', '0', '');
INSERT INTO `qw_auth_rule` VALUES ('72', '67', 'PushMessage/index_TW_IOS', 'TW_IOS 推送', '', '1', '1', '', '0', '0', '');
INSERT INTO `qw_auth_rule` VALUES ('76', '67', 'PushMessage/index_Single', '推送个人', '', '1', '1', '', '1', '0', '');
INSERT INTO `qw_auth_rule` VALUES ('77', '67', 'PushMessage/index_App', '推送群组', '', '1', '1', '', '1', '2', '');
INSERT INTO `qw_auth_rule` VALUES ('78', '0', '', '数据列表', 'menu-icon fa fa-user', '1', '1', '', '1', '45', '');
INSERT INTO `qw_auth_rule` VALUES ('79', '78', 'UserAmount/Index', '用户信息', '', '1', '1', '', '1', '0', '');
INSERT INTO `qw_auth_rule` VALUES ('80', '78', 'UserAmount/getRedisSets', 'redis操作', '', '1', '1', '', '1', '0', '');
INSERT INTO `qw_auth_rule` VALUES ('81', '0', '', '牌局管理', 'menu-icon fa fa-users', '1', '1', '', '1', '13', '');
INSERT INTO `qw_auth_rule` VALUES ('82', '81', 'Game/Index', '牌局列表', '', '1', '1', '', '1', '0', '');
INSERT INTO `qw_auth_rule` VALUES ('83', '78', 'UserAmount/Order', '订单列表', '', '1', '1', '', '1', '0', '');
INSERT INTO `qw_auth_rule` VALUES ('84', '0', '', '数据统计', 'menu-icon fa fa-user', '1', '1', '', '1', '46', '');
INSERT INTO `qw_auth_rule` VALUES ('85', '84', 'DataStat/insurance', '保险统计', '', '1', '1', '', '1', '0', '');
INSERT INTO `qw_auth_rule` VALUES ('90', '0', '', 'Banner图管理', 'menu-icon fa fa-legal', '1', '1', '', '1', '48', '');
INSERT INTO `qw_auth_rule` VALUES ('91', '90', 'Banner/Index', 'banner图列表', '', '1', '1', '', '1', '0', '');

-- ----------------------------
-- Table structure for qw_category
-- ----------------------------
DROP TABLE IF EXISTS `qw_category`;
CREATE TABLE `qw_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) NOT NULL COMMENT '0正常，1单页，2外链',
  `pid` int(11) NOT NULL COMMENT '父ID',
  `name` varchar(100) NOT NULL COMMENT '分类名称',
  `dir` varchar(100) NOT NULL COMMENT '目录名称',
  `seotitle` varchar(200) DEFAULT NULL COMMENT 'SEO标题',
  `keywords` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `url` varchar(255) NOT NULL,
  `cattemplate` varchar(100) NOT NULL,
  `contemplate` varchar(100) NOT NULL,
  `o` int(11) NOT NULL COMMENT '排序',
  PRIMARY KEY (`id`),
  KEY `fsid` (`pid`)
) ENGINE=MyISAM AUTO_INCREMENT=36 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of qw_category
-- ----------------------------

-- ----------------------------
-- Table structure for qw_devlog
-- ----------------------------
DROP TABLE IF EXISTS `qw_devlog`;
CREATE TABLE `qw_devlog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `v` varchar(225) NOT NULL COMMENT '版本号',
  `y` int(4) NOT NULL COMMENT '年分',
  `t` int(10) NOT NULL COMMENT '发布日期',
  `log` text NOT NULL COMMENT '更新日志',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of qw_devlog
-- ----------------------------
INSERT INTO `qw_devlog` VALUES ('1', '1.0.0', '2016', '1440259200', 'QWADMIN第一个版本发布。');
INSERT INTO `qw_devlog` VALUES ('2', '1.0.1', '2016', '1440259200', '修改cookie过于简单的安全风险。');

-- ----------------------------
-- Table structure for qw_flash
-- ----------------------------
DROP TABLE IF EXISTS `qw_flash`;
CREATE TABLE `qw_flash` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `pic` varchar(255) NOT NULL,
  `o` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `o` (`o`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of qw_flash
-- ----------------------------

-- ----------------------------
-- Table structure for qw_links
-- ----------------------------
DROP TABLE IF EXISTS `qw_links`;
CREATE TABLE `qw_links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `logo` varchar(255) NOT NULL,
  `o` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `o` (`o`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of qw_links
-- ----------------------------

-- ----------------------------
-- Table structure for qw_log
-- ----------------------------
DROP TABLE IF EXISTS `qw_log`;
CREATE TABLE `qw_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `t` int(10) NOT NULL,
  `ip` varchar(16) NOT NULL,
  `log` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=149 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of qw_log
-- ----------------------------
INSERT INTO `qw_log` VALUES ('1', 'admin', '1490860050', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('2', 'admin', '1490860315', '::1', '编辑菜单，ID：1');
INSERT INTO `qw_log` VALUES ('3', 'admin', '1490860421', '::1', '新增菜单，名称：test 菜单');
INSERT INTO `qw_log` VALUES ('4', 'admin', '1490864033', '::1', '备份完成！');
INSERT INTO `qw_log` VALUES ('5', 'admin', '1490864752', '::1', '数据表优化完成！');
INSERT INTO `qw_log` VALUES ('6', 'admin', '1490865079', '::1', '数据表修复完成！');
INSERT INTO `qw_log` VALUES ('7', 'admin', '1490867764', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('8', 'admin', '1490937834', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('9', 'admin', '1490938410', '::1', '新增菜单，名称：公告推送');
INSERT INTO `qw_log` VALUES ('10', 'admin', '1490953928', '::1', '新增菜单，名称：CN 推送');
INSERT INTO `qw_log` VALUES ('11', 'admin', '1490953987', '::1', '新增菜单，名称：CN_IOS 推送');
INSERT INTO `qw_log` VALUES ('12', 'admin', '1490954083', '::1', '新增菜单，名称：TW 推送');
INSERT INTO `qw_log` VALUES ('13', 'admin', '1490954398', '::1', '新增菜单，名称：TW_IOS 推送');
INSERT INTO `qw_log` VALUES ('14', 'admin', '1490955233', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('15', 'admin', '1491008760', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('16', 'admin', '1491010554', '::1', '新增菜单，名称：推送个人');
INSERT INTO `qw_log` VALUES ('17', 'admin', '1491010622', '::1', '新增菜单，名称：推送群组');
INSERT INTO `qw_log` VALUES ('18', 'admin', '1491361720', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('19', 'admin', '1491380949', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('20', 'admin', '1491380972', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('21', 'admin', '1492667058', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('22', 'admin', '1492673135', '192.168.0.190', '登录成功。');
INSERT INTO `qw_log` VALUES ('23', 'admin', '1492684011', '192.168.0.190', '登录成功。');
INSERT INTO `qw_log` VALUES ('24', 'admin', '1492742974', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('25', 'admin', '1492748491', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('26', 'admin', '1492764830', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('27', 'admin', '1492764851', '127.0.0.1', '登录成功。');
INSERT INTO `qw_log` VALUES ('28', 'admin', '1492764871', '127.0.0.1', '登录成功。');
INSERT INTO `qw_log` VALUES ('29', 'admin', '1492768471', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('30', 'admin', '1492771291', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('31', 'admin', '1493003896', '127.0.0.1', '登录成功。');
INSERT INTO `qw_log` VALUES ('32', 'admin', '1493003965', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('33', 'admin', '1493005315', '192.168.0.188', '登录成功。');
INSERT INTO `qw_log` VALUES ('34', 'admin', '1493021165', '192.168.0.188', '登录成功。');
INSERT INTO `qw_log` VALUES ('35', 'admin', '1493110833', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('36', 'admin', '1493111085', '192.168.0.188', '登录成功。');
INSERT INTO `qw_log` VALUES ('37', 'admin', '1493195274', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('38', 'admin', '1493278782', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('39', 'admin', '1493278976', '::1', '新增菜单，名称：用户信息');
INSERT INTO `qw_log` VALUES ('40', 'admin', '1493280248', '127.0.0.1', '登录成功。');
INSERT INTO `qw_log` VALUES ('41', 'admin', '1493281782', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('42', 'admin', '1493282010', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('43', 'admin', '1493282081', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('44', 'admin', '1493282124', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('45', 'admin', '1493282235', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('46', 'admin', '1493286177', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('47', 'admin', '1493286763', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('48', 'admin', '1493288576', '::1', '登录失败。');
INSERT INTO `qw_log` VALUES ('49', 'admin', '1493288622', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('50', 'admin', '1493289272', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('51', 'admin', '1493342639', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('52', 'admin', '1493345929', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('53', 'admin', '1493346124', '192.168.0.190', '登录成功。');
INSERT INTO `qw_log` VALUES ('54', 'admin', '1493348013', '192.168.0.190', '登录成功。');
INSERT INTO `qw_log` VALUES ('55', 'admin', '1493348065', '192.168.0.190', '登录成功。');
INSERT INTO `qw_log` VALUES ('56', 'admin', '1493349313', '192.168.0.190', '登录成功。');
INSERT INTO `qw_log` VALUES ('57', 'admin', '1493349588', '192.168.0.190', '登录成功。');
INSERT INTO `qw_log` VALUES ('58', 'admin', '1493349826', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('59', 'admin', '1493359203', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('60', 'admin', '1493362342', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('61', 'admin', '1493362419', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('62', 'admin', '1493362466', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('63', 'admin', '1493367143', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('64', 'admin', '1493367369', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('65', 'admin', '1493368750', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('66', 'admin', '1493372617', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('67', 'admin', '1493373076', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('68', 'admin', '1493373540', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('69', 'admin', '1493375412', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('70', 'admin', '1493688836', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('71', 'admin', '1493689852', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('72', 'admin', '1493694151', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('73', 'admin', '1493717114', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('74', 'admin', '1493718165', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('75', 'admin', '1493772993', '::1', '登录失败。');
INSERT INTO `qw_log` VALUES ('76', 'admin', '1493773059', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('77', 'admin', '1493950125', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('78', 'admin', '1493950300', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('79', 'admin', '1493954551', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('80', 'admin', '1494395581', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('81', 'admin', '1494395839', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('82', 'admin', '1494397391', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('83', 'admin', '1494400442', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('84', 'admin', '1494482269', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('85', 'admin', '1494482347', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('86', 'admin', '1494482496', '::1', '修改网站配置。');
INSERT INTO `qw_log` VALUES ('87', 'admin', '1494482537', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('88', 'admin', '1494482912', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('89', 'admin', '1494483719', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('90', 'admin', '1494486587', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('91', 'admin', '1494488348', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('92', 'admin', '1494489119', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('93', 'admin', '1494492869', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('94', 'admin', '1494495268', '::1', '修改网站配置。');
INSERT INTO `qw_log` VALUES ('95', 'admin', '1494495748', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('96', 'admin', '1494497323', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('97', 'admin', '1494551771', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('98', 'admin', '1494552466', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('99', 'admin', '1494556147', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('100', 'admin', '1494556384', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('101', 'admin', '1494557245', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('102', 'admin', '1494557381', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('103', 'admin', '1494830999', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('104', 'admin', '1494831622', '192.168.0.180', '登录成功。');
INSERT INTO `qw_log` VALUES ('105', 'admin', '1494832397', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('106', 'admin', '1494841117', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('107', 'admin', '1494841555', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('108', 'admin', '1494841727', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('109', 'admin', '1494987256', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('110', 'admin', '1494988948', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('111', 'admin', '1495003698', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('112', 'admin', '1495007383', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('113', 'admin', '1495009940', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('114', 'admin', '1495070347', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('115', 'admin', '1495078673', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('116', 'admin', '1495087157', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('117', 'admin', '1495093920', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('118', 'admin', '1495157011', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('119', 'admin', '1495160358', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('120', 'admin', '1495160744', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('121', 'admin', '1495172079', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('122', 'admin', '1495173057', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('123', 'admin', '1495422355', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('124', 'admin', '1495422593', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('125', 'admin', '1495424703', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('126', 'admin', '1495434859', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('127', 'admin', '1495447829', '127.0.0.1', '登录成功。');
INSERT INTO `qw_log` VALUES ('128', 'admin', '1495501266', '127.0.0.1', '登录成功。');
INSERT INTO `qw_log` VALUES ('129', 'admin', '1495501504', '127.0.0.1', '登录成功。');
INSERT INTO `qw_log` VALUES ('130', 'admin', '1495507676', '127.0.0.1', '登录成功。');
INSERT INTO `qw_log` VALUES ('131', 'admin', '1495510450', '127.0.0.1', '登录成功。');
INSERT INTO `qw_log` VALUES ('132', 'admin', '1495520154', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('133', 'ADMIN', '1495522271', '::1', '登录失败。');
INSERT INTO `qw_log` VALUES ('134', 'admin', '1495522294', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('135', 'admin', '1495524296', '127.0.0.1', '登录成功。');
INSERT INTO `qw_log` VALUES ('136', 'admin', '1495527588', '127.0.0.1', '登录成功。');
INSERT INTO `qw_log` VALUES ('137', 'admin', '1495527714', '127.0.0.1', '登录成功。');
INSERT INTO `qw_log` VALUES ('138', 'admin', '1495588565', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('139', 'admin', '1495588926', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('140', 'admin', '1495589216', '127.0.0.1', '登录成功。');
INSERT INTO `qw_log` VALUES ('141', 'admin', '1495589433', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('142', 'admin', '1495592729', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('143', 'admin', '1495594075', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('144', 'admin', '1495594333', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('145', 'admin', '1495612480', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('146', 'admin', '1495613375', '::1', '登录成功。');
INSERT INTO `qw_log` VALUES ('147', 'admin', '1495614375', '127.0.0.1', '登录成功。');
INSERT INTO `qw_log` VALUES ('148', 'admin', '1495618625', '::1', '登录成功。');

-- ----------------------------
-- Table structure for qw_member
-- ----------------------------
DROP TABLE IF EXISTS `qw_member`;
CREATE TABLE `qw_member` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(225) NOT NULL,
  `head` varchar(255) NOT NULL COMMENT '头像',
  `sex` tinyint(1) NOT NULL COMMENT '0保密1男，2女',
  `birthday` int(10) NOT NULL COMMENT '生日',
  `phone` varchar(20) NOT NULL COMMENT '电话',
  `qq` varchar(20) NOT NULL COMMENT 'QQ',
  `email` varchar(255) NOT NULL COMMENT '邮箱',
  `password` varchar(32) NOT NULL,
  `t` int(10) unsigned NOT NULL COMMENT '注册时间',
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of qw_member
-- ----------------------------
INSERT INTO `qw_member` VALUES ('1', 'admin', '/Public/attached/201601/1453389194.png', '1', '1420128000', '13800138000', '331349451', 'xieyanwei@qq.com', '66d6a1c8748025462128dc75bf5ae8d1', '1442505600');

-- ----------------------------
-- Table structure for qw_setting
-- ----------------------------
DROP TABLE IF EXISTS `qw_setting`;
CREATE TABLE `qw_setting` (
  `k` varchar(100) NOT NULL COMMENT '变量',
  `v` varchar(255) NOT NULL COMMENT '值',
  `type` tinyint(1) NOT NULL COMMENT '0系统，1自定义',
  `name` varchar(255) NOT NULL COMMENT '说明',
  PRIMARY KEY (`k`),
  KEY `k` (`k`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of qw_setting
-- ----------------------------
INSERT INTO `qw_setting` VALUES ('sitename', 'POKER', '0', '');
INSERT INTO `qw_setting` VALUES ('title', '扑克部落', '0', '');
INSERT INTO `qw_setting` VALUES ('keywords', '关键词', '0', '');
INSERT INTO `qw_setting` VALUES ('description', '网站描述', '0', '');
INSERT INTO `qw_setting` VALUES ('footer', '2016©巨潮网络', '0', '');
INSERT INTO `qw_setting` VALUES ('test', '', '1', '测试变量');
INSERT INTO `qw_setting` VALUES ('GETUI_ID', '188', '1', '个推id');

-- ----------------------------
-- Table structure for qw_user_diamond_log
-- ----------------------------
DROP TABLE IF EXISTS `qw_user_diamond_log`;
CREATE TABLE `qw_user_diamond_log` (
  `uid` int(11) NOT NULL COMMENT '用户id',
  `count` int(11) DEFAULT NULL COMMENT '消耗数量',
  `gid` int(11) DEFAULT NULL COMMENT '牌局id',
  `surcharge` varchar(255) DEFAULT NULL COMMENT '服务费',
  `balance` varchar(255) DEFAULT NULL COMMENT '余额',
  `time` datetime DEFAULT NULL COMMENT '日志插入时间',
  `reason` varchar(255) DEFAULT NULL COMMENT '扣费原因',
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of qw_user_diamond_log
-- ----------------------------

-- ----------------------------
-- Table structure for qw_wx_bind
-- ----------------------------
DROP TABLE IF EXISTS `qw_wx_bind`;
CREATE TABLE `qw_wx_bind` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `openid` varchar(200) DEFAULT NULL COMMENT '微信号',
  `status` tinyint(3) DEFAULT NULL,
  `bind_time` datetime DEFAULT NULL COMMENT '绑定时间',
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of qw_wx_bind
-- ----------------------------

-- ----------------------------
-- Table structure for tbl_insurance
-- ----------------------------
DROP TABLE IF EXISTS `tbl_insurance`;
CREATE TABLE `tbl_insurance` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `server_group` int(11) DEFAULT NULL,
  `server_name` varchar(32) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `code` varchar(32) DEFAULT NULL,
  `gid` int(11) DEFAULT NULL,
  `buy` int(11) DEFAULT NULL,
  `pay` int(11) unsigned zerofill DEFAULT NULL,
  `create_time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tbl_insurance
-- ----------------------------

-- ----------------------------
-- Table structure for tbl_insurance_stat
-- ----------------------------
DROP TABLE IF EXISTS `tbl_insurance_stat`;
CREATE TABLE `tbl_insurance_stat` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `server_group` int(11) DEFAULT NULL COMMENT '服务器组',
  `buy` int(11) DEFAULT NULL,
  `pay` int(11) unsigned zerofill DEFAULT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tbl_insurance_stat
-- ----------------------------
