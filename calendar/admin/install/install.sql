-- --------------------------------------------------------

--
-- Table structure for table `#__calendar_actionbuttons`
--

CREATE TABLE IF NOT EXISTS `#__calendar_actionbuttons` (
  `actionbutton_id` int(11) NOT NULL AUTO_INCREMENT,
  `actionbutton_name` varchar(255) NOT NULL COMMENT 'Default string to appear in button',
  `actionbutton_url_default` varchar(255) NOT NULL COMMENT 'Default url used by button',
  `actionbutton_params` text,
  PRIMARY KEY (`actionbutton_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__calendar_calendars`
--

CREATE TABLE IF NOT EXISTS `#__calendar_calendars` (
  `calendar_id` int(11) NOT NULL AUTO_INCREMENT,
  `calendar_name` varchar(255) NOT NULL,
  `calendar_alias` varchar(255) NOT NULL,
  `calendar_created_date` datetime NOT NULL COMMENT 'GMT',
  `calendar_modified_date` datetime NOT NULL COMMENT 'GMT',
  `calendar_filter_date_from` date NOT NULL,
  `calendar_filter_date_to` date NOT NULL,
  `calendar_filter_primary_categories` varchar(255) NOT NULL,
  `calendar_filter_secondary_categories` varchar(255) NOT NULL,
  `calendar_filter_types` varchar(255) NOT NULL,
  `calendar_tabbed_types` varchar(255) NOT NULL,
  `calendar_default_view` varchar(255) NOT NULL,
  `calendar_show_day` tinyint(1) NOT NULL,
  `calendar_show_three` tinyint(1) NOT NULL,
  `calendar_show_week` tinyint(1) NOT NULL,
  `calendar_show_month` tinyint(1) NOT NULL,
  `calendar_show_view_navigation` tinyint(1) NOT NULL,
  `calendar_show_mini_calendar` tinyint(1) NOT NULL,
  `calendar_show_categories_module` tinyint(1) NOT NULL,
  `calendar_show_list_view` tinyint(1) NOT NULL,
  `calendar_show_upcoming_events` tinyint(1) NOT NULL,
  `calendar_params` text NOT NULL,
  `calendar_layout` varchar(128) NOT NULL DEFAULT '',
  `default_date` date NOT NULL,
  `item_id` int(11) NOT NULL,
  `display_facebook_like` tinyint(1) NOT NULL,
  `display_tweet` tinyint(1) NOT NULL,
  `non_working_days` text NOT NULL,
  `non_working_day_text` text NOT NULL,
  `working_day_text` text NOT NULL,
  `working_day_link_text` text NOT NULL,
  `working_day_link` text NOT NULL,
  PRIMARY KEY (`calendar_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__calendar_categories`
--

CREATE TABLE IF NOT EXISTS `#__calendar_categories` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(128) NOT NULL DEFAULT '',
  `category_alias` varchar(255) NOT NULL DEFAULT '',
  `category_description` text,
  `category_thumb_image` varchar(255) DEFAULT NULL,
  `category_full_image` varchar(255) DEFAULT NULL,
  `created_date` datetime NOT NULL COMMENT 'GMT',
  `modified_date` datetime NOT NULL COMMENT 'GMT',
  `lft` int(11) NOT NULL,
  `rgt` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `ordering` int(11) NOT NULL,
  `category_enabled` tinyint(1) NOT NULL,
  `isroot` tinyint(1) NOT NULL,
  `category_params` text,
  `category_layout` varchar(255) DEFAULT '' COMMENT 'The layout file for this category',
  `category_class` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`category_id`),
  KEY `idx_category_name` (`category_name`),
  KEY `parent_id` (`parent_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__calendar_config`
--

CREATE TABLE IF NOT EXISTS `#__calendar_config` (
  `config_id` int(11) NOT NULL AUTO_INCREMENT,
  `config_name` varchar(255) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`config_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__calendar_dailyevents`
--

CREATE TABLE IF NOT EXISTS `#__calendar_dailyevents` (
  `dailyevent_id` int(11) NOT NULL AUTO_INCREMENT,
  `dailyevent_name` varchar(255) NOT NULL,
  `dailyevent_alias` varchar(255) NOT NULL,
  `dailyevent_created_date` datetime NOT NULL COMMENT 'GMT',
  `dailyevent_modified_date` datetime NOT NULL COMMENT 'GMT',
  `dailyevent_short_title` varchar(255) NOT NULL,
  `dailyevent_long_title` varchar(255) NOT NULL,
  `dailyevent_published` tinyint(1) NOT NULL,
  `dailyevent_date` date NOT NULL,
  `dailyevent_start_time` time NOT NULL,
  `dailyevent_end_time` time NOT NULL,
  `dailyevent_short_description` text,
  `dailyevent_long_description` text,
  `dailyevent_thumb_image` varchar(255) DEFAULT NULL,
  `dailyevent_full_image` varchar(255) DEFAULT NULL,
  `venue_id` int(11) DEFAULT NULL,
  `dailyevent_multimedia` text,
  PRIMARY KEY (`dailyevent_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__calendar_eventcategories`
--

CREATE TABLE IF NOT EXISTS `#__calendar_eventcategories` (
  `event_id` int(11) NOT NULL DEFAULT '0',
  `category_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`event_id`,`category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__calendar_eventinstances`
--

CREATE TABLE IF NOT EXISTS `#__calendar_eventinstances` (
  `eventinstance_id` int(11) NOT NULL AUTO_INCREMENT,
  `eventinstance_name` varchar(255) NOT NULL,
  `eventinstance_alias` varchar(255) NOT NULL,
  `eventinstance_published` tinyint(1) NOT NULL,
  `eventinstance_description` text,
  `eventinstance_date` date NOT NULL,
  `eventinstance_start_date` date NOT NULL,
  `eventinstance_end_date` date NOT NULL,
  `eventinstance_start_time` time NOT NULL,
  `eventinstance_thumb_image` varchar(255) DEFAULT NULL,
  `eventinstance_full_image` varchar(255) DEFAULT NULL,
  `eventinstance_created_date` datetime NOT NULL COMMENT 'GMT',
  `eventinstance_modified_date` datetime NOT NULL COMMENT 'GMT',
  `event_id` int(11) NOT NULL,
  `venue_id` int(11) DEFAULT NULL,
  `actionbutton_id` int(11) DEFAULT NULL,
  `actionbutton_url` text NOT NULL,
  `actionbutton_string` text NOT NULL,
  `eventinstance_params` text,
  `eventinstance_recurring` tinyint(1) NOT NULL,
  `recurring_id` int(11) NOT NULL,
  `eventinstance_end_time` time NOT NULL,
  PRIMARY KEY (`eventinstance_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__calendar_events`
--

CREATE TABLE IF NOT EXISTS `#__calendar_events` (
  `event_id` int(11) NOT NULL AUTO_INCREMENT,
  `event_name` varchar(255) NOT NULL,
  `event_alias` varchar(255) NOT NULL,
  `event_short_title` varchar(255) NOT NULL,
  `event_long_title` varchar(255) NOT NULL,
  `event_published` tinyint(1) NOT NULL,
  `event_short_description` text,
  `event_long_description` text,
  `event_thumb_image` varchar(255) DEFAULT NULL,
  `event_full_image` varchar(255) DEFAULT NULL,
  `event_created_date` datetime NOT NULL COMMENT 'GMT',
  `event_modified_date` datetime NOT NULL COMMENT 'GMT',
  `event_primary_category_id` int(11) DEFAULT NULL,
  `series_id` int(11) DEFAULT NULL,
  `type_id` int(11) DEFAULT '1',
  `event_multimedia` text,
  `event_display_type` varchar(255) NOT NULL,
  `event_upcoming_enabled` tinyint(1) NOT NULL,
  `digital_signage` tinyint(1) DEFAULT NULL,
  `event_image_caption` text,
  `event_offsite` tinyint(1) DEFAULT NULL, 
  PRIMARY KEY (`event_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__calendar_recurring`
--

CREATE TABLE IF NOT EXISTS `#__calendar_recurring` (
  `recurring_id` int(11) NOT NULL AUTO_INCREMENT,
  `recurring_name` varchar(255) NOT NULL,
  `recurring_alias` varchar(255) NOT NULL,
  `recurring_published` tinyint(1) NOT NULL,
  `recurring_description` text,
  `recurring_start_date` date NOT NULL,
  `recurring_end_type` varchar(255) NOT NULL,
  `recurring_end_date` date NOT NULL,
  `recurring_end_occurances` int(11) DEFAULT NULL,
  `recurring_start_time` time NOT NULL,
  `recurring_end_time` time NOT NULL,
  `recurring_created_date` datetime NOT NULL COMMENT 'GMT',
  `recurring_modified_date` datetime NOT NULL COMMENT 'GMT',
  `event_id` int(11) DEFAULT NULL,
  `venue_id` int(11) DEFAULT NULL,
  `actionbutton_id` int(11) DEFAULT NULL,
  `recurring_actionbutton_url` text NOT NULL,
  `recurring_actionbutton_string` text NOT NULL,
  `recurring_repeats` varchar(255) NOT NULL COMMENT 'daily, weekly, monthly, yearly',
  `recurring_params` text COMMENT 'Parameters for each eventinstance',
  `params` text COMMENT 'Parameters for the recurring item, such as which days of week to recur',
  `daily_repeats_every` int(11) DEFAULT NULL,
  `weekly_repeats_every` int(11) DEFAULT NULL,
  `monthly_repeats_every` int(11) DEFAULT NULL,
  `yearly_repeats_every` int(11) DEFAULT NULL,
  `weekly_repeats_on` varchar(255) NOT NULL COMMENT 'Pipe-separated value like MON|TUE|WED|THUR|FRI|SAT|SUN',
  `recurring_finishes` tinyint(1) NOT NULL COMMENT 'Does this recurring event ever finish or is it neverending?',
  `recurring_finishes_date` date NOT NULL COMMENT 'The date when this recurring event will be finished',
  `recurring_current_date` date NOT NULL COMMENT 'The current, maximum eventinstance date for this recurring event.  When this is < recurring_finishes_date, then more event instances need to be created for this recurring event on-demand.',
  `recurring_instances` int(11) NOT NULL COMMENT 'The number of instances of this recurring event that have been created',
  PRIMARY KEY (`recurring_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__calendar_secondcategories`
--

CREATE TABLE IF NOT EXISTS `#__calendar_secondcategories` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(128) NOT NULL DEFAULT '',
  `category_alias` varchar(255) NOT NULL DEFAULT '',
  `category_description` text,
  `category_thumb_image` varchar(255) DEFAULT NULL,
  `category_full_image` varchar(255) DEFAULT NULL,
  `created_date` datetime NOT NULL COMMENT 'GMT',
  `modified_date` datetime NOT NULL COMMENT 'GMT',
  `lft` int(11) NOT NULL,
  `rgt` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `ordering` int(11) NOT NULL,
  `category_enabled` tinyint(1) NOT NULL,
  `isroot` tinyint(1) NOT NULL,
  `category_params` text,
  `category_layout` varchar(255) DEFAULT '' COMMENT 'The layout file for this category',
  PRIMARY KEY (`category_id`),
  KEY `idx_category_name` (`category_name`),
  KEY `parent_id` (`parent_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__calendar_series`
--

CREATE TABLE IF NOT EXISTS `#__calendar_series` (
  `series_id` int(11) NOT NULL AUTO_INCREMENT,
  `series_name` varchar(255) NOT NULL,
  `series_title` varchar(255) NOT NULL,
  `series_description` text,
  `series_created_date` datetime NOT NULL COMMENT 'GMT',
  `series_modified_date` datetime NOT NULL COMMENT 'GMT',
  `series_thumb_image` varchar(255) DEFAULT NULL,
  `series_full_image` varchar(255) DEFAULT NULL,
  `series_tab_label` varchar(255) DEFAULT NULL,
  `series_associated_article_id` int(11) DEFAULT NULL,
  `series_multimedia` text,
  `series_image_caption` text,
  `series_primary_category_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`series_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table structure for table `#__calendar_types`
--

CREATE TABLE IF NOT EXISTS `#__calendar_types` (
  `type_id` int(11) NOT NULL AUTO_INCREMENT,
  `type_name` varchar(255) NOT NULL,
  `type_params` text,
  PRIMARY KEY (`type_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Dumping data for table `#__calendar_types`
--

INSERT IGNORE INTO `#__calendar_types` (`type_id`, `type_name`, `type_params`) VALUES
(1, 'Events', NULL);


-- --------------------------------------------------------

--
-- Table structure for table `#__calendar_venues`
--

CREATE TABLE IF NOT EXISTS `#__calendar_venues` (
  `venue_id` int(11) NOT NULL AUTO_INCREMENT,
  `venue_name` varchar(255) NOT NULL,
  `venue_description` text,
  `venue_created_date` datetime NOT NULL COMMENT 'GMT',
  `venue_modified_date` datetime NOT NULL COMMENT 'GMT',
  `venue_url` varchar(255) NOT NULL,
  PRIMARY KEY (`venue_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
