<?php
// Detecting through Session if we are in "partie froide" or "partie chaude"
//    session_start();
//    if(filter_var($_GET["partie"], FILTER_SANITIZE_STRING) == "chaude") {
//        $_SESSION["partie"] = "chaude";
//    }
//    if($_SESSION["partie"] == "chaude") {
//        // This page should be opened only from partie chaude
//        header('Location: /');
//        exit();
//    }

session_start();
if ('chaude' == $_SESSION['partie'] && 'froide' != $_GET['partie']) {
    ?>
    <h2><?php _p('Les partenaires'); ?></h2>
<?php
} ?>
</div>
<div style="position:relative;">
    <div id="map" style="height:1000px;z-index:0;"></div>
    <div id="notes" style="position:fixed;right:50px;top:200px;height:150px;width:40%;z-index:12000;padding:20px;border-radius: 6px;">

    </div>
</div>

<style>
    @import url("https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.6.0/leaflet.css");
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.6.0/leaflet.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-providers/1.10.1/leaflet-providers.min.js"></script>



<script>
    var Esri_WorldStreetMap = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Street_Map/MapServer/tile/{z}/{y}/{x}', {
        attribution: 'Tiles &copy; Esri &mdash; 2012'
    });
    var mapExtent = [-3.46044159, -45.58060417, 101.63266765, 22.90924329];
    var mapMinZoom = 1;
    var mapMaxZoom = 9;
    var bounds = new L.LatLngBounds(
      new L.LatLng(mapExtent[1], mapExtent[0]),
      new L.LatLng(mapExtent[3], mapExtent[2]));

    var CarteGeologiqueMarcou = L.tileLayer('/carte/{z}/{x}/{y}.png', {
        minZoom: mapMinZoom, maxZoom: mapMaxZoom,
        bounds: bounds,
        opacity: 0.85,
        attribution: 'Carte géologique Marcou : géoréférencement par <a href=https://www.ideesculture.com>Idéesculture 2020</a>'
    });

    var Stamen_Watercolor = L.tileLayer('https://stamen-tiles-{s}.a.ssl.fastly.net/watercolor/{z}/{x}/{y}.{ext}', {
        attribution: 'Map tiles by <a href="http://stamen.com">Stamen Design</a>, <a href="http://creativecommons.org/licenses/by/3.0">CC BY 3.0</a> &mdash; Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        subdomains: 'abcd',
        minZoom: 1,
        maxZoom: 16,
        ext: 'jpg'
    });

    var map = L.map('map',
        {
            center: [-14.8667, 54.4667],
            zoom: 5,
            layers:[Esri_WorldStreetMap, CarteGeologiqueMarcou]
        }
    );
    //L.control.layers().addTo(map);
    map.addControl(new L.Control.Layers(null, {"ESRI":Esri_WorldStreetMap, "Carte géologique Marcou":CarteGeologiqueMarcou, "Watercolor":Stamen_Watercolor}, {position:'topleft'}));

    L.marker([-20.8667, 55.4667]).addTo(map)
        .bindPopup('<a id="RéunionMarker" data-country="La Réunion" class="phoiMarker">La Réunion</a>');
    L.marker([-12.760910340467493, 45.1812744140625]).addTo(map)
        .bindPopup('<a id="MayotteMarker" data-country="Mayotte" class="phoiMarker">Mayotte</a>');
    L.marker([-18.9531, 47.5207]).addTo(map)
        .bindPopup('<a id="MadagascarMarker" data-country="Madagascar" class="phoiMarker">Madagascar</a>');
    L.marker([-6.1706, 39.3681]).addTo(map)
        .bindPopup('<a id="ZanzibarMarker" data-country="Zanzibar" class="phoiMarker">Zanzibar</a>');
    L.marker([-11.7087, 43.2525]).addTo(map)
        .bindPopup('<a id="ComoresMarker" data-country="Comores" class="phoiMarker">Comores</a>');
    L.marker([-20.1387, 57.3767]).addTo(map)
        .bindPopup('<a id="MauriceMarker" data-country="Maurice" class="phoiMarker">Maurice</a>');
    L.marker([-4.7024, 55.4494]).addTo(map)
        .bindPopup('<a id="SeychellesMarker" data-country="Seychelles" class="phoiMarker">Seychelles</a>');
    L.marker([-19.68397023588844,63.33618164062501],{draggable:'true'}).addTo(map)
        .bindPopup('<a id="RodriguesMarker" data-country="Rodrigues" class="phoiMarker">Rodrigues</a>');

    function onMapClick(e) {
        marker = new L.marker(e.latlng, {draggable:'true'});
        marker.on('dragend', function(event){
            var marker = event.target;
            var position = marker.getLatLng();
            marker.setLatLng(new L.LatLng(position.lat, position.lng),{draggable:'true'});
            map.panTo(new L.LatLng(position.lat, position.lng))
            console.log(position);
        });
        map.addLayer(marker);
    };

    //map.on('click', onMapClick);

    $(document).ready(function() {
        $(document).on("click", ".phoiMarker", function() {
            //let clicked = $(this).attr("id").substr(0, $(this).attr("id").length - 6);
            let clicked = $(this).attr("data-country");
            let url = "/index.php/Phoi/Partenaires/GetLinks/pays/"+clicked;
            $.get(url, function(data) {
                $("#notes").html(data);
            });
        })
    });
</script>

<style>
    .card {
        border: 1px solid #e0e0e0;
        margin-bottom: 10px;
    }
</style>

<div class="container">

