<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3pro.css">
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.3/leaflet.css" data-require="leaflet@0.7.3" data-semver="0.7.3" />
        <link rel="stylesheet" href="css/styles.css" />
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

            .solid {border-style: solid;}
    
            .center {
                position: absolute;
                right: 50px;
                top: 500px;
                margin: auto;
                text-align: center;
                width: 20%;
                border: 3px solid #73AD21;
                padding: 10px;}

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
            
            .jumbotron h2 {
                color: black;
                font-family: 'Shift', sans-serif;
                font-weight: bold;}

            .jumbotron h3 {
                font-family: 'Shift', sans-serif;
                font-size: 15px;
                font-weight: bold;}    
        </style>
    </head>
    <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.3/leaflet.js" data-require="leaflet@0.7.3" data-semver="0.7.3"></script>
    <script>
        //allazei to roloi tou admin seiriaka h' me bhma 15 leptwn
        function changestep() {
            var selectBox = document.getElementById("selectBox");
            var selectedValue = selectBox.options[selectBox.selectedIndex].value;
            if (selectedValue == 1) {
                document.getElementById("myTime").step = "60";
            } else {
                document.getElementById("myTime").step = "900";
            }
        }
    </script>
<?php include("includes/header.php") ?>
<?php include 'dbh.php' ?>
<?php include("includes/nav.php") ?>
        <!-- otan dhmiourghthei to arxeio ths exomoiwshs me ajax call xrwmatizei katallhla ton xarth -->
        <script type = "text/javascript" >
            async function doc_wait() {
                $.ajax({
                    type: 'HEAD',
                    url: 'Exomoiwsh.json',
                    success: function() {
                        $.getJSON("Exomoiwsh.json", function(data) {
                            var datalayer = L.geoJson(data, {
                            onEachFeature: function(feature, featureLayer) {
                                featureLayer.bindPopup(feature.properties.Name);
                            },
                            style: function(feature) {
                                switch (feature.properties.Color) {
                                    case 'red':
                                        return {
                                            color: "#ff0000"
                                        };
                                    case 'yellow':
                                        return {
                                            color: "#ffff00"
                                        };
                                    case 'green':
                                        return {
                                            color: "#02af00"
                                        };
                                }
                            }
                            }).addTo(map);
                            map.fitBounds(datalayer.getBounds());
                        });
                    },
                    error: function() {
                        alert('Page not found.');
                    }
                });
            }
        </script>
        <div class = "jumbotron" >
            <div class="container">
                <center>
                    <h2>Διαχείριση παρόδιας στάθμευσης</h2>
                    <br>
                </center>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <form method="post">
                        <h3>Επιλογή KML Αρχείου</h3>
                        <input type="file" name="myFile" id="file" accept=".KML">
                        <br>
                        <input type="submit"  class="w3-btn w3-blue-grey" value="Υποβολή">
                        <br>
                        <br>
                    </form>
                </div>
                <div class="col-md-4">
                    <form method="post">
                        <h3>Επιλογή Ώρας</h3>
                        <select id="selectBox"  onchange="changestep();">
                        <option value="1">Χειροκίνητα</option>
                        <option value="2">Ανά 15 λεπτά</option>
                        <input type="time" class="solid" id="myTime" name="timecheck" step="60">
                        <br>
                        <br>
                        <button class="w3-btn w3-dark-grey" type="submit" name="Start" id="start" position="center" >Έναρξη Προσομοίωσης</button>
                        </select>
                    </form>
                </div>
                <div class="col-md-4">
                    <form method="post">
                        <h3>Προσομοίωση ανά μία Ώρα</h3>
                        <button class="w3-btn w3-dark-grey" type="submit" name="Before" id="before" position="center">1 Ώρα Νωρίτερα</button>
                        <button class="w3-btn w3-dark-grey" type="submit" name="After" id="after" position="center">1 Ώρα Αργότερα</button>
                        <br>
                    </form>
                </div>
                <div class="col-md-4">
                    <br>
                    <form method="post">
                        <button class="w3-btn w3-blue-grey" name="DELETE" onclick="return confirm('Είστε σίγουροι ότι θέλετε να διαγράψετε τα δεδομένα του KML;')" > Διαγραφή των Δεδομένων του KML</button> 
                        <br><br>
                        <div id="timeprint"></div>
                    </form>
                </div>
            </div>
        </div>
        <center><div id="map_content"> <div id="map_canvas"> <div id="mapid" style="position: center; top: -10px; width: 1150px; height: 400px;"></div></div></div></center>    
