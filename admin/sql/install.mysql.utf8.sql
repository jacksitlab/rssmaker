DROP TABLE IF EXISTS `#__rssmaker_feeds`;
 
CREATE TABLE IF NOT EXISTS `#__rssmaker_feeds` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT '',
  `link` varchar(512) DEFAULT '',
  `desc` text,
  `cat_id` int(11) DEFAULT '0',
  `nums` int DEFAULT 5,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8  AUTO_INCREMENT=0;
