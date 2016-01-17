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

<!-- @begin navbar -->
<nav id="hm_navbar_top" class="navbar navbar-default navbar-fixed-top">
  <div id="hm_navbar_container" class="container-fluid">
    <div id="hm_navbar_header" class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#hm_navbar_collapse" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
    </div>
    <div id="hm_navbar_collapse" class="collapse navbar-collapse">
      <ul class="nav navbar-nav navbar-left">

        <!-- @begin form -->
        <form id="gc-form" class="navbar-form navbar-left" role="search" method="post" action="">
          <button id="btn-find" type="submit" class="btn btn-default" name="btn-submit"><span class="glyphicon glyphicon-search"></span></button>
          <div id="gc-input" class="form-group">
            <input id="gc-search-box" name="address" type="text" class="form-control" placeholder="Your Hot Spot.">
          </div>
        </form>
        <!-- @end form -->

      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li>
          <a href="https://www.csq2.com">
            <span class="glyphicon glyphicon-link"></span>&nbsp;Circle Squared Data Labs
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>
<!-- @end navbar -->
