CREATE TABLE `menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `pid` int(5) DEFAULT NULL,
  `pages_id` int(3) DEFAULT NULL,
  `modname` enum('','news','photo') DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `sort` int(5) DEFAULT NULL,
  `status` enum('1','') DEFAULT '1',
  `place` enum('main','top','bot') NOT NULL,
  `bg_img` varchar(255) NOT NULL,
  `title` text NOT NULL,
  `description` text NOT NULL,
  `keywords` text NOT NULL,
  `right_part` enum('1','') NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;