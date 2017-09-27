Drop table IF EXISTS tivoli2016_users;
CREATE TABLE `tivoli2016_users` (
  `user` varchar(20) NOT NULL,
  `password` varchar(256) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `tivoli2016_users` (user,password) VALUES ("hauge",SHA2('11PVT8se',256));

SELECT user FROM tivoli2016_users;