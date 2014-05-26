<?php

function geocode($url) {

  $file_handle = fopen($url, "r");
$fp = fopen("file.csv","w");
$fpj = fopen("file.json","w");

$file = "filegeo.json";

$f = fopen($file, "w");

  $array=array();


   $geojson = array(
    'type'      => 'FeatureCollection',
    'features'  => array()
 );




  while (!feof($file_handle)) {
$row++;
    $line = fgets($file_handle);
	$data_as_array = explode(',',  $line);
   // $line=str_replace('\n','',$line);
   // $line=str_replace('/','',$line);
    $line1 = "&location=".$data_as_array[0];
    $array=array($line1);
    // echo $array[0];

   
    //$string= "&key=Fmjtd%7Cluur2la7n9%2C8w%3Do5-9a221u";
    //echo $line.$string;
if($row > 1){ 
    $string="http://open.mapquestapi.com/geocoding/v1/batch?key=Fmjtd%7Cluur2la7n9%2C8w%3Do5-9a221u";
    $string=$string.$line1;
    $string=str_replace(' ','+',$string);

    $string=str_replace('  ','',$string);
    $json = file_get_contents($string);
    //echo $string;

    $jsonArr = json_decode($json);


    $lat1 = $jsonArr->results[0]->locations[0]->latLng->lat;
    $lon1 = $jsonArr->results[0]->locations[0]->latLng->lng;

$output= $line.",".$lat1.",".$lon1."<br>";
if ($lat1==""){
echo "<font color='red'>".$output."</font>";
}else{
    echo $output;
}
$line2=str_replace("\n","\\n",htmlentities ($line));
$line2=str_replace('\n','',$line2);
$line2=str_replace('\r\n','',$line2);
$page=$line2.",".$lat1.",".$lon1."\r\n";


fwrite($fp,$page);



 $feature = array('type' => 'Feature', 
      'properties' => array(
        'indirizzo' => $line2
 	
        //Other fields here, end without a comma
            ),
      'geometry' => array(
        'type' => 'Point',
        'coordinates' => array((float)$lon1, (float)$lat1)
            ),
        );
    array_push($geojson['features'], $feature);



  }
}
fwrite($fpj,$json);
$json=json_encode($geojson);


fwrite($f, $json);

fclose($f);

fclose($fp);
fclose($fpj);

  fclose($file_handle);
//echo "<br><br>Sto aprendo ".$url." e dalla prima colonna con gli indirizzi effettuo un geocoding usando http://open.mapquestapi.com che interroga OpenStreetMap<br>";
?>
<br><a href="file.csv">Download CSV</a><br>
<a href="file.json">Download Json MapQuest</a><br>
<a href="filegeo.json">Download GeoJson</a><br>

<html lang="it">
  <head>
  <link rel="stylesheet" href="http://necolas.github.io/normalize.css/2.1.3/normalize.css" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.7/leaflet.css" />
        <link rel="stylesheet" href="MarkerCluster.css" />
        <link rel="stylesheet" href="MarkerCluster.Default.css" />
  <script src="http://cdn.leafletjs.com/leaflet-0.7/leaflet.js"></script>
   <script src="leaflet.markercluster.js"></script>
<script type="text/javascript">
function microAjax(B,A){this.bindFunction=function(E,D){return function(){return E.apply(D,[D])}};this.stateChange=function(D){if(this.request.readyState==4){this.callbackFunction(this.request.responseText)}};this.getRequest=function(){if(window.ActiveXObject){return new ActiveXObject("Microsoft.XMLHTTP")}else{if(window.XMLHttpRequest){return new XMLHttpRequest()}}return false};this.postBody=(arguments[2]||"");this.callbackFunction=A;this.url=B;this.request=this.getRequest();if(this.request){var C=this.request;C.onreadystatechange=this.bindFunction(this.stateChange,this);if(this.postBody!==""){C.open("POST",B,true);C.setRequestHeader("X-Requested-With","XMLHttpRequest"); C.setRequestHeader("Content-type","application/x-www-form-urlencoded");C.setRequestHeader("Connection","close")}else{C.open("GET",B,true)}C.send(this.postBody)}};
</script>
  <style>
  #mapdiv{
        
}
#infodiv{
        background-color: rgba(255, 255, 255, 0.95);
    
  border-width: 2px;
  border-style: solid;
  border-color: rgba(255, 255, 255, 0.8);
  
  border-radius: 9px;
  
  padding: 10px;
  
  //width: 90%;
  font-size: 11px;
  botton: 2px;  
  left: 2px;
  
  bottom: 20px;  
  max-height: 50px;
  
  position: fixed;
  
  overflow-y: auto;
  overflow-x: hidden;
}
</style>
  </head>

