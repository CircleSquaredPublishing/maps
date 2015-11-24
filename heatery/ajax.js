var map, heatmap, infowindow;
var markers       = [];
var heatmap_data  = [];
var gradientNew   = ["rgba(0,255,255,0)",
    "rgba(25, 22, 218, 1)", "rgba(17, 191, 225, 1)", "rgba(16, 227, 217, 1)", "rgba(15, 229, 173, 1)", "rgba(14, 231, 128, 1)", "rgba(13, 233, 82, 1)", "rgba(12, 235, 34, 1)", "rgba(37, 237, 11, 1)", "rgba(85, 239, 10, 1)", "rgba(134, 241, 8, 1)", "rgba(185, 243, 7, 1)", "rgba(237, 245, 6, 1)", "rgba(247, 203, 5, 1)", "rgba(249, 152, 3, 1)", "rgba(251, 100, 2, 1)", "rgba(255, 127, 131, 1)", "rgba(253, 47, 1, 1)", "rgba(255, 0, 7, 1)"
];
var defaults      = {
  'zoom'                    :      10,
  'mapTypeId'               :     google.maps.MapTypeId.TERRAIN,
  'jsonPath'                :     'results.json'
}
function initialize() {
  var source        = $("#infoCardTemplate").html();
  var template      = Handlebars.compile(source);
  var bounds        = new google.maps.LatLngBounds();
  var mapOptions = {
    'center'                  :     defaults.center,
    'zoom'                    :     defaults.zoom,
    'mapTypeId'               :     defaults.mapTypeId,
    'mapTypeControlOptions'   :     {
        position              :     google.maps.ControlPosition.RIGHT_TOP
    }
  }
  map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

  $(document).ready(function(){
      $.getJSON(defaults.jsonPath, function(json) {
        $.each(json, function(key, value) {
          this.name    = value.fb_name;
          this.lat     = value.fb_lat;
          this.lng     = value.fb_lng;
          this.tac     = value.fb_talking_about;
          this.street  = value.fb_street;
          this.city    = value.fb_city;
          this.state   = value.fb_state;
          this.zip     = value.fb_zip;
          this.web     = value.fb_web;
          this.cover   = value.fb_cover;
          this.latLng  = new google.maps.LatLng(this.lat, this.lng);
          this.heatery = new google.maps.LatLng(this.lat, this.lng, this.tac);
          this.html    = '<div id="iw-container" class="container-fluid">' +
            '<div id="iw-details" class="row">' +
            '<div id="iw-title" class="col-xs-12">' +
            '&nbsp;' + this.name + '</div>' +
            '<div id="iw-content">' + this.street +
            '<br></div></div></div></div>';
          this.marker  = new google.maps.Marker({
            position  : this.latLng,
            title     : this.name,
            map       : map,
            content   : this.html
          });
          heatmap = new google.maps.visualization.HeatmapLayer({
            data      : heatmap_data,
            radius    : 10,
            opacity   : 0.3,
            gradient  : gradientNew,
            map       : map
          });
          infowindow = new google.maps.InfoWindow();
          markers.push(this.marker);
          heatmap_data.push(this.heatery);
          bounds.extend(this.marker.getPosition());
          map.fitBounds(bounds);
          map.panToBounds(bounds);

        google.maps.event.addListener(this.marker, 'click', function (){
          infowindow.setContent(this.content);
          infowindow.open(map,this);
        });

        google.maps.event.addDomListener(map, 'click', function(){
          infowindow.close();
        });

        google.maps.event.addListener(this.marker, 'click',function(){
            map.panTo(this.getPosition());
            map.setZoom(17);
        });

        });
        this.output  = template(json);
        this.entry   = $("#info_card").append(this.output);
      });
    });
  }
google.maps.event.addDomListener(window, 'load', initialize);
