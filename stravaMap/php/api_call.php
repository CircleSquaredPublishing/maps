<?php
/**
* CSQ2 Strava App
* @version 1.0.0 [November 30, 2015]
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

$seg_id = $_POST['key'];
settype($seg_id, 'integer');

$db                 = connect();
$build_url          = build_url( $seg_id );
$build_seg_url      = build_seg_url( $seg_id );
$send_seg_request   = send_seg_request( $build_seg_url, $seg_id );
$insert_seg_results = insert_seg_results( $db, $send_seg_request, $seg_id );
$send_request       = send_request( $build_url, $seg_id );
$insert_request     = insert_request( $db, $send_request, $seg_id );
$select_results     = select_results( $db, $send_request, $seg_id );

function connect(){
  require ( '/Users/admin/Documents/credentials/connect.php' );
    $host   = HOST;
    $dbname = STRAVA_DB;
    $user   = USER;
    $pass   = PASSWORD;
    $db     = new mysqli($host, $user, $pass, $dbname);
      return $db;
}

function build_url( $seg_id ) {
    $build_url = SEGMENTS_URL . $seg_id . '/leaderboard' . TOKEN;
  return $build_url;
}

function build_seg_url( $seg_id ) {
    $build_seg_url = SEGMENTS_URL . $seg_id . TOKEN;
      return $build_seg_url;
}

function send_seg_request( $build_seg_url, $seg_id ) {
  $new_seg_file   = ( './api_responses/' . 'segment_' .$seg_id . '.json' );
  $seg_fp         = fopen( $new_seg_file, 'w' );
  $curl           = curl_init();
    curl_setopt ( $curl, CURLOPT_URL, $build_seg_url );
    curl_setopt ( $curl, CURLOPT_FILE, $seg_fp );
    curl_exec   ( $curl );
    curl_close  ( $curl );
  $seg_response   = file_get_contents( $new_seg_file );
  $send_seg_request    = json_decode( $seg_response, true );
    return $send_seg_request;
}

function insert_seg_results( $db, $send_seg_request, $seg_id ) {
  $sql = ("INSERT INTO `segments`(segment_id,resource_state,segment_name,activity_type,distance,average_grade,maximum_grade,elevation_high,elevation_low,start_latitude,start_longitude,end_latitude,end_longitude,climb_category,city,state,private,hazardous,starred,created_at,updated_at,total_elevation_gain,map_id,map_polyline,map_resource_state,effort_count,athlete_count,star_count,athlete_segment_stats_effort_count,athlete_segment_stats_pr_elapsed_time,athlete_segment_stats_pr_date)VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");

  $insert = $db->prepare($sql);

  $insert->bind_param("iisssddddddddissiiissdssiiiiiis",$segment_id,$resource_state,$segment_name,$activity_type,$distance,$average_grade,$maximum_grade,$elevation_high,$elevation_low,$start_latitude,$start_longitude,$end_latitude,$end_longitude,$climb_category,$city,$state,$private,$hazardous,$starred,$created_at,$updated_at,$total_elevation_gain,$map_id,$map_polyline,$map_resource_state,$effort_count,$athlete_count,$star_count,$athlete_segment_stats_effort_count,$athlete_segment_stats_pr_elapsed_time,$athlete_segment_stats_pr_date);

  $segment_id           = mysqli_real_escape_string($db, $send_seg_request['id']);
  $resource_state       = mysqli_real_escape_string($db, $send_seg_request['resource_state']);
  $segment_name         = mysqli_real_escape_string($db, $send_seg_request['name']);
  $activity_type        = mysqli_real_escape_string($db, $send_seg_request['activity_type']);
  $distance             = mysqli_real_escape_string($db, $send_seg_request['distance']);
  $average_grade        = mysqli_real_escape_string($db, $send_seg_request['average_grade']);
  $maximum_grade        = mysqli_real_escape_string($db, $send_seg_request['maximum_grade']);
  $elevation_high       = mysqli_real_escape_string($db, $send_seg_request['elevation_high']);
  $elevation_low        = mysqli_real_escape_string($db, $send_seg_request['elevation_low']);
  $start_latitude       = mysqli_real_escape_string($db, $send_seg_request['start_latitude']);
  $start_longitude      = mysqli_real_escape_string($db, $send_seg_request['start_longitude']);
  $end_latitude         = mysqli_real_escape_string($db, $send_seg_request['end_latitude']);
  $end_longitude        = mysqli_real_escape_string($db, $send_seg_request['end_longitude']);
  $climb_category       = mysqli_real_escape_string($db, $send_seg_request['climb_category']);
  $city                 = mysqli_real_escape_string($db, $send_seg_request['city']);
  $state                = mysqli_real_escape_string($db, $send_seg_request['state']);
  $private              = mysqli_real_escape_string($db, $send_seg_request['private']);
  $hazardous            = mysqli_real_escape_string($db, $send_seg_request['hazardous']);
  $starred              = mysqli_real_escape_string($db, $send_seg_request['starred']);
  $created_at           = mysqli_real_escape_string($db, $send_seg_request['created_at']);
  $updated_at           = mysqli_real_escape_string($db, $send_seg_request['updated_at']);
  $total_elevation_gain = mysqli_real_escape_string($db, $send_seg_request['total_elevation_gain']);
  $map_id               = mysqli_real_escape_string($db, $send_seg_request['map']['id']);
  $map_polyline         = mysqli_real_escape_string($db, $send_seg_request['map']['polyline']);
  $map_resource_state   = mysqli_real_escape_string($db, $send_seg_request['map']['resource_state']);
  $effort_count         = mysqli_real_escape_string($db, $send_seg_request['effort_count']);
  $athlete_count        = mysqli_real_escape_string($db, $send_seg_request['athlete_count']);
  $star_count           = mysqli_real_escape_string($db, $send_seg_request['star_count']);
  $athlete_segment_stats_effort_count     = mysqli_real_escape_string($db, $send_seg_request['athlete_segment_stats']['effort_count']);
  $athlete_segment_stats_pr_elapsed_time  = mysqli_real_escape_string($db, $send_seg_request['athlete_segment_stats']['pr_elapsed_time']);
  $athlete_segment_stats_pr_date           = mysqli_real_escape_string($db, $send_seg_request['athlete_segment_stats']['pr_date']);
  $insert->execute();
}

function send_request( $build_url, $seg_id ) {
    $new_file   = ( './api_responses/' . 'call_' .$seg_id . '.json' );
    $fp         = fopen( $new_file, 'w' );
    $curl       = curl_init();
    curl_setopt ( $curl, CURLOPT_URL, $build_url );
    curl_setopt ( $curl, CURLOPT_FILE, $fp );
    curl_exec   ( $curl );
    curl_close  ( $curl );
    $response     = file_get_contents( $new_file );
    $send_request = json_decode( $response, true );
  return $send_request;
}

function insert_request( $db, $send_request, $seg_id ) {
  foreach($send_request['entries'] as $k=>$v) {
    $insert=$db->prepare("INSERT INTO `leaderboard`(athlete_name,athlete_id,athlete_gender,average_hr,average_watts,distance,elapsed_time,moving_time,start_date,start_date_local,activity_id,effort_id,rank,neighborhood_index,athlete_profile,segment_id)VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");

    $insert->bind_param("sisdddssssiiiisi",$athlete_name,$athlete_id,$athlete_gender,$average_hr,$average_watts,$distance,$elapsed_time,$moving_time,$start_date,$start_date_local,$activity_id,$effort_id,$rank,$neighborhood_index,$athlete_profile,$segment_id);

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

function select_results( $db, $send_request, $seg_id ){
  $select = ("SELECT DISTINCT athlete_name, average_watts, athlete_profile, segment_name, start_latitude, start_longitude, (segments.distance/moving_time) AS ratio, leaderboard.distance, moving_time, start_date, rank, effort_count, athlete_count, star_count FROM segments INNER JOIN leaderboard ON leaderboard.segment_id = segments.segment_id WHERE segments.segment_id=$seg_id AND leaderboard.insert_date=curdate() ORDER BY ratio DESC LIMIT 1");

  $result       = $db->query( $select );
  $json_results = array();
  $c            = 0;

  while( $obj = $result->fetch_object() ) {
    $json_results[$c] = $obj;
    ++$c;
    $seg_name         = $obj->seg_name;
    $athlete_profile  = $obj->athlete_profile;
    $athlete_name     = $obj->athlete_name;
    $average_watts    = $obj->average_watts;
    $average_hr       = $obj->average_hr;
    $distance         = $obj->distance;
    $moving_time      = $obj->moving_time;
    $start_date_local = $obj->start_date_local;
    $effort_count     = $obj->effort_count;
    $athlete_count    = $obj->athlete_count;
    $star_count       = $obj->star_count;
    $today            = date( 'l, F jS' );
    $date             = date_create( $start_date_local );
    $date2            = date_create( $today );
    $interval         = date_diff($date,$date2);
    $formatted_date   = date_format($date, 'F nS, Y');
    $rank             = $obj->rank;
    $name2            = ( './api_responses/' . 'response_' .$seg_id . '.json' );
    $fp2              = fopen( $name2, 'w' );
    fwrite( $fp2, json_encode( $json_results ) );
    fclose( $fp2 );
    var_dump( $obj );
  }
    $result->close();
  $db->close();
}
?>