<body>
<br>
<div id="mapdiv" style="width: 600px; height: 400px"></div>

  <script type="text/javascript">
		var lat=40.662617,
        lon=16.6191,
        zoom=5;
        var osm = new L.TileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {maxZoom: 15, attribution: 'Map Data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors'});
		var mapquest = new L.TileLayer('http://otile{s}.mqcdn.com/tiles/1.0.0/osm/{z}/{x}/{y}.png', {subdomains: '1234', maxZoom: 18, attribution: 'Map Data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors'});        

        var map = new L.Map('mapdiv', {
                    editInOSMControl: true,
            editInOSMControlOptions: {
                position: "topright"
            },
            center: new L.LatLng(lat, lon),
            zoom: zoom,
            layers: [osm]
        });
        
        var baseMaps = {
    "Mapnik": osm,
    "Mapquest Open": mapquest       
        };
        L.control.layers(baseMaps).addTo(map);

   
       

microAjax('filegeo.json',function (res) { 
var feat=JSON.parse(res);
loadLayer(feat);
 } );
    var ico=L.icon({iconUrl:'icccc.png', iconSize:[20,20],iconAnchor:[10,0]});
    var markers = L.markerClusterGroup();


 function loadLayer(url)
        {
                var myLayer = L.geoJson(url,{
                        onEachFeature:function onEachFeature(feature, layer) {
                           
                             if (feature.properties) {
var string='<b>Indrizzo:</b> '+feature.properties.indirizzo+"<br/>";


                                        layer.bindPopup(string);
                                }
                        },

                        pointToLayer: function (feature, latlng) {                
              	        var marker = new L.marker(latlng);//, { icon: ico });
                        markers[feature.properties.id] = marker;
                        return marker;

                        }
                }).addTo(markers);

      markers.addLayer(myLayer);
  
        }

 

     map.addLayer(markers);
map.fitBounds(markers.getBounds());
</script>
  
</body>
</html>


<?php
}


if (isset($_POST['vai'])){
  $url = filter_var($_POST['vai'], FILTER_VALIDATE_URL);
  if ($url) {
    geocode($url);
  }
  else {
    $error = 'Sorry, this is not a valid url.';
  }
}


if ( isset($_POST["invia"]) ) {

   if ( isset($_FILES["file"])) {

            //if there was an error uploading the file
        if ($_FILES["file"]["error"] > 0) {
            echo "Return Code: " . $_FILES["file"]["error"] . "<br />";

        }
        else {
                 //Print file details
             echo "Upload: " . $_FILES["file"]["name"] . "<br />";
             echo "Type: " . $_FILES["file"]["type"] . "<br />";
             echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
echo "<br />";
            // echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";

                 //if file already exists
             if (file_exists("test/upload/" . $_FILES["file"]["name"])) {
            echo $_FILES["file"]["name"] . " already exists. ";
             }
             else {
                    //Store file in directory "upload" with the name of "uploaded_file.csv"
            $storagename = "uploaded_file.csv";
            move_uploaded_file($_FILES["file"]["tmp_name"], "test/upload/" . $storagename);
         //   echo "Stored in: " . "test/upload/" . $_FILES["file"]["name"] . "<br /><br/>";

 $url = "test/upload/uploaded_file.csv";
//echo $url;
geocode($url);

            }
        }
     } else {
             echo "No file selected <br />";
     }
}


?>
<html>
    <head>
        <title>Geocoding OpenStreetMap from CSV file</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

</head>
    <body>

<p>Geocoder di file csv da una colonna con indirizzi. Gli indirizzi devono essere nella PRIMA COLONNA del file CSV.</p><p> Per un migliore geocoding inserisci l'indirizzo con questo formato:"via civico città nazione" esempio "via guglielmo oberdan 20 bologna italia".<br />
PS: Puoi anche inserire un file GoogleSpreadsheet, ma prima devi fare "Pubblica sul web", copiare il link e sostituire output=html in output=csv.</p>
<p>Esempio: https://docs.google.com/spreadsheet/pub?key=0AoZ9HGSxyqvydEFpdmdEbHExMUxmeVBJZDNXLTcyNnc&output=csv</p>
<p>Lo script interrogherà OpenStreetMap tramite il batch di http://open.mapquestapi.com/ <br />

La licenza è <a href="https://www.gnu.org/licenses/agpl.txt">AGPL3</a> tranne che per i files di terze parti che seguono le licenze dei singoli proprietari.</p>
<p>Il sorgente è su <a href="https://github.com/piersoft/GeocoderCSV2OSM">GITHUB</a></p>
<p>Realizzato da <a href="http://www.twitter.com/piersoft">@Piersoft</a></p>
<br>
<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
<input style="width: 500px" type="text" name="vai" placeholder="Incolla il link ad un file CSV monocolonna con indirizzi" />
<input type="submit" value="vai" onclick="test()" />
</form>


<table width="600">
<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST" enctype="multipart/form-data">

<tr>
<td width="60%">Seleziona un file di testo/csv con gli indirizzi nella prima colonna</td>
</tr>
<tr>
<td width="40%"><input type="file" name="file" id="file" />
<input type="submit" name="invia" /></td>

</tr>

</form>
</table>

<?php if (isset($error)) : ?>
  <p style="color: red;"> <?php print $error ?> </p>
<?php endif; ?>


 </body>
</html>

