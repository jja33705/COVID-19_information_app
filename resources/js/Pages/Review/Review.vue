<template>
    <app-layout title="Review">
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <Link :href="route('review.create')" v-if="$page.props.user">
                <div class="flex-1 h-full my-2">
                    <div class="flex items-center justify-center flex-1 h-full p-2 bg-yellow-300 text-white shadow rounded-lg">
                        <div class="relative">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </Link>


                <!-- input -->
                <div class="pt-2 mb-5 relative mx-auto text-gray-600">
                    <input
                        class="
                            border-2 border-gray-300
                            bg-white
                            h-10
                            px-5
                            pr-16
                            rounded-lg
                            text-base
                            focus:outline-none
                            w-full
                        "
                        @input="onInputSearchInput"
                        :class="searchHashtag? 'font-bold text-xl text-black' : ''"
                        type="search"
                        name="search"
                        placeholder="Search"
                        v-model="searchInput"
                        required
                        @keyup.enter="searchReview"
                    />
                    <button class="absolute right-0 top-0 mt-5 mr-4" @click="searchReview">
                        <svg
                            class="text-gray-600 h-4 w-4 fill-current"
                            xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink"
                            version="1.1"
                            id="Capa_1"
                            x="0px"
                            y="0px"
                            viewBox="0 0 56.966 56.966"
                            style="enable-background: new 0 0 56.966 56.966"
                            xml:space="preserve"
                            width="512px"
                            height="512px"
                        >
                            <path
                                d="M55.146,51.887L41.588,37.786c3.486-4.144,5.396-9.358,5.396-14.786c0-12.682-10.318-23-23-23s-23,10.318-23,23  s10.318,23,23,23c4.761,0,9.298-1.436,13.177-4.162l13.661,14.208c0.571,0.593,1.339,0.92,2.162,0.92  c0.779,0,1.518-0.297,2.079-0.837C56.255,54.982,56.293,53.08,55.146,51.887z M23.984,6c9.374,0,17,7.626,17,17s-7.626,17-17,17  s-17-7.626-17-17S14.61,6,23.984,6z"
                            />
                        </svg>
                    </button>
                </div>

                
                <!-- 리뷰 리스트 -->
                <infinite-scroll @infinite-scroll="getReviews" :noResult="noResult" :message="message">
                    <div class="sm:grid sm:grid-cols-2 lg:grid-cols-3 gap-4 space-y-4 sm:space-y-0 pb-5">
                        <review-card v-for="review in reviews.data" :key="review.id" :review="review"/>
                    </div>
                </infinite-scroll>
            </div>
        </div>
    </app-layout>
</template>



<script>
import AppLayout from '@/Layouts/AppLayout.vue';
import ReviewCard from '@/Components/ReviewCard.vue';
import InfiniteScroll from 'infinite-loading-vue3';
import { Link } from "@inertiajs/inertia-vue3";
export default {
    props: ['searchWay', 'search'],
    data() {
        return {
            reviews: {
                next_page_url: `http://localhost:8000/getReviews?searchWay=${this.searchWay}&search=${this.search ? this.search : ''}`,
                data: [],
            },
            searchInput: this.search ? (this.searchWay === 'hashtag' ? '#' + this.search : this.search) : '', //초기화할때 null이면 공백으로. 공백아니면서 해쉬태그면 # 붙여줌.
            searchHashtag: this.searchWay === 'hashtag', //해쉬태그를 검색하는건지 확인하는 변수
            timer: null,
            noResult: false,
            message: '',
        };
    },
    components: {
        AppLayout,
        ReviewCard,
        InfiniteScroll,
        Link,
    },
    methods: {
        getReviews() {
            console.log('데이터 불러옴');
            if (!this.timer) {
                this.timer = setTimeout(() => { //0.5초정도 로딩 표시후 불러옴. 스크롤 이벤트가 연속적으로 발생하므로 쓰로틀링처리 해줌.
                    this.timer = null;
                    if (this.reviews.next_page_url === null) {
                        this.noResult = true;
                        this.message = 'No more result';
                        return;
                    }
                    axios.get(this.reviews.next_page_url)
                    .then((res) => {
                        console.log(res);
                        res.data.data = [...this.reviews.data, ...res.data.data];
                        this.reviews = res.data;
                    })
                    .catch((err) => {
                        console.log(err);
                        this.noResult = true;
                        this.message = 'Error loading data';
                    });
                }, 500);
            }
        },

        searchReview() { //검색
            if (!this.searchHashtag) { //해쉬태그검색 아닐때
                this.$inertia.get(
                    `/review?searchWay=keyword&search=${this.searchInput}`, { preserveScroll: false }
                );
            } else { //해쉬태그검색할때
                this.$inertia.get(
                    `/review?searchWay=hashtag&search=${this.searchInput.slice(1)}`, { preserveScroll: false }
                );
            }
        },
        onInputSearchInput() { //해시태그 검색할때 클래스 바꿔 줌
            if(this.searchInput.startsWith('#')) {
                this.searchHashtag = true;
            } else {
                this.searchHashtag = false;
            }
        }
    },
    mounted() {
        console.log('마운트 됨');
        this.getReviews();
    }
}
</script>
