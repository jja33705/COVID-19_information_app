<template>
    <app-layout title="covid19">
        <div class="flex justify-center py-10 p-14">

            <!-- 확진자 정보 -->
            <div class="container mx-auto pr-4">
                <div class="max-w-sm bg-white mx-auto rounded-sm overflow-hidden shadow-lg hover:shadow-2xl transition duration-500 transform hover:scale-100 cursor-pointer">
                    <div class="h-16 bg-red-400 flex items-center justify-between">
                        <p class="mr-0 text-white text-lg pl-5">확진자 수</p>
                    </div>
                    <div class="flex justify-between px-5 pt-6 mb-2 text-sm text-gray-600">
                        {{ getStdDay }}일 기준
                    </div>
                    <div class="flex">
                        <span class="py-2 text-xl ml-5 font-medium">누적 확진자: {{ getDefCnt }}</span>
                        <covid-data-change :data="getDefCntChange >= 0 ? getDefCntChange : getDefCntChange * -1" :isRed="getDefCntChange > 0" :isUp="getDefCntChange > 0" />
                    </div>
                    <div class="flex">
                        <span class="py-2 text-xl ml-5 font-medium">신규 확진자: {{ getNewDefCnt }}</span>
                        <covid-data-change :data="getNewDefCntChange >= 0 ? getNewDefCntChange : getNewDefCntChange * -1" :isRed="getNewDefCntChange > 0" :isUp="getNewDefCntChange > 0" />
                    </div>
                    <div class="flex">
                        <span class="py-2 text-xl ml-5 font-medium">사망자: {{ getDeathCnt }}</span>
                        <covid-data-change :data="getDeathCntChange >= 0 ? getDeathCntChange : getDeathCntChange * -1" :isRed="getDeathCntChange > 0" :isUp="getDeathCntChange > 0" />
                    </div>
                    <div class="flex">
                        <span class="py-2 text-xl ml-5 font-medium">격리해제: <b>(일시 제공 중단)</b></span>
                        <!-- <covid-data-change :data="getIsolClearCntChange >= 0 ? getIsolClearCntChange : getIsolClearCntChange * -1" :isRed="getIsolClearCntChange < 0" :isUp="getIsolClearCntChange > 0" /> -->
                    </div>
                </div>
            </div>

            <!-- 신규 확진자 추세 차트 -->
            <div class="container mx-auto pr-4">
                <div class="max-w-sm bg-white mx-auto rounded-sm overflow-hidden shadow-lg hover:shadow-2xl transition duration-500 transform hover:scale-100 cursor-pointer">
                    <div class="h-16 bg-blue-500 flex items-center justify-between">
                        <p class="mr-0 text-white text-lg pl-5">신규 확진자 추세</p>
                    </div>
                    <div class="flex justify-between px-5 pt-6 mb-2">
                        <canvas id="newDefCntChart" width="500" height="400"></canvas>
                    </div>
                </div>
            </div>

            <!-- 지역별 정보 -->
            <div class="container mx-auto pr-4">
                <div class="max-w-sm bg-white mx-auto rounded-sm overflow-hidden shadow-lg hover:shadow-2xl transition duration-500 transform hover:scale-100 cursor-pointer">
                    <table class="text-left w-full border-collapse">
                        <thead>
                            <tr>
                                <th class="py-4 px-6 bg-purple-400 font-bold uppercase text-sm text-white border-b border-grey-light">지역</th>
                                <th class="py-4 px-6 text-center bg-purple-400 font-bold uppercase text-sm text-white border-b border-grey-light">신규 확진자</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="data in getPaginatedLocalData" :key="data.id" class="hover:bg-grey-lighter">
                                <td class="py-2 px-6 border-b border-grey-light font-medium">{{ data.gubun }}</td>
                                <td class="py-2 px-6 text-center border-b border-grey-light font-medium">
                                    {{ data.newDefCnt }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <!-- 지역별 정보 페이지네이션 -->
                    <div class="px-5 py-4 bg-white border-t flex flex-col xs:flex-row items-center xs:justify-between">
                        <span class="text-xs xs:text-sm text-gray-900">
                        Showing {{ ((localDataPage-1) * 5) + 1 }} to {{ ((this.localDataPage-1) * 5) + 5 < localData.length ? ((this.localDataPage-1) * 5) + 5 : localData.length }} of {{ localData.length }} Entries
                        </span>
                        <div class="inline-flex mt-2 xs:mt-0">
                            <button @click="onClickPrev" class="text-sm bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded-l">Prev</button>
                            <button @click="onClickNext"  class="text-sm bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded-r">Next</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import Chart from 'chart.js/auto';
import CovidDataChange from '@/Components/CovidDataChange'

export default ({
    components: {
        AppLayout,
        Head,
        Link,
        CovidDataChange,
    },
    props: {
        localData: Array,
        totalData: Array,
    },
    mounted() {
        // 최근 신규 확진자 차트를 그린다.
        const ctx = 'newDefCntChart';
        const newDefCntChartData = [];
        const newDefCntChartLabels = [];
        this.totalData.forEach((value) => {
            newDefCntChartData.push(value.newDefCnt);
            newDefCntChartLabels.push(value.stdDay.slice(-5));
        });
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: newDefCntChartLabels,
                datasets: [{
                    label: '신규 확진자',
                    data: newDefCntChartData,
                    backgroundColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(54, 162, 235, 1)',
                    ],
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
    },
    data() {
        return {
            localDataPage: 1,
        };
    },
    methods: {
        onClickPrev() { //지역 데이터 이전
            if (this.localDataPage > 1) {
                this.localDataPage -= 1
            }
        },
        onClickNext() {  //지역 데이터 다음
            if (this.localDataPage * 5 < this.localData.length) {
                this.localDataPage += 1
            }
        },
    },
    computed: {
        getPaginatedLocalData() { //페이지네이션한 지역별 데이터
            return this.localData.slice(((this.localDataPage-1) * 5), ((this.localDataPage-1) * 5) + 5);
        },
        getStdDay() { //기준시간
            return this.totalData[this.totalData.length-1].stdDay;
        },
        getNewDefCnt() { //신규확진자
            return this.totalData[this.totalData.length-1].newDefCnt;
        },
        getNewDefCntChange() { //신규확진자 변화
            return this.totalData[this.totalData.length-1].newDefCnt - this.totalData[this.totalData.length-2].newDefCnt;
        },
        getDefCnt() { // 누적 확진자 변화
            return this.totalData[this.totalData.length-1].defCnt;
        },
        getDefCntChange() { // 누적 확진자 변화
            return this.totalData[this.totalData.length-1].defCnt - this.totalData[this.totalData.length-2].defCnt;
        },
        getDeathCnt() { //사망자
            return this.totalData[this.totalData.length-1].deathCnt;
        },
        getDeathCntChange() { //사망자 변화
            return this.totalData[this.totalData.length-1].deathCnt - this.totalData[this.totalData.length-2].deathCnt;
        },
        getIsolClearCnt() { //격리해제 환자
            return this.totalData[this.totalData.length-1].isolClearCnt;
        },
        getIsolClearCntChange() { //격리해제 환자 변화
            return this.totalData[this.totalData.length-1].isolClearCnt - this.totalData[this.totalData.length-2].isolClearCnt;
        },
    },
});
</script>
