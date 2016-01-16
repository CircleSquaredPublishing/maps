<?php
/**
* @package Heatery
* @version 1.0.0 [November 30, 2015]
* @author Will Conkwright
* @copyright Copyright (c) 2016 Circle Squared Data Labs
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
?>

<?php require( 'functions.php' );?>
<body>
    <div id="map-canvas"></div>
    <div class="container" id="main">
        <div class="row">
            <div class="col-xs-4" id="left">
                <div id="info_panel" class="panel panel-default">
                    <div id="info_card" class="panel-heading">
                        <!-- HANDLEBARS TEMPLATE INJECTED HERE -->
                    </div>
                </div>
            </div>
            <div class="col-xs-8"></div>
        </div>
    </div>

    <!-- HANDLEBARS INFOCARD TEMPLATE -->
    <script id="infoCardTemplate" type="text/x-handlebars-template">
        <div class="container-fluid">
                {{#each .}}{{#if cover}}
                <div id="sb-title" class="col-xs-12">
                  {{cln_name}}
                </div>
                <div class="row">
                    <div id="profile_pic" class="col-xs-12">
                        <img src="{{fb_cover}}" data-pin-nopin="true">
                    </div>
                </div>
                <div id="sb-web" class="col-xs-12">
                <a href="{{web}}" target="_blank">{{niceWeb}}</a>
              </div>
                {{/if}}{{/each}}
            </div>
    </script>

    <!-- EXTERNAL ASSETS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?v=3.22&signed_in=true&libraries=visualization,places,geometry"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
    <script src="js/handlebars-v4.0.2.js"></script>
    <script src="js/ajax.js"></script>
</body>

</html>
