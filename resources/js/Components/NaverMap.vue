<template>
    <div id="map" style="width: 100%; height: 400px"></div>
    <button>??</button>
</template>





<script>
export default {
    props: [],
    data() {
        return {
            map: null,
        };
    },
    mounted() {
        //맵 생성
        const mapOptions = {
            center: new naver.maps.LatLng(37.3595704, 127.105399),
            logoControl: true,
            mapDataControl: true,
            mapTypeControl: true,
            zoom: 1,
        };
        this.map = new naver.maps.Map("map", mapOptions);
    }
}
</script>