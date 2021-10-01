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
            <div>{{ localData }}</div>
            <div v-if="totalData[0]">
                <div>기준날짜: {{ getStdDay }}</div>
                <div>신규 확진자: {{ getNewDefCnt }}</div>
                <div>확진자 수: {{ getDefCnt }}</div>
                <div>사망자 수: {{ getDeathCnt }}</div>
                <div>격리해제 수: {{ getIsolClearCnt }}</div>
            </div>
            <canvas id="newDefCntChart" width="200" height="200"></canvas>

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

const TOTAL_INDEX=18;

const makeDateString = (date) => {
    const year = date.getFullYear();
    const month = ("0" + (date.getMonth() + 1)).slice(-2);
    const day = ("0" + date.getDate()).slice(-2);
    return year + month + day;
};

export default defineComponent({
    components: {
        Head,
        Link,
    },
    props: {
        canLogin: Boolean,
        canRegister: Boolean,
    },
    mounted() {
        const today = new Date();
        const todayDateString = makeDateString(today);

        const previousDay = new Date(today.setDate(today.getDate() - 7));
        const previousDateString = makeDateString(previousDay);

        axios.get(`https://cors.bridged.cc/http://openapi.data.go.kr/openapi/service/rest/Covid19/getCovid19SidoInfStateJson?serviceKey=4lyV1AhLwS2E8AbWo7qJKIsGqL8UPCTIqKP7LkFo62%2BZbmluePY8GC9jW7J0d5IlpfRGcRPk5e3er8Nvg08YIQ%3D%3D&pageNo=1&numOfRows=10&startCreateDt=${previousDateString}&endCreateDt=${todayDateString}`)
        .then((res) => {
            console.log(res);
            const covidItems = res.data.response.body.items.item.slice(0, (TOTAL_INDEX + 1) * 7);
            for (let i = 0; i < covidItems.length; i += (TOTAL_INDEX + 1)){
                this.localData.push(covidItems.slice(i, i + TOTAL_INDEX));
                this.totalData.push(covidItems[i + TOTAL_INDEX]);
            }
            //가장 최근부터 7일간의 데이터를 가져와서 지역별, 합계데이터를 나눠서 저장함.
            console.log(this.localData);
            console.log(this.totalData);

            // 최근 신규 확진자 차트를 그린다.
            const ctx = 'newDefCntChart';
            const newDefCntChartData = [];
            const newDefCntChartLabels = [];
            this.totalData.reverse().map((value) => {
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


        })
        .catch((err) => {
            console.log(err);
        });
    },
    data() {
        return {
            localData: [],
            totalData: [],
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
