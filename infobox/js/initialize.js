function initialize() {
  var brownStyle = [{
      "elementType": "geometry",
      "stylers": [{
          "hue": "#ff4400"
        }, {
          "saturation": -68
        }, {
          "lightness": -4
        }, {
          "gamma": 0.72
      }]
      }, {
      "featureType": "road",
      "elementType": "labels.icon"
      }, {
      "featureType": "landscape.man_made",
      "elementType": "geometry",
      "stylers": [{
          "hue": "#0077ff"
      }, {
          "gamma": 3.1
      }]
      }, {
      "featureType": "water",
      "stylers": [{
          "hue": "#00ccff"
      }, {
          "gamma": 0.44
      }, {
          "saturation": -33
      }]
      }, {
      "featureType": "poi.park",
      "stylers": [{
          "hue": "#44ff00"
      }, {
          "saturation": -23
      }]
      }, {
      "featureType": "water",
      "elementType": "labels.text.fill",
      "stylers": [{
          "hue": "#007fff"
      }, {
          "gamma": 0.77
      }, {
          "saturation": 65
      }, {
          "lightness": 99
      }]
      }, {
      "featureType": "water",
      "elementType": "labels.text.stroke",
      "stylers": [{
          "gamma": 0.11
      }, {
          "weight": 5.6
      }, {
          "saturation": 99
      }, {
          "hue": "#0091ff"
      }, {
          "lightness": -86
      }]
      }, {
      "featureType": "transit.line",
      "elementType": "geometry",
      "stylers": [{
          "lightness": -48
      }, {
          "hue": "#ff5e00"
      }, {
          "gamma": 1.2
      }, {
          "saturation": -23
      }]
      }, {
      "featureType": "transit",
      "elementType": "labels.text.stroke",
      "stylers": [{
          "saturation": -64
      }, {
          "hue": "#ff9100"
      }, {
          "lightness": 16
      }, {
          "gamma": 0.47
      }, {
          "weight": 2.7
      }]
  }];
  var brown_style = new google.maps.StyledMapType(brownStyle,{
    name: 'Organic'
  });
  var source    = $('#infoBoxTemplate').html();
  var template  = Handlebars.compile(source);
  var data      = {content: [
    {'name'     : "Forrest Farm Supply"},
    {'address'  : "502 Main St"},
    {'postal'   : "Bayboro, NC 28515"},
    {'phone'    : "(252) 745-3551"},
    {'weekday'  : "Monday thru Friday: 7:30 AM - 5:00 PM"},
    {'saturday' : "Saturday: 7:30 AM - 1:00 PM"},
    {'sunday'   : "Sunday	Closed"},
    {'web'      : "http://www.forrestfarmsupply.com"},
    {'niceURL'  : "forrestfarmsupply.com"},
  ]};
  var output    = template(data);
  var defaults  = {
    'ForrestFarmSupply'  : new google.maps.LatLng(35.143676, -76.776925),
    'zoom'               : 15,
    'mapTypeId'       	 : google.maps.MapTypeId.TERRAIN,
    'toolTip'            : 'Forrest Farm Supply',
    'forestIcon1'        : 'https://www.csq2.com/icons/forest1.png',
    'forestIcon2'        : 'https://www.csq2.com/icons/forest2.png',
    'forestIcon3'        : 'https://www.csq2.com/icons/forest3.png',
    'mapTypeControl'     : true,
    'mtcPos'             : google.maps.ControlPosition.TOP_RIGHT,
    'mtcStyle'           : google.maps.MapTypeControlStyle.DROPDOWN_MENU,
    'mtcIDs'             : ['Organic', google.maps.MapTypeId.ROADMAP,
    google.maps.MapTypeId.SATELLITE, google.maps.MapTypeId.TERRAIN]
  }
  var mapOptions = {
    'center'                  : defaults.ForrestFarmSupply,
    'zoom'                    : defaults.zoom,
    'mapTypeId'               : defaults.mapTypeId,
    'mapTypeControl'          : defaults.mapTypeControl,
    'mapTypeControlOptions'   :     {
        'position'            : defaults.mtcPos,
        'style'               : defaults.mtcStyle,
        'mapTypeIds'          : defaults.mtcIDs
    }
  }
  var map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);
      map.mapTypes.set('Organic', brown_style);
      map.setMapTypeId('Organic');
  var infobox = new InfoBox({
    'content'						: '<p>Empty</p>',
    'closeBoxURL'       : "",
    'disableAutoPan'		: false,
    'maxWidth'					: 0,
    'pixelOffset'				: new google.maps.Size(-140, 0),
    'zIndex'						: null,
    'infoBoxClearance'	: new google.maps.Size(1, 1),
    'isHidden'					: false,
    'pane'							: "floatPane",
    'enableEventPropagation'	: false
  });
  var marker = new google.maps.Marker({
    'map'				: map,
    'position'	: defaults.ForrestFarmSupply,
    'animation' : google.maps.Animation.DROP,
    'title'     : defaults.toolTip,
    'map'       : map,
    'icon'      : defaults.forestIcon1
  });
  var pano = null;
  google.maps.event.addListener(infobox, 'domready', function() {
    if(pano != null) {
      pano.unbind("position");
      pano.setVisible(false);
    }
    pano = new google.maps.StreetViewPanorama(document.getElementById("streetview"),{
      'navigationControl'         : true,
      'navigationControlOptions'  : {
        'style'                   : google.maps.NavigationControlStyle.ANDROID,
      },
      'enableCloseButton'         : false,
      'addressControl'            : false,
      'linksControl'              : false
    });
    pano.bindTo("position", marker);
    pano.setVisible(true);
  });
  google.maps.event.addListener(infobox, 'click', function() {
    pano.unbind("position");
    pano.setVisible(false);
    pano = null;
  });
  google.maps.event.addListener(marker, "click", function() {
    infobox.setContent(output);
    infobox.open(map, this);
  });
  google.maps.event.addListener(map, "click", function() {
    infobox.close(map, marker);
  });
}
google.maps.event.addDomListener(window, 'load', initialize);
