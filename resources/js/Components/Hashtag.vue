<template>
    <span class="m-1 bg-gray-200 hover:bg-gray-300 rounded-full px-2 font-bold text-sm leading-loose cursor-pointer" >#{{ hashtag.contents }}</span>
</template>



<script>
export default {
    props: ['hashtag'],
}
</script>