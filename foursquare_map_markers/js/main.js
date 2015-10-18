var map, geocoder;
var infoWindow = new google.maps.InfoWindow();
var markers = [];

function clearOverlays() {
    if (markers.length != 0) {
        for (var i = 0; i < markers.length; i++) {
            markers[i].setMap(null);
        }
    }
}

function initialize() {
    var myOptions = {
        zoom: 14,
        center: new google.maps.LatLng(35.1092, -77.0692),
        mapTypeId: google.maps.MapTypeId.TERRAIN
    };
    map = new google.maps.Map(document.getElementById("map-canvas"), myOptions);
}



$("#locate").click(function (event) {
    geocoder = new google.maps.Geocoder();
    $("#foursquareOption").change(function(){
        var selectedText = $(this).find("option:selected").text();
        var address = document.getElementById("address").value;
    geocoder.geocode({address: address},
    function (results, status) {
        var addr_type = results[0].types[0];
        if (status == google.maps.GeocoderStatus.OK)
            ShowLocation(results[0].geometry.location, address, addr_type, selectedText);
        else
            alert("Geocode was not successful for the following reason: " + status);
        });
    });
});

function ShowLocation(latlng, address, addr_type, selectedText) {
    clearOverlays();
    map.setCenter(latlng);
    var fb = "https://api.foursquare.com/v2/venues/explore?v=20150918&ll=" + latlng + "&radius=3200&section="+selectedText+"&limit=50&novelty=new&client_id=LUDUFON05OQ3US4C0FT0TEKWXKSD0NHIPVGKF0TGUZGY4YUR&client_secret=F2E3NQTQKY3WS1APVGFVA31ESHW2ONNPVNJ11NPYVBV05W2I";
    console.log(selectedText);
    fb = fb.replace(/[()]/g, "");
    $(document).ready(function () {
        var bounds = new google.maps.LatLngBounds();
        $.getJSON(fb, function (json) {
            $.each(json.response.groups, function (key, value) {
                $.each(value.items, function (key2, value2) {
                    var name = value2.venue.name;
                    var lat = value2.venue.location.lat;
                    var lng = value2.venue.location.lng;
                    var address = value2.venue.location.address;
                    var city = value2.venue.location.city;
                    var state = value2.venue.location.state;
                    var zip = value2.venue.location.postalCode;
                    var phone = value2.venue.contact.formattedPhone;
                    var latLng = new google.maps.LatLng(lat, lng);
                    var marker = new google.maps.Marker({
                        position: latLng,
                        map: map,
                        title: name
                    });
                    markers.push(marker);
                    bounds.extend(marker.getPosition());
                    var html = '<div id="name">' + name + '</div><p></p>' + '<div id="address"><p>' + address + '<p></p>' + city + ',&nbsp;' + state + '&nbsp;' + zip + '</p></div>';
                    infoWindow = new google.maps.InfoWindow({
                        content: html,
                        maxWidth: 350
                    });
                    google.maps.event.addListener(marker, 'click', function () {
                        infoWindow.setContent(html);
                        infoWindow.open(map, this);
                    });
                    google.maps.event.addListener(map, 'click', function () {
                        infoWindow.close();
                    });
                });
                map.fitBounds(bounds);
                map.panToBounds(bounds);
            });
        });
    });
}
google.maps.event.addDomListener(window, 'load', initialize);