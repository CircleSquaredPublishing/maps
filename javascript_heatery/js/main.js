window.onload = getLocation;
var TILE_SIZE = 256;
var desiredRadiusPerPointInMeters = 150;
var map, marker, infoWindow, geocoder, heatmap;
var infoWindow = new google.maps.InfoWindow();
var bounds = new google.maps.LatLngBounds();
var markers=[];

function clearOverlays() {
    if (markers.length != 0) {
        for (var i = 0; i < markers.length; i++) {
            markers[i].setMap(null);
        }
    }
}
/*Begin Mercator Projection*/
function bound(value, opt_min, opt_max) {
    if (opt_min !== null) value = Math.max(value, opt_min);
    if (opt_max !== null) value = Math.min(value, opt_max);
    return value;
}

function degreesToRadians(deg) {
    return deg * (Math.PI / 180);
}

function radiansToDegrees(rad) {
    return rad / (Math.PI / 180);
}

function MercatorProjection() {
    this.pixelOrigin_ = new google.maps.Point(TILE_SIZE / 2, TILE_SIZE / 2);
    this.pixelsPerLonDegree_ = TILE_SIZE / 360;
    this.pixelsPerLonRadian_ = TILE_SIZE / (2 * Math.PI);
}

MercatorProjection.prototype.fromLatLngToPoint = function (latLng, opt_point) {
    var me = this;
    var point = opt_point || new google.maps.Point(0, 0);
    var origin = me.pixelOrigin_;
    point.x = origin.x + latLng.lng() * me.pixelsPerLonDegree_;
    var siny = bound(Math.sin(degreesToRadians(latLng.lat())), -0.9999, 0.9999);
    point.y = origin.y + 0.5 * Math.log((1 + siny) / (1 - siny)) * -me.pixelsPerLonRadian_;
    return point;
};

MercatorProjection.prototype.fromPointToLatLng = function (point) {
    var me = this;
    var origin = me.pixelOrigin_;
    var lng = (point.x - origin.x) / me.pixelsPerLonDegree_;
    var latRadians = (point.y - origin.y) / -me.pixelsPerLonRadian_;
    var lat = radiansToDegrees(2 * Math.atan(Math.exp(latRadians)) - Math.PI / 2);
    return new google.maps.LatLng(lat, lng);
};

function getNewRadius() {
    var numTiles = 1 << map.getZoom();
    var center = map.getCenter();
    var moved = google.maps.geometry.spherical.computeOffset(center, 10000, 90); /*1000 meters to the right*/
    var projection = new MercatorProjection();
    var initCoord = projection.fromLatLngToPoint(center);
    var endCoord = projection.fromLatLngToPoint(moved);
    var initPoint = new google.maps.Point(
        initCoord.x * numTiles,
        initCoord.y * numTiles);
    var endPoint = new google.maps.Point(
        endCoord.x * numTiles,
        endCoord.y * numTiles);
    var pixelsPerMeter = (Math.abs(initPoint.x - endPoint.x)) / 10000.0;
    var totalPixelSize = Math.floor(desiredRadiusPerPointInMeters * pixelsPerMeter);
    console.log(totalPixelSize);
    return totalPixelSize;
} /* End Mercator Projection */

function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(geoSuccess, geoError, {
            maximumAge: 30000,
            timeout: 5000,
            enableHighAccuracy: true
        });
    } else {
        alert("Geolocation is not supported by your browser.");
    }
}

function geoSuccess(position) {
    var lat = position.coords.latitude;
    var lng = position.coords.longitude;
    var loc = new google.maps.LatLng(lat, lng);
    displayMap(position.coords);
    var fb = "https://graph.facebook.com/v2.4/search?&q=restaurant&type=place&center=" + loc + "&distance=5000&fields=talking_about_count,location,name&offset=0&limit=5000&access_token=1452021355091002|x-ZB0iKqWQmYqnJQ-wXoUjl-XtY";
    fb = fb.replace(/[()]/g, "");
    $(document).ready(function () {
        $.ajax({
            url: fb,
            dataType: "text",
            cache: true,
            success: function (data) {
                var restaurantData = $.parseJSON(data);
                var myData = [];
                var markers = [];
                var gradientNew = ["rgba(0,255,255,0)",
"rgba(25, 22, 218, 1)", "rgba(17, 191, 225, 1)", "rgba(16, 227, 217, 1)", "rgba(15, 229, 173, 1)", "rgba(14, 231, 128, 1)", "rgba(13, 233, 82, 1)", "rgba(12, 235, 34, 1)", "rgba(37, 237, 11, 1)", "rgba(85, 239, 10, 1)", "rgba(134, 241, 8, 1)", "rgba(185, 243, 7, 1)", "rgba(237, 245, 6, 1)", "rgba(247, 203, 5, 1)", "rgba(249, 152, 3, 1)", "rgba(251, 100, 2, 1)", "rgba(255, 127, 131, 1)", "rgba(253, 47, 1, 1)", "rgba(255, 0, 7, 1)"
];
                for (var i = 0; i < restaurantData.data.length; i++) {
                    var lat = restaurantData.data[i].location.latitude;
                    var lng = restaurantData.data[i].location.longitude;
                    var wgt = restaurantData.data[i].talking_about_count;
                    var latLng = new google.maps.LatLng(lat, lng, wgt);
                    myData.push(latLng);
                }
                heatmap = new google.maps.visualization.HeatmapLayer({
                    data: myData,
                    radius: getNewRadius(),
                    opacity: 0.3,
                    gradient: gradientNew,
                    map: map
                });
            }
        });
    });
}

