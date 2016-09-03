DROP TABLE IF EXISTS tivoli2016_postcheckin_change_log;
CREATE TABLE tivoli2016_postcheckin_change_log (
  `action` VARCHAR(20),
  `mobile` varchar(16) NOT NULL,
  `postid` bigint(6) unsigned NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;


DROP TRIGGER IF EXISTS insert_postcheckin_trig;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`haugemedia_net`@`%`*/ /*!50003 TRIGGER `insert_postcheckin_trig` AFTER INSERT ON `tivoli2016_postcheckin`
  FOR EACH ROW
    BEGIN
		INSERT INTO tivoli2016_postcheckin_change_log SET action = 'INSERT', mobile = NEW.mobile, postid = NEW.postid;
    END */;;
DELIMITER ;

DROP TRIGGER IF EXISTS update_postcheckin_trig;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`haugemedia_net`@`%`*/ /*!50003 TRIGGER `update_postcheckin_trig` AFTER UPDATE ON `tivoli2016_postcheckin`
  FOR EACH ROW
    BEGIN
		INSERT INTO tivoli2016_postcheckin_change_log SET action = 'UPDATE', mobile = NEW.mobile, postid = NEW.postid;
    END */;;
DELIMITER ;

DROP TRIGGER IF EXISTS delete_postcheckin_trig;
DELIMITER ;;
  /*!50003 CREATE*/ /*!50017 DEFINER=`haugemedia_net`@`%`*/ /*!50003 TRIGGER `delete_postcheckin_trig` AFTER DELETE ON `tivoli2016_postcheckin`
FOR EACH ROW
  BEGIN
    INSERT INTO tivoli2016_postcheckin_change_log SET action = 'DELETE', mobile = OLD.mobile, postid = OLD.postid;
  END */;;
DELIMITER ;