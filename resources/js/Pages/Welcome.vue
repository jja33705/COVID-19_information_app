<template>
    <app-layout :title="covid19_TRAVEL">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                covid19_TRAVEL
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div>기준날짜: {{ getStdDay }}</div>
                        <div>신규 확진자: {{ getNewDefCnt }}</div>
                        <div>확진자 수: {{ getDefCnt }}</div>
                        <div>사망자 수: {{ getDeathCnt }}</div>
                        <div>격리해제 수: {{ getIsolClearCnt }}</div>
                        <canvas id="newDefCntChart" width="600" height="400"></canvas>
                        <table class="w-1/2 border-collapse border border-blue-400">
                            <thead>
                                <tr>
                                    <th class="border border-blue-400">지역</th>
                                    <th class="border border-blue-400">신규 확진자</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="data in localData" :key="data.id">
                                    <td class="border border-blue-400">{{ data.gubun }}</td>
                                    <td class="border border-blue-400">{{ data.localOccCnt + data.overFlowCnt }}</td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="flex justify-around">
                            <div>123</div>
                            <div>456</div>
                            <div>789</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>


</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import { defineComponent } from "vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import Chart from 'chart.js/auto';

const AREA_CODE = {
    'Seoul': 1,
    'Incheon': 2,
    'Daejeon': 3,
    'Daegu': 4,
    'Gwangju': 5,
    'Busan': 6,
    'Ulsan': 7,
    'Sejong': 8,
    'Gyeonggi-do': 31,
    'Gangwon-do': 32,
    'Chungcheongbuk-do': 33,
    'Chungcheongnam-do': 34,
    'Gyeongsangbuk-do': 35,
    'Chungcheongnam-do': 36,
    'Jeollabuk-do': 37,
    'Jeollanam-do': 38,
    'Jeju': 39,
};

export default defineComponent({
    components: {
        AppLayout,
        Head,
        Link,
    },
    props: {
        canLogin: Boolean,
        canRegister: Boolean,
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
        [...this.totalData].reverse().map((value) => {
            newDefCntChartData.push(value.localOccCnt + value.overFlowCnt);
            newDefCntChartLabels.push(value.stdDay);
        });
        const myChart = new Chart(ctx, {
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

        };
    },
    methods: {},
    computed: {
        getStdDay() { //기준시간
            return this.totalData[0].stdDay;
        },
        getNewDefCnt() { //신규확진자
            return this.totalData[0].localOccCnt + this.totalData[0].overFlowCnt;
        },
        getDefCnt() { //확진자
            return this.totalData[0].defCnt;
        },
        getDeathCnt() { //사망자
            return this.totalData[0].deathCnt;
        },
        getIsolClearCnt() { //격리해제 환자
            return this.totalData[0].isolClearCnt;
        },
    },
});
</script>
