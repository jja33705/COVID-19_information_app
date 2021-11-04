<template>
    <article class="bg-white group relative rounded-lg overflow-hidden shadow-lg hover:shadow-2xl transform duration-200">
        <div class="relative w-full h-80 md:h-64 lg:h-44">
            <img :src="travelSpot.firstimage ? travelSpot.firstimage : 'http://localhost:8000/storage/images/no_image.png'" :alt="travelSpot.title" class="w-full h-full object-center object-cover">
        </div>
        <div class="px-3 py-4">
            <h3 class="text-sm text-gray-500 pb-2">
                <a class="bg-indigo-600 py-1 px-2 text-white rounded-lg" href="#">
                    <span class="inset-0"></span>
                    지역 신규 확진자 수: {{ newDefCnt }}명
                </a>
            </h3>
            <h3 class="tracking-widest text-indigo-500 text-xs font-medium title-font">
                {{ travelSpot.addr1 }}
            </h3>
            <p class="text-base font-semibold text-gray-900 group-hover:text-indigo-600">
                <Link :href="route('travel.show', {
                    'id': travelSpot.contentid,
                    'page': page,
                    'search': search,
                    'searchWay': searchWay,
                    'lat': lat,
                    'lng': lng                
                })">{{ travelSpot.title }}
                </Link>
            </p>
        </div>
    </article>
</template>


<script>
import { Link } from "@inertiajs/inertia-vue3";
export default {
    components: {
        Link,
    },
    props: ['travelSpot', 'page', 'search', 'searchWay', 'lat', 'lng', 'newDefCnt'],
};
</script>