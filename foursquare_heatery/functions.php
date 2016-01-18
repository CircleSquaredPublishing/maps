<?php
/**
* @package Foursquare Heatery
* @version 0.0.1 [January 17, 2016]
* @author Will Conkwright
* @copyright Copyright (c) 2016 Circle Squared Data Labs
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
require( 'header.php' );
require( 'navbar.php' );

$address           = $_POST['address'];
$search_options    = $_POST['search_options'];
$db                = connect();
$geo_var           = geocode($address);
$latitude          = $geo_var[0];
$longitude         = $geo_var[1];
$city              = $geo_var[2];
$client_id         = "LUDUFON05OQ3US4C0FT0TEKWXKSD0NHIPVGKF0TGUZGY4YUR";
$client_secret     = "F2E3NQTQKY3WS1APVGFVA31ESHW2ONNPVNJ11NPYVBV05W2I";
$version           = '20160101';
$api_results       = get_api_results( $latitude, $longitude, $client_id, $client_secret, $version );
$insert_api_results= insert_api_results( $db, $api_results );
$pop_var           = populate_variables( $db, $latitude, $longitude );

function page_title() {
  $title = 'Foursquare API';
  if( isset( $title ) && is_string( $title ) )
  {
    print_r( $title );
  }
  else
  {
    print_r( 'Circle Squared Data Labs' );
  }
}

function get_date_time() {
  date_default_timezone_set('America/New_York');
  $date_time = date( 'l F jS Y h:i A' );
  echo 'Enter a city name in the "Your Hot Spot" search box and click "Find".<br>All data is current as of ' . $date_time . '.';
}

function connect() {
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

function get_api_results( $latitude, $longitude, $client_id, $client_secret, $version, $search_options ) {
  $url = "https://api.foursquare.com/v2/venues/explore?v=$version&ll=".$latitude.",".$longitude."&radius=50000&section=coffee&limit=50&novelty=new&client_id=$client_id&client_secret=$client_secret";
  $table      = basename( __FILE__ , '.php' );
  $name       = ( 'data/' . $table . '.json' );
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
      foreach($api_results['response']['groups'] as $v){
      foreach($v['items'] as $v2){
      foreach($v2['venue']['categories'] as $v3){
      foreach($v2['tips'] as $v4){
      $stmt= $db->prepare(
          "INSERT INTO foursquare_explore(
          `fsid`,
          `name`,
          `phone`,
          `address`,
          `lat`,
          `lng`,
          `postal`,
          `fs_cc`,
          `city`,
          `state`,
          `fs_country`,
          `formattedAddress1`,
          `formattedAddress2`,
          `formattedAddress3`,
          `fs_checkins`,
          `fs_users`,
          `fs_tips`,
          `web`,
          `price`,
          `fs_price_sym`,
          `fs_rating`,
          `fs_rating_signals`,
          `hours`,
          `fs_hereNow`,
          `fs_category`,
          `fs_tips_created`,
          `fs_tips_text`,
          `fs_tips_url`,
          `fs_tips_likes`,
          `fs_tips_userId`,
          `fs_tips_userFirst`,
          `fs_tips_userLast`,
          `fs_tips_userPicPre`,
          `fs_tips_userPicSuff`,
          `hours_status`)
          VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
      $stmt->bind_param("dsssddisssssssiiisssdisissssiisssss",
          $fsid,
          $fs_name,
          $fs_phone,
          $fs_address,
          $fs_lat,
          $fs_lng,
          $fs_postalCode,
          $fs_cc,
          $fs_city,
          $fs_state,
          $fs_country,
          $formattedAddress1,
          $formattedAddress2,
          $formattedAddress3,
          $fs_checkins,
          $fs_users,
          $fs_tips,
          $fs_url,
          $fs_price_msg,
          $fs_price_sym,
          $fs_rating,
          $fs_rating_signals,
          $fs_hours,
          $fs_hereNow,
          $fs_category,
          $fs_tips_created,
          $fs_tips_text,
          $fs_tips_url,
          $fs_tips_likes,
          $fs_tips_userId,
          $fs_tips_userFirst,
          $fs_tips_userLast,
          $fs_tips_userPicPre,
          $fs_tips_userPicSuff,
          $hours_status);
          $fsid=               mysqli_real_escape_string($db,$v2['venue']['id']);
          $fs_name=            mysqli_real_escape_string($db,$v2['venue']['name']);
          $fs_phone=           mysqli_real_escape_string($db,$v2['venue']['contact']['formattedPhone']);
          $fs_address=            mysqli_real_escape_string($db,$v2['venue']['location']['address']);
          $fs_lat=                mysqli_real_escape_string($db,$v2['venue']['location']['lat']);
          $fs_lng=                mysqli_real_escape_string($db,$v2['venue']['location']['lng']);
          $fs_postalCode=         mysqli_real_escape_string($db,$v2['venue']['location']['postalCode']);
          $fs_cc=                 mysqli_real_escape_string($db,$v2['venue']['location']['cc']);
          $fs_city=               mysqli_real_escape_string($db,$v2['venue']['location']['city']);
          $fs_state=              mysqli_real_escape_string($db,$v2['venue']['location']['state']);
          $fs_country=            mysqli_real_escape_string($db,$v2['venue']['location']['country']);
          $formattedAddress1=     mysqli_real_escape_string($db,$v2['venue']['location']['formattedAddress'][0]);
          $formattedAddress2=     mysqli_real_escape_string($db,$v2['venue']['location']['formattedAddress'][1]);
          $formattedAddress3=     mysqli_real_escape_string($db,$v2['venue']['location']['formattedAddress'][2]);
          $fs_checkins=           mysqli_real_escape_string($db,$v2['venue']['stats']['checkinsCount']);
          $fs_users=              mysqli_real_escape_string($db,$v2['venue']['stats']['usersCount']);
          $fs_tips=               mysqli_real_escape_string($db,$v2['venue']['stats']['tipCount']);
          $fs_url=                mysqli_real_escape_string($db,$v2['venue']['url']);
          $fs_price_msg=          mysqli_real_escape_string($db,$v2['venue']['price']['message']);
          $fs_price_sym=          mysqli_real_escape_string($db,$v2['venue']['price']['currency']);
          $fs_rating=             mysqli_real_escape_string($db,$v2['venue']['rating']);
          $fs_rating_signals=     mysqli_real_escape_string($db,$v2['venue']['ratingSignals']);
          $fs_hours=              mysqli_real_escape_string($db,$v2['venue']['hours']['isOpen']);
          $fs_hereNow=            mysqli_real_escape_string($db,$v2['venue']['hereNow']['count']);
          $fs_category=           mysqli_real_escape_string($db,$v3['shortName']);
          $fs_tips_created=       mysqli_real_escape_string($db,$v4['createdAt']);
          $fs_tips_text=          mysqli_real_escape_string($db,$v4['text']);
          $fs_tips_url=           mysqli_real_escape_string($db,$v4['canonicalUrl']);
          $fs_tips_likes=         mysqli_real_escape_string($db,$v4['likes']['count']);
          $fs_tips_userId=        mysqli_real_escape_string($db,$v4['user']['id']);
          $fs_tips_userFirst=     mysqli_real_escape_string($db,$v4['user']['firstName']);
          $fs_tips_userLast=      mysqli_real_escape_string($db,$v4['user']['lastName']);
          $fs_tips_userPicPre=    mysqli_real_escape_string($db,$v4['user']['photo']['prefix']);
          $fs_tips_userPicSuff=   mysqli_real_escape_string($db,$v4['user']['photo']['suffix']);
          $hours_status=          mysqli_real_escape_string($db,$v2['venue']['hours']['status']);
          $stmt->execute();
        }
      }
    }
  }
  $stmt->close();
}

function select_api_results( $latitude, $longitude ) {
  $name             = ( 'data/results.json' );
  $fp               = fopen( $name, 'w' );
  $stmt =
  "SELECT `name`, `phone`, `formattedAddress1`, `city`, `state`, `postal`, `lat`, `lng`, `fs_checkins`, `fs_users`, `fs_tips`, `web`, `fs_price_sym`, `fs_rating`, `hours`, `hours_status`, `fs_category`,
  TRUNCATE((SQRT ( POW ( 69.1 * ( lat - $latitude ), 2 ) + POW ( 69.1 * ( $longitude - lng ) * COS ( lat/ 57.3 ), 2 ) ) * 0.621371),2)
  AS distance
  FROM foursquare_explore
  WHERE fs_date=CURDATE()
  HAVING distance < 2
  ORDER BY `fs_rating` DESC LIMIT 50";
    return $stmt;
}

function populate_variables( $db, $latitude, $longitude ) {
  $select_api_results       = select_api_results( $latitude, $longitude );
    if( $result           = $db->query( $select_api_results ) ) {
        $json_results     = array();
        $c                = 0;
      while( $obj         = $result->fetch_object() ){
        $json_results[$c] = $obj;
        $fs_name[$c]      = ( stripslashes  ( $obj->name ) );
        $fs_phone[$c]     = ( $obj->phone );
        $fs_checkins[$c]  = ( number_format ( $obj->fs_checkins,  0, null, ',' ) );
        $fs_users[$c]     = ( number_format ( $obj->fs_users,     0, null, ',' ) );
        $fs_tips[$c]      = ( number_format ( $obj->fs_tips,      0, null, ',' ) );
        $hours_status[$c] = ( $obj->hours_status);
        $name             = ( 'data/results.json' );
        $fp               = fopen( $name, 'w' );
        fwrite( $fp, json_encode( $json_results ) );
        fclose( $fp );
        ++$c;
      }
      $result->close();
    }
  $db->close();
}

?>
