

DROP TABLE IF EXISTS `acos`;
CREATE TABLE `acos` (
  `id` int(10) NOT NULL auto_increment,
  `parent_id` int(10) default NULL,
  `model` varchar(255) default NULL,
  `foreign_key` int(10) default NULL,
  `alias` varchar(255) default NULL,
  `lft` int(10) default NULL,
  `rght` int(10) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=267 ;


DROP TABLE IF EXISTS `aros`;
CREATE TABLE `aros` (
  `id` int(10) NOT NULL auto_increment,
  `parent_id` int(10) default NULL,
  `model` varchar(255) default NULL,
  `foreign_key` int(10) default NULL,
  `alias` varchar(255) default NULL,
  `lft` int(10) default NULL,
  `rght` int(10) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;


DROP TABLE IF EXISTS `aros_acos`;
CREATE TABLE `aros_acos` (
  `id` int(10) NOT NULL auto_increment,
  `aro_id` int(10) NOT NULL default '0',
  `aco_id` int(10) NOT NULL default '0',
  `_create` char(2) NOT NULL default '0',
  `_read` char(2) NOT NULL default '0',
  `_update` char(2) NOT NULL default '0',
  `_delete` char(2) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `ARO_ACO_KEY` (`aro_id`,`aco_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=41 ;


DROP TABLE IF EXISTS `core_blocks`;
CREATE TABLE `core_blocks` (
  `id` int(8) unsigned NOT NULL auto_increment,
  `content_area` varchar(64) default NULL,
  `content` text,
  `core_page_id` int(8) default NULL,
  `core_block_type_id` int(8) default NULL,
  `revision_count` int(8) default '0',
  `created` datetime default NULL,
  `modified` datetime default NULL,
  `deleted` int(1) default '0',
  `deleted_date` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;



DROP TABLE IF EXISTS `core_blocks_revs`;
CREATE TABLE `core_blocks_revs` (
  `id` int(8) unsigned NOT NULL,
  `version_id` int(8) unsigned NOT NULL auto_increment,
  `version_created` datetime default NULL,
  `title` varchar(255) default NULL,
  `slug` varchar(255) default NULL,
  `content_area` varchar(64) default NULL,
  `content` text,
  `core_page_id` int(8) default NULL,
  `core_block_type_id` int(8) default NULL,
  `created` datetime default NULL,
  `modified` datetime default NULL,
  `deleted` int(1) default '0',
  `deleted_date` datetime default NULL,
  PRIMARY KEY  (`version_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=26 ;



DROP TABLE IF EXISTS `core_pages`;
CREATE TABLE `core_pages` (
  `id` int(8) unsigned NOT NULL auto_increment,
  `title` varchar(255) default NULL,
  `title_long` varchar(512) default NULL,
  `slug` varchar(255) default NULL,
  `view_file` varchar(255) default NULL,
  `layout_file` varchar(255) default NULL,
  `publish_time` datetime default NULL,
  `unpublish_time` datetime default NULL,
  `meta_description` varchar(255) default NULL,
  `meta_keywords` varchar(255) default NULL,
  `parent_id` int(10) default NULL,
  `lft` int(10) default NULL,
  `rght` int(10) default NULL,
  `is_protected` int(1) default '0',
  `show_in_menu` int(1) default '0',
  `created` datetime default NULL,
  `modified` datetime default NULL,
  `is_published` int(1) default '0',
  `deleted` int(1) default '0',
  `deleted_date` datetime default NULL,
  `redirect_to_first_child` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;


DROP TABLE IF EXISTS `core_custom_fields`;
CREATE TABLE `core_custom_fields` (
  `id` int(8) NOT NULL auto_increment,
  `name` varchar(255) default NULL,
  `value` varchar(255) default NULL,
  `core_page_id` int(8) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;


DROP TABLE IF EXISTS `core_block_types`;
CREATE TABLE `core_block_types` (
  `id` int(8) unsigned NOT NULL auto_increment,
  `title` varchar(255) default NULL,
  `slug` varchar(255) default NULL,
  `description` text,
  `created` datetime default NULL,
  `modified` datetime default NULL,
  `deleted` int(1) default '0',
  `deleted_date` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;


DROP TABLE IF EXISTS `core_search_index`;
CREATE TABLE `core_search_index` (
  `id` int(8) NOT NULL auto_increment,
  `model` varchar(255) default NULL,
  `foreign_key` int(8) default NULL,
  `data` longtext character set utf8 collate utf8_unicode_ci NOT NULL,
  `created` datetime default NULL,
  `modified` datetime default NULL,
  `deleted` int(1) default '0',
  `deleted_date` datetime default NULL,
  PRIMARY KEY  (`id`),
  KEY `association_key` (`foreign_key`,`model`),
  FULLTEXT KEY `data` (`data`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `tickets`;
CREATE TABLE `tickets` (
  `id` varchar(36) NOT NULL,
  `hash` varchar(255) default NULL,
  `data` varchar(255) default NULL,
  `created` datetime default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `hashs` (`hash`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
