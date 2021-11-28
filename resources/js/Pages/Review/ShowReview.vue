<template>
    <app-layout>
        <div class="py-8">
            <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
                <div class="flex justify-end">
                    <button class="text-gray-500 hover:text-black" v-if="$page.props.user && $page.props.user.id == review.user.id" @click="onClickUpdate">수정</button>
                    <button class="ml-2 text-gray-500 hover:text-black" v-if="$page.props.user && $page.props.user.id == review.user.id" @click="onClickDelete">삭제</button>
                </div>
                <h1 class="font-semibold mb-4 text-3xl">{{ review.title }}</h1>
                <div class="mb-1 text-gray-500">작성자: {{ review.user.name }}</div>
                <div class="flex justify-between mb-1 text-gray-500">
                    <div>게시일: {{ dateFormat(review.updated_at) }}</div>
                    <div>조회수: {{ review.viewCount }}</div>
                </div>
                <div class="mb-3 text-gray-500">위치: <Link class="font-bold" :href="`/travel/${review.contentId}`">{{ review.place }}</Link></div>
                <img v-if="review.image" class="mb-4" :src="`/storage/images/${review.image}`" >
                <div class="mb-6 text-lg">{{ review.contents }}</div>
                <div class="mb-3 flex -m-1 flex-wrap">
                    <hashtag v-for="hashtag in review.hashtags" :key="hashtag.id" :hashtag="hashtag" />
                </div>
                <hr>
                <!-- 댓글 -->
                <div class="flex my-4">
                    <div class="text-xl font-semibold">댓글</div>
                </div>
                <div>
                    <form @submit.prevent="onSubmitComment" v-if="$page.props.user">
                        <textarea v-model="form.contents" class="w-full shadow-inner p-4 border-0 rounded-lg focus:shadow-outline text-lg" placeholder="댓글을 입력하세요" cols="6" rows="3"></textarea>
                        <div class="text-red-500" v-if="errors.contents">{{ errors.contents }}</div>
                        <button type="submit" class="font-bold py-2 px-4 w-full bg-green-400 text-lg text-white shadow-md rounded-lg ">댓글 작성 </button>
                    </form>
                    <Comment v-for="comment in comments" :key="comment.id" :comment="comment" @on-delete-comment="onDeleteComment" @on-update-comment="onUpdateComment" @on-submit-reply="onSubmitReply" />
                    <div class="text-red-500" v-if="errors.updateContents">{{ errors.updateContents }}</div>
                </div>
            </div>
        </div>

    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue';
import Comment from '@/Components/Comment.vue';
import Hashtag from '@/Components/Hashtag.vue';
import dayjs from 'dayjs';
import { Link, useForm } from "@inertiajs/inertia-vue3";
export default {
    props: ['review', 'comments', 'errors'],
    components: {
        AppLayout,
        Link,
        Comment,
        Hashtag,
    },
    setup() {
        const form = useForm({
            contents: '',
        });

        return { form };
    },
    methods: {
        onClickDelete() {
            if (confirm('정말로 삭제하시겠습니까?')) {
                this.$inertia.delete(`/review/${this.review.id}`);
            }
        },
        onClickUpdate() {
            this.$inertia.get(`/review/edit/${this.review.id}`);
        },
        dateFormat(date) {
            return dayjs(date).format('YYYY년 MM월 DD일 HH:mm:ss');
        },
        onSubmitComment() {  //댓글 작성
            this.form.post(`/comment/${this.review.id}`, { preserveScroll: true });
            this.form.contents = '';
        },
        onSubmitReply(parentId, contents) { //답글(대댓글) 작성
            this.$inertia.post(`/comment/${this.review.id}`, {
                parentId: parentId,
                contents: contents,
            }, {
                preserveScroll: true,
            });
        },
        onDeleteComment(id) {
            if(confirm('정말로 삭제하시겠습니까?')) {
                this.$inertia.delete(`/comment/${id}`, { preserveScroll: true });
            }
        },
        onUpdateComment(id, updateContents) {
            this.$inertia.patch(`/comment/${id}`, { updateContents: updateContents }, { preserveScroll: true });
        },
    },
}
</script>