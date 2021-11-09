<template>
    <app-layout title="Review">
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="py-12">
                    <h1 class="text-3xl font-bold text-center">후기 작성</h1>
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 bg-white border-b border-gray-200">
                                <form @submit.prevent="submit" @keydown.enter.prevent>
                                    <div class="mb-4">
                                        <label class="text-xl text-gray-600">제목</label>
                                        <br>
                                        <input type="text" class="border-2 border-gray-300 p-2 w-full" required v-model="form.title">
                                    </div>
                                    <div class="mb-4">
                                        <label class="text-xl text-gray-600">장소</label>
                                        <br>
                                        <input type="text" class="border-2 border-gray-300 p-2 w-full" v-model="form.place">
                                    </div>

                                    <div class="mb-8">
                                        <label class="text-xl text-gray-600">내용</label>
                                        <br>
                                        <textarea name="content" class="border-2 border-gray-500 w-full h-48" required v-model="form.contents"></textarea>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label class="text-xl text-gray-600">이미지 업로드</label>
                                        <br>
                                        <input type="file" @input="form.image = $event.target.files[0]" />
                                    </div>

                                    <div class="my-6">
                                        <div class="relative">
                                            <div class="my-4 flex">
                                                <input class="rounded-l-lg p-4 border-t mr-0 border-b border-l text-gray-800 border-gray-200 bg-white" placeholder="#없이 입력" v-model="hashtagInput" @keyup.enter="onClickAddHashtag" ref="hashtagInput"/>
                                                <button class="px-8 rounded-r-lg bg-gray-200  text-gray-800 font-bold p-4" @click="onClickAddHashtag" type="button">해시태그 추가</button>
                                            </div>
                                            <!-- selections -->
                                            <div class="bg-gray-200 inline-flex items-center text-sm rounded mt-2 mr-1 overflow-hidden" v-for="hashtag in form.hashtags" :key="hashtag">
                                                <span class="ml-2 mr-1 font-bold leading-relaxed truncate max-w-xs px-1">#{{ hashtag }}</span>
                                                <button class="w-6 h-8 inline-block align-middle text-gray-500 bg-gray-300 focus:outline-none" @click="onClickDeleteHahtag(hashtag)">
                                                    <svg class="w-6 h-6 fill-current mx-auto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M15.78 14.36a1 1 0 0 1-1.42 1.42l-2.82-2.83-2.83 2.83a1 1 0 1 1-1.42-1.42l2.83-2.82L7.3 8.7a1 1 0 0 1 1.42-1.42l2.83 2.83 2.82-2.83a1 1 0 0 1 1.42 1.42l-2.83 2.83 2.83 2.82z"/></svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-center">
                                        <div class="m-3">
                                            <button class="bg-green-100 text-gray-800 font-bold rounded border-b-2 border-green-500 hover:border-green-600 hover:bg-green-500 hover:text-white shadow-md py-2 px-6 inline-flex items-center" type="submit">
                                                <span class="mr-2">작성</span>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                                    <path fill="currentcolor" d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"></path>
                                                </svg>
                                            </button>
                                        </div>
                                        <div class="m-3">
                                            <Link :href="route('review.index', { searchWay: 'keyWord' })">
                                                <button class="bg-red-100 text-gray-800 font-bold rounded border-b-2 border-red-500 hover:border-red-600 hover:bg-red-500 hover:text-white shadow-md py-2 px-6 inline-flex items-center" type="button">
                                                    <span class="mr-2">취소</span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                                        <path fill="currentcolor" d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12 19 6.41z"/>
                                                    </svg>
                                                </button>
                                            </Link>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>
<script>
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm } from '@inertiajs/inertia-vue3';
import { Link } from "@inertiajs/inertia-vue3";
export default {
    setup() {
        const form = useForm({
            title: null,
            place: null,
            contents: null,
            hashtags: [],
            image: null,
        });

        function submit() {
            form.post('/review/store');
        }

        return { form, submit }
    },
    components: {
        AppLayout,
        Link,
    },
    data() {
        return {
            hashtagInput: '',
        }
    },
    methods: {
        onClickAddHashtag() { //해쉬태그 추가
            if (this.form.hashtags.includes(this.hashtagInput) || !this.hashtagInput.trim()) {
                return;
            }
            this.form.hashtags.push(this.hashtagInput.trim());
            this.hashtagInput = '';
            this.$refs.hashtagInput.focus();
        },

        onClickDeleteHahtag(hashtag) { //해쉬태그 삭제
            this.form.hashtags.splice(this.form.hashtags.indexOf(hashtag), 1);
        },
    }
}
</script>