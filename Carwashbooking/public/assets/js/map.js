$(document).ready(function ()
{
    var $map = $('#map-default'),
        map,
        lat,
        lng,
        color = "#6b48ff";

    function initMap() {

        map = document.getElementById('map-default');
        lat = map.getAttribute('data-lat');
        lng = map.getAttribute('data-lng');

        var myLatlng = new google.maps.LatLng(lat, lng);
        var mapOptions = {
            zoom: 12,
            scrollwheel: false,
            center: myLatlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
        }

        map = new google.maps.Map(map, mapOptions);

        var marker = new google.maps.Marker({
            position: myLatlng,
            map: map,
            animation: google.maps.Animation.DROP,
            title: 'Hello World!'
        });

        var contentString = '<div class="info-window-content"><h2>Argon Dashboard PRO</h2>' +
            '<p>A beautiful premium dashboard for Bootstrap 4.</p></div>';

        var infowindow = new google.maps.InfoWindow({
            content: contentString
        });

        google.maps.event.addListener(marker, 'click', function() {
            infowindow.open(map, marker);
        });
    }

    if($map.length) {
        google.maps.event.addDomListener(window, 'load', initMap);
    }

    //
    // Google maps
    //

    var $map = $('#map-custom'),
        map,
        lat,
        lng,
        color = "#6b48ff";

    function initMap()
    {
        map = document.getElementById('map-custom');
        lat = map.getAttribute('data-lat');
        lng = map.getAttribute('data-lng');
        // console.log('lat',lat,'lng',lng);
        var myLatlng = new google.maps.LatLng(lat, lng);
        var mapOptions = {
            zoom: 12,
            scrollwheel: false,
            center: myLatlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            styles: [{"featureType":"administrative","elementType":"labels.text.fill","stylers":[{"color":"#444444"}]},{"featureType":"landscape","elementType":"all","stylers":[{"color":"#f2f2f2"}]},{"featureType":"poi","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"all","stylers":[{"saturation":-100},{"lightness":45}]},{"featureType":"road.highway","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"road.arterial","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"all","stylers":[{"color":color},{"visibility":"on"}]}]
        }

        map = new google.maps.Map(map, mapOptions);

        var marker = new google.maps.Marker({
            position: myLatlng,
            draggable: true,
            map: map,
            animation: google.maps.Animation.DROP,
            title: 'Hello Admin!'
        });

        var contentString = '<div class="info-window-content"><h2>'+ $('title').html() +'</h2>' +
            '<p>company address</p></div>';

        var infowindow = new google.maps.InfoWindow({
            content: contentString,
        });


        google.maps.event.addListener(marker, 'dragend', function()
        {
            // console.log(this.getPosition().formatted_address);
            infowindow.open(map, marker);

            $('#lat').val(this.getPosition().lat());
            $('#lang').val(this.getPosition().lng());
            // $('#lat').val(22.23422);
            // $('#lang').val(70.800751);
            geocodePosition(marker.getPosition());
        });
    }

    function geocodePosition(pos)
    {
        var geocoder = new google.maps.Geocoder();
        geocoder.geocode({
          latLng: pos
        }, function(responses)
        {
          if (responses && responses.length > 0) {
            $('#company_address').val(responses[0].formatted_address);
          } else {
            updateMarkerAddress('Cannot determine address at this location.');
          }
        });
    }

    if($map.length)
    {
        google.maps.event.addDomListener(window, 'load', initMap);
    }
});
