<template>
    <div class="bg-white">
        <div class="shadow-lg hover:shadow-xl transform transition duration-500 hover:scale-105">
            <img class="w-full h-full sm:h-80 lg:h-48 object-contain bg-gray-100" :src="review.image ? `http://127.0.0.1:8000/storage/images/${review.image}` : 'http://127.0.0.1:8000/storage/images/no_image.png'" />
            <div class="px-4 py-2">
                <Link :href="`/review/${review.id}`">
                    <h1 class="text-xl font-gray-700 font-bold truncate ...">{{ review.title }}</h1>
                </Link>
                <p class="text-sm tracking-normal">{{ review.user.name }}</p>
            </div>
            <hr />
            <div class='px-5 py-3'>
                <h3 class="font-bold text-xs">TAGS</h3>
                <div class="my-2 flex -m-1 flex-nowrap overflow-x-scroll hide-scroll-bar" :class="`hashtag-${review.id}`" @wheel.prevent="hashtagWheelEvent">
                    <hashtag v-for="hashtag in review.hashtags" :key="hashtag.id" :hashtag="hashtag" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Hashtag from '@/Components/Hashtag.vue';
import { Link } from "@inertiajs/inertia-vue3";
export default {
    props: ['review'],
    components: {
        Hashtag,
        Link,
    },
    methods: {
        hashtagWheelEvent(e) { //해쉬태그칸 가로로 스크롤
            document.querySelector(`.hashtag-${this.review.id}`).scrollLeft += e.deltaY;
        }
    }
};
</script>

<style scoped>
.hide-scroll-bar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
.hide-scroll-bar::-webkit-scrollbar {
    display: none;
}
</style>