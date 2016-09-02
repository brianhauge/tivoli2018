DROP TABLE IF EXISTS tivoli2016_score_change_log;
CREATE TABLE tivoli2016_score_change_log (
  `action` VARCHAR(20),
  `teamid`bigint(6) unsigned NOT NULL,
  `point` bigint(6) unsigned NOT NULL,
  `postid` bigint(6) unsigned NOT NULL,
  `creator` varchar(255),
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;


DROP TRIGGER IF EXISTS insert_score_trig;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`haugemedia_net`@`%`*/ /*!50003 TRIGGER `insert_score_trig` AFTER INSERT ON `tivoli2016_score`
  FOR EACH ROW
    BEGIN
		INSERT INTO tivoli2016_score_change_log SET action = 'INSERT', teamid = NEW.teamid, point = NEW.point, postid = NEW.postid, creator = NEW.creator;
    END */;;
DELIMITER ;

DROP TRIGGER IF EXISTS update_score_trig;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`haugemedia_net`@`%`*/ /*!50003 TRIGGER `update_score_trig` AFTER UPDATE ON `tivoli2016_score`
  FOR EACH ROW
    BEGIN
		INSERT INTO tivoli2016_score_change_log SET action = 'UPDATE', teamid = NEW.teamid, point = NEW.point, postid = NEW.postid, creator = NEW.creator;
    END */;;
DELIMITER ;

DROP TRIGGER IF EXISTS delete_score_trig;
DELIMITER ;;
  /*!50003 CREATE*/ /*!50017 DEFINER=`haugemedia_net`@`%`*/ /*!50003 TRIGGER `delete_score_trig` AFTER DELETE ON `tivoli2016_score`
FOR EACH ROW
  BEGIN
    INSERT INTO tivoli2016_score_change_log SET action = 'DELETE', teamid = OLD.teamid, point = OLD.point, postid = OLD.postid, creator = OLD.creator;
  END */;;
DELIMITER ;