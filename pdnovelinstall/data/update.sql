DROP TABLE IF EXISTS pre_pdnovel_image;
CREATE TABLE IF NOT EXISTS pre_pdnovel_image (
  id int(11) unsigned NOT NULL AUTO_INCREMENT,
  novelid int(11) unsigned NOT NULL default '0',
  chapterid int(10) unsigned NOT NULL default '0',
  fromurl char(100) NOT NULL default '', 
  path char(100) NOT NULL default '',
  PRIMARY KEY (id),
  KEY novelid (novelid)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;