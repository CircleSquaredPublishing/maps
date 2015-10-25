/*********************************************************
 * Segments shown on this route:
 *---------------------------------------------------------*
 *'7384668' = Straight Never Forward. Marker1
 *'2890903' = Florence Rd from Whortonsville FD Marker2
 *'6086655' = Hwy 55 Spruill -> Kershaw Marker3
 *'3993428' = Fire Dept. Sprint Marker4
 *'7384728' = OC2RD Marker5
 *'10657360'= Orchard Creek Loop Marker6
 *'2828945' = Kershaw TT ** REMOVED BECAUSE NOT ON ROUTE **
 *---------------------------------------------------------*
 **********************************************************/
var map, file;
var markers = [];
var infowindow = new google.maps.InfoWindow();
var map, file;
var files = [7384668, 2890903, 6086655, 3993428, 2828945, 10657360];
var defaults = {
  defaultLat: 35.095173,
  defaultLng: -76.683178,
  routeLayer: 'https://www.csq2.com/florence_loop/route-v3.kml',
  initZoom: 12,
  phpPath: '/development/stravaMap/api_calls/',
  jsonPath: '/development/stravaMap/api_responses/leaders/'
}

function initialize() {
  var src = defaults.routeLayer;
  var mapOptions = {
    center: new google.maps.LatLng(defaults.defaultLat, defaults.defaultLng),
    zoom: defaults.initZoom,
    mapTypeId: google.maps.MapTypeId.TERRAIN,
    mapTypeControlOptions: {
      position: google.maps.ControlPosition.RIGHT_TOP
    }
  map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
  map.setMapTypeId(google.maps.MapTypeId.TERRAIN);

  loadKmlLayer(src, map);

  for (i = 0; file = files[i]; i++) {
    Leader(file);
  }

}

function Leader(file) {
  $.ajax({
    url: defaults.phpPath + file + '.php',
    success: function() {
        $.getJSON(defaults.jsonPath + file + '.json', function(json) {
          $.each(json, function(key, value) {
            this.segment = value.seg_name;
            this.rank = value.rank;
            this.ratio = value.ratio;
            this.profile = '<img src=' + '"' + value.athlete_profile + '"' + 'data-pin-nopin="true">';
            this.name = value.athlete_name;
            this.start_lat = value.start_lat;
            this.start_lng = value.start_lng;
            this.watts = value.average_watts;
            this.wattsRound = Math.round(this.watts);
            this.distance = Math.round(value.distance);
            this.movingTime = value.moving_time;
            this.distanceMiles = this.distance / 1620;
            this.hours = this.movingTime / 3600;
            this.speed = this.distanceMiles / this.hours;
            this.speedRound = Math.round(this.speed);
            this.start_date = value.start_date;
            this.start_date_parse = Date.parse(this.start_date).format("%b %e, %Y");
            this.date1 = new Date.parse(this.start_date);
            this.date2 = new Date.today();
            this.timeDiff = Math.abs(this.date2.getTime() - this.date1.getTime());
            this.diffDays = Math.ceil(this.timeDiff / (1000 * 3600 * 24));
            this.time = value.moving_time;
            this.minutes = Math.floor(this.time / 60);
            this.seconds = this.time - this.minutes * 60;
            this.finalTime = str_pad_left(this.minutes, '0', 2) + ':' + str_pad_left(this.seconds, '0', 2);

            this.html = '<div class=\"container-fluid\">' +
              '<div class=\"row\">' +
              '<div id=\"sb-title\" class=\"col-xs-12\">' +
              'KOM: ' + this.name + '<br>' +
              'Speed: ' + this.speedRound + ' mph' + '<br>' +
              'Time: ' + this.finalTime + '</div></div><hr>' +
              '<div class="row">' +
              '<div id="profile_pic" class="col-xs-12">' + this.profile + '</div></div></div>';
            this.infoCard =
              '<div class=\"container-fluid\">' +
              '<div class=\"row\">' +
              '<div id=\"sb-title\" class=\"col-xs-12\">' + this.segment + '</div><hr>' +
              '<div class="row">' +
              '<div id="profile_pic" class="col-xs-6">' + this.profile + '</div>' +
              '<div id="profile_info" class="col-xs-6">' +
              'KOM: ' + this.name + '<br>' +
              'Average Speed: ' + this.speedRound + ' mph' + '<br>' +
              'Average Power: ' + this.wattsRound + ' watts' + '<br>' +
              'Distance: ' + this.distance + ' meters' + '<br>' +
              'Time: ' + this.finalTime + '<br>' +
              'Record was set on ' +
              this.start_date_parse + '<br>' +
              ' which makes it ' +
              this.diffDays +
              ' days old.'; + '</div></div></div></div><hr>';

            this.marker = new google.maps.Marker({
              position: new google.maps.LatLng(this.start_lat, this.start_lng),
              animation: google.maps.Animation.DROP,
              map: map,
              content: this.html
            });

            markers.push(this.marker.position);
            this.entry = $('#info_card').append(this.infoCard);

            google.maps.event.addListener(this.marker, 'click', function() {
              infowindow.setContent(this.content);
              infowindow.open(map, this);
            });

            google.maps.event.addDomListener(map, 'click', function() {
              infowindow.close();

            });

          }); //end of $.each()
        }); //end of $.getJSON()
      } //end of $.ajax.success()
  }); //end of $.ajax()
} //end of Leader()



function str_pad_left(string, pad, length) {
  return (new Array(length + 1).join(pad) + string).slice(-length);
}

function loadKmlLayer(src, map) {
  var kmlLayer = new google.maps.KmlLayer(src, {
    suppressInfoWindows: true,
    preserveViewport: true,
    map: map
  });
  google.maps.event.addListener(kmlLayer, 'click', function(e) {
    var content = e.featureData.infoWindowHtml;
    var routeInfo = document.getElementById('capture');
    routeInfo.innerHTML = content;
  });
}

google.maps.event.addDomListener(window, 'load', initialize);
