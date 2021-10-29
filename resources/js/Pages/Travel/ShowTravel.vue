<template>
    <app-layout>
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex justify-end">
                    <Link :href="route('travel.index', { searchWay: searchWay, search: search, page: page, lat: lat, lng: lng })">
                        <button class="px-4 py-2 rounded-md font-semibold text-sm font-medium border-0 focus:outline-none focus:ring transition text-black-600 bg-purple-50 hover:text-black-800 hover:bg-purple-100 active:bg-purple-200 focus:ring-purple-300" type="submit">목록으로</button>
                    </Link>
                </div>
                <div class="flex justify-center text-4xl my-7 font-semibold">{{ content.title }}</div>
                <img v-for="image in images" :key="image.serialnum" class="w-full" :src="image.originimgurl">
                <div class="my-3">
                    <span>주소: {{ content.addr1 }}</span>
                    <span>{{ content.addr2 }}</span>
                </div>
                <div class="my-3" v-if="content.homepage">
                    <span>홈페이지: </span>
                    <span v-html="content.homepage"></span>
                </div>
                <div v-html="content.overview" class="text-lg"></div>
            </div>
        </div>
    </app-layout>
</template>




<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link } from "@inertiajs/inertia-vue3";
export default {
    props: ['searchWay', 'page', 'search', 'lat', 'lng', 'content', 'images'],
    components: {
        AppLayout,
        Link,
    },
};
</script>