/*


INBOUND
{
  "messageId": "0A0000000123ABCD1",
  "msisdn": "447700900001",
  "to": "447700900000",
  "text": "Hello world",
  "type": "text",
  "keyword": "Hello",
  "message-timestamp": "2020-01-01T12:00:00.000+00:00",
  "timestamp": "1578787200",
  "nonce": "aaaaaaaa-bbbb-cccc-dddd-0123456789ab",
  "concat": "true",
  "concat-ref": "1",
  "concat-total": "3",
  "concat-part": "2",
  "data": "abc123",
  "udh": "abc123"
}

DLVR
{
  "messageId": "0A0000001234567B",
  "msisdn": "447700900000",
  "to": "Acme Inc",
  "message-timestamp": "2020-01-01T12:00:00.000+00:00"
  "network-code": "12345",
  "price": "0.03330000",
  "status": "delivered",  <-- delivered, expired, failed, rejected, accepted, buffered, unknown
  "scts": "2001011400",
  "err-code": "0",
}


Outbound

{
  "message-count":"1",
  "messages":[{
    "to":"4525212002",
    "message-id":"0D0000008FC49EC0",
    "status":"0",
    "remaining-balance":"6.42100000",
    "message-price":"0.02210000",
    "network":"23802"}]}

*/

DROP TABLE IF EXISTS `tivoli2018_smsgw`;
CREATE TABLE `tivoli2018_smsgw` (
  `id`  BIGINT(6) NOT NULL AUTO_INCREMENT,
  `msisdn` varchar(16) NOT NULL,
  `to` varchar(16) NOT NULL,
  `direction` ENUM('in','out'),
  `text` TEXT,
  `type` ENUM('text','unicode','binary') DEFAULT 'text',
  `keyword` varchar(20),
  `messageId` varchar(16),
  `message-timestamp` varchar(20) NOT NULL,
  `timestamp` varchar(16),
  `concat` ENUM('true','false') DEFAULT 'false',
  `concat-ref` varchar(6),
  `concat-total` varchar(6),
  `price` VARCHAR(10) DEFAULT 0,
  `remaining-balance` VARCHAR(10),
  `status` VARCHAR(12) DEFAULT 'notProcessed',
  `scts` varchar(16),
  `err-code` varchar(16),
  `data` VARCHAR(180) DEFAULT NULL,
  `udh` VARCHAR(180) DEFAULT NULL,  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,

  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `tivoli2018_smsgw`
  ADD KEY `msisdn_key` (`msisdn`),
  ADD KEY `to_key` (`to`),
  ADD KEY `message-timestamp_key` (`message-timestamp`),
  ADD KEY `created_at_key` (`created_at`);

SELECT * FROM tivoli2018_smsgw
#WHERE tivoli2018_smsgw.concat not like 'Glglqhl';

SELECT * FROM tivoli2018_smsgw where status in ('notProcessed') AND direction = 'in' limit 10;

UPDATE tivoli2018_smsgw set haugemedia_net_db2.tivoli2018_smsgw.status = 'notProcessed';