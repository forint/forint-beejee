CREATE TABLE IF NOT EXISTS `task` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `description` TEXT,
  `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `published` TIMESTAMP DEFAULT 0,
  `status` BOOLEAN NOT NULL DEFAULT FALSE,
  `admin_edited` BOOLEAN NOT NULL DEFAULT FALSE,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;