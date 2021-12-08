<template>
    <section class="mx-auto my-7 max-w-3xl" v-if="images.length">
        <div class="shadow-2xl relative">
            <div>
                <img class="w-full object-cover" :src="images[imageIndex].originimgurl"/>
            </div>
            <a v-if="imageIndex > 0" class="absolute left-0 inset-y-0 flex items-center px-4 text-white hover:text-gray-800 cursor-pointer text-3xl font-extrabold" @click="onClickLeftImage">❮</a>
            <a v-if="imageIndex < images.length - 1" class="absolute right-0 inset-y-0 flex items-center px-4 text-white hover:text-gray-800 cursor-pointer text-3xl font-extrabold" @click="onClickRightImage">❯</a>
        </div>
        <div class="py-3"></div>
        <div class="flex overflow-x-scroll bg-gray-100">
            <div class="flex">
                <img v-for="(image, index) in images" :key="image.serialnum" class="h-24 hover:opacity-100 cursor-pointer" :class="imageIndex === index ? 'opacity-100' : 'opacity-50'" :alt="image.serialnum" :src="image.smallimageurl" @click="onClickSmallImage(index)">
            </div>
        </div>
    </section>
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
        },
        onClickSmallImage(index) {
            this.imageIndex = index;
        }
    }
}
</script>