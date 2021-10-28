<template>
    <app-layout :title="Tour">
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="pt-2 relative mx-auto text-gray-600">
                    <input
                        class="
                            border-2 border-gray-300
                            bg-white
                            h-10
                            px-5
                            pr-16
                            rounded-lg
                            text-sm
                            focus:outline-none
                            w-full
                        "
                        type="search"
                        name="search"
                        placeholder="Search"
                        v-model="searchInput"
                        required
                    />
                    <Link :href="route('travel.index', { searchWay: 'keyWord', search: encodeURIComponent(searchInput) })">
                        <button
                            class="absolute right-0 top-0 mt-5 mr-4"
                        >
                            <svg
                                class="text-gray-600 h-4 w-4 fill-current"
                                xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink"
                                version="1.1"
                                id="Capa_1"
                                x="0px"
                                y="0px"
                                viewBox="0 0 56.966 56.966"
                                style="
                                    enable-background: new 0 0 56.966 56.966;
                                "
                                xml:space="preserve"
                                width="512px"
                                height="512px"
                            >
                                <path
                                    d="M55.146,51.887L41.588,37.786c3.486-4.144,5.396-9.358,5.396-14.786c0-12.682-10.318-23-23-23s-23,10.318-23,23  s10.318,23,23,23c4.761,0,9.298-1.436,13.177-4.162l13.661,14.208c0.571,0.593,1.339,0.92,2.162,0.92  c0.779,0,1.518-0.297,2.079-0.837C56.255,54.982,56.293,53.08,55.146,51.887z M23.984,6c9.374,0,17,7.626,17,17s-7.626,17-17,17  s-17-7.626-17-17S14.61,6,23.984,6z"
                                />
                            </svg>
                        </button>
                    </Link>
                </div>
                <div>
                    <button @click="onClickNearButton" class="px-4 py-2 rounded-md font-semibold text-sm font-medium border-0 focus:outline-none focus:ring transition text-black-600 bg-purple-50 hover:text-black-800 hover:bg-purple-100 active:bg-purple-200 focus:ring-purple-300" type="submit">주변 관광지 보기</button>
                </div>
                <div id="map" style="width: 100%; height: 400px"></div>
                <div class="flex flex-wrap mt-4">
                    <travel-spot-card v-for="travelSpot in travelSpots"
                        :key="travelSpot.contentid" :travelSpot="travelSpot" @click="onClickTravel(travelSpot)" :searchWay="searchWay" :search="search" :lat="lat" :lng="lng" :page="page"/>
                </div>
                <!-- <div v-if="loading" class="flex justify-center my-5">
                    <pulse-loader
                        :loading="loading"
                        :color="loadingColor"
                    ></pulse-loader>
                </div> -->
                <!-- <div v-if="!loading && travelSpots.lenth === 0" class="flex justify-center my-4">검색결과 없음</div> -->
                <div class="flex justify-center py-8">
                    <Link :href="route('travel.index', { searchWay: searchWay, search: search, page: Number(page)-1, lat: lat, lng: lng })" v-if="page > 1">
                        <button class="border border-black text-black block rounded-sm font-bold py-4 px-6 mr-2 flex items-center hover:bg-black hover:text-white">
                            <svg
                                class="h-5 w-5 mr-2 fill-current"
                                version="1.1"
                                id="Layer_1"
                                xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink"
                                x="0px"
                                y="0px"
                                viewBox="-49 141 512 512"
                                style="enable-background: new -49 141 512 512"
                                xml:space="preserve"
                            >
                                <path
                                    id="XMLID_10_"
                                    d="M438,372H36.355l72.822-72.822c9.763-9.763,9.763-25.592,0-35.355c-9.763-9.764-25.593-9.762-35.355,0 l-115.5,115.5C-46.366,384.01-49,390.369-49,397s2.634,12.989,7.322,17.678l115.5,115.5c9.763,9.762,25.593,9.763,35.355,0 c9.763-9.763,9.763-25.592,0-35.355L36.355,422H438c13.808,0,25-11.193,25-25S451.808,372,438,372z"
                                ></path>
                            </svg>
                            Previous page
                        </button>
                    </Link>
                    <Link v-if="page * 12 < totalCount" :href="route('travel.index', { searchWay: searchWay, search: search, page: Number(page)+1, lat: lat, lng: lng })">
                        <button
                            class="
                                border border-black
                                text-black
                                block
                                rounded-sm
                                font-bold
                                py-4
                                px-6
                                ml-2
                                flex
                                items-center
                                hover:bg-black hover:text-white
                            "
                        >
                            Next page
                            <svg
                                class="h-5 w-5 ml-2 fill-current"
                                clasversion="1.1"
                                id="Layer_1"
                                xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink"
                                x="0px"
                                y="0px"
                                viewBox="-49 141 512 512"
                                style="enable-background: new -49 141 512 512"
                                xml:space="preserve"
                            >
                                <path
                                    id="XMLID_11_"
                                    d="M-24,422h401.645l-72.822,72.822c-9.763,9.763-9.763,25.592,0,35.355c9.763,9.764,25.593,9.762,35.355,0l115.5-115.5C460.366,409.989,463,403.63,463,397s-2.634-12.989-7.322-17.678l-115.5-115.5c-9.763-9.762-25.593-9.763-35.355,0
                            c-9.763,9.763-9.763,25.592,0,35.355l72.822,72.822H-24c-13.808,0-25,11.193-25,25S-37.808,422-24,422z"
                                />
                            </svg>
                        </button>
                    </Link>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
