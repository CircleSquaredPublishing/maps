<?php
/**
* CSQ2 Strava App
* @version 2.0.0 [December 6, 2015]
* @author Will Conkwright
* @copyright Copyright (c) 2015 Circle Squared Data Labs
* @license Licensed MIT
*
* Permission is hereby granted, free of charge, to any person obtaining a copy
* of this software and associated documentation files (the "Software"), to deal
* in the Software without restriction, including without limitation the rights
* to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
* copies of the Software, and to permit persons to whom the Software is
* furnished to do so, subject to the following conditions:
*
* The above copyright notice and this permission notice shall be included in
* all copies or substantial portions of the Software.
*
* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
* IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
* FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.  IN NO EVENT SHALL THE
* AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
* LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
* OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
* THE SOFTWARE.
*/


/**
* @global integer|array $seg_id
* @uses main.js
* The handlerData property of the GOOGLEMAP object calls a $.getJSON() function that loops through
* an array of Strava segment IDs. Those IDs are POST(ed) here.
*/

$seg_id = $_POST['key'];
$db = connect();
/**
* @var object $db Variable to store the database connection object.
*/

insert_segment($db, $seg_id);
insert_leaderboard($db, $seg_id);
select_results($db, $seg_id);

/**
* @method object connect() Creates our database connection object.
* @param  string $host     Reference to the HOST constant that contains server host name.
* @param  string $dbname   Reference to the STRAVA_DB constant that contains database name.
* @param  string $user     Reference to the USER constant that contains user name.
* @param  string $pass     Reference to the PASSWORD constant that contains database password.
* @var    object $db       Stores the new mysqli database object created from above parameters.
* @return object           Our Database connection object.
*/

function connect(){
  require ( '/Users/admin/Documents/credentials/connect.php' );
    $host   = HOST;
    $dbname = STRAVA_DB;
    $user   = USER;
    $pass   = PASSWORD;
    $db     = new mysqli($host, $user, $pass, $dbname);

    if (mysqli_connect_errno()) {
      printf("Connect failed: %s\n", mysqli_connect_error());
      exit();
    } else {
      return $db;
    }
}

/**
* @method insert_segment( array|$db, string|$segment_request, integer|$seg_id)
* @param $db
* @param $segment_request
* @param $segment_id
*/

