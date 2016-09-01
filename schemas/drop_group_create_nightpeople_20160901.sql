DROP TABLE tivoli2016_groups;
CREATE TABLE IF NOT EXISTS tivoli2016_nightpeople (
  `name` varchar(255) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `kreds` varchar(20) NOT NULL,
  UNIQUE KEY `score` (`name`, `mobile`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;