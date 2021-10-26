<template>
    <app-layout>
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex justify-end">돌아가기</div>
                <div class="flex justify-center text-4xl my-7 font-semibold">{{ content.title }}</div>
                <img v-for="image in images" :key="image.serialnum" class="w-full" :src="image.originimgurl">
                <div class="my-3">주소: {{ content.addr1 + content.addr2 }}</div>
                <div class="my-3" v-if="content.homepage">
                    <span>홈페이지: </span>
                    <span v-html="content.homepage"></span>
                </div>
                <div v-html="content.overview" class="text-lg"></div>
            </div>
        </div>
    </app-layout>
</template>




<script>
import AppLayout from "@/Layouts/AppLayout.vue";
export default ({
    props: {
        contentId: Number,
    },
    components: {
        AppLayout,
    },
    data() {
        return {
            content: {},
            images: [],
        };
    },
    mounted() {
        axios.get(`/api/travel/${this.contentId}`) //상세정보
        .then((res) => {
            console.log(res);
            this.content = res.data.body.items.item;
        })
        .catch((err) => {
            console.log(err);
        });
        
        axios.get(`/api/image/${this.contentId}`) //이미지
        .then((res) => {
            console.log(res);
            this.images = res.data.body.items.item;
        })
        .catch((err) => {
            console.log(err);
        });
    },
})
</script>