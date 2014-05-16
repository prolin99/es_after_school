# //$Id: module.sql,v 1.1.2.1 2004/10/22 15:26:46 prolin Exp $

# 資料表格式： `afdb_month`
#
CREATE TABLE `afdb_month` (
  `motn_id` int(11) NOT NULL,
  `grade_year` tinyint(4) NOT NULL,
  `time_mode` tinyint(4) NOT NULL DEFAULT '0',
  `cost` int(11) NOT NULL DEFAULT '260',
  `stud_dc` float NOT NULL DEFAULT '1',
  `sect_num` int(11) NOT NULL DEFAULT '0',
  `teacher_dc` float NOT NULL DEFAULT '0.7',
  `class_num` tinyint(4) NOT NULL DEFAULT '0',
  `stud_num` int(11) NOT NULL DEFAULT '0',
  `pay` int(11) NOT NULL DEFAULT '0',
  `pay_sum` int(11) NOT NULL DEFAULT '0',
  `deadline` date NOT NULL,
  PRIMARY KEY (`motn_id`,`grade_year`,`time_mode`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='課後期別';



# 資料表格式： `afdb_sign`
#

CREATE TABLE `afdb_sign` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `month_id` int(11) NOT NULL,
  `grade_year` tinyint(4) NOT NULL,
  `class_id` tinyint(4) NOT NULL,
  `class_id_base` varchar(5) NOT NULL,
  `stud_name` varchar(20) NOT NULL,
  `stud_sex` tinyint(4) NOT NULL,
  `time_mode` tinyint(4) NOT NULL,
  `spec` tinyint(4) NOT NULL,
  `ps` varchar(80) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `month_id` (`month_id`,`grade_year`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='課後報名'  ;