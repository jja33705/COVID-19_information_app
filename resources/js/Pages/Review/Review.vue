<template>
    <app-layout :title="Review">
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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
                        :class="searchedHashtag? 'font-bold text-xl' : ''"
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
                <div class="sm:grid sm:grid-cols-2 lg:grid-cols-3 gap-4 space-y-4 sm:space-y-0">
                    <review-card v-for="review in reviews.data" :key="review.id" :review="review"/>
                </div>
                <!-- infinite loading -->
                <div class=" mt-10 flex items-center justify-center">
                    <infinite-loading @infinite="getReviews"/>
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
    props: ['searchWay', 'search'],
    data() {
        return {
            reviews: {
                next_page_url: `http://localhost:8000/getReviews?searchWay=${this.searchWay}&search=${this.search ? this.search : ''}`,
                data: [],
            },
            searchInput: this.search ? (this.searchWay === 'hashtag' ? '#' + this.search : this.search) : '', //초기화할때 null이면 공백으로. 공백아니면서 해쉬태그면 # 붙여줌.
            searchedHashtag: this.searchWay === 'hashtag', //해쉬태그를 검색하는건지 확인하는 변수
        }
    },
    components: {
        AppLayout,
        ReviewCard,
        InfiniteLoading,
    },
    methods: {
        getReviews(state) {
            setTimeout(() => { //0.8초정도 로딩 표시후 불러옴
                if (this.reviews.next_page_url === null) {
                    state.complete();
                    return;
                }
                axios.get(`${this.reviews.next_page_url}`)
                .then((res) => {
                    console.log(res);
                    res.data.data = [...this.reviews.data, ...res.data.data];
                    this.reviews = res.data;
                    state.loaded();
                })
                .catch((err) => {
                    state.error();
                });
            }, 800);
        },
        searchReview() { //검색
            if (!this.searchedHashtag) { //해쉬태그검색 아닐때
                this.$inertia.get(
                    `/review?searchWay=keyWord&search=${this.searchInput}`, { preserveScroll: false }
                );
            } else { //해쉬태그검색할때
                this.$inertia.get(
                    `/review?searchWay=hashtag&search=${this.searchInput.slice(1)}`, { preserveScroll: false }
                );
            }
        },
        onInputSearchInput() { //해시태그 검색할때 클래스 바꿔 줌
            if(this.searchInput.startsWith('#')) {
                this.searchedHashtag = true;
            } else {
                this.searchedHashtag = false;
            }
        }
    },
}
</script>