const AREA_CODE = {
    1: "서울",
    2: "인천",
    3: "대전",
    4: "대구",
    5: "광주",
    6: "부산",
    7: "울산",
    8: "세종",
    31: "경기",
    32: "강원",
    33: "충북",
    34: "충남",
    35: "경북",
    36: "경남",
    37: "전북",
    38: "전남",
    39: "제주",
};

import AppLayout from "@/Layouts/AppLayout.vue";
import { Link } from "@inertiajs/inertia-vue3";
import PulseLoader from "vue-spinner/src/PulseLoader.vue";
import TravelSpotCard from '@/Components/TravelSpotCard.vue';

export default {
    components: {
        AppLayout,
        Link,
        PulseLoader,
        TravelSpotCard,
    },
    props: ['localData', 'searchResult', 'page', 'search', 'totalCount', 'searchWay', 'lat', 'lng'],
    data() {
        return {
            map: null,
            travelSpots: this.searchResult,
            searchInput: this.search ? decodeURIComponent(this.search) : '',
            // loading: true,
            loadingColor: "#000000",
            newDefCnts: {}, //신규 확진자
        };
    },
    methods: {
        onClickNearButton() {
            const getCurrentLocationError = () => { //자기위치 가져오기(실패) 콜백
                console.log("cant get current location");
            }
            const getCurrentLocationSuccess = (position) => { //자기위치 가져오기(성공) 콜백
                // this.setMyLocation({ lat: position.coords.latitude, lng: position.coords.longitude });
                this.$inertia.get(`/travel?searchWay=near&lat=${position.coords.latitude}&lng=${position.coords.longitude}`)
            }
            if (!navigator.geolocation) { //자기위치 가져오기
                console.log("cant get location in this browser");
            } else {
                navigator.geolocation.getCurrentPosition(
                    getCurrentLocationSuccess,
                    getCurrentLocationError
                );
            }
        },
        clearMarkersAndInfoWindows() {
            if (this.travelSpots.length === 0) {
                return;
            }
            this.travelSpots.map((v) => {
                v.marker.setMap(null);
                v.infoWindow.close();
            });
        },
        setMarkers() {
            if (this.travelSpots.length === 0) {
                return;
            }
            //현재 가지고 있는 데이터들로 지도에 마커와 인포창 표시
            this.travelSpots.map((v) => {
                const spot = new naver.maps.LatLng(v.mapy, v.mapx);
                const marker = new naver.maps.Marker({
                    map: this.map,
                    position: spot,
                });
                v.marker = marker;
                var contentString = [
                    '<div class="iw_inner">',
                    `   <div class="font-bold">${v.title}</div>`,
                    "</div>",
                ].join("");
                const infoWindow = new naver.maps.InfoWindow({
                    content: contentString,
                    borderWidth: 0,
                    disableAnchor: true,
                    backgroundColor: 'transparent',
                });
                v.infoWindow = infoWindow;

                //마커 클릭시 정보창 보여줌
                naver.maps.Event.addListener(marker, "click", (e) => {
                    if (infoWindow.getMap()) {
                        infoWindow.close();
                    } else {
                        console.log(this);
                        infoWindow.open(this.map, marker);
                    }
                });
            });
        },
        // getCurrentLocationSuccess(position) {
        //     //자기위치 가져오기(성공)
        //     this.lat = position.coords.latitude;
        //     this.lng = position.coords.longitude;
        //     const currentLocation = new naver.maps.LatLng(this.lat, this.lng);
        //     this.map.setCenter(currentLocation);
        //     this.map.setOptions("zoom", 10);
        //     new naver.maps.Marker({
        //         map: this.map,
        //         position: currentLocation,
        //         icon: {
        //             path: naver.maps.SymbolPath.CIRCLE,
        //             style: naver.maps.SymbolStyle.CIRCLE,
        //             fillColor: "#FF0000",
        //             fillOpacity: 1,
        //             strokeColor: "#000000",
        //             strokeStyle: "solid",
        //             strokeWeight: 1,
        //             radius: 10,
        //         },
        //     });
        //     this.getNearTravelSpots();
        // },

        // getNearTravelSpots() {
        //     //주변 관광지 데이터 가져오기(10km)
        //     // cors때문에 라라벨백엔드로 우회해서 받음...
        //     this.loading = true;
        //     axios
        //         .get(
        //             `/api/nearTravelSpots?lat=${this.lat}&lng=${this.lng}&page=${this.page}`
        //         )
        //         .then((res) => {
        //             this.clearMarkersAndInfoWindows();
        //             console.log(res);
        //             this.totalCount = res.data.body.totalCount;
        //             if (this.totalCount == 0) {
        //                 this.travelSpots = []
        //             } else {
        //                 if (!res.data.body.items.item.length) { //길이가 1일때
        //                     this.travelSpots = [res.data.body.items.item];
        //                 } else {
        //                     this.travelSpots = res.data.body.items.item;
        //                 }
        //             }
        //             this.loading = false;
        //             this.setMarkers();
        //         })
        //         .catch((err) => {
        //             console.log(err);
        //         });
        // },
        // searchTravelSpots() {
        //     //검색
        //     // cors때문에 라라벨백엔드로 우회해서 받음...
        //     this.loading = true;
        //     axios
        //         .get(
        //             `/api/searchTravelSpots?search=${encodeURIComponent(
        //                 this.searched
        //             )}&page=${this.page}`
        //         )
        //         .then((res) => {
        //             this.clearMarkersAndInfoWindows();
        //             console.log(res);
        //             this.totalCount = res.data.body.totalCount;
        //             if (this.totalCount == 0) {
        //                 this.travelSpots = []
        //             } else {
        //                 if (!res.data.body.items.item.length) { //길이가 1일때
        //                     this.travelSpots = [res.data.body.items.item];
        //                 } else {
        //                     this.travelSpots = res.data.body.items.item;
        //                 }
        //             }
        //             this.loading = false;
        //             this.map.setOptions("zoom", 1);
        //             this.setMarkers();
        //         })
        //         .catch((err) => {
        //             console.log(err);
        //         });
        // },
        onClickTravel(travelSpot) {
            //여행지 하나 클릭
            //클릭한 여행지로 맵 위치 이동하고 줌 시킴
            const spot = new naver.maps.LatLng(travelSpot.mapy, travelSpot.mapx);
            this.map.setCenter(spot);
            this.map.setOptions("zoom", 13);
            travelSpot.infoWindow.open(this.map, travelSpot.marker);
        },
        getNewDefCntOfSpot(areaCode) { //해당 지역 확진자수
            return this.newDefCnts[AREA_CODE[areaCode]];
        },
    },
    mounted() {
        //지역별 확진자수 초기화...
        this.localData.map((v) => {
            this.newDefCnts[v.gubun] = v.localOccCnt + v.overFlowCnt;
        });

        //맵 생성
        const mapOptions = {
            center: new naver.maps.LatLng(37.3595704, 127.105399),
            logoControl: true,
            mapDataControl: true,
            mapTypeControl: true,
            zoom: 1,
        };
        this.map = new naver.maps.Map("map", mapOptions);


    },
};
</script>
