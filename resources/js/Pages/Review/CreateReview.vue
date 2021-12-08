<template>
    <app-layout title="Review">
        <div class="py-8">
            <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
                <div class="py-12">
                    <h1 class="text-3xl font-bold text-center">후기 작성</h1>
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 bg-white border-b border-gray-200">
                                <form @submit.prevent="onSubmitForm" @keydown.enter.prevent>
                                    <div class="mb-4">
                                        <label class="text-xl text-gray-600">제목</label>
                                        <br>
                                        <input type="text" class="border-2 border-gray-300 p-2 w-full" v-model="form.title">
                                        <div class="text-red-500" v-if="errors.title">{{ errors.title }}</div>
                                    </div>

                                    <div class="mb-8">
                                        <label class="text-xl text-gray-600">내용</label>
                                        <br>
                                        <textarea name="content" class="border-2 border-gray-500 w-full h-96" v-model="form.contents"></textarea>
                                        <div class="text-red-500" v-if="errors.contents">{{ errors.contents }}</div>
                                    </div>
                                    
                                    <!-- 사진 업로드 -->
                                    <div class="mb-4">
                                        <button @click.prevent="$refs.image.click()" class="bg-blue-500 rounded-lg font-bold text-white text-center px-4 py-3 transition duration-300 ease-in-out hover:bg-blue-600 mr-6 ">
                                            이미지 업로드
                                        </button>
                                        <br>
                                        <input type="file" class="hidden" ref="image" @input="inputImage" />
                                        <div v-if="previewImagesSrc.length" class="flex flex-wrap  bg-gray-100 pt-3 pl-3">
                                            <span class="image-area mb-3 mr-3" v-for="(previewImageSrc, index) in previewImagesSrc" :key="previewImageSrc">
                                                <img class="h-40" :src="previewImageSrc" alt="preview">
                                                <button type="button" @click="onClickDeleteImage(index)" class="remove-image" href="#" style="display: inline;">&#215;</button>
                                            </span>
                                        </div>
                                        <div v-if="previewImagesSrc.length" class="text-gray-500">{{ form.images.length }}개 선택됨</div>
                                        <div class="text-red-500" v-if="hasImageError">이미지파일만 업로드 가능합니다.</div>
                                    </div>

                                    <div class="my-6">
                                        <div class="relative">
                                            <div class="my-4 flex">
                                                <input class="rounded-l-lg p-4 border-t mr-0 border-b border-l text-gray-800 border-gray-200 bg-white" placeholder="#없이 입력" v-model="hashtagInput" @keyup.enter="onClickAddHashtag"/>
                                                <button class="px-8 rounded-r-lg bg-gray-200  text-gray-800 font-bold p-4" @click="onClickAddHashtag" type="button">해시태그 추가</button>
                                            </div>
                                            <div class="text-red-500" v-if="hashtagsError">{{ hashtagsError }}</div>
                                            <!-- selections -->
                                            <div class="bg-gray-200 inline-flex items-center text-sm rounded mt-2 mr-1 overflow-hidden" v-for="(hashtag, index) in form.hashtags" :key="hashtag">
                                                <span class="ml-2 mr-1 font-bold leading-relaxed truncate max-w-xs px-1">#{{ hashtag }}</span>
                                                <button class="w-6 h-8 inline-block align-middle text-gray-500 bg-gray-300 focus:outline-none" @click="onClickDeleteHahtag(index)">
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
import { useForm } from '@inertiajs/inertia-vue3';
export default {
    props: ['contentId', 'place', 'errors'],
    setup() {
        const form = useForm({
            title: null,
            contents: null,
            hashtags: [],
            images: [],
        });

        return { form }
    },
    components: {
        AppLayout,
    },
    data() {
        return {
            hashtagInput: '',
            previewImagesSrc: [],
            hashtagsError: '',
            hasImageError: false,
        }
    },
    methods: {
        onClickAddHashtag() { //해쉬태그 추가
            let hashtag = this.hashtagInput.replace(/#/gi, '').trim();
            if (this.form.hashtags.includes(hashtag)) {
                this.hashtagsError = '이미 추가한 해시태그입니다.'
                return;
            }
            if (!hashtag) {
                this.hashtagsError = '한글자 이상 입력해 주세요.'
                return;
            }
            this.form.hashtags.push(hashtag);
            this.hashtagInput = '';
            this.hashtagsError = '';
        },

        onClickDeleteHahtag(index) { //해쉬태그 삭제
            this.form.hashtags.splice(index, 1);
        },
        onClickDeleteImage(index) {
            this.form.images.splice(index, 1);
            this.previewImagesSrc.splice(index, 1);
        },
        inputImage(e) {
            if (e.target.files[0]) {
                this.form.images.push(e.target.files[0]);
                this.previewImagesSrc.push(URL.createObjectURL(e.target.files[0]));
            }
            e.target.value = null; //같은 파일 또 선택되도록 null 할당해줌
        },
        checkHasImageError() {
            //이미지가 아닌 것을 올려서 난 에러가 있는지 확인
            this.hasImageError =  false;
            Object.keys(this.errors).forEach((key) => {
                if (key.startsWith('images')) {
                    this.hasImageError =  true;
                    return;
                }
            });
            
        },
        onSubmitForm() {
            this.form.post(`/review/${this.contentId}?place=${this.place}`, {
                preserveScroll: true,
                onError: () => this.checkHasImageError(),
            });
            
        }
    },
}
</script>

<style scoped>
.image-area {
    position: relative;
    background: #333;
}
.remove-image {
    display: none;
    position: absolute;
    top: -10px;
    right: -10px;
    border-radius: 10em;
    padding: 2px 6px 3px;
    text-decoration: none;
    font: 700 21px/20px sans-serif;
    background: #555;
    border: 3px solid #fff;
    color: #FFF;
    box-shadow: 0 2px 6px rgba(0,0,0,0.5), inset 0 2px 4px rgba(0,0,0,0.3);
    text-shadow: 0 1px 2px rgba(0,0,0,0.5);
    -webkit-transition: background 0.5s;
    transition: background 0.5s;
    }
    .remove-image:hover {
    background: #E54E4E;
    padding: 3px 7px 5px;
    top: -11px;
    right: -11px;
    }
    .remove-image:active {
    background: #E54E4E;
    top: -10px;
    right: -11px;
    }
</style>
