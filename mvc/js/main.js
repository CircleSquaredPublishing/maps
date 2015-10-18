var heatmap;
var map;
var retroStyle = [
{
    "featureType": "administrative",
    "stylers": [
    {
        "visibility": "off"
    }]
},
{
    "featureType": "poi",
    "stylers": [
    {
        "visibility": "simplified"
    }]
},
{
    "featureType": "road",
    "elementType": "labels",
    "stylers": [
    {
        "visibility": "simplified"
    }]
},
{
    "featureType": "water",
    "stylers": [
    {
        "visibility": "simplified"
    }]
},
{
    "featureType": "transit",
    "stylers": [
    {
        "visibility": "simplified"
    }]
},
{
    "featureType": "landscape",
    "stylers": [
    {
        "visibility": "simplified"
    }]
},
{
    "featureType": "road.highway",
    "stylers": [
    {
        "visibility": "off"
    }]
},
{
    "featureType": "road.local",
    "stylers": [
    {
        "visibility": "on"
    }]
},
{
    "featureType": "road.highway",
    "elementType": "geometry",
    "stylers": [
    {
        "visibility": "on"
    }]
},
{
    "featureType": "water",
    "stylers": [
    {
        "color": "#84afa3"
    },
    {
        "lightness": 52
    }]
},
{
    "stylers": [
    {
        "saturation": -17
    },
    {
        "gamma": 0.36
    }]
},
{
    "featureType": "transit.line",
    "elementType": "geometry",
    "stylers": [
    {
        "color": "#3f518c"
    }]
}];
var appleStyle = [
{
    "featureType": "landscape.man_made",
    "elementType": "geometry",
    "stylers": [
    {
        "color": "#f7f1df"
    }]
},
{
    "featureType": "landscape.natural",
    "elementType": "geometry",
    "stylers": [
    {
        "color": "#d0e3b4"
    }]
},
{
    "featureType": "landscape.natural.terrain",
    "elementType": "geometry",
    "stylers": [
    {
        "visibility": "off"
    }]
},
{
    "featureType": "poi",
    "elementType": "labels",
    "stylers": [
    {
        "visibility": "off"
    }]
},
{
    "featureType": "poi.business",
    "elementType": "all",
    "stylers": [
    {
        "visibility": "off"
    }]
},
{
    "featureType": "poi.medical",
    "elementType": "geometry",
    "stylers": [
    {
        "color": "#fbd3da"
    }]
},
{
    "featureType": "poi.park",
    "elementType": "geometry",
    "stylers": [
    {
        "color": "#bde6ab"
    }]
},
{
    "featureType": "road",
    "elementType": "geometry.stroke",
    "stylers": [
    {
        "visibility": "off"
    }]
},
{
    "featureType": "road",
    "elementType": "labels",
    "stylers": [
    {
        "visibility": "off"
    }]
},
{
    "featureType": "road.highway",
    "elementType": "geometry.fill",
    "stylers": [
    {
        "color": "#ffe15f"
    }]
},
{
    "featureType": "road.highway",
    "elementType": "geometry.stroke",
    "stylers": [
    {
        "color": "#efd151"
    }]
},
{
    "featureType": "road.arterial",
    "elementType": "geometry.fill",
    "stylers": [
    {
        "color": "#ffffff"
    }]
},
{
    "featureType": "road.local",
    "elementType": "geometry.fill",
    "stylers": [
    {
        "color": "black"
    }]
},
{
    "featureType": "transit.station.airport",
    "elementType": "geometry.fill",
    "stylers": [
    {
        "color": "#cfb2db"
    }]
},
{
    "featureType": "water",
    "elementType": "geometry",
    "stylers": [
    {
        "color": "#a2daf2"
    }]
}];
var lightStyle = [
{
    "featureType": "landscape",
    "stylers": [
    {
        "hue": "#FFBB00"
    },
    {
        "saturation": 43.400000000000006
    },
    {
        "lightness": 37.599999999999994
    },
    {
        "gamma": 1
    }]
},
{
    "featureType": "road.highway",
    "stylers": [
    {
        "hue": "#FFC200"
    },
    {
        "saturation": -61.8
    },
    {
        "lightness": 45.599999999999994
    },
    {
        "gamma": 1
    }]
},
{
    "featureType": "road.arterial",
    "stylers": [
    {
        "hue": "#FF0300"
    },
    {
        "saturation": -100
    },
    {
        "lightness": 51.19999999999999
    },
    {
        "gamma": 1
    }]
},
{
    "featureType": "road.local",
    "stylers": [
    {
        "hue": "#FF0300"
    },
    {
        "saturation": -100
    },
    {
        "lightness": 52
    },
    {
        "gamma": 1
    }]
},
{
    "featureType": "water",
    "stylers": [
    {
        "hue": "#0078FF"
    },
    {
        "saturation": -13.200000000000003
    },
    {
        "lightness": 2.4000000000000057
    },
    {
        "gamma": 1
    }]
},
{
    "featureType": "poi",
    "stylers": [
    {
        "hue": "#00FF6A"
    },
    {
        "saturation": -1.0989010989011234
    },
    {
        "lightness": 11.200000000000017
    },
    {
        "gamma": 1
    }]
}];
var oldStyle = [
{
    "featureType": "administrative",
    "stylers": [
    {
        "visibility": "off"
    }]
},
{
    "featureType": "poi",
    "stylers": [
    {
        "visibility": "simplified"
    }]
},
{
    "featureType": "road",
    "stylers": [
    {
        "visibility": "simplified"
    }]
},
{
    "featureType": "water",
    "stylers": [
    {
        "visibility": "simplified"
    }]
},
{
    "featureType": "transit",
    "stylers": [
    {
        "visibility": "simplified"
    }]
},
{
    "featureType": "landscape",
    "stylers": [
    {
        "visibility": "simplified"
    }]
},
{
    "featureType": "road.highway",
    "stylers": [
    {
        "visibility": "off"
    }]
},
{
    "featureType": "road.local",
    "stylers": [
    {
        "visibility": "on"
    }]
},
{
    "featureType": "road.highway",
    "elementType": "geometry",
    "stylers": [
    {
        "visibility": "on"
    }]
},
{
    "featureType": "water",
    "stylers": [
    {
        "color": "#84afa3"
    },
    {
        "lightness": 52
    }]
},
{
    "stylers": [
    {
        "saturation": -77
    }]
},
{
    "featureType": "road"
}];
var paleStyle = [
{
    "featureType": "administrative",
    "elementType": "all",
    "stylers": [
    {
        "visibility": "on"
    },
    {
        "lightness": 33
    }]
},
{
    "featureType": "landscape",
    "elementType": "all",
    "stylers": [
    {
        "color": "#f2e5d4"
    }]
},
{
    "featureType": "poi.park",
    "elementType": "geometry",
    "stylers": [
    {
        "color": "#c5dac6"
    }]
},
{
    "featureType": "poi.park",
    "elementType": "labels",
    "stylers": [
    {
        "visibility": "on"
    },
    {
        "lightness": 20
    }]
},
{
    "featureType": "road",
    "elementType": "all",
    "stylers": [
    {
        "lightness": 20
    }]
},
{
    "featureType": "road.highway",
    "elementType": "geometry",
    "stylers": [
    {
        "color": "#c5c6c6"
    }]
},
{
    "featureType": "road.arterial",
    "elementType": "geometry",
    "stylers": [
    {
        "color": "#e4d7c6"
    }]
},
{
    "featureType": "road.local",
    "elementType": "geometry",
    "stylers": [
    {
        "color": "#fbfaf7"
    }]
},
{
    "featureType": "water",
    "elementType": "all",
    "stylers": [
    {
        "visibility": "on"
    },
    {
        "color": "#acbcc9"
    }]
}];
var brownStyle = [
{
    "elementType": "geometry",
    "stylers": [
    {
        "hue": "#ff4400"
    },
    {
        "saturation": -68
    },
    {
        "lightness": -4
    },
    {
        "gamma": 0.72
    }]
},
{
    "featureType": "road",
    "elementType": "labels.icon"
},
{
    "featureType": "landscape.man_made",
    "elementType": "geometry",
    "stylers": [
    {
        "hue": "#0077ff"
    },
    {
        "gamma": 3.1
    }]
},
{
    "featureType": "water",
    "stylers": [
    {
        "hue": "#00ccff"
    },
    {
        "gamma": 0.44
    },
    {
        "saturation": -33
    }]
},
{
    "featureType": "poi.park",
    "stylers": [
    {
        "hue": "#44ff00"
    },
    {
        "saturation": -23
    }]
},
{
    "featureType": "water",
    "elementType": "labels.text.fill",
    "stylers": [
    {
        "hue": "#007fff"
    },
    {
        "gamma": 0.77
    },
    {
        "saturation": 65
    },
    {
        "lightness": 99
    }]
},
{
    "featureType": "water",
    "elementType": "labels.text.stroke",
    "stylers": [
    {
        "gamma": 0.11
    },
    {
        "weight": 5.6
    },
    {
        "saturation": 99
    },
    {
        "hue": "#0091ff"
    },
    {
        "lightness": -86
    }]
},
{
    "featureType": "transit.line",
    "elementType": "geometry",
    "stylers": [
    {
        "lightness": -48
    },
    {
        "hue": "#ff5e00"
    },
    {
        "gamma": 1.2
    },
    {
        "saturation": -23
    }]
},
{
    "featureType": "transit",
    "elementType": "labels.text.stroke",
    "stylers": [
    {
        "saturation": -64
    },
    {
        "hue": "#ff9100"
    },
    {
        "lightness": 16
    },
    {
        "gamma": 0.47
    },
    {
        "weight": 2.7
    }]
}];
/************************************************************\
*A distance widget that will display a circle that can be resized and will
provide the radius in km.
@param {google.maps.Map} map The map to attach to.
@constructor
\************************************************************/
function DistanceWidget(map) {
    this.set('map', map);
    this.set('position', map.getCenter());
    var marker = new google.maps.Marker({
        draggable: true,
        title: 'Move me!'
    });
    marker.bindTo('map', this);// Bind the marker map property to the DistanceWidget map property
    marker.bindTo('position', this);// Bind the marker position property to the DistanceWidget position property
    var radiusWidget = new RadiusWidget();// Create a new radius widget
    radiusWidget.bindTo('map', this);// Bind the radiusWidget map to the DistanceWidget map
    radiusWidget.bindTo('center', this, 'position');// Bind the radiusWidget center to the DistanceWidget position
    this.bindTo('distance', radiusWidget);// Bind to the radiusWidgets' distance property
    this.bindTo('bounds', radiusWidget);// Bind to the radiusWidgets' bounds property
}
DistanceWidget.prototype = new google.maps.MVCObject();
/************************************************************\
* A radius widget that add a circle to a map and centers on a marker.
@constructor
\************************************************************/
function RadiusWidget() {
    var circle = new google.maps.Circle({
        strokeWeight: 2
    });
    this.set('distance', 1);// Set the distance property value, default to 50km.
    this.bindTo('bounds', circle);// Bind the RadiusWidget bounds property to the circle bounds property.
    circle.bindTo('center', this);// Bind the circle center to the RadiusWidget center property
    circle.bindTo('map', this);// Bind the circle map to the RadiusWidget map
    circle.bindTo('radius', this); // Bind the circle radius property to the RadiusWidget radius property
    this.addSizer_(); // Add the sizer marker
}

