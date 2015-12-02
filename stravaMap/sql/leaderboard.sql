/**
* Create table code for the Strava App leaderboard table.
*/

CREATE TABLE `leaderboard` (
  `id` int(15) unsigned NOT NULL AUTO_INCREMENT,
  `athlete_name` varchar(150) NOT NULL,
  `athlete_id` int(15) unsigned NOT NULL,
  `athlete_gender` char(1) NOT NULL,
  `average_hr` decimal(4,1) unsigned NOT NULL,
  `average_watts` decimal(4,1) unsigned NOT NULL,
  `distance` decimal(8,1) unsigned NOT NULL,
  `elapsed_time` int(10) unsigned NOT NULL,
  `moving_time` int(10) unsigned NOT NULL,
  `start_date` datetime NOT NULL,
  `start_date_local` datetime NOT NULL,
  `activity_id` int(15) unsigned NOT NULL,
  `effort_id` int(15) unsigned NOT NULL,
  `rank` int(5) unsigned NOT NULL,
  `neighborhood_index` tinyint(1) unsigned NOT NULL,
  `athlete_profile` varchar(500) NOT NULL DEFAULT '',
  `segment_id` int(15) unsigned NOT NULL,
  `insert_date` date NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `segment_id` (`segment_id`,`athlete_name`,`moving_time`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;
