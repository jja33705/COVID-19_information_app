<template>
    <Head title="home" />
    <div
        class="
            relative
            flex
            items-top
            justify-center
            min-h-screen
            bg-gray-100
            dark:bg-gray-900
            sm:items-center sm:pt-0
        "
    >
        <div
            v-if="canLogin"
            class="hidden fixed top-0 right-0 px-6 py-4 sm:block"
        >
            <Link
                v-if="$page.props.user"
                :href="route('dashboard')"
                class="text-sm text-gray-700 underline"
            >
                Dashboard
            </Link>

            <template v-else>
                <Link
                    :href="route('login')"
                    class="text-sm text-gray-700 underline"
                >
                    Log in
                </Link>

                <Link
                    v-if="canRegister"
                    :href="route('register')"
                    class="ml-4 text-sm text-gray-700 underline"
                >
                    Register
                </Link>
            </template>
        </div>

        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div>기준날짜: {{ getStdDay }}</div>
            <div>신규 확진자: {{ getNewDefCnt }}</div>
            <div>확진자 수: {{ getDefCnt }}</div>
            <div>사망자 수: {{ getDeathCnt }}</div>
            <div>격리해제 수: {{ getIsolClearCnt }}</div>
            <canvas id="newDefCntChart" width="400" height="400"></canvas>

            <div class="flex justify-around">
                <div>123</div>
                <div>456</div>
                <div>789</div>
            </div>
        </div>
    </div>
</template>

<script>
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