<?php
    //otan epilexthei to kml arxeio gemizoume thn vash kai dhmiourgoume to json arxeio
    if(isset($_POST['myFile']) && $_POST['myFile'] != '') {
        $filename = $_POST['myFile'];
        $ext = end((explode(".",$filename)));
        if($ext!="kml"){
            echo "<script>alert('Λάθος τύπος αρχείου');</script>";
            header("Refresh:0");
        }
        function getAreaOfPolygon($ring, $marks)
        {
            $area = 0;
            for ($vi = 0, $vl = sizeof($marks); $vi < $vl; $vi++) {
                $thisx = $ring[$vi][0];
                $thisy = $ring[$vi][1];
                $nextx = $ring[($vi + 1) % $vl][0];
                $nexty = $ring[($vi + 1) % $vl][1];
                $area += ($thisx * $nexty) - ($thisy * $nextx);
            }
            $area = abs(($area / 2));
            return $area;
        }

        function getCentroidOfPolygon($ring, $marks){
            $cx = 0;
            $cy = 0;
            for ($vi = 0, $vl = sizeof($ring); $vi < $vl; $vi++) {
                $thisx = $ring[$vi][0];
                $thisy = $ring[$vi][1];
                $nextx = $ring[($vi + 1) % $vl][0];
                $nexty = $ring[($vi + 1) % $vl][1];
                $p = ($thisx * $nexty) - ($thisy * $nextx);
                $cx += ($thisx + $nextx) * $p;
                $cy += ($thisy + $nexty) * $p;
            }
            $area = getAreaOfPolygon($ring, $marks);
            $cx   = -$cx / (6 * $area);
            $cy   = -$cy / (6 * $area);
            return array(
                $cx,
                $cy
            );
        }

        ini_set('max_execution_time', 300);
        $coordinates = array();
        $names       = array();
        $populations = array();
        $dom         = new DOMDocument();
        $dom->load($_POST['myFile']);
        $plc = $dom->getElementsByTagName("Placemark");
        foreach ($plc as $placemark) {
            $crd           = $placemark->getElementsByTagName("coordinates");
            $coordinates[] = $crd[1]->nodeValue;
            $nm            = $placemark->getElementsByTagName("name");
            $names[]       = $nm[0]->nodeValue;
            $ppl           = $placemark->getElementsByTagName("description");
            $entities1     = $ppl[0]->nodeValue;
            $entities      = html_entity_decode($entities1);
            $doc           = new DOMDocument();
            $doc->loadHTML($entities);
            $xpath     = new DOMXpath($doc);
            $classname = "atr-value";
            $a         = $xpath->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");
            error_reporting(0);
            $populations[] = $a[2]->nodeValue;
            error_reporting(1);
        }
        for ($x = 0; $x < count($names); $x++) {
            $nam   = $names[$x];
            $pop   = $populations[$x];
            $coord = $coordinates[$x];
            $nam   = mysqli_real_escape_string($conn, $nam);
            $coord = mysqli_real_escape_string($conn, $coord);
            $pop   = mysqli_real_escape_string($conn, $pop);
            $marks = explode(" ", $coord);
            //  To marks exei mesa sintetagmenes sthn morfh [(x,y),(x,y),(x,y)]
            for ($k = 0; $k < count($marks); $k++) {
                $splitxy     = explode(",", $marks[$k]);
                $ring[$k][0] = $splitxy[0];
                $ring[$k][1] = $splitxy[1];
            }
            $centeroid = getCentroidOfPolygon($ring, $marks);
            $cent      = $centeroid[0] . "," . $centeroid[1];
            unset($ring);
            $query_check    = "SELECT * FROM kml WHERE name='{$nam}'";
            $result_login   = mysqli_query($conn,$query_check);
            $anything_found = mysqli_num_rows($result_login);
            if($anything_found > 0) {
                $message = "Το KML αρχείο έχει ήδη φορτωθεί";
                echo "<script type='text/javascript'>alert('$message');</script>";
                break;
            }
            else{
                $sql_insert = "INSERT INTO kml (name, population, parkseats , alloc , centeroid, coordinates) VALUES ('$nam' , '$pop' , '100' , '1' , '$cent' , '$coord')";
                if (mysqli_query($conn, $sql_insert)) {}
                else{
                    echo "ERROR: Could not able to execute $sql_insert. " . mysqli_error($conn);
                }
            }
        }
        
        //dhmiourgia tou json

        $sql      = "select * from kml";
        $response = array();
        $posts    = array();
        $result   = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
            $name      = $row['name'];
            $coord     = $row['coordinates'];
            $marks     = explode(" ", $coord);
            $centeroid = $row['centeroid'];
            $center    = explode(",", $centeroid);
            for ($k = 0; $k < count($marks); $k++) {
                $splitxy     = explode(",", $marks[$k]);
                $ring[$k][0] = floatval($splitxy[0]);
                $ring[$k][1] = floatval($splitxy[1]);
            }
            $features[] = array(
                'type' => 'Feature',
                'properties' => array(
                    'Name' => $name
                ),
                'geometry' => array(
                    'type' => 'GeometryCollection',
                    'geometries' => array(
                        array(
                            'type' => 'Polygon',
                            'coordinates' => array(
                                $ring
                            )
                        )
                    )
                )
            );
            unset($ring);
        }
        $response['features'] = $features;
        $fp = fopen('results.json', 'w');
        fwrite($fp, json_encode($response, JSON_PRETTY_PRINT));
        fclose($fp);
    }
    //adeiasma ths vashs, katharisma tou xarth kai diagrafh twn arxeiwn json
    if (isset($_POST['DELETE'])) {
        $sql = "DELETE FROM kml";
        if (mysqli_query($conn, $sql)) {} 
        else {
            echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
        }
        $filename1 = 'results.json';
        $filename2 = 'Exomoiwsh_user.json';
        $filename3 = 'Exomoiwsh.json';
        if(file_exists($filename1)){
            unlink('results.json');
        }
        if(file_exists($filename2)){
            unlink('Exomoiwsh_user.json');
        }
        if(file_exists($filename3)){
            unlink('Exomoiwsh.json');
        }
        echo "<script>polygonGroup.clearlayers();</script>";
    }
    //ektelei thn exomoiwsh gia thn epilegmenh wra kathws kai gia mia wra meta h' prin antistoixa
    if (isset($_POST['Start']) && file_exists('results.json')) {
        $is_time  = $_POST['timecheck'];
        $timecheck = $_POST['timecheck'];
        $is_time  = intval($is_time);
        include 'json_create_admin.php';
        echo "
            <script type=\"text/javascript\">
            var e = document.getElementById('timeprint').innerHTML = 'Πραγματοποιείται προσομοίωση για ώρα : $timecheck ';
            </script>
        ";
        $_SESSION["is_time"]= $is_time;
            
    }
    if (isset($_POST['Before']) && file_exists('results.json')) {
        $is_time = $_SESSION["is_time"];
        if($is_time > 0){
            $is_time  = $is_time-1;
        }
        else $is_time = 23;
        include 'json_create_admin.php';
        echo "
            <script type=\"text/javascript\">
            var e = document.getElementById('timeprint').innerHTML = 'Πραγματοποιείται προσομοίωση για ώρα : $is_time ';
            </script>
        ";
        $_SESSION["is_time"]= $is_time;
    }

    if (isset($_POST['After']) && file_exists('results.json')) {
        $is_time = $_SESSION["is_time"];
        if($is_time == 23){
            $is_time = 0;
        }
        else $is_time  = $is_time +1;
        include 'json_create_admin.php';
        echo "
            <script type=\"text/javascript\">
            var e = document.getElementById('timeprint').innerHTML = 'Πραγματοποιείται προσομοίωση για ώρα : $is_time ';
            </script>
        ";
        $_SESSION["is_time"]= $is_time;
    }
