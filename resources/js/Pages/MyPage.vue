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
                    <button @click="onClickCountButton">확진자수</button>
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
            axios
                .get(
                    "https://cors.bridged.cc/https://api.odcloud.kr/api/15077586/v1/centers?page=1&perPage=10&serviceKey=wZXfBdmDrek55joz9YP0lhLRj5Do59jCcELbR0NGdpZzCFpnufcYpFMgike1cU3tpG1MDQpS6NcbibCFr1S58A%3D%3D"
                )
                .then((res) => {
                    console.log(res);
                    this.data = res.data;
                })
                .catch((err) => {
                    console.log(err);
                });
        },
        onClickCountButton() {
            axios.get(`https://cors.bridged.cc/http://openapi.data.go.kr/openapi/service/rest/Covid19/getCovid19InfStateJson?${encodeURIComponent('ServiceKey')}=${encodeURIComponent('4lyV1AhLwS2E8AbWo7qJKIsGqL8UPCTIqKP7LkFo62+ZbmluePY8GC9jW7J0d5IlpfRGcRPk5e3er8Nvg08YIQ==')}&pageNo=1&numOfRows=10&startCreateDt=20200310&endCreateDt=20200315`)
            .then((res) => {
                console.log(res);
            })
            .catch((err) => {
                console.log(err);
            });
        }
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
    },
});
</script>
