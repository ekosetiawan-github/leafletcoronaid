<!DOCTYPE html>
<html>
<head>
	
	<title>Quick Start - Leaflet</title>

	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<link rel="shortcut icon" type="image/x-icon" href="docs/images/favicon.ico" />

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css" integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ==" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js" integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew==" crossorigin=""></script>


	
</head>
<body>

<?php
date_default_timezone_set("Asia/Jakarta");
$result = array();
function covid($id)
{
    $array = json_decode(file_get_contents("https://services5.arcgis.com/VS6HdKS0VfIhv8Ct/arcgis/rest/services/COVID19_Indonesia_per_Provinsi/FeatureServer/0/query?where=1%3D1&outFields=*&outSR=4326&f=json"), true);
    $data = $array["features"];
    foreach ($data as $d) {		
		if ($d["attributes"]["Kode_Provi"] == $id){			
			$result["Provinsi"] =  $d["attributes"]["Provinsi"];
			$result["P"] =  $d["attributes"]["Kasus_Posi"];
			$result["S"] =  $d["attributes"]["Kasus_Semb"];
			$result["M"] =  $d["attributes"]["Kasus_Meni"];
			$result["X"] =  $d["geometry"]["x"];
			$result["Y"] =  $d["geometry"]["y"];
		}      
    }
	return $result;
}
$data = covid(17); //ganti dengan kode provinsi anda

?>



<div id="mapid" style="width: 600px; height: 400px;"></div>
<script>

	var mymap = L.map('mapid').setView([<?php echo $data['Y']; ?>, <?php echo $data['X']; ?>], 13);

	L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
		maxZoom: 18,
		attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
			'<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
			'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
		id: 'mapbox/streets-v11',
		tileSize: 512,
		zoomOffset: -1
	}).addTo(mymap);

	L.marker([<?php echo $data['Y']; ?>, <?php echo $data['X']; ?>]).addTo(mymap)
		.bindPopup("Positif: <span style='color:orange'>"+<?php echo $data['P']; ?>+"</span><br />Sembuh: <span style='color:green'>"+<?php echo $data['S']; ?>+"</span><br />Meninggal: <span style='color:red'>"+<?php echo $data['M']; ?>+"</span>").openPopup();

	
	var popup = L.popup();

	function onMapClick(e) {
		popup
			.setLatLng(e.latlng)
			.setContent("You clicked the map at " + e.latlng.toString())
			.openOn(mymap);
	}

	mymap.on('click', onMapClick);

</script>



</body>
</html>