?>
    <script>
        function onEachFeature(feature, layer) {
            if (feature.properties && feature.properties.popupContent) {
                layer.bindPopup(feature.properties.popupContent);
            }
        }
        //arxikopoihsh tou xarth
        var map = L.map('mapid').setView([40.65459689980922, 22.9119873046875], 5);
        L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
        maxZoom: 18,
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
            '<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
            'Imagery     © <a href="https://www.mapbox.com/">Mapbox</a>',
        id: 'mapbox.streets'
        }).addTo(map);
        //prosthiki polugwnwn apo to arxeio json ston xarth
        var polygonGroup = L.layerGroup().addTo(map);
        $.getJSON("results.json", function(data) {
        var datalayer = L.geoJson(data, {
            onEachFeature: function(feature, featureLayer) {
                featureLayer.bindPopup('<form>ID Mπλοκ:<br><input style="table-layout: fixed; width: 110%" type ="text" name ="bname" value="' + feature.properties.Name + '" disabled><br>Αριθμός Θέσεων Πάρκινγκ:<br><input style="table-layout: fixed; width: 30%"type="number"name="parkseats"><br>Καμπύλη ζήτησης τετραγώνου:<br><input type="radio" name="demand" value="1" checked> 1(Center)<br><input type="radio" name="demand" value="2"> 2(Suburb)<br><br><br><input type="button" value="Submit" onclick="sendForm()"></form>');
            }
        }).addTo(polygonGroup);
        datalayer.setStyle({
            color: 'grey'
        });
        map.fitBounds(datalayer.getBounds());
        });
        //h sunarthsh pou kaleitai apo thn forma tou admin gia kathe polugwno
        function sendForm() {
            var Name = document.getElementsByName("bname")[0].value;
            var Seats = document.getElementsByName("parkseats")[0].value;
            var radios = document.getElementsByName("demand");
            var Demand;
            if (radios[0].checked) {
                Demand = radios[0].value;
            } else {
                Demand = radios[1].value;
            }
            //update ths vashs
            $.post('action_page.php', {
                seats: Seats,
                demand: Demand,
                bname: Name
                },
                function(data) {
                $('#test').html(data);
                }
            );
        }
</script>
<div id="test"></div>
<?php include("includes/footer.php") ?>