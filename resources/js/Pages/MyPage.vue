d<template>
    <app-layout title="myPage">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                MyPage
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <form @submit.prevent="search">
                        <input type="text" v-model="searchString">
                        <button type="submit">검색</button>
                    </form>
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
            map: null,
            lat: 0, //자기 위도
            lng: 0,  //자기 경도
            tourSpots: [],
            searchString: '',
            markers: [],
        };
    },
    methods: {
        clearMarkers() {
            this.markers.map((v) => {
                v.setMap(null);
            });
            this.markers = [];
        },
        setMarkers() { //현재 가지고 있는 데이터들로 마커 표시
            this.clearMarkers();
            this.tourSpots.map((v) => {
                const spot = new naver.maps.LatLng(v.mapy, v.mapx);
                const marker = new naver.maps.Marker({
                    map: this.map,
                    position: spot,
                });
                this.markers.push(marker);
                var contentString = [
                    '<div class="iw_inner">',
                    `   <h3>${v.title}</h3>`,
                    `   <p>${v.addr1}<br />`,
                    `       <img src="${v.firstimage}" width="55" height="55" alt="${v.title}" class="thumb" /><br />`,
                    '   </p>',
                    '</div>',
                ].join('');
                const infoWindow = new naver.maps.InfoWindow({
                    content: contentString,
                });

                //마커 클릭시 정보창 보여줌
                naver.maps.Event.addListener(marker, 'click', (e) => {
                    if (infoWindow.getMap()) {
                        infoWindow.close();
                    } else {
                        console.log(this);
                        infoWindow.open(this.map, marker);
                    }
                });
            });
        },
        getCurrentLocationSuccess(position) { //자기위치 가져오기(성공)
            this.lat = position.coords.latitude;
            this.lng = position.coords.longitude;
            const LatLng = new naver.maps.LatLng(this.lat, this.lng);
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

            //주변 관광지 데이터 가져오기(2km)
            axios.get(`https://cors.bridged.cc/http://api.visitkorea.or.kr/openapi/service/rest/KorService/locationBasedList?serviceKey=4lyV1AhLwS2E8AbWo7qJKIsGqL8UPCTIqKP7LkFo62%2BZbmluePY8GC9jW7J0d5IlpfRGcRPk5e3er8Nvg08YIQ%3D%3D&numOfRows=10&pageNo=1&MobileOS=ETC&MobileApp=AppTest&arrange=B&contentTypeId=12&mapX=${this.lng}&mapY=${this.lat}&radius=3000&listYN=Y`)
            .then((res) => {
                this.tourSpots = res.data.response.body.items.item;
                this.setMarkers();
            })
            .catch((err) => {
                console.log(err);
            });
        },
        getCurrentLocationError() { //자기위치 가져오기(실패)
            console.log('cant get current location');
        },
        search() { //검색
            axios.get(`https://cors.bridged.cc/http://api.visitkorea.or.kr/openapi/service/rest/KorService/searchKeyword?serviceKey=4lyV1AhLwS2E8AbWo7qJKIsGqL8UPCTIqKP7LkFo62%2BZbmluePY8GC9jW7J0d5IlpfRGcRPk5e3er8Nvg08YIQ%3D%3D&MobileApp=AppTest&MobileOS=ETC&pageNo=1&numOfRows=10&listYN=Y&arrange=B&contentTypeId=12&keyword=${encodeURIComponent(this.searchString)}`)
            .then((res) => {
                console.log(res);
                this.tourSpots = res.data.response.body.items.item;
                this.setMarkers();
            })
            .catch((err) => {
                console.log(err);
            });
        },
    },
    mounted() {
        //맵 생성
        const mapOptions = {
            center: new naver.maps.LatLng(37.3595704, 127.105399),
            scaleControl: true,
            logoControl: true,
            mapDataControl: true,
            mapTypeControl: true,
            zoomControl: true,
            zoom: 1
        };
        this.map = new naver.maps.Map('map', mapOptions);
        
        //자기위치 가져오기
        if(!navigator.geolocation) {
            console.log('cant get location in this browser');
        } else {
            navigator.geolocation.getCurrentPosition(this.getCurrentLocationSuccess, this.getCurrentLocationError);
        }
    },
});
</script>
