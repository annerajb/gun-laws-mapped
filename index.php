<?php
include_once('geoPHP/geoPHP.inc');
function wkb_to_json($wkb) 
{
    $geom = geoPHP::load($wkb,'wkb');
    return $geom->out('json');
}
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');
$conn = new PDO('mysql:host=localhost;dbname=gunlawsmap','root','');
$sql = null;
switch ($_GET["law_id"]) 
{
    case "phtml_suppressors":
        $sql = 'SELECT location.*,supressor.source,supressor.note,supressor.permitted, AsWKB(location.geometry) AS wkb FROM location JOIN supressor ON  location.location_code = supressor.location_code';
        break;
    case"phtml_machineguns":
        $sql = 'SELECT location.*,machineguns.source,machineguns.note,machineguns.permitted, AsWKB(location.geometry) AS wkb FROM location JOIN machineguns ON  location.location_code = machineguns.location_code';
        break;
    case"phtml_magazine":
        $sql = 'SELECT location.*,magazine_capacity.amount,magazine_capacity.source,magazine_capacity.note,magazine_capacity.permitted, AsWKB(location.geometry) AS wkb FROM location JOIN magazine_capacity ON  location.location_code = magazine_capacity.location_code';
        break;
    case "phtml_delay":
        $sql = 'SELECT location.*,delay.source,delay.note,delay.time, AsWKB(location.geometry) AS wkb FROM location JOIN delay ON  location.location_code = delay.location_code';
        break;
    case "phtml_cost":
        $sql = 'SELECT location.*,cost.source,cost.note,cost.cost, AsWKB(location.geometry) AS wkb FROM location JOIN cost ON  location.location_code = cost.location_code';
        break;
    default:
        exit;
        break;
}

# Try query or error
$rs = $conn->query($sql);
if (!$rs) 
{
    echo 'An SQL error occured.\n';
    exit;
}

# Build GeoJSON feature collection array
$geojson = array(
   'type'      => 'FeatureCollection',
   'features'  => array()
);

# Loop through rows to build feature arrays
while ($row = $rs->fetch(PDO::FETCH_ASSOC)) 
{
    $properties = $row;
    # Remove wkb and geometry fields from properties
    unset($properties['wkb']);
    unset($properties['SHAPE']);
    $feature = array(
         'type' => 'Feature',
         'geometry' => json_decode(wkb_to_json($row['wkb'])),
         'properties' => $properties
    );
    # Add feature arrays to feature collection array
    array_push($geojson['features'], $feature);
}   

echo '{ "lawLegend":  {"0": {"color":"#FF0000","text":"No"},"1": {"color":"#00FF00","text":"Yes"},"2": {"color":"#FFFF00","text":"Kinda"}},';
echo '"statesData":'. json_encode($geojson, JSON_NUMERIC_CHECK)."}";
$conn = NULL;
?>
        

