var heatmap;
var heatmap_data  = [];
var streetview = document.createElement("DIV");
    streetview.style.width  = "350px";
    streetview.style.height = "225px";
    streetview.style.background = "transparent";
var infowindow = new google.maps.InfoWindow({
    content : streetview
});
var defaults = {
  'jsonPath'                :     'data/results.json',
  'gradientNew'             :     ["rgba(0,255,255,0)",
      "rgba(25, 22, 218, 1)", "rgba(17, 191, 225, 1)", "rgba(16, 227, 217, 1)", "rgba(15, 229, 173, 1)", "rgba(14, 231, 128, 1)", "rgba(13, 233, 82, 1)", "rgba(12, 235, 34, 1)", "rgba(37, 237, 11, 1)", "rgba(85, 239, 10, 1)", "rgba(134, 241, 8, 1)", "rgba(185, 243, 7, 1)", "rgba(237, 245, 6, 1)", "rgba(247, 203, 5, 1)", "rgba(249, 152, 3, 1)", "rgba(251, 100, 2, 1)", "rgba(255, 127, 131, 1)", "rgba(253, 47, 1, 1)", "rgba(255, 0, 7, 1)"
  ]
}
var source   = $("#infoCardTemplate").html();
var template = Handlebars.compile(source);
function initialize() {
  var bounds  = new google.maps.LatLngBounds();
  var map     = new google.maps.Map(document.getElementById('map-canvas'),{
    'mapTypeId'               :     google.maps.MapTypeId.TERRAIN,
    'mapTypeControlOptions'   :     {
        position              :     google.maps.ControlPosition.RIGHT_TOP
    }
  });
  $(document).ready(function(){
    set_markers(map, bounds);
  });
}

function set_markers(map, bounds){
    var ts   = new Date().getTime();
    var data = {_: ts};
      $.getJSON(defaults.jsonPath, function(json) {
        var output  = template(json);
        var entry   = $("#info_card").append(output);
        $.each(json, function(key, value) {
          this.pano    = null;
          this.id      = key + 1
          this.web     = value.fb_web;
          this.cover   = value.fb_cover;
          this.about   = value.fb_about;
          this.chef    = value.fb_culinary_team;
          this.desc    = value.fb_description;
          this.name    = value.fb_name;
          this.lat     = value.fb_lat;
          this.lng     = value.fb_lng;
          this.city    = value.fb_city;
          this.state   = value.fb_state;
          this.street  = value.fb_street;
          this.zip     = value.fb_zip;
          this.tac     = value.fb_talking_about;
          this.whc     = value.fb_were_here;
          this.likes   = value.fb_likes;
          this.score   = Number(value.heatery_score);
          this.point   = new google.maps.LatLng(this.lat, this.lng);
          this.heatery = new google.maps.LatLng(this.lat, this.lng, this.score);
          this.html    = '<div id="iw-container" class="container-fluid">' +
            '<div id="iw-details" class="row">' +
            '<div id="iw-title" class="col-xs-12">' +
            '&nbsp;' + this.name + '</div>' +
            '<div id="iw-content">' + this.street +
            '<br></div></div></div></div>';
          this.marker  = new google.maps.Marker({
            position  : this.point,
            animation : google.maps.Animation.DROP,
            title     : this.name,
            map       : map,
            icon      : 'https://mt.googleapis.com/vt/icon/name=icons/spotlight/spotlight-waypoint-b.png&text='+this.id+'&psize=16&font=fonts/Roboto-Regular.ttf&color=ff333333&ax=44&ay=48'
          });
          heatmap_data.push(this.heatery);
          heatmap = new google.maps.visualization.HeatmapLayer({
            data      : heatmap_data,
            radius    : 65,
            opacity   : 0.05,
            gradient  : defaults.gradientNew,
            map       : map
          });
          google.maps.event.addListener(this.marker, 'click', function (){
            infowindow.open(map,this);
          });
          google.maps.event.addDomListener(map, 'click', function(){
            infowindow.close();
          });
          google.maps.event.addListenerOnce(infowindow, 'domready', function(){
           if(this.pano != null) {
              this.pano.unbind("position");
              this.pano.setVisible(false);
            }else {console.log(this);}//show something if no streetview
              this.pano = new google.maps.StreetViewPanorama(streetview,{
                navigationControl : true,
                navigationControlOptions: {
                  style           : google.maps.NavigationControlStyle.ANDROID
                },
                enableCloseButton : false,
                addressControl    : false,
                linksControl      : false
              });
              this.pano.bindTo("position", this);
              this.pano.setVisible(true);
          });
          bounds.extend(this.marker.getPosition());
          map.fitBounds(bounds);
      });
    });
  }
google.maps.event.addDomListener(window, 'load', initialize);
