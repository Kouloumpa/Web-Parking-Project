<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3pro.css">
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.3/leaflet.css" data-require="leaflet@0.7.3" data-semver="0.7.3" />
        <link rel="stylesheet" href="css/styles.css" />
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <style>
        #map_content {
            padding: 0px;
            width: 100%;
            height: 100%;
            overflow: hidden;}
        
        #map_canvas {
            width: 100%;
            height: 100%;
            padding: 0;
            text-shadow: none;}
        
        div.b {
            position: center;}
        
        .solid {border-style: solid;}
        
        .jumbotron {
            padding: 30px;
            padding: 8rem 1rem;
            margin-bottom: 2rem;
            background-color: #e9ecef;
            border-radius: 1rem;
            background: -moz-linear-gradient(top, rgba(30,87,153,1) 0%, rgba(30,87,153,0.54) 46%, rgba(125,185,232,0.18) 82%, rgba(125,185,232,0) 100%); /* FF3.6-15 */
            background: -webkit-linear-gradient(top, rgba(30,87,153,1) 0%,rgba(30,87,153,0.54) 46%,rgba(125,185,232,0.18) 82%,rgba(125,185,232,0) 100%); /* Chrome10-25,Safari5.1-6 */
            background: linear-gradient(to bottom, rgba(30,87,153,1) 0%,rgba(30,87,153,0.54) 46%,rgba(125,185,232,0.18) 82%,rgba(125,185,232,0) 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
            filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#1e5799', endColorstr='#007db9e8',GradientType=0 ); /* IE6-9 */}

        .jumbotron .container {
            position: relative;}
    
        .jumbotron h1 {
            color: #fff;
            font-size: 48px;
            font-family: 'Shift', sans-serif;
            font-weight: bold;}
        
        .jumbotron p {
            font-size: 20px;
            color: #393c3d;}

    </style>
<?php include("includes/header.php"); ?>
<?php include("includes/nav.php"); ?>
<?php include 'dbh.php'; ?>

  <div class="jumbotron">
        <div class="container">
            <center>
                <h1>Παρκάρισμα με ένα...κλικ!</h1>
                <p>Βρείτε τη θέση parking που ταιριάζει στις ανάγκες σας εύκολα.</p>
            </center>
            <br>
            <br>
        </div>
        <form  method="post">
            <div align="center"><b>Επιλέξτε Ώρα:</b><label for="timecheck"></label>
                <input type="time"  class="solid" id="myTime" name="timecheck" step="60">
                <button  type="submit" class="w3-btn w3-indigo"  name="Start" id="start" position="center">Υποβολή</button>
            </div>
        </form>
  </div>       
<?php
    //epilogh trexousas wras
    $timezone = "Europe/Helsinki";
    date_default_timezone_set($timezone);
    $is_time = date('H');
    if (isset($_POST['Start'])) {
        $is_time = $_POST['timecheck'];
        $is_time = intval($is_time);
    }
    $sql_is_empty   = "select * from kml";
    $result_check   = mysqli_query($conn, $sql_is_empty);
    $anything_found = mysqli_num_rows($result_check);
    if($anything_found > 0 ){
        //to json_create.php pairnei thn metablhth $is_time kai dhmiourgei ena json apo thn vash gia thn sugekrimenh metablhth
        include 'json_create.php';
    }
    else{
        echo "Δεν επιλέχθηκε KML αρχείο";
    }
?>
    <center><div id="map_content"><div id="map_canvas"><div id="mapid" style="position: center; top: -10px; width: 1150px; height: 400px;"></div></div></div></center>
<script>
    function onEachFeature(feature, layer) {
        if (feature.properties && feature.properties.popupContent) {
        layer.bindPopup(feature.properties.popupContent);
        }
    }
    var map       = L.map('mapid').setView([40.65459689980922, 22.9119873046875],5);
    var theMarker = {};
    var thecircle = {};
    map.on('click',function(e){
        lat = e.latlng.lat;
        lon = e.latlng.lng;
        //point
        if (theMarker != undefined) {
              map.removeLayer(theMarker);
              map.removeLayer(thecircle);
        };
        theMarker = L.marker([lat,lon]).addTo(map).bindPopup('<form><u><b>Αναζήτηση προτάσεων περιοχής στάθμευσης</b></u><br><br>Μέγιστη Ακτίνα (σε μέτρα):<br><input style="table-layout: fixed; width: 100%"type="number" name="aktina"><br><input type="button" value="Submit" onclick="find_suggestion(lat,lon)"></form>')
        .openPopup();
    });
    L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
        maxZoom: 18,
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
          '<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
          'Imagery     © <a href="https://www.mapbox.com/">Mapbox</a>',
        id: 'mapbox.streets'
    }).addTo(map);
    //xrwmatismos xarth gia epilegmenh wra apo ton xrhsth
    $.getJSON("Exomoiwsh_user.json",function(data){
        var datalayer = L.geoJson(data ,{
            onEachFeature: function(feature, featureLayer) {
            },
            style: function(feature) {
                switch (feature.properties.Color) {
                    case 'red':     return {color: "#ff0000"};
                    case 'yellow':  return {color: "#ffff00"};
                    case 'green':   return {color: "#02af00"};
                }
            }
        }).addTo(map);
        map.fitBounds(datalayer.getBounds());
    });
// h sunarthsh pou kaleitai mesw ths formas tou xarth    
function find_suggestion(lat,lon){
        map.removeLayer(thecircle);
        var Radius = document.getElementsByName("aktina")[0].value;
        thecircle  = L.circle([lat, lon], {
            color: 'orange',
            fillColor: '#f03',
            fillOpacity: 0.5,
            radius: Radius
        }).addTo(map);
    <?php
        $i=0;
        $Center     = array();
        $sql        = "select * from kml";
        $result     = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
            $centeroides    = $row['centeroid'];
            $center         = explode(",", $centeroides);
            $Center[$i][0]  = $center[0];
            $Center[$i][1]  = $center[1];
            $i              = $i+1;
        }
    ?>
    //to pol periexei ola ta kentroeidh ths vashs
    var pol = <?php echo json_encode($Center); ?>;
    var x   = <?php echo $i ?>;
    var i;
    var obj = [];
    //pairnoume tis plhrofories gia kathe polugwno
    <?php
        $string     = file_get_contents("Exomoiwsh_user.json");
        $json       = json_decode($string);
        $block_name = array();
        $occupied   = array();
        $total_seats= array();
        $free_seats = array();
        $max_length = (sizeof($json->features));
        for ($i = 0 ; $i < $max_length ; $i++){
            $block_name[$i]  = $json->features[$i]->properties->Name;
            $occupied[$i]    = $json->features[$i]->properties->Occupied;
            $total_seats[$i] = $json->features[$i]->properties->TotalSeats;
            $free_seats[$i]  = $total_seats[$i] - $occupied[$i];
            if( $free_seats[$i] < 0)
                $free_seats[$i]=0;
        }
    ?>
    var number_of_free_seats = <?php echo json_encode($free_seats); ?>;
    var randomGeoPoints      =[]; //periexei ola ta tuxaia shmeia enos polugwnou pou einai entos tou kuklou
    for (i = 0; i < x; i++) {
        //elegxoume kathe kentroeides an einai entos ths aktinas mesw ths sunarthshs distance
        var polygon_long = pol[i][0];
        var polygon_lat  = pol[i][1];
        var Distance     = distance(lat,lon,polygon_lat,polygon_long);
        if(Distance <= Radius){
            randomGeoPoints.push(generateRandomPoints({'lat':polygon_lat, 'lng':polygon_long}, 50, number_of_free_seats[i]));
        }
    }
    var total_points= []; //periexei ola ta tuxaia shmeia pou dhmiourgountai se aktina 50 metrwn apo kathe kentroeides analoga me tis eleutheres theseis
    for(i=0; i<randomGeoPoints.length; i++){
        total_points = total_points.concat(randomGeoPoints[i]);
    }
    var test          = distanceMatrix(randomGeoPoints);
    var myJsonString  = JSON.stringify(test.upper_matrix);
    var myJsonString1 = JSON.stringify(test.point_ids);
    var leng          = 0;
    var maxcluster    = []; //periexei ta megista se aritmo shmeiwn clusters
    //to clusters periexei ola ta clusters pou epistrefei o DBSCAN
    var clusters      = $.post('DBSCAN.php' , {upper_matrix: myJsonString , point_ids: myJsonString1} , function(result){
        var clusters      = JSON.parse(result);
        var leng          = 0;
        var sintetagmenes = []; //periexei tis suntetagmenes twn shmeiwn twn max clusters
        var maxcluster    = [];
        for(i = 0 ; i < clusters.length - 1 ; i++){
            if(clusters[i].length > leng) {
                leng = clusters[i].length;
            }
        }
        for(i = 0 ; i < clusters.length - 1 ; i++){
            if (clusters[i].length == leng){
                maxcluster.push(clusters[i]);
            }
        }
        maxcluster.forEach(function(element , index , array){
            var x = [];
            element.forEach(function(element2d , index2d , array2d){
                x.push(total_points[element2d]);
            })
            sintetagmenes[index] = x;
        });
        var polygon = []; //periexei tis sunttetagmenes kathe shmeiou kathe cluster
        for(i = 0 ; i<sintetagmenes.length ; i++){
            var y = [];
            for(j=0 ; j<sintetagmenes[i].length ; j++){
                var testt = new Array(sintetagmenes[i][j].lat , sintetagmenes[i][j].lng);
                y.push(testt);
            }
            polygon[i]= y;
        }
        var centeroids = [];
        var distances  =[];
        for(i = 0; i < polygon.length ; i++){
            //upologizoume to kentroeides kathe megistou cluster 
            var x = d3.polygonCentroid(polygon[i]);
            centeroids[i] = x;
            lat1 = centeroids[i][0];
            lon1 = centeroids[i][1];
            diiii = distance(lat1 , lon1 , lat , lon);
            distances.push(diiii);
        }
        //dhmiourgoume to json tou xrhsth pou periexei ths suntetagmenes kai thn apostash kathe kentroeidous kathe max cluster apo to point pou orise o xrhsths
        var geojson = {};
        geojson['type'] = 'FeatureCollection';
        geojson['features'] = [];

        for (var k in centeroids) {
            var newFeature = {
                "type": "Feature",
                "geometry": {
                    "type": "Point",
                    "coordinates": centeroids[k],
                    "distance": distances[k]
                }
        };
        geojson['features'].push(newFeature);
        }
        var marker;
        var layerGroup = L.layerGroup().addTo(map);
        map.on('click', function (e) {
            layerGroup.clearLayers();
        }
        );
            for(i = 0 ; i < centeroids.length ; i++){
                marker = new L.Marker([geojson.features[i].geometry.coordinates[0],geojson.features[i].geometry.coordinates[1]]).addTo(layerGroup);
                var polcoords = new Array(); polcoords.push([geojson.features[i].geometry.coordinates[0],geojson.features[i].geometry.coordinates[1]]);
                polcoords.push([lat , lon]);
                line = new L.polyline(polcoords).addTo(layerGroup);
            }
            var userJSON = $.post('userJSONcreate.php' , {geojson: geojson}, function(result){
            });
    });
}


