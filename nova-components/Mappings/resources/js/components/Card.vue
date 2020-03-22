<template>
    <card class="flex flex-col items-center justify-center">
        <div id="map-container">
            <div id="map"></div>
        </div>
    </card>
</template>

<script>
export default {
    props: ['card'],

    async mounted() {
        const response = await axios.get('/api/v1/spaces');
        let center,map,markers;
        center = new google.maps.LatLng(1.2921, 36.8219);
        map = new google.maps.Map(document.getElementById('map'), {
            zoom: 5,
            center: center,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });
        markers = [];
        for (let i = 0; i < response.data.length; i++) {
            let latLng = new google.maps.LatLng(response.data[i].latitude,
                response.data[i].longitude);
            let marker = new google.maps.Marker({
                position: latLng,
                title: response.data[i].client.name,
            });
            google.maps.event.addListener(marker, 'click', function () {
                new google.maps.InfoWindow({
                    content: '<p style="font-size: 15px"><span style="color: red">Client:</span> '+response.data[i].client.name+ '</p>'
                }).open(map, marker)
            });
            markers.push(marker);
        }
        new MarkerClusterer(map, markers, {
            maxZoom: 15,
            imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'
        });
    },
}
</script>
<style type="text/css">
    #map-container {
        padding: 6px;
        border-width: 1px;
        border-style: solid;
        border-color: #ccc #ccc #999 #ccc;
        -webkit-box-shadow: rgba(64, 64, 64, 0.5) 0 2px 5px;
        -moz-box-shadow: rgba(64, 64, 64, 0.5) 0 2px 5px;
        box-shadow: rgba(64, 64, 64, 0.1) 0 2px 5px;
        width: 100%;
    }

    #map {
        width: 100%;
        height: 500px;
    }
</style>
