var heatmap;
var heatmap_data  = [];
var streetview = document.createElement("DIV");
    streetview.style.width  = "450px";
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
      $.getJSON(defaults.jsonPath, data, function(json) {
        $.each(json, function(key, value) {
          this.pano       = null;
          this.id         = key + 1
          this.web        = value.fb_web;
          this.cover      = value.fb_cover;
          this.about      = value.fb_about;
          this.cln_about  = this.about.replace(/\\/g,"");
          this.chef       = value.fb_culinary_team;
          this.cln_chef   = this.chef.replace(/\\/g,"");
          this.desc       = value.fb_description;
          this.cln_desc   =this.desc.replace(/\\/g,"");
          this.name       = value.fb_name;
          this.cln_name   = this.name.replace(/\\/g,"");
          this.lat        = value.fb_lat;
          this.lng        = value.fb_lng;
          this.score      = Number(value.heatery_score);
          this.point      = new google.maps.LatLng(this.lat, this.lng);
          this.heatery    = new google.maps.LatLng(this.lat, this.lng, this.score);
          this.marker  = new google.maps.Marker({
            position  : this.point,
            title     : this.cln_name,
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

          google.maps.event.addListener(infowindow, 'domready', function (){
            var iwOuter       = $('.gm-style-iw');
            var iwBackground  = iwOuter.prev();
            iwBackground.children(':nth-child(2)').css({
              'display' : 'none'
            });
            iwBackground.children(':nth-child(4)').css({
              'display' : 'none'
            });
            iwBackground.children(':nth-child(3)').find('div').children().css({
              'box-shadow': 'rgba(82, 66, 4, 0.5); 0px 1px 6px',
              'z-index'   : '1'
            });
            var iwCloseBtn = iwOuter.next();
                iwCloseBtn.css({
                  opacity         : '1',
                  right           : '38px',
                  top             : '3px',
                  'border-radius' : '13px',
                  'box-shadow'    : '0 0 5px rgb(82, 66, 4)'
                });
            if($('.iw-content').height() < 140){
              $('.iw-bottom-gradient').css({
              display : 'none'
              });
            }
            iwCloseBtn.mouseout(function(){
                    $(this).css({
                    opacity : '1'
                    });
                });
            });

          bounds.extend(this.marker.getPosition());
          map.fitBounds(bounds);
        });

        this.output  = template(json);
        this.entry   = $("#info_card").append(this.output);

      });
    }

google.maps.event.addDomListener(window, 'load', initialize);
