/**
* @name Strava App
* @version 1.0.0 [November 30, 2015]
* @author Will Conkwright
* @copyright Copyright (c) 2015 Circle Squared Data Labs
* @license Licensed MIT
*
* Permission is hereby granted, free of charge, to any person obtaining a copy
* of this software and associated documentation files (the "Software"), to deal
* in the Software without restriction, including without limitation the rights
* to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
* copies of the Software, and to permit persons to whom the Software is
* furnished to do so, subject to the following conditions:
*
* The above copyright notice and this permission notice shall be included in
* all copies or substantial portions of the Software.
*
* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
* IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
* FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.  IN NO EVENT SHALL THE
* AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
* LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
* OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
* THE SOFTWARE.
*/

/**
* Reference to the global Google map object.
* @var {map}
* @type {Object}
*/

var map;

/**
* Reference to the location of files containing segment data.
* @var {jsonPath}
* @type {string}
*/

var jsonPath = ('api_responses/response_');

/**
* Strava segment IDs.
* @var {files}
* @type {Array}
*/

var files = [10629665,10630567,10630608,6118824,10630643,10630679];

/**
*  Reference to the location of kml files containing route data.
* @var {src}
* @type {string}
*/

var src = ('https://www.csq2.com/kml/fondeaux.kml');

/**
* Google bounds object.
* Note : Sets the zoom and bounds of the map. No need to set zoom and center in map object.
* @var {bounds}
* @type {Object}
*/

var bounds = new google.maps.LatLngBounds();

/**
* Google infowindow object.
* Note: Global object ensures only one infowindow is displayed at a time.
* @var {infowindow}
* @type {object}
*/

var infowindow  = new google.maps.InfoWindow({
  content : this.contentString
});

// Creates the GOOGLEMAP object.
var GOOGLEMAP = {

  /**
   * initialize function
   */

  initialize : function() {
    var mapOptions  = {
      'mapTypeId'             : google.maps.MapTypeId.SATELLITE,
      'mapTypeControlOptions' : {
          position            : google.maps.ControlPosition.RIGHT_TOP
        }
      }
    map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
    GOOGLEMAP.loadKmlLayer(src, map);
    for( i = 0; file = files[i]; i++ ) {
      this.loadStravaData(file);
    }
  },

  /**
   * loadKmlLayer function
   * @param {string} src  References location of route kml file on server.
   * @param {object} map  References the Google map object.
   * @return {object} google.maps.KmlLayer();
   */

  loadKmlLayer  : function(src,map) {
    var kmlOptions = {
      suppressInfoWindows : true,
      preserveViewport    : true,
      map                 : map
    }
    return new google.maps.KmlLayer(src,kmlOptions);
  },

  /**
   * handlerData function
   */

  handlerData     :   function(json) {
    var source   = $('#infoCardTemplate').html();
    var template = Handlebars.compile(source);
      $.getJSON(jsonPath + file + '.json', function(json) {
        $.each(json, function(key, value) {

          /**
           * speedRound function
           * @return {number} speedRound
           */

          this.speedRound     = function() {
            var distance      = Math.round(value.distance);
            var time          = value.moving_time;
            var distanceMiles = distance / 1620;
            var hours         = time / 3600;
            var speed         = distanceMiles / hours;
            var speedRound    = Math.round(speed);
            return speedRound;
          };

          /**
           * diffDays function
           * @return {number} diffDays
           */

          this.start_date  = Date.parse(value.start_date).format("%b %e, %Y");
          this.diffDays    = function() {
            var start_date = this.start_date;
            var date1      = new Date.parse(start_date);
            var date2      = new Date.today();
            var timeDiff   = Math.abs(date2.getTime() - date1.getTime());
            var diffDays   = Math.ceil(timeDiff / (1000 * 3600 * 24));
            return diffDays
          };

          /**
           * finalTime function
           */

          this.minutes   = Math.floor(value.moving_time / 60);
          this.seconds   = Math.floor(value.moving_time - this.minutes * 60);
          this.finalTime = GOOGLEMAP.stringPadLeft(this.minutes, '0', 2) + ':' + GOOGLEMAP.stringPadLeft(this.seconds, '0', 2);

          /**
           * Create the map markers.
           */

          this.segment    = value.segment_name;
          this.name       = value.athlete_name;
          this.wattsRound = Math.round(value.average_watts);
          this.html       = '<div id="iw-container" class="container-fluid"><div id="iw-details" class="row"><div id="iw-title" class="col-xs-12">'+ this.segment +'</div><div id="iw-content"><p>'+'&nbsp;&nbsp;'+this.name +' is the segment leader.</p></div></div>';
          this.lat        = value.start_latitude;
          this.lng        = value.start_longitude;
          this.marker     = new google.maps.Marker({
              position : new google.maps.LatLng(this.lat, this.lng),
              map      : map,
              content  : this.html
            });
          bounds.extend(this.marker.getPosition());
          map.fitBounds(bounds);

          /**
           * Compile Handlebars template and append to #info_card element.
           */

          this.output     = template(json);
          this.entry      = $('#info_card').append(this.output);

          /**
           * Add listener to markers that sets content and opens infowindow.
           */

          google.maps.event.addListener(this.marker, 'click', function (){
            infowindow.setContent(this.content);
            infowindow.open(map,this);
          });

          /**
           * Add listener to markers that closes infowindow.
           */

          google.maps.event.addDomListener(map, 'click', function(){
            infowindow.close();
          });

        });
      });
    },

  /**
   * stringPadLeft function
   * @return {string}
   */

  stringPadLeft : function(string, pad, length) {
    return (new Array(length + 1).join(pad) + string).slice(-length);
  },

  /**
   * loadStravaData function
   */

  loadStravaData  : function(file) {
      $.ajax({
        type  : 'POST',
        async : false,
        url   : 'api_call.php',
        data: ( {key : file} ),
        success: this.handlerData
        });
      }
    }

    google.maps.event.addListener(infowindow, 'domready', function (){
      var iwOuter       = $('.gm-style-iw');
      var iwBackground  = iwOuter.prev();
      var iwCloseBtn    = iwOuter.next();
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

/**
 * Call to the initialize function.
 */

$(document).ready(function(){
  GOOGLEMAP.initialize();
});
