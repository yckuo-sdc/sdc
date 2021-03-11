<!DOCTYPE html>
<html>
  <head>
    <title>Earthquake Markers</title>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <style type="text/css">
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 100%;
      }

      /* Optional: Makes the sample page fill the window. */
      html,
      body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
    </style>
    <script>
      let map;

      function initMap() {
        map = new google.maps.Map(document.getElementById("map"), {
          zoom: 2,
          center: new google.maps.LatLng(2.8, -187.3),
          mapTypeId: "terrain",
        });
        // Create a <script> tag and set the USGS URL as the source.
        const script = document.createElement("script");
        // This example uses a local copy of the GeoJSON stored at
        // http://earthquake.usgs.gov/earthquakes/feed/v1.0/summary/2.5_week.geojsonp
        script.src =
          "https://developers.google.com/maps/documentation/javascript/examples/json/earthquake_GeoJSONP.js";
        document.getElementsByTagName("head")[0].appendChild(script);
      }

      // Loop through the results array and place a marker for each
      // set of coordinates.
      const eqfeed_callback = function (results) {
        for (let i = 0; i < results.features.length; i++) {
          const coords = results.features[i].geometry.coordinates;
          const latLng = new google.maps.LatLng(coords[1], coords[0]);
          new google.maps.Marker({
            position: latLng,
            map: map,
          });
        }
      };
    </script>
  </head>
  <body>
    <div id="map"></div>

    <!-- Async script executes immediately and must be after any DOM elements used in callback. -->
    <script
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAA4ort7FALNXS2tZWhvewy8AF6utjvCfk&callback=initMap&libraries=&v=weekly"
      async
    ></script>
  </body>
</html>


