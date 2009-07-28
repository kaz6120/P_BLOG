CREATE TABLE `p_rss_box` (
  `r_id` int(11) NOT NULL auto_increment,
  `r_name` varchar(100) NOT NULL default '',
  `r_uri` varchar(255) NOT NULL default '',
  `r_category` varchar(50) NOT NULL default '',
  `r_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `r_mod` datetime default '0000-00-00 00:00:00',
  PRIMARY KEY  (`r_id`)
) TYPE = MYISAM