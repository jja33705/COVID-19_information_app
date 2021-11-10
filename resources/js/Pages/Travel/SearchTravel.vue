<template>
    <app-layout title="Travel">
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
                            text-base
                            focus:outline-none
                            w-full
                        "
                        type="search"
                        name="search"
                        placeholder="Search"
                        v-model="searchInput"
                        required
                        @keyup.enter="searchTravelSpots"
                    />
                    <button class="absolute right-0 top-0 mt-5 mr-4" @click="searchTravelSpots">
                        <svg
                            class="text-gray-600 h-4 w-4 fill-current"
                            xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink"
                            version="1.1"
                            id="Capa_1"
                            x="0px"
                            y="0px"
                            viewBox="0 0 56.966 56.966"
                            style="enable-background: new 0 0 56.966 56.966"
                            xml:space="preserve"
                            width="512px"
                            height="512px"
                        >
                            <path
                                d="M55.146,51.887L41.588,37.786c3.486-4.144,5.396-9.358,5.396-14.786c0-12.682-10.318-23-23-23s-23,10.318-23,23  s10.318,23,23,23c4.761,0,9.298-1.436,13.177-4.162l13.661,14.208c0.571,0.593,1.339,0.92,2.162,0.92  c0.779,0,1.518-0.297,2.079-0.837C56.255,54.982,56.293,53.08,55.146,51.887z M23.984,6c9.374,0,17,7.626,17,17s-7.626,17-17,17  s-17-7.626-17-17S14.61,6,23.984,6z"
                            />
                        </svg>
                    </button>
                </div>
                <div>
                    <button
                        @click="onClickNearButton"
                        class="
                            px-4
                            py-2
                            rounded-md
                            font-semibold
                            text-sm
                            font-medium
                            border-0
                            focus:outline-none focus:ring
                            transition
                            text-black-600
                            bg-purple-50
                            hover:text-black-800 hover:bg-purple-100
                            active:bg-purple-200
                            focus:ring-purple-300
                        "
                        type="submit"
                    >
                        주변 관광지 보기
                    </button>
                    
                    <label class="mx-2">시도:</label>
                    <select v-model="selectedAreaCode" @change="onChangeAreaCode">
                        <option value="" selected>-- 선택 없음 --</option>
                        <option :value="area.code" v-for="area in areas" :key="area.rnum">{{ area.name }}</option>
                    </select>

                    <label class="mx-2">시군구:</label>
                    <select v-model="selectedSigunguCode">
                        <option value="" selected>-- 선택 없음 --</option>
                        <option :value="sigungu.code" v-for="sigungu in sigungus" :key="sigungu.rnum">{{ sigungu.name }}</option>
                    </select>


                </div>
                
                
                <!-- 지도 -->
                <naver-map
                    :travelSpots="travelSpots"
                    :searchWay="searchWay"
                    :lat="lat"
                    :lng="lng"
                    :selectedTravelSpot="selectedTravelSpot"
                    :localCovidData="localCovidData"
                />

                <!-- 여행지 목록 -->
                <div class="mt-6 grid md:grid-cols-2 lg:grid-cols-4 gap-x-6 gap-y-8">
                    <travel-spot-card
                        v-for="travelSpot in travelSpots"
                        :key="travelSpot.contentid"
                        :travelSpot="travelSpot"
                        @click="onClickTravelSpot(travelSpot)"
                        :searchWay="searchWay"
                        :search="search"
                        :lat="lat"
                        :lng="lng"
                        :page="page"
                        :newDefCnt="newDefCntOfSpot(travelSpot.areacode)"
                    />
                </div>
                <div class="flex justify-center py-8">
                    <Link
                        :href="
                            route('travel.index', {
                                searchWay: searchWay,
                                search: search,
                                page: Number(page) - 1,
                                lat: lat,
                                lng: lng,
                            })
                        "
                        v-if="page > 1" preserve-scroll
                    >
                        <button
                            class="
                                border border-black
                                text-black
                                block
                                rounded-sm
                                font-bold
                                py-4
                                px-6
                                mr-2
                                flex
                                items-center
                                hover:bg-black hover:text-white
                            "
                        >
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
                    <Link
                        v-if="page * 12 < totalCount"
                        :href="
                            route('travel.index', {
                                searchWay: searchWay,
                                search: search,
                                page: Number(page) + 1,
                                lat: lat,
                                lng: lng,
                            })
                        " preserve-scroll
                    >
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
import TravelSpotCard from "@/Components/TravelSpotCard.vue";
import NaverMap from "@/Components/NaverMap.vue";

