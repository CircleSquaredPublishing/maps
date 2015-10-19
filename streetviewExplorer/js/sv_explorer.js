function initialize() {
    var myLatlng = new google.maps.LatLng(35.1063135,-77.038236);
    var myOptions = {
        zoom: 18,
        center: myLatlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        streetViewControl: false
    }
    var map = new google.maps.Map(document.getElementById("map-canvas"), myOptions);
    var contentString = '<div id="iwsw" class="container-fluid"></div>';
    var infowindow = new google.maps.InfoWindow({
        content: contentString
    });
    var marker = new google.maps.Marker({
        position: myLatlng,
        map: map,
        draggable: true,
        animation: google.maps.Animation.DROP,
    });
    google.maps.event.addListener(marker, 'click', function () {
        infowindow.open(map, marker);
    });
    var pano = null;
    var panoOptions = {
        position: myLatlng,
        pov: {heading: 64.7,pitch: -3,zoom: 0
        },
        linksControl:false
    };
    google.maps.event.addListener(infowindow, 'domready', function () {
        if (pano != null) {
            pano.unbind("position");
            pano.setVisible(false);
        }
        pano = new google.maps.StreetViewPanorama(document.getElementById("iwsw"),panoOptions, {
            navigationControl: true,
            navigationControlOptions: {
                style: google.maps.NavigationControlStyle.SMALL
            },

        });
        pano.bindTo("position", marker);
        pano.setVisible(true);
        var iwOuter = $('.gm-style-iw');
        var iwBackground = iwOuter.prev();
        iwBackground.children(':nth-child(2)').css({
            'display': 'none'
        });
        iwBackground.children(':nth-child(4)').css({
            'display': 'none'
        });
        iwBackground.children(':nth-child(3)').find('div').children().css({
            'box-shadow': '#000 0px 1px 6px',
            'z-index': '1'
        });
        var iwCloseBtn = iwOuter.next();
        iwCloseBtn.css({
            opacity: '0',
            right: '55px',
            top: '5px',
            border: '1px solid #000',
        });
    });
    google.maps.event.addListener(infowindow, 'closeclick', function () {
        pano.unbind("position");
        pano.setVisible(false);
        pano = null;
    });
    google.maps.event.addListener(map, 'click', function () {
        infowindow.close();
    });
}
google.maps.event.addDomListener(window, 'load', initialize);