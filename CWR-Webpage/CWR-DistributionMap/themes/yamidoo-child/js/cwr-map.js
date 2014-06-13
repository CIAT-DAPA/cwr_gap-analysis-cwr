
/*  
 * Author: Alex Gabriel Castañeda
 * This script was created to show kml, distribution points and tile images used 
 * in Crop Wild Relatives project. 
 * */
var map;
var markers = new Array();
var bounds;
var clickedLat;
var image_icon;
var current_latlng;
var boundsAppearingMarkers;
var startLatLng;
var kmlLayer;
var maxZoomLevel;
var minZoomLevel;


function initialize() {
    bounds = new google.maps.LatLngBounds();
    boundsAppearingMarkers = new google.maps.LatLngBounds();
    maxZoomLevel = 9;
    minZoomLevel = 2;

    var zoom = 7;
    if (google.loader.ClientLocation) {
        current_latlng = new google.maps.LatLng(google.loader.ClientLocation.latitude, google.loader.ClientLocation.longitude);
    } else {
        current_latlng = new google.maps.LatLng(19, -16.75);
        zoom = 3;
    }

    clickedLat = current_latlng;
    startLatLng = current_latlng;
    var myOptions = {
        zoom: zoom,
        center: current_latlng,
        disableDefaultUI: true,
        scrollwheel: true,
        streetViewControl: false,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    }
    
    map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);

    google.maps.event.addListener(map, 'zoom_changed', function() {
        if (map.getZoom() > maxZoomLevel){
            if(map.getZoom() < minZoomLevel){
                map.setZoom(minZoomLevel);
            } else {
                map.setZoom(maxZoomLevel);
            }
        }
        if(map.getZoom() < minZoomLevel){
            map.setZoom(minZoomLevel);
        }
    });
    
       /* Restrict the coordinate access on the world */
    var allowedBounds = new google.maps.LatLngBounds(
        new google.maps.LatLng(-90,-180), 
        new google.maps.LatLng(90,180)
        );
    var lastValidCenter = map.getCenter();

    google.maps.event.addListener(map, 'center_changed', function() {
        if (allowedBounds.contains(map.getCenter())) {
            lastValidCenter = map.getCenter();
            return; 
        }
        // not valid anymore => return to last valid position
        map.panTo(lastValidCenter);
    });

}


/* Initialize the google map with de mapType specified */
function initializeWithMapType(mapType) {
    bounds = new google.maps.LatLngBounds();
    boundsAppearingMarkers = new google.maps.LatLngBounds();
    maxZoomLevel = 9;
    minZoomLevel = 2;

    var zoom = 7;
    if (google.loader.ClientLocation) {
        current_latlng = new google.maps.LatLng(google.loader.ClientLocation.latitude, google.loader.ClientLocation.longitude);
    } else {
        current_latlng = new google.maps.LatLng(19, -16.75);
        zoom = 3;
    }

    clickedLat = current_latlng;
    startLatLng = current_latlng;
    var myOptions = {
        zoom: zoom,
        center: current_latlng,
        disableDefaultUI: true,
        scrollwheel: true,
        streetViewControl: false
    }
    
    map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
    
    if(mapType == "map"){
        map.setMapTypeId(google.maps.MapTypeId.ROADMAP);
    }else if(mapType == "terrain"){
        map.setMapTypeId(google.maps.MapTypeId.TERRAIN);
    }else{
        map.setMapTypeId(google.maps.MapTypeId.SATELLITE);
    }

    google.maps.event.addListener(map, 'zoom_changed', function() {
        if (map.getZoom() > maxZoomLevel){
            if(map.getZoom() < minZoomLevel){
                map.setZoom(minZoomLevel);
            } else {
                map.setZoom(maxZoomLevel);
            }
        }
        if(map.getZoom() < minZoomLevel){
            map.setZoom(minZoomLevel);
        }
    });
    
    /* Restrict the coordinate access on the world */
    var allowedBounds = new google.maps.LatLngBounds(
        new google.maps.LatLng(-90,-180), 
        new google.maps.LatLng(90,180)
        );
    var lastValidCenter = map.getCenter();

    google.maps.event.addListener(map, 'center_changed', function() {
        if (allowedBounds.contains(map.getCenter())) {
            lastValidCenter = map.getCenter();
            return; 
        }
        // not valid anymore => return to last valid position
        map.panTo(lastValidCenter);
    });
    
    /* Set the last markers on the map */
    if(markers.length != 0) {
        var points = Array();
        for(var i=0;i < markers.length;i++){
            points.push([markers[i].position.lat(),markers[i].position.lng()]);
        }
        showMarkers(points);
    }
    
}

