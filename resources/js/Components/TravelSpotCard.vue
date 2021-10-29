<template>
    <div class="hover:bg-gray-100 xl:w-1/3 md:w-1/2 p-4 border-solid border-2">
        <div class="p-6 rounded-lg">
            <img class="lg:h-60 xl:h-56 md:h-64 sm:h-72 xs:h-72 h-72 rounded w-full object-cover object-center mb-6" :src="travelSpot.firstimage ? travelSpot.firstimage : 'http://localhost:8000/storage/images/no_image.png'" :alt="travelSpot.title"/>
            <h3 class="tracking-widest text-indigo-500 text-xs font-medium title-font">
                {{ travelSpot.addr1 }}
            </h3>
            <div class="text-lg text-gray-900 font-medium title-font mb-2">
                <Link :href="route('travel.show', {
                    'id': travelSpot.contentid,
                    'page': page,
                    'search': search,
                    'searchWay': searchWay,
                    'lat': lat,
                    'lng': lng                
                })">{{ travelSpot.title }}</Link>
            </div>
            <div class="bg-red-400 rounded-md font-semibold text-xs text-gray-100 p-2 right-4 bottom-0 float-right">
                지역 신규 확진자 수: {{  }}명
            </div>
        </div>
    </div>
</template>


<script>
import { Link } from "@inertiajs/inertia-vue3";
export default {
    components: {
        Link,
    },
    props: ['travelSpot', 'myLocation', 'page', 'search', 'searchWay', 'lat', 'lng'],
};
</script>