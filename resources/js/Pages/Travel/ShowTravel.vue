<template>
    <app-layout title="Travel">
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex justify-end">
                    <Link :href="route('travel.index', { searchWay: searchWay, search: search, page: page, lat: lat, lng: lng })">
                        <button class="px-4 py-2 rounded-md font-semibold text-sm font-medium border-0 focus:outline-none focus:ring transition text-black-600 bg-purple-50 hover:text-black-800 hover:bg-purple-100 active:bg-purple-200 focus:ring-purple-300" type="submit">목록으로</button>
                    </Link>
                </div>
                <div class="flex justify-center text-4xl mt-7 font-semibold">{{ content.title }}</div>
                <section class="mx-auto max-w-2xl my-7">
                    <div class="shadow-2xl relative">
                        <div>
                            <img class="w-full object-cover" :src="images[imageIndex].originimgurl"/>
                        </div>
                        <a v-if="imageIndex > 0" class="absolute left-0 inset-y-0 flex items-center px-4 text-white hover:text-gray-800 cursor-pointer text-3xl font-extrabold" @click="onClickLeftImage">❮</a>
                        <a v-if="imageIndex < images.length - 1" class="absolute right-0 inset-y-0 flex items-center px-4 text-white hover:text-gray-800 cursor-pointer text-3xl font-extrabold" @click="onClickRightImage">❯</a>
                    </div>
                </section>

                <naver-map :searchResult="[content]" :searchWay="searchWay" :lat="lat" :lng="lng" :localData="localData" />
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
import NaverMap from '@/Components/NaverMap';
export default {
    props: ['searchWay', 'page', 'search', 'lat', 'lng', 'content', 'images', 'localData'],
    components: {
        AppLayout,
        Link,
        NaverMap,
    },
    data() {
        return {
            imageIndex: 0,
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
        }
    }
};
</script>