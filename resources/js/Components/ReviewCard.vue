<template>
    <div class="bg-white">
        <div class="shadow-lg hover:shadow-xl transform transition duration-500 hover:scale-105">
            <img class="w-full" src="https://i.imgur.com/lmYYa2s.png" />
            <div class="px-4 py-2">
                <h1 class="text-xl font-gray-700 font-bold truncate ...">{{ review.title }}</h1>
                <div class="flex space-x-2 mt-2">
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </span>
                    <h3 class="text-base text-gray-600 font-semibold mb-2 truncate ...">{{ review.place }}</h3>
                </div>
                <p class="text-sm tracking-normal truncate ...">{{ review.contents }}</p>
            </div>
            <hr />
            <div class='px-5 py-3'>
                <h3 class="font-bold text-xs" >TAGS</h3>
                <div class='my-3 flex -m-1 flex-nowrap overflow-x-scroll hide-scroll-bar' :class="`hashtag-${review.id}`" @wheel.prevent="hashtagWheelEvent">
                    <hashtag v-for="hashtag in review.hashtags" :key="hashtag.id" :hashtag="hashtag" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Hashtag from '@/Components/Hashtag.vue';
export default {
    props: ['review'],
    components: {
        Hashtag,
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