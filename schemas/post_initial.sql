CREATE TABLE `tivoli2018_post` (
  `postnr` bigint(6) UNSIGNED NOT NULL,
  `type` varchar(20) NOT NULL,
  `navn` bigint(6) UNSIGNED NOT NULL,
  `location` varchar(20) NOT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `tivoli2018_post`
  ADD UNIQUE KEY `postnr_type` (`postnr`,`type`);

select * from tivoli2018_users

insert into tivoli2018_users values ('user',sha2('password',256),now(),now());

