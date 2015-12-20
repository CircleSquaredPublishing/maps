var GEOLOCATE = {
  getLocation : function() {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(GEOLOCATE.initialize, GEOLOCATE.error, {
        maximumAge          : 30000,
        timeout             : 5000,
        enableHighAccuracy  : false
      });
    } else {
      alert("Geolocation is not supported by your browser.");
    }
  },
  initialize : function(position){
    var lat     = position.coords.latitude;
    var lng     = position.coords.longitude;
    var center  = new google.maps.LatLng(lat, lng);
    var pano    = null;
    var map         = new google.maps.Map(document.getElementById("map-canvas"), {
      zoom      : 14,
      center    : center,
      mapTypeId : google.maps.MapTypeId.TERRAIN
    });

    var marker = new google.maps.Marker({
      position  : center,
      map       : map,
      title     : (lat + ', ' + lng),
      icon      : 'https://mt.googleapis.com/vt/icon/name=icons/spotlight/spotlight-waypoint-b.png&text=A&psize=16&font=fonts/Roboto-Regular.ttf&color=ff333333&ax=44&ay=48'
    });
  },
  error : function(error){
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
}

$(document).ready(function(){
  GEOLOCATE.getLocation();
});
