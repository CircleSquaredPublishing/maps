<?php require( 'functions.php' );?>
<!DOCTYPE html>
<html>

<head>
    <title>
        <?php page_title( $title ); ?>
    </title>
    <link rel="stylesheet" type="text/css" href="css/style.css" />
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css"
    />
</head>

<body>
    <div id="map-canvas"></div>
    <div class="container" id="main">
        <div class="row">
            <div class="col-xs-4" id="left">
                <div id="info_panel" class="panel panel-default">
                    <div id="info_head" class="panel-heading">
                        <h2 id="info_head_title">Find Your Hot Spot.</h2>
                    </div>
                    <div id="info_card" class="panel-heading">
                        <!-- HANDLEBARS TEMPLATE INJECTED HERE -->
                    </div>
                </div>
            </div>
            <div class="col-xs-8"></div>
        </div>
    </div>

    <!-- HANDLEBARS INFOCARD TEMPLATE -->
    <script id="infoCardTemplate" type="text/x-handlebars-template">
        <div class="container-fluid">
                {{#each .}}{{#if fb_cover}}
                <div id="sb-title" class="col-xs-12">
                  {{fb_name}}
                </div>
                <hr>
                <div class="row">
                    <div id="profile_pic" class="col-xs-12">
                        <img src="{{fb_cover}}" data-pin-nopin="true">
                    </div>
                  </div>
                {{/if}}{{/each}}
            </div>
        </div>
        <hr>
    </script>

    <!-- EXTERNAL ASSETS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=false&libraries=visualization,places,geometry"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
    <script src="js/handlebars-v4.0.2.js"></script>
    <script src="js/ajax.js"></script>
</body>

</html>
