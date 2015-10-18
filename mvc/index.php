<!DOCTYPE html>
<html>

<head>
    <title>Radius Tool for Google Maps</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=false">
    </script>
    <link rel="stylesheet" href="css/style.min.css" />
</head>
<body>
    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="http://www.csq2.com">Circle Squared Data Labs</a>
            </div>
            <div class="collapse navbar-collapse" id="myNavbar">
                <ul class="nav navbar-nav">
                    <li class="active"><a href="#">Radius Tool For Google Maps</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li id="info" style="color:#fff;"></li>
                </ul>
            </div>
        </div>
    </nav>
    <div id="map-canvas"></div>
    <script src="js/main.min.js"></script>
</body>
</html>