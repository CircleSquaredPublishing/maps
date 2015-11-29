<!DOCTYPE html>
<html lang="en">
<head>
  <?php
  $title = 'Heatery';
  function page_title ( $title ) {
    if( isset( $title ) && is_string( $title ) ){
      $page_title = print_r( $title );
      } else {
        echo 'Find Your Hot Spot.';
      }
        return $page_title;
  }
  ?>
  <title><?php page_title( $title ); ?></title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="apple-mobile-web-app-status-bar-style" content="translucent black" />
    <meta name="p:domain_verify" content="99be6fb68b0c975e69a515c6fad020ab" />
    <meta property="fb:app_id" content="1452021355091002" />
    <meta name='yandex-verification' content='4a2af8bc9af8ffa5' />
    <meta name="alexaVerifyID" content="RZ-VW1FIkLhufpGOHO8oCry4swk" />
    <meta name="google-site-verification" content="hLFvZbrU2DgALxyrC2fQPOE8n2Dk0Ri58qbT_RIdhkI" />
    <meta name="google-site-verification" content="Y9hyPVrpJzkcgV58YBmyU6BWV6d-hiIwAnQgTv66QfY" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="author" content="Circle Squared Data Labs" />
    <meta name="author" content="Circle Squared Data Labs" />
    <meta property="og:url" content="https://www.heatery.io/heaterymap/" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="Heatery Map: Find Your Hot Spot." />
    <meta property="og:description" content="Find the hottest restaurants in your area or anywhere in the world with the Heatery Map." />
    <meta property="og:image" content="https://www.heatery.io/hm-media/hm-img/hm_fb_og_img.png" />
    <link rel="stylesheet" type="text/css" href="css/style.css" />
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css"
    />
</head>

<body>

<div id="wrapper">
  <div id="navbar" class="navbar navbar-inverse navbar-fixed-top">
    <form class="nav navbar-nav navbar-left navbar-form" role="search" method="post" action="">
      <button class="btn btn-link" type="submit"  name="btn-submit" >Find</button>
      <div class="form-group" style="display:inline;">
        <input name="address" type="text" class="form-control" placeholder="Your Hot Spot.">
      </div>
    </form>
  </div>
  <div class="container">
    <div class="row">
      <?php require('functions.php'); ?>
      <div id="sidebar-nav" class="col-sm-4"><!-- Inject Handlebars Template Here --></div>
      <div id="map-canvas" class="col-sm-8"><!-- Map Goes Here --></div>
    </div>
  </div>
</div>

  <!-- @begin handlebars infocard template -->
  <script id="infoCardTemplate" type="text/x-handlebars-template">
    <div class="container-fluid">
      {{#each .}}{{#if cover}}
      <div id="sb-title" class="col-xs-12">
        {{cln_name}}
      </div>
      <div class="row">
        <div id="profile-pic" class="col-xs-12">
          <img src="{{fb_cover}}" data-pin-nopin="true">
        </div>
      </div>
      <div id="sb-web" class="col-xs-12">
        <a href="{{web}}" target="_blank">{{niceWeb}}</a>
      </div>
        {{/if}}{{/each}}
      </div>
  </script>
  <!-- @end handlebars infocard template -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
  <script src="https://maps.googleapis.com/maps/api/js?v=3.22&signed_in=false&libraries=visualization,places,geometry"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
  <script src="js/handlebars-v4.0.2.js"></script>
  <script src="js/ajax.js"></script>

</body>
</html>
