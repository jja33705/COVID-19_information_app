<template>
    <app-layout title="myPage">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                MyPage
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <button @click="onClickPlaceButton">접종처</button>
                    <div v-if="data">{{ data }}</div>
                    <div id="map" style="width:100%; height:400px;"></div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
export default defineComponent({
    components: {
        AppLayout,
    },
    data() {
        return {
            data: null,
            map: null,
        };
    },
    methods: {
        onClickPlaceButton() {
            axios.get(
                    "https://cors.bridged.cc/https://api.odcloud.kr/api/15077586/v1/centers?page=1&perPage=10&serviceKey=wZXfBdmDrek55joz9YP0lhLRj5Do59jCcELbR0NGdpZzCFpnufcYpFMgike1cU3tpG1MDQpS6NcbibCFr1S58A%3D%3D")
            .then((res) => {
                console.log(res);
                this.data = res.data;
                new naver.maps.Marker({
                    position: new naver.maps.LatLng(35.869985, 128.583716),
                    map: this.map
                });
            })
            .catch((err) => {
                console.log(err);
            });
        },
        getCurrentLocationSuccess(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            const LatLng = new naver.maps.LatLng(lat, lng);
            new naver.maps.Marker({
                map: this.map,
                position: LatLng,
                icon: {
                    path: naver.maps.SymbolPath.CIRCLE,
                    style: naver.maps.SymbolStyle.CIRCLE,
                    fillColor: '#FF0000',
                    fillOpacity: 1,
                    strokeColor: '#000000',
                    strokeStyle: 'solid',
                    strokeWeight: 1,
                    radius: 10,
                },
            });
        },
        getCurrentLocationError() {
            console.log('cant get current location');
        },
    },
    mounted() {
        var mapOptions = {
            center: new naver.maps.LatLng(37.3595704, 127.105399),
            scaleControl: true,
            logoControl: true,
            mapDataControl: true,
            mapTypeControl: true,
            zoomControl: true,
            zoom: 1
        };
        this.map = new naver.maps.Map('map', mapOptions);
        if(!navigator.geolocation) {
            console.log('cant get location in this browser');
        } else {
            navigator.geolocation.getCurrentPosition(this.getCurrentLocationSuccess, this.getCurrentLocationError);
        }
    },
});
</script>
