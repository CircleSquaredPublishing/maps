<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
/**
* File Name:    hm_insert.php
* Description:  Executes INSERT statement into social_data database for JSON results from
* call to Facebook Graph API. Executes SELECT statement and populates variables with results
* from the SELECT statement. All the functions for creating the 'results.json' file.
* Author:       Circle Squared Data Labs
* Author URI:   https://www.heatery.io
*/
?>

<!-- Modal
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
      <div class="modal-content">
          <div id="myModalHeader" class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
              <h4 class="modal-title" id="myModalLabel">
                <img id="myModalHeaderImg" src="https://www.heatery.io/hm-media/hm-img/hm_logo_csq_lg.jpg"/>
                <br>Welcome to the Circle Squared Data Labs Heatery Map
              </h4>
          </div>
          <div id="myModalBody" class="modal-body">
              <p><?php get_date_time();?></p>
          </div>
      </div>
  </div>
</div>-->
<!-- End Modal -->

<!-- navbar -->
<nav id="hm_navbar_top" class="navbar navbar-default navbar-fixed-top">
    <div id="hm_navbar_container" class="container-fluid">
        <div id="hm_navbar_header" class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#hm_navbar_collapse" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <div id="hm_navbar_collapse" class="collapse navbar-collapse">
            <ul class="nav navbar-nav navbar-left">

              <!-- form -->
                <form id="gc-form" class="navbar-form navbar-left" role="search" method="post" action="">
                    <button id="btn-find" type="submit" class="btn btn-default" name="btn-submit" >Find
                    </button>
                    <div id="gc-input" class="form-group">
                        <input id="gc-search-box" name="address" type="text" class="form-control" placeholder="Your Hot Spot.">
                    </div>
                </form>
              <!-- end form -->

            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class="active">
                    <a href="#">Heatery Map<span class="sr-only">(current)</span></a>
                </li>
                <li>
                    <a href="https://www.heatery.io/login">Login<span class="sr-only">(login)</span></a>
                </li>
                <li>
                    <a href="https://www.heatery.io">Home<span class="sr-only">(home)</span></a>
                </li>
                <li>
                    <a href="#">Circle Squared Data Labs</a>
                </li>
                <a id="hm_navbar_brand" class="navbar-brand" href="https://www.heatery.io">
                    <!--<img id="hm_navbar_brand_img" alt="heatery.io" src="https://www.heatery.io/hm-media/hm-img/hm_logo_csq_lg.jpg"/>-->
                </a>
            </ul>
        </div>
    </div>
</nav>
<!-- end navbar -->

<?php
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
    $host   = HOST;
    $dbname = DB;
    $user   = USER;
    $pass   = PASSWORD;
    $db     = new mysqli($host, $user, $pass, $dbname);
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
  $name       = ( $table . '.json' );
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
  HAVING fb_distance < 2
  ORDER BY heatery_score DESC LIMIT 11";
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
        $heatery_score[$c]    = ( $obj->heatery_score );
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
