<?php

function test(){
  echo "Sto aprendo ".$_POST[user]." e dalla prima colonna con gli indirizzi effettuo un geocoding usando http://open.mapquestapi.com che interroga OpenStreetMap<br>";
$file_handle = fopen($_POST[user], "r");
$array=array(); 
while (!feof($file_handle)) {
  

 $line = fgets($file_handle);
 $line1 = "&location=".$line;
 $array=array($line1); 
// echo $array[0];

echo "<br>";
//$string= "&key=Fmjtd%7Cluur2la7n9%2C8w%3Do5-9a221u";
//echo $line.$string;

$string="http://open.mapquestapi.com/geocoding/v1/batch?key=Fmjtd%7Cluur2la7n9%2C8w%3Do5-9a221u";
$string=$string.$line1;
$string=str_replace(' ','+',$string);
$json = file_get_contents($string);
//echo $string;

$jsonArr = json_decode($json);

$lat1 = $jsonArr->results[0]->locations[0]->latLng->lat;
$lon1 = $jsonArr->results[0]->locations[0]->latLng->lng;

echo $line.",".$lat1.",".$lon1;

//header("Location: http://open.mapquestapi.com/geocoding/v1/batch?".$string.$array[0]); 

//echo $array[0];
}

fclose($file_handle);
}    
if(isset($_POST[user])){
test();
}

?>
<html>
    <head>
        <title>Geocoding OpenStreetMap da file CSV</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

</head>
    <body>
<br>
<form action="<?php echo $PHP_SELF;?>" method="POST">
<input style="width: 500px" type="text" name="user" placeholder="Incolla il link ad un file CSV monocolonna con indirizzi" />
<input type="submit" value="submit" onclick="test()" />
</form>
<br>
<p>Puoi anche inserire un file GoogleSpreadsheet, ma prima devi fare "Pubblica sul web", copiare il link e sostituire output=html in output=csv</p>
<p>Esempio: https://docs.google.com/spreadsheet/pub?key=0AoZ9HGSxyqvydEFpdmdEbHExMUxmeVBJZDNXLTcyNnc&output=csv</p>

 </body>
</html>

