<template>
    <app-layout title="covid19">
        <div class="flex justify-center py-10 p-14">

            <!-- 확진자 정보 -->
            <div class="container mx-auto pr-4">
                <div class="max-w-sm bg-white mx-auto rounded-sm overflow-hidden shadow-lg hover:shadow-2xl transition duration-500 transform hover:scale-100 cursor-pointer">
                    <div class="h-16 bg-red-400 flex items-center justify-between">
                        <p class="mr-0 text-white text-lg pl-5">확진자 정보</p>
                    </div>
                    <div class="flex justify-between px-5 pt-6 mb-2 text-sm text-gray-600">
                        {{ getStdDay }}일 기준
                    </div>
                    <p class="py-2 text-xl ml-5 font-medium">확진자 수: {{ getDefCnt }}</p>
                    <p class="py-2 text-xl ml-5 font-medium">신규 확진자: {{ getNewDefCnt }}</p>
                    <p class="py-2 text-xl ml-5 font-medium">사망자 수: {{ getDeathCnt }}</p>
                    <p class="py-2 text-xl ml-5 font-medium">격리해제 수: {{ getIsolClearCnt }}</p>
                </div>
            </div>

            <!-- 신규 확진자 추세 차트트 -->
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
                                    {{ data.localOccCnt + data.overFlowCnt }}
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

export default ({
    components: {
        AppLayout,
        Head,
        Link,
    },
    props: {
        localData: Array,
        totalData: Array,
    },
    mounted() {
        console.log(this.localData);
        console.log(this.totalData);

        // 최근 신규 확진자 차트를 그린다.
        const ctx = 'newDefCntChart';
        const newDefCntChartData = [];
        const newDefCntChartLabels = [];
        [...this.totalData].map((value) => {
            newDefCntChartData.push(value.localOccCnt + value.overFlowCnt);
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
            return this.totalData[this.totalData.length-1].localOccCnt + this.totalData[0].overFlowCnt;
        },
        getDefCnt() { //확진자
            return this.totalData[this.totalData.length-1].defCnt;
        },
        getDeathCnt() { //사망자
            return this.totalData[this.totalData.length-1].deathCnt;
        },
        getIsolClearCnt() { //격리해제 환자
            return this.totalData[this.totalData.length-1].isolClearCnt;
        },
    },
});
</script>
