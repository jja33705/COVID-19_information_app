<template>
    <div class="bg-gray-100 rounded-md mx-auto max-w-2xl relative p-2 sm:p-4 overflow-hidden" v-if="images.length">

        <div class="rounded-full bg-gray-600 text-white absolute top-5 right-5 text-sm px-2 text-center z-10">
            <span>{{ imageIndex + 1 }}</span>/<span>{{ images.length }}</span>
        </div>

        <button @click="onClickPrevious" v-if="imageIndex > 0" class="absolute left-5 top-1/2 -translate-y-1/2 bg-gray-100 rounded-full w-11 h-11 flex justify-center items-center shadow-md z-10">
            <svg class="w-8 h-8 font-bold text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7">
                </path>
            </svg>
        </button>

        <button @click="onClickNext" v-if="imageIndex < images.length - 1" class="absolute right-5 top-1/2 -translate-y-1/2 bg-gray-100 rounded-full w-11 h-11 flex justify-center items-center shadow-md z-10">
            <svg class="w-8 h-8 font-bold text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
            </svg>
        </button>

        <div class="relative">
            <div>
                <img alt="image" class="w-full" :src="`/storage/images/${images[imageIndex].filename}`">
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['images'],
    data() {
        return {
            imageIndex: 0,
        };
    },
    methods: {
        onClickPrevious() {
            if (this.imageIndex < 1) {
                return;
            }
            this.imageIndex -= 1;
        },
        onClickNext() {
            if (this.imageIndex > this.images.length - 2) {
                return;
            }
            this.imageIndex += 1;
        }
    }
}
</script>