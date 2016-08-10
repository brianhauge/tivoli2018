CREATE TABLE IF NOT EXISTS `tivoli2016_teams` (
  `id` bigint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `groups` tinytext,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS tivoli2016_groups (
  `id` bigint(2) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS tivoli2016_posts (
  `id` bigint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS tivoli2016_score;
CREATE TABLE tivoli2016_score (
  `id` bigint(6) unsigned NOT NULL AUTO_INCREMENT,
  `teamid`bigint(6) unsigned NOT NULL,
  `point` bigint(6) unsigned NOT NULL,
  `postid` bigint(6) unsigned NOT NULL,
  `creator` varchar(255),
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`, `teamid`, `postid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;