<template>
    <app-layout :title="Tour">

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="shadow-xl">
                    <form @submit.prevent="searchTourSpots">
                    <div class="pt-2 relative mx-auto text-gray-600">
                        <input class="border-2 border-gray-300 bg-white h-10 px-5 pr-16 rounded-lg text-sm focus:outline-none w-full" type="search" name="search" placeholder="Search" v-model="searchString">
                        <button type="submit" class="absolute right-0 top-0 mt-5 mr-4">
                        <svg class="text-gray-600 h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px"
                            viewBox="0 0 56.966 56.966" style="enable-background:new 0 0 56.966 56.966;" xml:space="preserve"
                            width="512px" height="512px">
                            <path
                            d="M55.146,51.887L41.588,37.786c3.486-4.144,5.396-9.358,5.396-14.786c0-12.682-10.318-23-23-23s-23,10.318-23,23  s10.318,23,23,23c4.761,0,9.298-1.436,13.177-4.162l13.661,14.208c0.571,0.593,1.339,0.92,2.162,0.92  c0.779,0,1.518-0.297,2.079-0.837C56.255,54.982,56.293,53.08,55.146,51.887z M23.984,6c9.374,0,17,7.626,17,17s-7.626,17-17,17  s-17-7.626-17-17S14.61,6,23.984,6z" />
                        </svg>
                        </button>
                    </div>
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
        setMarkers() { //현재 가지고 있는 데이터들로 지도에 마커 표시
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
            const currentLocation = new naver.maps.LatLng(this.lat, this.lng);
            this.map.setCenter(currentLocation);
            this.map.setOptions('zoom',10);
            new naver.maps.Marker({
                map: this.map,
                position: currentLocation,
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
            this.getNearTourSpots();
        },
        getCurrentLocationError() { //자기위치 가져오기(실패)
            console.log('cant get current location');
        },
        getNearTourSpots() { //주변 관광지 데이터 가져오기(10km)
            axios.get(`https://cors.bridged.cc/http://api.visitkorea.or.kr/openapi/service/rest/KorService/locationBasedList?serviceKey=4lyV1AhLwS2E8AbWo7qJKIsGqL8UPCTIqKP7LkFo62%2BZbmluePY8GC9jW7J0d5IlpfRGcRPk5e3er8Nvg08YIQ%3D%3D&numOfRows=10&pageNo=1&MobileOS=ETC&MobileApp=AppTest&arrange=B&contentTypeId=12&mapX=${this.lng}&mapY=${this.lat}&radius=10000&listYN=Y`)
            .then((res) => {
                this.tourSpots = res.data.response.body.items.item;
                this.setMarkers();
            })
            .catch((err) => {
                console.log(err);
            });
        },
        searchTourSpots() { //검색
            axios.get(`https://cors.bridged.cc/http://api.visitkorea.or.kr/openapi/service/rest/KorService/searchKeyword?serviceKey=4lyV1AhLwS2E8AbWo7qJKIsGqL8UPCTIqKP7LkFo62%2BZbmluePY8GC9jW7J0d5IlpfRGcRPk5e3er8Nvg08YIQ%3D%3D&MobileApp=AppTest&MobileOS=ETC&pageNo=1&numOfRows=10&listYN=Y&arrange=B&contentTypeId=12&keyword=${encodeURIComponent(this.searchString)}`)
            .then((res) => {
                this.map.setOptions('zoom', 1);
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
            logoControl: true,
            mapDataControl: true,
            mapTypeControl: true,
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
