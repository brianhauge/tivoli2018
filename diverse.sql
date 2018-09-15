
-- TRUNCATE TABLE tivoli2018_postcheckin;
-- TRUNCATE TABLE tivoli2018_postcheckin_change_log;
-- SELECT * FROM tivoli2018_postcheckin;
-- SELECT * FROM tivoli2018_postcheckin_change_log;
-- TRUNCATE TABLE tivoli2018_score;
-- TRUNCATE TABLE tivoli2018_score_change_log;
-- INSERT INTO tivoli2018_postcheckin (mobile, postid) VALUES ("4525212002","200");
-- INSERT INTO tivoli2018_score (teamid,point,postid,creator) SELECT id,"100","200","4525212002" FROM tivoli2018_teams WHERE updated_at < "2018-09-01";
-- INSERT INTO tivoli2018_score (teamid,point,postid,creator) SELECT id,"50","200","4525212002" FROM tivoli2018_teams WHERE updated_at > "2018-09-01";