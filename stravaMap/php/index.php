<!DOCTYPE html>

<html>

<head>
  <title>Strava App Template</title>
  <meta charset="UTF-8" />
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
            <h2 id="capture">Strava App Template</h2>
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
        <div class="row">
            {{#each .}}
            <div id="sb-title" class="col-xs-12">{{segment}}</div>
            <hr>
            <div class="row">
                <div id="profile_pic" class="col-xs-6">
                    <img src="{{athlete_profile}}" />
                </div>
                <div id="profile_info" class="col-xs-6">
                    KOM: {{athlete_name}}
                    <br> Average Speed: {{speedRound}} mph
                    <br> Average Power: {{wattsRound}} watts
                    <br> Distance: {{distance}} meters
                    <br> Time: {{finalTime}}
                    <br> Record was set on: {{start_date}}
                    <br> which makes it {{diffDays}} days old.
                </div>
            </div>
            {{/each}}
        </div>
    </div>
</script>


<!-- EXTERNAL ASSETS -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script src="js/date.js"></script>
<script src="js/time.js"></script>
<script src="js/extras.js"></script>
<script src='js/handlebars-v4.0.2.js'></script>
<script src="js/main.js"></script>
</body>

</html>
