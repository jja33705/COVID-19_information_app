<template>
    <app-layout :title="Review">
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="sm:grid sm:grid-cols-2 lg:grid-cols-3 gap-4 space-y-4 sm:space-y-0">
                    <review-card v-for="review in reviews" :key="review.id" :review="review"/>
                    <infinite-loading :reviews="reviews" @infinite="getReviews"/>
                </div>
            </div>
        </div>
    </app-layout>
</template>



<script>
import AppLayout from '@/Layouts/AppLayout.vue';
import ReviewCard from '@/Components/ReviewCard.vue';
import InfiniteLoading from 'v3-infinite-loading';
import "v3-infinite-loading/lib/style.css";
export default {
    data() {
        return {
            reviews: [],
            page: 1,
        }
    },
    components: {
        AppLayout,
        ReviewCard,
        InfiniteLoading,
    },
    mounted() {
        this.getReviews();
    },
    methods: {
        getReviews(state) {
            console.log('불러옴');
            axios.get(`/getReviews?page=${this.page}`)
            .then((res) => {
                console.log(res);
                state.loaded();
                this.page += 1;
            })
            .catch((err) => {
                console.log(err);
            });
        }
    },
}
</script>