function insert_segment( $db, $seg_id ) {

  /**
  * @method segment_request( string|$build_seg_url, integer|$seg_id )
  * @param string   $build_seg_url
  * @param integer  $seg_id
  * @var   string   $new_seg_file     Directory location for new file.
  * @var   resource $seg_fp           fopen() function passed $new_seg_file as parameter.
  * @var   string   $curl             Stores curl_init() function.
  * @var   string   $seg_response     file_get_contents() function passed $new_seg_file as parameter.
  * @var   array    $segment_request json_decode() $seg_response string.
  * @return string|array              Returns the decoded results of the API call.
  */

  function segment_request( $db, $seg_id ) {
    $build_seg_url  = SEGMENTS_URL . $seg_id . TOKEN;
    $new_seg_file   = ( './api_responses/' . 'segment_' .$seg_id . '.json' );
    $seg_fp         = fopen( $new_seg_file, 'w' );
    $curl           = curl_init();
      curl_setopt ( $curl, CURLOPT_URL, $build_seg_url );
      curl_setopt ( $curl, CURLOPT_FILE, $seg_fp );
      curl_exec   ( $curl );
      curl_close  ( $curl );
    $seg_response     = file_get_contents( $new_seg_file );
    $segment_request = json_decode( $seg_response, true );
      return $segment_request;
  }
  $segment_request = segment_request( $db, $seg_id );
  $sql = ("INSERT INTO `segments`(segment_id,resource_state,segment_name,activity_type,distance,average_grade,maximum_grade,elevation_high,elevation_low,start_latitude,start_longitude,end_latitude,end_longitude,climb_category,city,state,private,hazardous,starred,created_at,updated_at,total_elevation_gain,map_id,map_polyline,map_resource_state,effort_count,athlete_count,star_count,athlete_segment_stats_effort_count,athlete_segment_stats_pr_elapsed_time,athlete_segment_stats_pr_date)VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
  $insert = $db->prepare($sql);
  $insert->bind_param("iisssddddddddissiiissdssiiiiiis",$segment_id,$resource_state,$segment_name,$activity_type,$distance,$average_grade,$maximum_grade,$elevation_high,$elevation_low,$start_latitude,$start_longitude,$end_latitude,$end_longitude,$climb_category,$city,$state,$private,$hazardous,$starred,$created_at,$updated_at,$total_elevation_gain,$map_id,$map_polyline,$map_resource_state,$effort_count,$athlete_count,$star_count,$athlete_segment_stats_effort_count,$athlete_segment_stats_pr_elapsed_time,$athlete_segment_stats_pr_date);
  $segment_id           = mysqli_real_escape_string($db, $segment_request['id']);
  $resource_state       = mysqli_real_escape_string($db, $segment_request['resource_state']);
  $segment_name         = mysqli_real_escape_string($db, $segment_request['name']);
  $activity_type        = mysqli_real_escape_string($db, $segment_request['activity_type']);
  $distance             = mysqli_real_escape_string($db, $segment_request['distance']);
  $average_grade        = mysqli_real_escape_string($db, $segment_request['average_grade']);
  $maximum_grade        = mysqli_real_escape_string($db, $segment_request['maximum_grade']);
  $elevation_high       = mysqli_real_escape_string($db, $segment_request['elevation_high']);
  $elevation_low        = mysqli_real_escape_string($db, $segment_request['elevation_low']);
  $start_latitude       = mysqli_real_escape_string($db, $segment_request['start_latitude']);
  $start_longitude      = mysqli_real_escape_string($db, $segment_request['start_longitude']);
  $end_latitude         = mysqli_real_escape_string($db, $segment_request['end_latitude']);
  $end_longitude        = mysqli_real_escape_string($db, $segment_request['end_longitude']);
  $climb_category       = mysqli_real_escape_string($db, $segment_request['climb_category']);
  $city                 = mysqli_real_escape_string($db, $segment_request['city']);
  $state                = mysqli_real_escape_string($db, $segment_request['state']);
  $private              = mysqli_real_escape_string($db, $segment_request['private']);
  $hazardous            = mysqli_real_escape_string($db, $segment_request['hazardous']);
  $starred              = mysqli_real_escape_string($db, $segment_request['starred']);
  $created_at           = mysqli_real_escape_string($db, $segment_request['created_at']);
  $updated_at           = mysqli_real_escape_string($db, $segment_request['updated_at']);
  $total_elevation_gain = mysqli_real_escape_string($db, $segment_request['total_elevation_gain']);
  $map_id               = mysqli_real_escape_string($db, $segment_request['map']['id']);
  $map_polyline         = mysqli_real_escape_string($db, $segment_request['map']['polyline']);
  $map_resource_state   = mysqli_real_escape_string($db, $segment_request['map']['resource_state']);
  $effort_count         = mysqli_real_escape_string($db, $segment_request['effort_count']);
  $athlete_count        = mysqli_real_escape_string($db, $segment_request['athlete_count']);
  $star_count           = mysqli_real_escape_string($db, $segment_request['star_count']);
  $athlete_segment_stats_effort_count     = mysqli_real_escape_string($db, $segment_request['athlete_segment_stats']['effort_count']);
  $athlete_segment_stats_pr_elapsed_time  = mysqli_real_escape_string($db, $segment_request['athlete_segment_stats']['pr_elapsed_time']);
  $athlete_segment_stats_pr_date           = mysqli_real_escape_string($db, $segment_request['athlete_segment_stats']['pr_date']);
  $insert->execute();
}

/**
* @method insert_leaderboard( array|$db, string|$leaderboard_request, integer|$seg_id)
* @param $db
* @param $leaderboard_request
* @param $seg_id
*/

function insert_leaderboard( $db, $seg_id ) {

  /**
  * @method leaderboard_request( string|$build_url, integer|$seg_id )
  * @param string   $build_url
  * @param integer  $seg_id
  */

  function leaderboard_request( $db, $seg_id ) {
      $build_url = SEGMENTS_URL . $seg_id . '/leaderboard' . TOKEN;
      $new_file     = ( './api_responses/' . 'call_' .$seg_id . '.json' );
      $fp           = fopen( $new_file, 'w' );
      $curl         = curl_init();
      curl_setopt ( $curl, CURLOPT_URL, $build_url );
      curl_setopt ( $curl, CURLOPT_FILE, $fp );
      curl_exec   ( $curl );
      curl_close  ( $curl );
      $response     = file_get_contents( $new_file );
      $leaderboard_request = json_decode( $response, true );
    return $leaderboard_request;
  }

  $sql = "INSERT INTO `leaderboard`(athlete_name,athlete_id,athlete_gender,average_hr,average_watts,distance,elapsed_time,moving_time,start_date,start_date_local,activity_id,effort_id,rank,neighborhood_index,athlete_profile,segment_id)VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

  $insert=$db->prepare( $sql );

  $insert->bind_param("sisdddssssiiiisi",$athlete_name,$athlete_id,$athlete_gender,$average_hr,$average_watts,$distance,$elapsed_time,$moving_time,$start_date,$start_date_local,$activity_id,$effort_id,$rank,$neighborhood_index,$athlete_profile,$segment_id);

  $leaderboard_request = leaderboard_request($db, $seg_id);

  foreach( $leaderboard_request['entries'] as $k => $v ) {
    $athlete_name       = mysqli_real_escape_string($db, $v['athlete_name']);
    $athlete_id         = mysqli_real_escape_string($db, $v['athlete_id']);
    $athlete_gender     = mysqli_real_escape_string($db, $v['athlete_gender']);
    $average_hr         = mysqli_real_escape_string($db, $v['average_hr']);
    $average_watts      = mysqli_real_escape_string($db, $v['average_watts']);
    $distance           = mysqli_real_escape_string($db, $v['distance']);
    $elapsed_time       = mysqli_real_escape_string($db, $v['elapsed_time']);
    $moving_time        = mysqli_real_escape_string($db, $v['moving_time']);
    $start_date         = mysqli_real_escape_string($db, $v['start_date']);
    $start_date_local   = mysqli_real_escape_string($db, $v['start_date_local']);
    $activity_id        = mysqli_real_escape_string($db, $v['activity_id']);
    $effort_id          = mysqli_real_escape_string($db, $v['effort_id']);
    $rank               = mysqli_real_escape_string($db, $v['rank']);
    $neighborhood_index = mysqli_real_escape_string($db, $v['neighborhood_index']);
    $athlete_profile    = mysqli_real_escape_string($db, $v['athlete_profile']);
    $segment_id         = $seg_id;
    $insert->execute();
    }
  $insert->close();
}

/**
* @method select_results( srray|$db, integer|$seg_id )
* @param $db
* @param $seg_id
*/

function select_results( $db, $seg_id ){
  $select = ("SELECT DISTINCT athlete_name, average_watts, athlete_profile, segment_name, start_latitude, start_longitude, (segments.distance/moving_time) AS ratio, leaderboard.distance, moving_time, start_date, rank, effort_count, athlete_count, star_count FROM segments INNER JOIN leaderboard ON leaderboard.segment_id = segments.segment_id WHERE segments.segment_id=$seg_id ORDER BY ratio DESC LIMIT 1");

  if ( $select = $db->query( $select ) ) {
    $json_results = array();
    $i = 0;
    while ( $obj        = $select->fetch_object() ) {
      $json_results[$i] = $obj;
      ++$i;
      $name2     = ( './api_responses/' . 'response_' .$seg_id . '.json' );
      $fp2       = fopen( $name2, 'w' );
      fwrite( $fp2, json_encode( $json_results ) );
      fclose( $fp2 );
    }
    $select->close();
  }
  $db->close();
}
?>
