<?php
/**
* @package Foursquare Heatery
* @version 0.0.1 [January 17, 2016]
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

<?php require( 'functions.php' ); ?>

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
    {{#each .}}{{#if name}}
    <div id="sb-title" class="col-xs-12">
      <span class="glyphicon glyphicon-tag"></span>&nbsp;{{fs_category}}
      <br>
      <span class="glyphicon glyphicon-time"></span>&nbsp;{{#if hours_status}}{{hours_status}}{{else}}&nbsp;No hours posted.{{/if}}
      <hr>
      <span class="glyphicon glyphicon-map-marker"></span>&nbsp;{{cln_name}}
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{address}}
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{city}},&nbsp;{{state}}&nbsp;{{postal}}
      <hr>
      <span class="glyphicon glyphicon-phone-alt"></span>
      &nbsp;{{#if phone}}<a href="tel:{{phone}}">{{phone}}</a>{{else}}&nbsp;No Phone #{{/if}}
      <br>
      <span class="glyphicon glyphicon-home"></span>
      <a href="{{web}}" target="_blank">&nbsp;{{#if web}}{{niceWeb}}{{else}}&nbsp;No Website{{/if}}</a>
      <hr>
      <span class="glyphicon glyphicon-fire"></span>&nbsp;{{fs_rating}}
      <br>
      <span class="glyphicon glyphicon-thumbs-up"></span>&nbsp;{{fs_checkins}}
      <br>
      <span class="glyphicon glyphicon-user"></span>&nbsp;{{fs_users}}
    </div>
    {{/if}}{{/each}}
  </div>
</script>

<!-- EXTERNAL ASSETS -->
<script src="dist/js/lib/jquery.min.js"></script>
<script src="dist/js/lib/googleapis.min.js"></script>
<script src="dist/js/lib/bootstrap.min.js"></script>
<script src="dist/js/lib/jquery-ui.min.js"></script>
<script src="dist/js/lib/handlebars-v4.0.2.js"></script>
<script src="dist/js/main.min.js"></script>
</body>

</html>
