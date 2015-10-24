<?php
/*******************************************************************************
*File Name: api_call.php
*Description: Calls the Strava API, then inserts and queries the DB returning *the segment KOM.
*Author: Circle Squared Data Labs
*Author URI: https://www.csq2.com
*******************************************************************************/
require '../hm_conn.php';

    $seg_id =basename(__FILE__);
    settype($seg_id, 'integer');
    $table = basename(__FILE__, '.php');
    $name = ('../api_responses/' . $table . '.json');
    $fp = fopen( $name, 'w' );
    $curl=curl_init();
    $url='https://www.strava.com/api/v3/segments/'. $seg_id . '/leaderboard?access_token=e7d0e2ade2f9acf308a3140c4d5ffeb37fb313bd';
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_FILE, $fp);
    curl_exec($curl);
    curl_close($curl);
    $response=file_get_contents($name);
    $results=json_decode($response, true);

    foreach($results[entries] as $k=>$v) {
        json_decode($v,true);
        echo PHP_EOL;
        $insert=$conn->prepare("INSERT INTO `strava_seg_leader`
          (athlete_name,
          athlete_id,
          athlete_gender,
          average_hr,
          average_watts,
          distance,
          elapsed_time,
          moving_time,
          start_date,
          start_date_local,
          activity_id,
          effort_id,
          rank,
          neighborhood_index,
          athlete_profile,
          segment_id)
          VALUES
          (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");

          $insert->bind_param("sisdddssssiiiisi",
          $athlete_name,
          $athlete_id,
          $athlete_gender,
          $average_hr,
          $average_watts,
          $distance,
          $elapsed_time,
          $moving_time,
          $start_date,
          $start_date_local,
          $activity_id,
          $effort_id,
          $rank,
          $neighborhood_index,
          $athlete_profile,
          $segment_id);

          $athlete_name=          mysqli_real_escape_string($conn, $v['athlete_name']);
          $athlete_id=            mysqli_real_escape_string($conn, $v['athlete_id']);
          $athlete_gender=        mysqli_real_escape_string($conn, $v['athlete_gender']);
          $average_hr=            mysqli_real_escape_string($conn, $v['average_hr']);
          $average_watts=         mysqli_real_escape_string($conn, $v['average_watts']);
          $distance=              mysqli_real_escape_string($conn, $v['distance']);
          $elapsed_time=          mysqli_real_escape_string($conn, $v['elapsed_time']);
          $moving_time=           mysqli_real_escape_string($conn, $v['moving_time']);
          $start_date=            mysqli_real_escape_string($conn, $v['start_date']);
          $start_date_local=      mysqli_real_escape_string($conn, $v['start_date_local']);
          $activity_id=           mysqli_real_escape_string($conn, $v['activity_id']);
          $effort_id=             mysqli_real_escape_string($conn, $v['effort_id']);
          $rank=                  mysqli_real_escape_string($conn, $v['rank']);
          $neighborhood_index=    mysqli_real_escape_string($conn, $v['neighborhood_index']);
          $athlete_profile=       mysqli_real_escape_string($conn, $v['athlete_profile']);
          $segment_id=            mysqli_real_escape_string($conn, $seg_id);
          $insert->execute();
        }
    $insert->close();

    $select = "SELECT DISTINCT athlete_name, average_watts, athlete_profile, seg_name, start_lat, start_lng, (strava_segments.distance/moving_time) AS ratio, strava_seg_leader.distance, moving_time, start_date, rank FROM strava_segments INNER JOIN strava_seg_leader ON strava_seg_leader.segment_id = strava_segments.seg_id WHERE strava_segments.seg_id=$seg_id AND insert_date=curdate() ORDER BY ratio DESC LIMIT 1";

    $result = $conn->query($select);
    $json_results=array();
    $c=0;
    while($obj = $result->fetch_object()) {
    $json_results[$c]=$obj;
    ++$c;
        $seg_name=$obj->seg_name;
        $athlete_profile=$obj->athlete_profile;
        $athlete_name=$obj->athlete_name;
        $average_watts=$obj->average_watts;
        $average_hr=$obj->average_hr;
        $distance=$obj->distance;
        $moving_time = $obj->moving_time;
        $start_date_local=$obj->start_date_local;
        $today=date('l, F jS');
        $date=date_create($start_date_local);
        $date2=date_create($today);
        $interval=date_diff($date,$date2);
        $formatted_date=date_format($date, 'F nS, Y');
        $rank=$obj->rank;
        $fp2 = fopen('../api_responses/leaders/' . $seg_id . '.json', 'w');
        fwrite($fp2, json_encode($json_results));
        fclose($fp2);
        print_r($json_results);
    }

    $result->close();
    $conn->close();
?>
