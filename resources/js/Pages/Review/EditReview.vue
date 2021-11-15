<template>
    <app-layout title="Review">
        <div class="py-8">
            <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
                <div class="py-12">
                    <h1 class="text-3xl font-bold text-center">후기 수정</h1>
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 bg-white border-b border-gray-200">
                                <form @submit.prevent="form.post(`/review/${this.review.id}`)" @keydown.enter.prevent>
                                    <div class="mb-4">
                                        <label class="text-xl text-gray-600">제목</label>
                                        <br>
                                        <input type="text" class="border-2 border-gray-300 p-2 w-full" v-model="form.title">
                                        <div class="text-red-500" v-if="errors.title">{{ errors.title }}</div>
                                    </div>

                                    <div class="mb-8">
                                        <label class="text-xl text-gray-600">내용</label>
                                        <br>
                                        <textarea name="content" class="border-2 border-gray-500 w-full h-48" v-model="form.contents"></textarea>
                                        <div class="text-red-500" v-if="errors.contents">{{ errors.contents }}</div>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <button @click.prevent="$refs.image.click()" class="bg-blue-500 rounded-lg font-bold text-white text-center px-4 py-3 transition duration-300 ease-in-out hover:bg-blue-600 mr-6">
                                            이미지 업로드
                                        </button>
                                        <br>
                                        <input class="hidden" ref="image" type="file" @input="inputImage" />
                                        <div class="text-red-500" v-if="errors.image">{{ errors.image }}</div>
                                        <img v-if="form.image" :src="previewImageSrc" alt="preview">
                                        <button v-if="form.image" @click.prevent="onClickDeleteImage" class="py-3 px-6 text-white rounded-lg bg-red-500 shadow-lg block md:inline-block">선택취소</button>
                                    </div>

                                    <div class="my-6">
                                        <div class="relative">
                                            <div class="my-4 flex">
                                                <input class="rounded-l-lg p-4 border-t mr-0 border-b border-l text-gray-800 border-gray-200 bg-white" placeholder="#없이 입력" v-model="hashtagInput" @keyup.enter="onClickAddHashtag"/>
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
export default {
    props: ['review', 'errors'],
    components: {
        AppLayout,
    },
    data() {
        return {
            hashtagInput: '',
            form: this.$inertia.form({
                _method: 'patch',
                title: null,
                contents: null,
                hashtags: [],
                image: null,
            }),
            previewImageSrc: '',
        };
    },
    methods: {
        onClickAddHashtag() { //해쉬태그 추가
            let hashtag = this.hashtagInput.replace(/#/gi, '').trim();
            console.log(hashtag);
            if (this.form.hashtags.includes(hashtag) || !hashtag) {
                return;
            }
            this.form.hashtags.push(hashtag);
            this.hashtagInput = '';
        },
        onClickDeleteHahtag(hashtag) { //해쉬태그 삭제
            this.form.hashtags.splice(this.form.hashtags.indexOf(hashtag), 1);
        },
        inputImage(e) {
            if (e.target.files[0]) {
                console.log(e.target.files[0]);
                this.form.image = e.target.files[0];
                this.previewImageSrc = URL.createObjectURL(e.target.files[0]);
            } else {
                this.form.image = null;
                this.previewImageSrc = '';
            }
        },
        onClickDeleteImage() {
            this.form.image = null,
            this.previewImageSrc = '';
        }
    },
    mounted() {
        this.form.title = this.review.title;
        this.form.contents = this.review.contents;
        this.review.hashtags.map((v) => {
            this.form.hashtags.push(v.contents);
        });
        if (this.review.image) {
            this.form.image = this.review.image;
            this.previewImageSrc = `http://localhost:8000/storage/images/${this.review.image}`;
        }
    },
}
</script>