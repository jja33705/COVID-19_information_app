<template>
    <app-layout :title="Tour">

        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="shadow-xl">
                    <form @submit.prevent="searchTravelSpots">
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
                    <div class="flex flex-wrap my-4">
                        <div class="hover:bg-gray-100 xl:w-1/3 md:w-1/2 p-4 border-solid border-2" v-for="spot in travelSpots" :key="spot.contentid" @click="onClickTravel(spot)">
                            <div class="p-6 rounded-lg">
                                <img class="lg:h-60 xl:h-56 md:h-64 sm:h-72 xs:h-72 h-72  rounded w-full object-cover object-center mb-6" :src="spot.firstimage" :alt="spot.title">
                                <h3 class="tracking-widest text-indigo-500 text-xs font-medium title-font">{{ spot.addr1 }}</h3>
                                <div class="text-lg text-gray-900 font-medium title-font mb-4">
                                    <Link :href="route('travel.show', {id: spot.contentid})">
                                        {{ spot.title }}
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
const AREA_CODE = {
    1: '서울',
    2: '인천',
    3: '대전',
    4: '대구',
    5: '광주',
    6: '부산',
    7: '울산',
    8: '세종',
    31: '경기',
    32: '강원',
    33: '충북',
    34: '충남',
    35: '경북',
    36: '경남',
    37: '전북',
    38: '전남',
    39: '제주',
};

import { defineComponent } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link } from '@inertiajs/inertia-vue3';
export default defineComponent({
    components: {
        AppLayout,
        Link,
    },
    data() {
        return {
            map: null,
            lat: 0, //자기 위도
            lng: 0,  //자기 경도
            travelSpots: [],
            searchString: '',
            markers: [],
        };
    },
    methods: {
        clearMarkers() { //현재 마커 전부 삭제
            this.markers.map((v) => {
                v.setMap(null);
            });
            this.markers = [];
        },
        setMarkers() { //현재 가지고 있는 데이터들로 지도에 마커와 인포창 표시
            this.clearMarkers();
            this.travelSpots.map((v) => {
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
            this.getNeartravelSpots();
        },
        getCurrentLocationError() { //자기위치 가져오기(실패)
            console.log('cant get current location');
        },
        getNeartravelSpots() { //주변 관광지 데이터 가져오기(10km)
            // cors때문에 라라벨백엔드로 우회해서 받음...
            axios.get(`/api/nearTravelSpots?lat=${this.lat}&lng=${this.lng}`)
            .then((res) => {
                console.log(res);
                this.travelSpots = res.data.body.items.item;
                this.setMarkers();
            })
            .catch((err) => {
                console.log(err);
            });
        },
        searchTravelSpots() { //검색
            // cors때문에 라라벨백엔드로 우회해서 받음...
            axios.get(`/api/searchTravelSpots?search=${encodeURIComponent(this.searchString)}`)
            .then((res) => {
                this.travelSpots = res.data.body.items.item;
                this.setMarkers();
                this.map.setOptions('zoom', 1);
            })
            .catch((err) => {
                console.log(err);
            });
        },
        onClickTravel(tourSpot) { //여행지 하나 클릭
            //클릭한 여행지로 맵 위치 이동하고 줌 시킴
            const spot = new naver.maps.LatLng(tourSpot.mapy, tourSpot.mapx);
            this.map.setCenter(spot);
            this.map.setOptions('zoom', 10);
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
