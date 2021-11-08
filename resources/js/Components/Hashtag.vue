<template>
    <span class="m-1 bg-gray-200 hover:bg-gray-300 rounded-full px-2 font-bold text-sm leading-loose cursor-pointer" >
        <Link :href="route('review.index', { searchWay: 'hashtag', search:hashtag.contents })">#{{ hashtag.contents }}</Link>
    </span>
</template>



<script>
import { Link } from "@inertiajs/inertia-vue3";
export default {
    props: ['hashtag'],
    components: {
        Link,
    }
}
</script>