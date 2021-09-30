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
            <div v-if="covidData">{{ covidData }}</div>
            <div>기준날짜: {{ getRecentDate }}</div>
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
        console.log(todayDateString);

        const previousDay = new Date(today.setDate(today.getDate() - 8));
        const previousDateString = makeDateString(previousDay);
        console.log(previousDateString);

        axios
            .get(
                `https://cors.bridged.cc/http://openapi.data.go.kr/openapi/service/rest/Covid19/getCovid19InfStateJson?serviceKey=4lyV1AhLwS2E8AbWo7qJKIsGqL8UPCTIqKP7LkFo62%2BZbmluePY8GC9jW7J0d5IlpfRGcRPk5e3er8Nvg08YIQ%3D%3D&pageNo=1&numOfRows=10&startCreateDt=${previousDateString}&endCreateDt=${todayDateString}`
            )
            .then((res) => {
                console.log(res);
                this.covidData = res.data.response.body.items.item.slice(0, 7);
                //가장 최근부터 7일간의 데이터를 가져옴옴
            })
            .catch((err) => {
                console.log(err);
            });
    },
    data() {
        return {
            covidData: [],
        };
    },
    methods: {},
    computed: {
        getRecentDate() {
            // const date = new Date(this.covidData[0].stateDt);
            if(this.covidData[0]) {
                return this.covidData[0].stateDt;
            }
        },
    },
});
</script>
