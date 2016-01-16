<?php
/**
* @package    Heatery
* @version    1.0.0 [November 30, 2015]
* @author     Will Conkwright
* @copyright  Copyright (c) 2016 Circle Squared Data Labs
* @license    Licensed MIT
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

require( 'header.php' );
require( 'modal.php' );
require( 'navbar.php' );

$title              = 'Heatery';
$address            = $_POST['address'];
$db                 = connect();
$geo_var            = geocode($address);
$latitude           = $geo_var[0];
$longitude          = $geo_var[1];
$city               = $geo_var[2];
$api_results        = get_api_results( $latitude, $longitude );
$insert_api_results = insert_api_results( $db, $api_results );
$pop_var            = populate_variables( $db, $latitude, $longitude );

function page_title ( $title ) {
  if( isset( $title ) && is_string( $title ) ){
      $page_title = print_r( $title );
  } else {
      echo 'Find Your Hot Spot.';
    }
  return $page_title;
}

function get_date_time() {
  date_default_timezone_set('America/New_York');
  $date_time = date( 'l F jS Y h:i A' );
  echo 'Enter a city name in the "Your Hot Spot" search box and click "Find".<br>All data is current as of ' . $date_time . '.';
}

function connect () {
  require ( '/Users/admin/Documents/credentials/connect.php' );
    $db     = new mysqli($servername, $username, $password, $dbname);
  return $db;
}

function geocode( $address ){
  $data_array = array();
  $address    = urlencode( $address );
  $url        = "https://maps.google.com/maps/api/geocode/json?sensor=false&address={$address}";
  $response   = file_get_contents( $url );
  $results    = json_decode($response, true);
  $lat        =  $results['results'][0]['geometry']['location']['lat'];
  $long       =  $results['results'][0]['geometry']['location']['lng'];
  $geo_city   =  $results['results'][0]['address_components'][0]['long_name'];
    array_push( $data_array, $lat, $long, $geo_city );
      return $data_array;
}

function get_api_results( $latitude, $longitude ){
  $url = 'https://graph.facebook.com/v2.5/search?q=restaurant&type=place&distance=3200&center='.$latitude.','.$longitude.'&fields=location,name,likes,talking_about_count,were_here_count,description,website,cover,about,culinary_team&limit=250&access_token=1452021355091002|x-ZB0iKqWQmYqnJQ-wXoUjl-XtY';
  $table      = basename( __FILE__ , '.php' );
  $name       = ( './data/' . $table . '.json' );
  $file       = fopen( $name, 'w' );
  $ch         = curl_init();
  curl_setopt( $ch, CURLOPT_URL, $url );
  curl_setopt( $ch, CURLOPT_FILE, $file );
  curl_exec( $ch );
  curl_close( $ch );
  $response = file_get_contents( $name );
  $results  = json_decode( $response, true );
    return $results;
}

function insert_api_results( $db, $api_results ) {
  $sql = ("INSERT INTO top10_markers(
    FID,
    fb_web,
    fb_cover,
    fb_about,
    fb_culinary_team,
    fb_description,
    fb_name,
    fb_likes,
    fb_were_here,
    fb_talking_about,
    fb_street,
    fb_city,
    fb_state,
    fb_zip,
    fb_lat,
    fb_lng)
    VALUES
    (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
    $stmt1 = $db->prepare( $sql );
    $stmt1->bind_param("dssssssiiisssidd",
      $FID,
      $fb_web,
      $fb_cover,
      $fb_about,
      $fb_culinary_team,
      $fb_description,
      $fb_name,
      $fb_likes,
      $fb_were_here,
      $fb_talking_about,
      $fb_street,
      $fb_city,
      $fb_state,
      $fb_zip,
      $fb_lat,
      $fb_lng);

  foreach ( $api_results['data'] as $k=>$v) {
      $FID              = mysqli_real_escape_string($db, $v['id']);
      $fb_web           = mysqli_real_escape_string($db, $v['website']);
      $fb_cover         = mysqli_real_escape_string($db, $v['cover']['source']);
      $fb_about         = mysqli_real_escape_string($db, $v['about']);
      $fb_culinary_team = mysqli_real_escape_string($db, $v['culinary_team']);
      $fb_description   = mysqli_real_escape_string($db, $v['description']);
      $fb_name          = mysqli_real_escape_string($db, $v['name']);
      $fb_likes         = mysqli_real_escape_string($db, $v['likes']);
      $fb_were_here     = mysqli_real_escape_string($db, $v['were_here_count']);
      $fb_talking_about = mysqli_real_escape_string($db, $v['talking_about_count']);
      $fb_street        = mysqli_real_escape_string($db, $v['location']['street']);
      $fb_city          = mysqli_real_escape_string($db, $v['location']['city']);
      $fb_state         = mysqli_real_escape_string($db, $v['location']['state']);
      $fb_zip           = mysqli_real_escape_string($db, $v['location']['zip']);
      $fb_lat           = mysqli_real_escape_string($db, $v['location']['latitude']);
      $fb_lng           = mysqli_real_escape_string($db, $v['location']['longitude']);
    $stmt1->execute();
  }
  $stmt1->close();
}

function select_api_results( $latitude, $longitude ) {
  $name                 = ( './data/results.json' );
  $fp                   = fopen( $name, 'w' );
  $stmt = "SELECT
  fb_web,
  fb_cover,
  fb_about,
  fb_culinary_team,
  fb_description,
  fb_name,
  fb_date,
  fb_lat,
  fb_lng,
  fb_city,
  fb_state,
  fb_street,
  fb_zip,
  fb_talking_about,
  fb_were_here,
  fb_likes,
  TRUNCATE((fb_talking_about * 0.75) + (((fb_were_here + fb_likes)/2) * 0.25), 0) AS heatery_score,
  TRUNCATE(fb_lat,3) AS fb_lat,
  TRUNCATE (fb_lng,3) AS fb_lng,
  TRUNCATE((SQRT(POW(69.1 * (fb_lat - $latitude),2) + POW(69.1 *( $longitude - fb_lng) * COS(fb_lat/57.3), 2)) * 0.621371),2)
  AS fb_distance
  FROM top10_markers
  WHERE fb_date = curdate()
  AND fb_name != 'The Trawl Door'
  AND fb_name != 'Oriental, North Carolina'
  AND fb_name != 'Toucan Grill & Fresh Bar'
  AND fb_name != 'Oriental Smith Creek'
  AND fb_name != 'Healthy Habits'
  AND fb_name != 'Kamp Kress'
  AND fb_name != 'Oriental Bridge'

  HAVING fb_distance < 2
  ORDER BY heatery_score DESC LIMIT 10";
    return $stmt;


}

function populate_variables( $db, $latitude, $longitude ) {
        $select_api_results   = select_api_results( $latitude, $longitude );
        if ( $result          = $db->query( $select_api_results ) ) {
            $json_results     = array();
            $c                = 0;
 while ( $obj                 = $result->fetch_object() ) {
        $json_results[$c]     = $obj;
        $fb_name[$c]          = $obj->fb_name;
        $fb_lat[$c]           = $obj->fb_lat;
        $fb_lng[$c]           = $obj->fb_lng;
        $fb_street[$c]        = $obj->fb_street;
        $fb_city[$c]          = $obj->fb_city;
        $fb_state[$c]         = $obj->fb_state;
        $fb_zip[$c]           = $obj->fb_zip;
        $fb_talking_about[$c] = ( number_format( $obj->fb_talking_about, 0, null, ',' ) );
        $fb_likes[$c]         = ( number_format( $obj->fb_likes, 0, null, ',') );
        $fb_were_here[$c]     = ( number_format( $obj->fb_were_here, 0, null, ',') );
        $fb_date[$c]          = $obj->fb_date;
        $fb_description[$c]   = $obj->fb_description;
        $fb_web[$c]           = $obj->fb_web;
        $fb_web_parse[$c]     = ( parse_url( $obj->fb_web, PHP_URL_HOST ) );
        $fb_cover[$c]         = $obj->fb_cover;
        $fb_about[$c]         = $obj->fb_about;
        $fb_culinary_team[$c] = $obj->fb_culinary_team;
        $heatery_score[$c]    = $obj->heatery_score;
        $name                 = ( './data/results.json' );
        $fp                   = fopen( $name, 'w' );
        fwrite( $fp, json_encode( $json_results ) );
        fclose( $fp );
        ++$c;
      }
      $result->close();
  }
  $db->close();
}

?>
