CREATE DATABASE hotspot;

CREATE USER 'mtech'@'localhost' IDENTIFIED BY 'Mmxsp65#';
GRANT ALL PRIVILEGES ON 'hotspot'.* TO 'mtech'@'localhost' WITH GRANT OPTION;

DROP TABLE IF EXISTS `customers`;
CREATE TABLE `customers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `phone` varchar(16) NOT NULL,
  `device` int DEFAULT '0',
  `mpesa_code` varchar(32) NOT NULL,
  `amount` int NOT NULL,
  `days` varchar(8) NOT NULL,
  `expiry_time` datetime DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mpesa_code` (`mpesa_code`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

