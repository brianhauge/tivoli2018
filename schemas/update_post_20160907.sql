DROP TABLE tivoli2016_posts;
ALTER TABLE tivoli2016_score MODIFY postid VARCHAR(20) NOT NULL;
ALTER TABLE tivoli2016_postcheckin MODIFY postid VARCHAR(20) NOT NULL;
ALTER TABLE tivoli2016_postcheckin_change_log MODIFY postid VARCHAR(20) NOT NULL;