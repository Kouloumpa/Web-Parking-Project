<?php
$sql      = "select * from kml";
$response = array();
$posts    = array();
$result   = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_assoc($result)) {
    $name      = $row['name'];
    $park      = $row['parkseats'];
    $alloc     = $row['alloc'];
    $kat       = $row['population'];
    $coord     = $row['coordinates'];
    $marks     = explode(" ", $coord);
    $centeroid = $row['centeroid'];
    $center    = explode(",", $centeroid);
    for ($k = 0; $k < count($marks); $k++) {
        $splitxy     = explode(",", $marks[$k]);
        $ring[$k][0] = floatval($splitxy[0]);
        $ring[$k][1] = floatval($splitxy[1]);
    }
    $always_kat = intval(0.2 * $kat);
    if ($always_kat > $park) {
        $always_kat = $park;
        $kat        = intval($always_kat);
    } else {
        if ($alloc == 1) {
            $sql2    = "select demand1 from allocation where time = '$is_time'";
            $result1 = mysqli_query($conn, $sql2);
            
            
            while ($row = mysqli_fetch_assoc($result1)) {
                $demand1 = $row['demand1'];
                $kat     = ($park - $always_kat) * $demand1;
                $kat     = intval($kat);
                $kat     = $kat + $always_kat;
            }
        } else {
            $sql3    = "select demand2 from allocation where time = '$is_time'";
            $result2 = mysqli_query($conn, $sql3);
            
            while ($row = mysqli_fetch_assoc($result2)) {
                $demand2 = $row['demand2'];
                $kat     = ($park - $always_kat) * $demand2;
                $kat     = intval($kat);
                $kat     = $kat + $always_kat;
            }
        }
        
    }
    
    $kat = intval(($kat / $park) * 100);
    if ($kat < 60) {
        $color = 'green';
    } elseif ($kat < 85 && $kat > 59) {
        $color = 'yellow';
    } else {
        $color = 'red';
    }
    $features[] = array(
        
        'type' => 'Feature',
        'properties' => array(
            'Name' => $name,
            'Color' => $color,
            'Occupied' => $kat,
            'TotalSeats' => $park
        ),
        'geometry' => array(
            'type' => 'GeometryCollection',
            'geometries' => array(
                // array(
                //   'type' => 'Point',
                //     'coordinates' => array(floatval($center[0]) , //floatval($center[1]))
                // ),
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

$fp = fopen('Exomoiwsh.json', 'w+');
fwrite($fp, json_encode($response, JSON_PRETTY_PRINT));
fclose($fp);
echo '<script type="text/javascript">', 'doc_wait();', '</script>';

?>