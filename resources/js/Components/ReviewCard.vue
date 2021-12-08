<template>
    <div class="bg-white">
        <div class="shadow-lg hover:shadow-xl">
            <Link :href="`/review/${review.id}`">
            <img class="w-full h-72 sm:h-60 lg:h-48 object-cover bg-gray-200" :src="review.images[0] ? `/storage/images/${review.images[0].filename}` : '/storage/images/no_image.png'" />
            </Link>
            <div class="px-4 py-2">
                <h1 class="text-xl font-gray-700 font-bold truncate ... mb-1">{{ review.title }}</h1>
                <p class="text-sm tracking-normal mb-1 font-gray-600">{{ review.place }}</p>
                <p class="text-sm tracking-normal font-semibold">{{ review.user.name }}</p>
            </div>
            <hr />
            <!-- <div class='px-5 py-3'>
                <h3 class="font-bold text-xs">TAGS</h3>
                <div class="my-2 flex -m-1 flex-wrap">
                    <hashtag v-for="hashtag in review.hashtags" :key="hashtag.id" :hashtag="hashtag" />
                </div>
            </div> -->
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
