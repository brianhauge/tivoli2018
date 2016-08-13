DROP TABLE IF EXISTS tivoli2016_postcheckin;
CREATE TABLE tivoli2016_postcheckin (
  `mobile` varchar(16) NOT NULL,
  `postid` bigint(6) unsigned NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`mobile`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;