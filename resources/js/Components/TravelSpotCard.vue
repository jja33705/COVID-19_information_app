<template>
    <article @click="$emit('onClickTravelSpot', travelSpot.contentid)" class="bg-white group relative rounded-lg overflow-hidden shadow-lg hover:shadow-2xl transform duration-200 hover:scale-110 transition hover:z-10">
        <div class="relative w-full h-80 md:h-64 lg:h-44">
            <img :src="travelSpot.firstimage ? travelSpot.firstimage : '/storage/images/no_image.png'" :alt="travelSpot.title" class="w-full h-full object-center object-cover">
        </div>
        <div class="px-3 py-4">
            <h3 class="text-sm text-gray-500 pb-2">
                <span class="py-1 px-2 text-white rounded-lg" :class="colorClass">
                    <span class="inset-0"></span>
                    지역 신규 확진자 수: {{ newDefCnt }}명
                </span>
            </h3>
            <h3 class="tracking-widest text-gray-600 text-xs font-medium title-font">
                {{ travelSpot.addr1 }}
            </h3>
            <button class="text-base font-semibold text-gray-800 group-hover:text-black">
                {{ travelSpot.title }}
            </button>
        </div>
    </article>
</template>


<script>
import { Link } from "@inertiajs/inertia-vue3";
export default {
    components: {
        Link,
    },
    props: ['travelSpot', 'newDefCnt'],
    computed: {
        colorClass() {
            if (this.newDefCnt >= 1000) {
                return 'bg-red-600';
            }
            if (this.newDefCnt >= 100) {
                return 'bg-indigo-600';
            }
                return 'bg-green-600';
        }
    }
};
</script>
