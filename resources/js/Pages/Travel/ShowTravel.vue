<template>
    <app-layout title="Travel">
        <div class="py-8">
            <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
                <div class="flex justify-center text-4xl my-7 font-semibold">{{ travelSpot.title }}</div>
                <section class="mx-auto max-w-2xl my-7" v-if="images.length > 0">
                    <div class="shadow-2xl relative">
                        <div>
                            <img class="w-full object-cover" :src="images[imageIndex].originimgurl"/>
                        </div>
                        <a v-if="imageIndex > 0" class="absolute left-0 inset-y-0 flex items-center px-4 text-white hover:text-gray-800 cursor-pointer text-3xl font-extrabold" @click="onClickLeftImage">❮</a>
                        <a v-if="imageIndex < images.length - 1" class="absolute right-0 inset-y-0 flex items-center px-4 text-white hover:text-gray-800 cursor-pointer text-3xl font-extrabold" @click="onClickRightImage">❯</a>
                    </div>
                </section>
                <div class="text-2xl font-semibold mt-8">지역 신규 확진자</div>
                <hr />
                <canvas id="areaNewDefCntChart" class="w-full"></canvas>
                <div class="text-2xl font-semibold mt-8">위치</div>
                <hr />
                <naver-map :travelSpots="[travelSpot]" :localCovidData="localCovidData" />
                <div class="my-3">
                    <span>주소: {{ travelSpot.addr1 }}</span>
                    <span>{{ travelSpot.addr2 }}</span>
                </div>
                <div class="text-2xl font-semibold mt-8">상세정보</div>
                <hr />
                <div class="my-3" v-if="travelSpot.homepage">
                    <span>홈페이지: </span>
                    <span v-html="travelSpot.homepage"></span>
                </div>
                <div v-html="travelSpot.overview" class="text-lg mb-5"></div>
                <hr>
                <div class="my-5 flex justify-between">
                    <div class="flex">
                        <div class="text-2xl font-semibold">방문자 후기</div>
                        <div class="text-2xl text-green-700 font-bold mx-2">{{ reviewCount }}</div>
                    </div>
                    
                    <Link :href="`/review/create/${contentId}?place=${travelSpot.title}`">
                        <button v-if="$page.props.user" class="p-2 pl-5 mb-3 pr-5 bg-green-500 text-gray-100 text-lg rounded-lg focus:border-4 border-green-300">새 후기 작성
                    </button>
                    </Link>
                </div>
                <div class="sm:grid sm:grid-cols-2 gap-4 space-y-4 sm:space-y-0 pb-5">
                        <review-card v-for="review in reviews" :key="review.id" :review="review" />
                </div>
                <div class="flex justify-end">
                    <Link :href="`/review?searchWay=place&search=${travelSpot.title}`">후기 더 보기</Link>
                </div>
            </div>
        </div>
    </app-layout>
</template>




<script>

import AppLayout from "@/Layouts/AppLayout.vue";
import { Link } from "@inertiajs/inertia-vue3";
import NaverMap from '@/Components/NaverMap';
import ReviewCard from '@/Components/ReviewCard.vue';
import Chart from 'chart.js/auto';
export default {
    props: ['contentId', 'localCovidData', 'reviews', 'reviewCount', 'areaCovidData', 'gubun'],
    components: {
        AppLayout,
        Link,
        NaverMap,
        ReviewCard,
    },
    data() {
        return {
            imageIndex: 0,
            travelSpot: {},
            images: [],
        }
    },
    methods: {
        onClickLeftImage() {
            if (this.imageIndex < 1) {
                return;
            }
            this.imageIndex -= 1;
        },
        onClickRightImage() {
            if (this.imageIndex > this.images.length - 2) {
                return;
            }
            this.imageIndex += 1;
        },
    },
    mounted() {
        console.log('마운트 됨');
        //이미지 불러오기
        axios.get(`https://9wmf8sj38i.execute-api.ap-northeast-2.amazonaws.com/stage1/images?id=${this.contentId}`)
        .then((res) => {
            console.log(res);
            if (res.data.response.body.totalCount > 1) {
                if (res.data.response.body.items.item.length > 1) {
                    this.images = res.data.response.body.items.item;
                } else {
                    this.images = [res.data.response.body.items.item];
                }
            }
        })
        .catch((err) => {
            console.log(err);
        });

        //상세정보 불러오기
        axios.get(`https://9wmf8sj38i.execute-api.ap-northeast-2.amazonaws.com/stage1/travelSpot?id=${this.contentId}`)
        .then((res) => {
            console.log(res);
            this.travelSpot = res.data.response.body.items.item;
        })
        .catch((err) => {
            console.log(err);
        });

        // 최근 신규 확진자 차트를 그린다.
        const ctx = 'areaNewDefCntChart';
        const areaNewDefCntChartData = [];
        const areaNewDefCntChartLabels = [];
        [...this.areaCovidData].map((value) => {
            areaNewDefCntChartData.push(value.newDefCnt);
            areaNewDefCntChartLabels.push(value.stdDay.slice(-5));
        });
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: areaNewDefCntChartLabels,
                datasets: [{
                    label: `${this.gubun} 신규 확진자`,
                    data: areaNewDefCntChartData,
                    backgroundColor: 'rgb(255, 99, 132)',
                    borderColor: 'rgb(255, 99, 132)',
                }],
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    },
                },
            },
        });
    }
};
</script>