/* Repeat the X and Y axis for the TileImages on the map */
function getNormalizedCoord(coord, zoom) {
    var y = coord.y;
    var x = coord.x;

    // tile range in one direction range is dependent on zoom level
    // 0 = 1 tile, 1 = 2 tiles, 2 = 4 tiles, 3 = 8 tiles, etc
    var tileRange = 1 << zoom;

    // don't repeat across y-axis (vertically)
    if (y < 0 || y >= tileRange) {
        return null;
    }

    // repeat across x-axis
    if (x < 0 || x >= tileRange) {
        return null;
    }

    return {
        x: x,
        y: y
    };
}

/* Clean the map preserving the zoom and center */
function resetMap(mapType){
    var zoom_old = map.getZoom();
    var center_old = map.getCenter();
    initializeWithMapType(mapType);
    map.setZoom(zoom_old);
    map.setCenter(center_old);
}

/* Delete and clean markers on the map */
function clearMarkers(){
    for(var i=0;i<markers.length;i++){ // Cleaning markers
        markers[i].setMap(null);
    }
    markers = null;
    markers = new Array(); 
}

/* Show distribution points in the map */
function showMarkers(points) {
    image_icon =  "http://gisweb.ciat.cgiar.org/distributionMaps/point.png"; // static url for the point used in the distribution point maps
    for(var i=0;i<points.length;i++){ // Points es una variable que contiene todos los puntos coordenados tipo (latitud, longitud) para la especie / gene pool suministrada
        marker = new google.maps.Marker({
            position: new google.maps.LatLng(points[i][0],points[i][1]),
            map: map,
            icon: {
                url: image_icon
            }
        });
        markers.push(marker); // Add markers
        bounds.extend(marker.position);
    } 
    map.fitBounds(bounds);
    map.panToBounds(bounds);  
}

/* Show a kml from url */
function showKmlFile(url) {
    kmlLayer = new google.maps.KmlLayer(); // get the layer
    kmlLayer.setUrl(url);
    kmlLayer.setMap(map);
}

/* Show the tile map from url, pointToBounds is necessary to autocenter and zoom the map, it has distribution points as array with lat - lon coordinates */
function showTileImages(url,pointToBounds) {
    
    if(pointToBounds != null){
        for(var i=0;i<pointToBounds.length;i++){ // Points es una variable que contiene todos los puntos coordenados tipo (latitud, longitud) para la especie / gene pool suministrada
            bounds.extend(new google.maps.LatLng(pointToBounds[i][0],pointToBounds[i][1])); // Extendiendo bounds para todos los marcadores
        } 
    }
    
    var imageMapType = new google.maps.ImageMapType({
        getTileUrl: function(coord, zoom) {
            var normalizedCoord = getNormalizedCoord(coord, zoom);
            if (!normalizedCoord) {
                return null;
            }
            return [url, '/', zoom, '/x', normalizedCoord.x, '_y', normalizedCoord.y, '.png'].join('');
        },
        tileSize: new google.maps.Size(256, 256),
        opacity: 0.53
    });

    map.overlayMapTypes.push(imageMapType);
    
    if(pointToBounds != null){
        map.fitBounds(bounds);
        map.panToBounds(bounds);
    }else{
        map.setCenter(new google.maps.LatLng(0,0));
        map.setZoom(2);
    }
}

function showTileImagesByCrop(cropName,mapType) {
    var imageMapType = new google.maps.ImageMapType({
        getTileUrl: function(coord, zoom) {
            return ['http://gisweb.ciat.cgiar.org/distributionMaps/',cropName,'/',mapType,
            '/', zoom, '/x', coord.x, '_y', coord.y, '.png'].join('');
        },
        tileSize: new google.maps.Size(256, 256),
        opacity: 0.6
    });
    map.overlayMapTypes.push(imageMapType);
}