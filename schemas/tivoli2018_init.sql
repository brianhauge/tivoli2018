SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------

--
-- Table structure for table `tivoli2018_crew`
--

DROP TABLE IF EXISTS `tivoli2018_crew`;
CREATE TABLE `tivoli2018_crew` (
  `name` varchar(255) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `kreds` varchar(20) NOT NULL,
  `comment` TEXT NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tivoli2018_postcheckin`
--

CREATE TABLE `tivoli2018_postcheckin` (
  `mobile` varchar(16) NOT NULL,
  `postid` varchar(20) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Triggers `tivoli2018_postcheckin`
--
DELIMITER $$
CREATE TRIGGER `delete_postcheckin_trig` AFTER DELETE ON `tivoli2018_postcheckin` FOR EACH ROW BEGIN
    INSERT INTO tivoli2018_postcheckin_change_log SET action = 'DELETE', mobile = OLD.mobile, postid = OLD.postid;
  END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `insert_postcheckin_trig` AFTER INSERT ON `tivoli2018_postcheckin` FOR EACH ROW BEGIN
		INSERT INTO tivoli2018_postcheckin_change_log SET action = 'INSERT', mobile = NEW.mobile, postid = NEW.postid;
    END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_postcheckin_trig` AFTER UPDATE ON `tivoli2018_postcheckin` FOR EACH ROW BEGIN
		INSERT INTO tivoli2018_postcheckin_change_log SET action = 'UPDATE', mobile = NEW.mobile, postid = NEW.postid;
    END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `tivoli2018_postcheckin_change_log`
--

CREATE TABLE `tivoli2018_postcheckin_change_log` (
  `action` varchar(20) DEFAULT NULL,
  `mobile` varchar(16) NOT NULL,
  `postid` varchar(20) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tivoli2018_score`
--

CREATE TABLE `tivoli2018_score` (
  `id` bigint(6) UNSIGNED NOT NULL,
  `teamid` bigint(6) UNSIGNED NOT NULL,
  `point` bigint(6) UNSIGNED NOT NULL,
  `postid` varchar(20) NOT NULL,
  `creator` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Triggers `tivoli2018_score`
--
DELIMITER $$
CREATE TRIGGER `delete_score_trig` AFTER DELETE ON `tivoli2018_score` FOR EACH ROW BEGIN
    INSERT INTO tivoli2018_score_change_log SET action = 'DELETE', teamid = OLD.teamid, point = OLD.point, postid = OLD.postid, creator = OLD.creator;
  END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `insert_score_trig` AFTER INSERT ON `tivoli2018_score` FOR EACH ROW BEGIN
		INSERT INTO tivoli2018_score_change_log SET action = 'INSERT', teamid = NEW.teamid, point = NEW.point, postid = NEW.postid, creator = NEW.creator;
    END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_score_trig` AFTER UPDATE ON `tivoli2018_score` FOR EACH ROW BEGIN
		INSERT INTO tivoli2018_score_change_log SET action = 'UPDATE', teamid = NEW.teamid, point = NEW.point, postid = NEW.postid, creator = NEW.creator;
    END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `tivoli2018_score_change_log`
--

CREATE TABLE `tivoli2018_score_change_log` (
  `action` varchar(20) DEFAULT NULL,
  `teamid` bigint(6) UNSIGNED NOT NULL,
  `point` bigint(6) UNSIGNED NOT NULL,
  `postid` varchar(20) NOT NULL,
  `creator` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tivoli2018_teams`
--

CREATE TABLE `tivoli2018_teams` (
  `id` bigint(6) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `leader` varchar(255) NOT NULL,
  `mobile` varchar(16) NOT NULL,
  `email` varchar(255) NOT NULL,
  `kreds` varchar(255) NOT NULL,
  `numberofmembers` varchar(20) NOT NULL,
  `groups` tinytext,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tivoli2018_trace`
--

CREATE TABLE `tivoli2018_trace` (
  `tstamp` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `msisdn` bigint(20) UNSIGNED NOT NULL,
  `method` varchar(512) NOT NULL,
  `input` varchar(512) DEFAULT NULL,
  `output` varchar(512) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tivoli2016_users`
--

CREATE TABLE `tivoli2018_users` (
  `user` varchar(20) NOT NULL,
  `password` varchar(256) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Example: insert into tivoli2018_users values ('user',sha2('password',256),now(),now());

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tivoli2018_nightpeople`
--
ALTER TABLE `tivoli2018_nightpeople`
  ADD UNIQUE KEY `score` (`name`,`mobile`);

ALTER TABLE `tivoli2018_score_change_log`
    ADD INDEX `overview` (`action`, `teamid`, `point`, `postid`, `creator`);

--
-- Indexes for table `tivoli2018_postcheckin`
--
ALTER TABLE `tivoli2018_postcheckin`
  ADD PRIMARY KEY (`mobile`);

--
-- Indexes for table `tivoli2018_score`
--
ALTER TABLE `tivoli2018_score`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `score` (`teamid`,`postid`);

--
-- Indexes for table `tivoli2018_teams`
--
ALTER TABLE `tivoli2018_teams`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tivoli2018_trace`
--
ALTER TABLE `tivoli2018_trace`
  ADD KEY `msisdn_key` (`msisdn`),
  ADD KEY `tstamp_key` (`tstamp`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tivoli2018_score`
--
ALTER TABLE `tivoli2018_score`
  MODIFY `id` bigint(6) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tivoli2018_teams`
--
ALTER TABLE `tivoli2018_teams`
  MODIFY `id` bigint(6) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
