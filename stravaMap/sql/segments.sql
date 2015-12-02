/**
* Create Table code for the Strava App segments table.
*/


CREATE TABLE `segments` (
  `id` int(15) unsigned NOT NULL AUTO_INCREMENT,
  `segment_id` int(15) unsigned NOT NULL,
  `resource_state` int(1) unsigned NOT NULL,
  `segment_name` varchar(250) NOT NULL DEFAULT '',
  `activity_type` char(15) NOT NULL DEFAULT '',
  `distance` decimal(10,2) unsigned NOT NULL,
  `average_grade` decimal(3,1) NOT NULL,
  `maximum_grade` decimal(3,1) NOT NULL,
  `elevation_high` decimal(6,1) NOT NULL,
  `elevation_low` decimal(6,1) NOT NULL,
  `start_latitude` float(10,6) NOT NULL,
  `start_longitude` float(10,6) NOT NULL,
  `end_latitude` float(10,6) NOT NULL,
  `end_longitude` float(10,6) NOT NULL,
  `climb_category` int(1) unsigned NOT NULL,
  `city` char(80) NOT NULL DEFAULT '',
  `state` char(2) NOT NULL DEFAULT '',
  `private` tinyint(1) NOT NULL,
  `hazardous` tinyint(1) NOT NULL,
  `starred` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` varchar(150) NOT NULL DEFAULT '',
  `total_elevation_gain` decimal(6,1) NOT NULL,
  `map_id` varchar(15) NOT NULL DEFAULT '',
  `map_polyline` varchar(2500) NOT NULL DEFAULT '',
  `map_resource_state` int(1) unsigned NOT NULL,
  `effort_count` int(6) unsigned NOT NULL,
  `athlete_count` int(6) unsigned NOT NULL,
  `star_count` int(1) unsigned NOT NULL,
  `athlete_segment_stats_effort_count` int(6) unsigned NOT NULL,
  `athlete_segment_stats_pr_elapsed_time` int(6) unsigned NOT NULL,
  `athlete_segment_stats_pr_date` varchar(15) NOT NULL DEFAULT '',
  `insert_date` date NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `segment_id` (`segment_id`,`insert_date`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;