export default {
    components: {
        AppLayout,
        Link,
        PulseLoader,
        TravelSpotCard,
        NaverMap,
    },
    props: [
        "localCovidData",
        "page",
        "search",
        "searchWay",
        "lat",
        "lng",
        "areaCode",
        "sigunguCode",
    ],
    data() {
        return {
            searchInput: this.search ? decodeURIComponent(this.search) : "",
            selectedTravelSpot: null,
            selectedAreaCode: this.areaCode == null ? '' : this.areaCode,
            selectedSigunguCode: '',
            travelSpots: [],
            totalCount: 0,
            areas: [],
            sigungus: [],
        };
    },
    methods: {
        getCurrentLocationError() {
            //자기위치 가져오기(실패) 콜백
            console.log("cant get current location");
        },
        getCurrentLocationSuccess(position) {
            //자기위치 가져오기(성공) 콜백
            this.$inertia.get(
                `/travel?searchWay=near&lat=${position.coords.latitude}&lng=${position.coords.longitude}`
            );
        },
        onClickNearButton() {
            if (!navigator.geolocation) {
                //자기위치 가져오기
                console.log("cant get location in this browser");
            } else {
                navigator.geolocation.getCurrentPosition(
                    this.getCurrentLocationSuccess,
                    this.getCurrentLocationError
                );
            }
        },
        searchTravelSpots() {
            this.$inertia.get(
                `/travel?searchWay=keyword&search=${this.searchInput}`, { preserveScroll: false }
            );
        },
        onClickTravelSpot(travelSpot) {
            this.selectedTravelSpot = travelSpot;
        },
        newDefCntOfSpot(areaCode) {
            //해당 지역 확진자수
            console.log(this.areas);
            console.log(this.localCovidData);
            const areaName = AREA_CODE[areaCode];
            const local = this.localCovidData.find((e) => {
                if (areaName === e.gubun) {
                    return true;
                }
            });
            return local.newDefCnt;
        },
        onChangeAreaCode() { //지역 선택했을때
            if (!this.selectedAreaCode) {
                this.sigungus = [];
                return;
            } else {
                if (this.sigungus.length == 0) {
                    axios.get(`https://9wmf8sj38i.execute-api.ap-northeast-2.amazonaws.com/stage1/sigungus?areaCode=${this.selectedAreaCode}`)
                    .then((res) => {
                        console.log(res);
                        this.sigungus = res.data.response.body.items.item;
                    })
                    .catch((err) => {
                        console.log(err);
                    });
                }
            }
        },
        async initializeData() {
            try {
                //지역분류 불러오기
                const areaResponse = await axios.get('https://9wmf8sj38i.execute-api.ap-northeast-2.amazonaws.com/stage1/areas')
                console.log(areaResponse);
                this.areas = areaResponse.data.response.body.items.item;

                if (this.sigunguCode) {
                    const sigunguResponse = await axios.get(`https://9wmf8sj38i.execute-api.ap-northeast-2.amazonaws.com/stage1/sigungus?areaCode=${this.selectedAreaCode}`);
                    this.sigungus = res.data.response.body.items.item;
                    this.selectedSigunguCode = this.sigunguCode === null ? '' : this.sigunguCode;
                }

                //검색방법에 따라 분기처리
                switch(this.searchWay) {  //검색방법에 따라 분기처리
                    case 'near': //주변 관광지 데이터터
                        const nearResponse = await axios.get(`https://9wmf8sj38i.execute-api.ap-northeast-2.amazonaws.com/stage1/nearTravelSpots?lng=${this.lng}&lat=${this.lat}&page=${this.page}`);
                        console.log(nearResponse);
                        this.totalCount = nearResponse.data.response.body.totalCount;
                        if (this.totalCount > 0) {
                            if (nearResponse.data.response.body.items.item.length > 1) {
                                this.travelSpots = nearResponse.data.response.body.items.item;
                            } else {
                                this.travelSpots.push(nearResponse.data.response.body.items.item);
                            }
                        }
                        break;
                    case 'keyword': //키워드로 검색
                        const keywordResponse = await axios.get(`https://9wmf8sj38i.execute-api.ap-northeast-2.amazonaws.com/stage1/keywordTravelSpots?page=${this.page}&search=${this.search}`);
                        console.log(keywordResponse);
                        this.totalCount = keywordResponse.data.response.body.totalCount;
                        if (this.totalCount > 0) {
                            if (keywordResponse.data.response.body.items.item.length > 1) {
                                this.travelSpots = keywordResponse.data.response.body.items.item;
                            } else {
                                this.travelSpots.push(keywordResponse.data.response.body.items.item);
                            }
                        }
                        break;
                    default:
                        break;
                }
            } catch (err) {
                console.log(err);
            }

        },
    },
    mounted() {
        this.initializeData();
    }
};
</script>