RadiusWidget.prototype = new google.maps.MVCObject();

/* Update the radius when the distance has changed. */
RadiusWidget.prototype.distance_changed = function () {
    this.set('radius', this.get('distance') * 1000);
};

/* Add the sizer marker to the map. @private */
RadiusWidget.prototype.addSizer_ = function () {
    var sizer = new google.maps.Marker({
        draggable: true,
        title: 'Drag me!'
    });
    sizer.bindTo('map', this);
    sizer.bindTo('position', this, 'sizer_position');
    var me = this;
    google.maps.event.addListener(sizer, 'drag', function () {
        me.setDistance();// Set the circle distance (radius)
    });
};

/**
 * Update the center of the circle and position the sizer back on the line.
 * Position is bound to the DistanceWidget so this is expected to change when
 * the position of the distance widget is changed.
 */
RadiusWidget.prototype.center_changed = function () {
    var bounds = this.get('bounds');
    if (bounds) {
        var lng = bounds.getNorthEast().lng();
        var position = new google.maps.LatLng(this.get('center').lat(), lng);
        this.set('sizer_position', position);
    }
};
/**
 * Calculates the distance between two latlng locations in km.
 * @see http://www.movable-type.co.uk/scripts/latlong.html
 * @param {google.maps.LatLng} p1 The first lat lng point.
 * @param {google.maps.LatLng} p2 The second lat lng point.
 * @return {number} The distance between the two points in km.
 * @private
 */
