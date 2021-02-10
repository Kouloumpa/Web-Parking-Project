<?php
    $geojson = $_POST["geojson"];
    $fp = fopen('userJSON.json', 'w+');
    fwrite($fp, json_encode($geojson, JSON_PRETTY_PRINT));
    fclose($fp);
?>  