function generateRandomPoints(center, radius, count) {
    var points = [];
    for (var i=0; i<count; i++) {
        points.push(generateRandomPoint(center, radius));
    }
    return points;
}


function generateRandomPoint(center, radius) {
    var x0   = center.lng;
    var y0   = center.lat;
    var rd   = radius/111300;
    var u    = Math.random();
    var v    = Math.random();
    var w    = rd * Math.sqrt(u);
    var t    = 2 * Math.PI * v;
    var x    = w * Math.cos(t);
    var y    = w * Math.sin(t);
    var xp   = x/Math.cos(y0);
    var ytot = +y + +y0;
    var xtot = +xp + +x0;
    return {'lat': ytot, 'lng': xtot};
}

function distance(lat1,lon1,lat2,lon2) {
	var R      = 6371;
	var dLat   = (lat2-lat1) * Math.PI / 180;
	var dLon   = (lon2-lon1) * Math.PI / 180;
	var a      = Math.sin(dLat/2) * Math.sin(dLat/2) + Math.cos(lat1 * Math.PI / 180 ) * Math.cos(lat2 * Math.PI / 180 ) * Math.sin(dLon/2) * Math.sin(dLon/2);
	var c      = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
	var d      = R * c;
    if (lat1 == lat2 && lon1 == lon2) return 0;
    return Math.round(d*1000);
	return d;
}
//pairnei san orisma ton pinaka me ta tuxaia shmeia kai epistrefei ton anw trigwniko pinaka apostasewn kai ton pinaka shmeiwn
function distanceMatrix(randomGeoPoints){
    var dianisma = [];
    randomGeoPoints.forEach(function(element , index , array){
        element.forEach(function(element2d , index2d , array2d){
            dianisma.push({'lat':element2d.lat, 'lng':element2d.lng});
        })
    });
    var size=dianisma.length;
    var DistanceMatrix= new Array(size);
    for(var k = 0; k < DistanceMatrix.length; k++){
        DistanceMatrix[k] = new Array(size);
    }
    for (var i = 0; i < size; i++) {
        for (var j = 0; j < size; j++){
            DistanceMatrix[i][j] = distance(dianisma[i].lat,dianisma[i].lng,dianisma[j].lat,dianisma[j].lng);
        }
    }
    var point_ids = [];
    for(var i = 0 ; i < DistanceMatrix.length ; i++){
        point_ids[i] = String(i);
    }
    return { upper_matrix: DistanceMatrix , point_ids: point_ids };
}
</script>
<?php    include("includes/footer.php"); ?>