RadiusWidget.prototype.distanceBetweenPoints_ = function (p1, p2) {
    if (!p1 || !p2) {
        return 0;
    }
    var R = 6371; // Radius of the Earth in km
    var dLat = (p2.lat() - p1.lat()) * Math.PI / 180;
    var dLon = (p2.lng() - p1.lng()) * Math.PI / 180;
    var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
        Math.cos(p1.lat() * Math.PI / 180) * Math.cos(p2.lat() * Math.PI /
            180) *
        Math.sin(dLon / 2) * Math.sin(dLon / 2);
    var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    var d = R * c;
    return d.toFixed(2);
};
/**
 * Set the distance of the circle based on the position of the sizer.
 */
RadiusWidget.prototype.setDistance = function () {
/**
* As the sizer is being dragged, its position changes.  Because the
* RadiusWidget's sizer_position is bound to the sizer's position, it will
* change as well.
*/
    var pos = this.get('sizer_position');
    var center = this.get('center');
    var distance = this.distanceBetweenPoints_(center, pos);
    this.set('distance', distance);// Set the distance property for any objects that are bound to it
};

function displayMap(coords) {
    var mapDiv = document.getElementById('map-canvas');
    var retro_style = new google.maps.StyledMapType(retroStyle, {
        name: "Retro"
    });
    var apple_style = new google.maps.StyledMapType(appleStyle, {
        name: "Apple"
    });
    var light_style = new google.maps.StyledMapType(lightStyle, {
        name: "Dusk"
    });
    var old_style = new google.maps.StyledMapType(oldStyle, {
        name: "Vintage"
    });
    var pale_style = new google.maps.StyledMapType(paleStyle, {
        name: "Cloud"
    });
    var brown_style = new google.maps.StyledMapType(brownStyle, {
        name: "Organic"
    });
    var map = new google.maps.Map(mapDiv, {
        center: new google.maps.LatLng(35.1066678528547, -
            77.03828485889437),
        zoom: 15,
        panControl: false,
        zoomControl: true,
        mapTypeControl: true,
        mapTypeControlOptions: {
            style: google.maps.MapTypeControlStyle.DROPDOWN_MENU,
            position: google.maps.ControlPosition.TOP_RIGHT,
            mapTypeIds: ["Retro", "Apple", "Dusk", "Vintage",
                "Cloud", "Organic", google.maps.MapTypeId.ROADMAP,
                google.maps.MapTypeId.SATELLITE, google.maps.MapTypeId
                .TERRAIN
            ]
        }
    });
    map.mapTypes.set("Retro", retro_style);
    map.mapTypes.set("Apple", apple_style);
    map.mapTypes.set("Dusk", light_style);
    map.mapTypes.set("Vintage", old_style);
    map.mapTypes.set("Cloud", pale_style);
    map.mapTypes.set("Organic", brown_style);
    map.setMapTypeId("Vintage");
    var distanceWidget = new DistanceWidget(map);
    google.maps.event.addListener(distanceWidget, 'distance_changed',
        function () {
            displayInfo(distanceWidget);
        });
    google.maps.event.addListener(distanceWidget, 'position_changed',
        function () {
            displayInfo(distanceWidget);
        });
}

function displayInfo(widget) {
    var info = document.getElementById('info');
    info.innerHTML = 'Position: ' + widget.get('position') + '<br>' 
        + 'distance: ' + widget.get('distance') + ' km';
}
google.maps.event.addDomListener(window, 'load', displayMap);