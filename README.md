GeocoderCSV2OSM
===============

Piccolo script per ricavare da indirizzi le coordinate geografiche usando come geocoder OpenStreetMap


1.2
Inserita possibbilità di upload file monocolonna csv con indirizzi
Creare sul server nella dir di geo.php 2 sotto dir: test/upload e rendere upload in scrittura 777

1.1
Inserito il download dei file geojson, json e csv 
Inserita mappa OSM che visualizza subito gli indirizzi trovati
Evidenziato con colore rosso il geocode non effettuato

1.0
Incollare nel form l'URL ad un file CSV monocolonna, con indirizzi per riga.
Si può usare anche GoogleSpreadsheet, ma prima devi fare "Pubblica sul web", copiare il link e sostituire output=html in output=csv.
Lo script interrogherà OpenStreetMap tramite il batch di Open.Mapquest.com
