DROP TABLE IF EXISTS log_lounch_script;
CREATE TABLE `log_lounch_script` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `command` varchar(45) DEFAULT NULL,
  `comman_line` varchar(255) DEFAULT NULL,
  `pass` varchar(12) DEFAULT NULL,
  `return_val` text,
  `datetime_exec` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `server` varchar(255) DEFAULT NULL,
  
  `order_id` int(11) DEFAULT NULL,
  `type` char(12) DEFAULT NULL,
  `datetime_begin` timestamp NULL DEFAULT NULL,
  `datetime_expire` timestamp NULL DEFAULT NULL,
  `num_order` int(11) DEFAULT NULL,
  `user` char(255) DEFAULT NULL,
  `price` float(6,2) DEFAULT NULL,
  `ammount` int(3) DEFAULT NULL,
  `portable` enum('1','') DEFAULT '',
  `period` char(12) DEFAULT NULL,
  `action` char(12) DEFAULT NULL,
  `protocol` char(5) DEFAULT NULL,
  `account` char(12) DEFAULT NULL,
  `os` enum('win','mac') DEFAULT 'win',
  
  `date_create` timestamp NULL DEFAULT NULL,
  `datetime_edit` timestamp NULL DEFAULT NULL,
  `user_update` char(255) DEFAULT NULL,


  PRIMARY KEY (`id`),
  KEY `ordex_inx` (`order_id`),
  KEY `command_inx` (`command`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- 23:23:37	CREATE TABLE `log_lounch_script` (   `id` int(11) NOT NULL AUTO_INCREMENT,   `action` varchar(45) DEFAULT NULL,   `comman_line` varchar(255) DEFAULT NULL,   `pass` varchar(12) DEFAULT NULL,   `return_val` text,   `datetime_exec` timestamp NULL DEFAULT CURRENT_TIMESTAMP,   `order_id` int(11) DEFAULT NULL,    `type` char(12) DEFAULT NULL,   `datetime_begin` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,   `datetime_expire` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',      `num_order` int(11) DEFAULT NULL,   `user` char(255) DEFAULT NULL,   `price` float(6,2) DEFAULT NULL,   `ammount` int(3) DEFAULT NULL,   `portable` enum('1','') DEFAULT '',   `period` char(12) DEFAULT NULL,   `protocol` char(5) DEFAULT NULL,   `account` char(12) DEFAULT NULL,   `os` enum('win','mac') DEFAULT 'win',   `server` varchar(255) DEFAULT NULL,   `date_create` timestamp NULL DEFAULT NULL,   `datetime_edit` timestamp NULL DEFAULT NULL,   `user_update` char(255) DEFAULT NULL,     PRIMARY KEY (`id`),   KEY `ordex_inx` (`order_id`),   KEY `act_inx` (`action`) ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8	Error Code: 1293. Incorrect table definition; there can be only one TIMESTAMP column with CURRENT_TIMESTAMP in DEFAULT or ON UPDATE clause	0.156 sec

SELECT  
	t.name as type,
    o.datetime_begin,
    o.datetime_expire,
    o.num_order,o.price, u.name as user,o.price,o.ammount,
    o.portable,	pi.name as period,p.name as protocol,a.name as ammount,o.os,
	getServerByOrderID(o.id) as servers,  
	o.date_create,
    o.datetime_edit,
	uu.name as user_update
                    
From orders o
      Left JOIN users u ON u.id=o.user_id
      Left JOIN periods pi ON pi.id=o.period_id
      Left JOIN types t ON t.id = o.type_id
      Left JOIN protocols p ON p.id = o.protocol_id
      Left JOIN accounts a ON a.id = o.account_id      
      Left JOIN users uu ON uu.id=o.user_update_id