function geoError(error) {
    switch (error.code) {
        case error.PERMISSION_DENIED:
            alert("The Heatery Map Requires Your Location for Accurate Results");
            break;
        case error.POSITION_UNAVAILABLE:
            alert("Location information is unavailable.")
            break;
        case error.TIMEOUT:
            alert("The request to get user location timed out.")
            break;
        case error.UNKNOWN_ERROR:
            alert("An unknown error occurred.")
            break;
    }
}

function displayMap(coords) {
    var successPosition = new google.maps.LatLng(coords.latitude, coords.longitude);
    var myOptions = {
        zoom: 14,
        center: successPosition,
        mapTypeId: google.maps.MapTypeId.TERRAIN
    };
    map = new google.maps.Map(document.getElementById("map-canvas"), myOptions);
    google.maps.event.addListener(map, 'zoom_changed', function () {
        heatmap.setOptions({
            radius: getNewRadius()
        });
    });
    google.maps.event.addListener(map, 'click', function (event) {
        heatmap.setMap(null);
        var lat = event.latLng.lat();
        var lng = event.latLng.lng();
        var loc = new google.maps.LatLng(lat, lng);
        var fb = "https://graph.facebook.com/v2.4/search?&q=restaurant&type=place&center=" + loc + "&distance=5000&fields=talking_about_count,location,name&offset=0&limit=5000&access_token=1452021355091002|x-ZB0iKqWQmYqnJQ-wXoUjl-XtY";
        fb = fb.replace(/[()]/g, "");
        $(document).ready(function () {
            $.ajax({
                url: fb,
                dataType: "text",
                cache: true,
                success: function (data) {
                    var restaurantData = $.parseJSON(data);
                    var myData = [];
                    var gradientNew = ["rgba(0,255,255,0)",
"rgba(25, 22, 218, 1)", "rgba(17, 191, 225, 1)", "rgba(16, 227, 217, 1)", "rgba(15, 229, 173, 1)", "rgba(14, 231, 128, 1)", "rgba(13, 233, 82, 1)", "rgba(12, 235, 34, 1)", "rgba(37, 237, 11, 1)", "rgba(85, 239, 10, 1)", "rgba(134, 241, 8, 1)", "rgba(185, 243, 7, 1)", "rgba(237, 245, 6, 1)", "rgba(247, 203, 5, 1)", "rgba(249, 152, 3, 1)", "rgba(251, 100, 2, 1)", "rgba(255, 127, 131, 1)", "rgba(253, 47, 1, 1)", "rgba(255, 0, 7, 1)"
];
                    for (var i = 0; i < restaurantData.data.length; i++) {
                        var lat = restaurantData.data[i].location.latitude;
                        var lng = restaurantData.data[i].location.longitude;
                        var wgt = restaurantData.data[i].talking_about_count;
                        var latLng = new google.maps.LatLng(lat, lng, wgt);
                        myData.push(latLng);
                    }
                    heatmap = new google.maps.visualization.HeatmapLayer({
                        data: myData,
                        radius: getNewRadius(),
                        opacity: 0.3,
                        gradient: gradientNew,
                        map: map
                    });
                }
            });
        });
    });
}

/* Geocodes user input */
$("#locate").click(function (event) {
    geocoder = new google.maps.Geocoder();
    var address = document.getElementById("address").value;
    geocoder.geocode({
        address: address
    }, function (results, status) {
        var addr_type = results[0].types[0];
        if (status == google.maps.GeocoderStatus.OK)
            ShowLocation(results[0].geometry.location, address, addr_type);
        else
            alert("Geocode was not successful for the following reason: " + status);
    });
});

function ShowLocation(latlng, address, addr_type) {
    heatmap.setMap(null);
    map.setCenter(latlng);
    var zoom = 14;
    var fb = "https://graph.facebook.com/v2.5/search?&q=restaurant&type=place&center=" + latlng + "&distance=5000&fields=talking_about_count,location,name&offset=0&limit=5000&access_token=1452021355091002|x-ZB0iKqWQmYqnJQ-wXoUjl-XtY";
    fb = fb.replace(/[()]/g, "");
     $(document).ready(function () {
        $.ajax({
            url: fb,
            dataType: "text",
            success: function (data) {
                var ajaxData = $.parseJSON(data);
                for (var i = 0; i < ajaxData.data.length; i++) {
                    var lat = ajaxData.data[i].location.latitude;
                    var lng = ajaxData.data[i].location.longitude;
                    var name = ajaxData.data[i].name;
                    var street = ajaxData.data[i].location.street;
                    var city = ajaxData.data[i].location.city;
                    var state = ajaxData.data[i].location.state;
                    var zip = ajaxData.data[i].location.zip;
                    var latLng = new google.maps.LatLng(lat, lng);
                    bounds.extend(latLng);
                    var html = '<div id="name">' + name + '</div><p></p>' + '<div id="address"><p>' + street + '<p></p>' + city + ',&nbsp;' + state + '&nbsp;' + zip + '</p></div>';
                    var marker = new google.maps.Marker({
                        position: latLng,
                        map: map,
                        infowindow: html
                    });
                    markers.push(marker);
                    google.maps.event.addListener(marker, 'click', function () {
                        infoWindow.setContent(this.infowindow);
                        infoWindow.open(map, this);
                    });
                    marker.setMap(map);
                    google.maps.event.addListener(map, 'click', function(){
                       infoWindow.close(); 
                    });

                    map.fitBounds(bounds);
                }
            }
        });
    });
    

}

google.maps.event.addDomListener(window, 'load', displayMap);