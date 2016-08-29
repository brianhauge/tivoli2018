Drop table IF EXISTS tivoli2016_trace;
CREATE TABLE `tivoli2016_trace` (
  `tstamp` DATETIME(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `msisdn` bigint(20) unsigned NOT NULL,
  `method` varchar(512) NOT NULL,
  `input` varchar(512) DEFAULT NULL,
  `output` varchar(512) DEFAULT NULL,
  KEY `msisdn_key` (`msisdn`),
  KEY `tstamp_key` (`tstamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8