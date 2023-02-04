
1. categories
CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'To Identify User',
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `parent` int(11) NOT NULL,
  `ordering` int(11) DEFAULT NULL,
  `visibility` tinyint(4) NOT NULL DEFAULT '0',
  `allow_comment` tinyint(4) NOT NULL DEFAULT '0',
  `allow_ads` tinyint(4) NOT NULL DEFAULT '0',
   PRIMARY KEY (`id`),
    UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci


2. items
CREATE TABLE `items` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'To Identify items',
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` varchar(255) NOT NULL,  
  `country_made` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,  
  `rating` smallint(6) NOT NULL, 
  `likes` int(11) DEFAULT NULL,
  `cat_id` int(11) NOT NULL, 
  `user_id` int(11) NOT NULL,
  `tags` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL,
  `approve` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` date NOT NULL,
  PRIMARY KEY (`id`)  
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci


  ALTER TABLE `items`
  ADD CONSTRAINT `cat_1` FOREIGN KEY (`cat_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;


3. comments
CREATE TABLE `comments` (
  `c_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'To Identify comments',
  `comment` text NOT NULL,
  `status` tinyint(4) NOT NULL,
  `comment_date` date NOT NULL,
  `item_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`c_id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci

  ALTER TABLE `comments`
  ADD CONSTRAINT `comment_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `items_comment` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;





2. vendors
CREATE TABLE `vendors` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'To Identify vendors',
  `name` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `about` varchar(255) NOT NULL,  
  `type` varchar(255) NOT NULL,
  `office` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `phone2` text NOT NULL,
  `logo` text NOT NULL,  
  `user_id` tinyint(4) DEFAULT NULL,
  `created_at` date NOT NULL,
  `updated_at` date DEFAULT NULL,
 PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  ADD INDEX (`city`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci