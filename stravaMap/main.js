/**
*This script is set up to gather data from predetermined Strava Segments. The *idea behind it is to create a localized map that contains data that will lead *new users to best and most competitve segments in their area.
 */
var map, file;

var files = [
/**
*The files here are PHP files that call the Strava API, insert data into the *database, and run SQL queries to retrieve data. They are named after the *segment ID. Each file when executed creates a corresponding (named) JSON file. *This file is what gets parsed by the code below.
*/
];

var defaults = {
    defaultLat: 35.095173,
    defaultLng: -76.683178,
    routeLayer:'https://www.csq2.com/florence_loop/route-v3.kml',
    initZoom: 12
}

function initialize() {
    var src = defaults.routeLayer;
    var mapOptions = {
        center: new google.maps.LatLng(defaults.defaultLat,defaults.defaultLng),
        zoom: defaults.initZoom
    }
    map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
    map.setMapTypeId(google.maps.MapTypeId.TERRAIN);

    loadKmlLayer(src, map);

    for(i=0; file = files[i]; i++){
        Leader(file);
    }

}


function Leader(file){
$.ajax({
url: file + '.php',
success: function(){
  $.getJSON(file + '.json', function(json){
    $.each(json,function(key,value){
      this.segment = value.seg_name;
      this.rank = value.rank;
      this.ratio = value.ratio;
      this.profile = '<img src=' + '"' + value.athlete_profile + '"' + 'data-pin-nopin="true">';
      this.name = value.athlete_name;
      this.start_lat = value.start_lat;
      this.start_lng = value.start_lng;
      this.marker = new google.maps.Marker({
        position: new google.maps.LatLng(this.start_lat,this.start_lng),
        animation: google.maps.Animation.DROP,
        map:map
      });
      this.watts = value.average_watts;
      this.wattsRound = Math.round(this.watts);
      this.distance = Math.round(value.distance);
      this.movingTime = value.moving_time;
      this.distanceMiles = this.distance / 1620;
      this.hours = this.movingTime / 3600;
      this.speed = this.distanceMiles / this.hours;
      this.speedRound = Math.round(this.speed);
      this.start_date = value.start_date;

      Date.daysBetween = function (record_date,today) {
          var one_day = 86400000;
          var start_date_ms = record_date.getTime();
          var date2_ms = today.getTime();
          var difference_ms = date2_ms - start_date_ms;
          return Math.round(difference_ms / one_day);
      }

      var begin = new Date(this.start_date);
      var record_date = new Date(begin.getFullYear(), begin.getMonth(), begin.getDate());
      var today = new Date();

      this.time = value.moving_time;
      this.minutes = Math.floor(this.time / 60);
      this.seconds = this.time - this.minutes * 60;
      this.finalTime = str_pad_left(this.minutes, '0', 2) + ':' + str_pad_left(this.seconds, '0', 2);

      this.html = '<div class=\"container-fluid\">' +
          '<div class=\"row\">' +
          '<div id=\"sb-title\" class=\"col-xs-12\">' +
          'KOM: ' + this.name + '<br>' +
          'Speed: ' + this.speedRound + ' mph' + '<br>' +
          'Time: ' + this.finalTime+ '</div></div><hr>' +
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
              record_date.toLocaleDateString() +
              ' which makes it ' +
                Date.daysBetween(record_date, today) +
              ' days old.'; + '</div></div></div></div><hr>';

      this.entry = $("#info_card").append(this.infoCard);
      this.infowindow = new google.maps.InfoWindow();
      });
      });
    }
  });
}

function str_pad_left(string, pad, length) {
    return (new Array(length + 1).join(pad) + string).slice(-length);
}

function loadKmlLayer(src, map) {
    var kmlLayer = new google.maps.KmlLayer(src, {
        suppressInfoWindows: true,
        preserveViewport: true,
        map: map
    });
    google.maps.event.addListener(kmlLayer, 'click', function (e) {
        var content = e.featureData.infoWindowHtml;
        var routeInfo = document.getElementById('capture');
        routeInfo.innerHTML = content;
    });
}

google.maps.event.addDomListener(window, 'load', initialize);
