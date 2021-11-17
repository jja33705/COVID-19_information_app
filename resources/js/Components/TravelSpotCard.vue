<template>
    <article @click="$emit('onClickTravelSpot', travelSpot.contentid)" class="bg-white group relative rounded-lg overflow-hidden shadow-lg hover:shadow-2xl transform duration-200 hover:scale-110 transition hover:z-10">
        <div class="relative w-full h-80 md:h-64 lg:h-44">
            <img :src="travelSpot.firstimage ? travelSpot.firstimage : '/storage/images/no_image.png'" :alt="travelSpot.title" class="w-full h-full object-center object-cover">
        </div>
        <div class="px-3 py-4">
            <h3 class="text-sm text-gray-500 pb-2">
                <span class="bg-indigo-600 py-1 px-2 text-white rounded-lg">
                    <span class="inset-0"></span>
                    지역 신규 확진자 수: {{ newDefCnt }}명
                </span>
            </h3>
            <h3 class="tracking-widest text-indigo-500 text-xs font-medium title-font">
                {{ travelSpot.addr1 }}
            </h3>
            <button class="text-base font-semibold text-gray-900 group-hover:text-indigo-600">
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
};
</script>
