<template>
    <app-layout>
        <div>{{ contentId }}</div>
    </app-layout>
</template>




<script>
import { defineComponent } from 'vue';
import AppLayout from "@/Layouts/AppLayout.vue";
export default defineComponent({
    props: {
        contentId: Number,
    },
    components: {
        AppLayout,
    }
})
</script>