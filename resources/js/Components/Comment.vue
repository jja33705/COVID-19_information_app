<template>
    <div>
        <div class="bg-white rounded-lg p-3 flex flex-col justify-center border-dotted border border-gray-400 md:items-start shadow-lg bg-white" v-if="!updating">
            <div class="flex">
                <span class="text-black font-semibold text-lg text-left ">{{ comment.user.name }}</span>
                <span class="ml-3 text-gray-500">{{ dateFormat(comment.updated_at) }}</span>
                <button v-if="$page.props.user" @click="onClickReply" class="ml-4 text-gray-500 hover:text-black">답글 작성</button>
                <button v-if="$page.props.user && $page.props.user.id == comment.user.id" @click="onClickUpdate" class="ml-8 text-gray-500 hover:text-black">수정</button>
                <button v-if="$page.props.user && $page.props.user.id == comment.user.id" @Click="$emit('onDeleteComment', comment.id)" class="ml-1 text-gray-500 hover:text-black">삭제</button>
            </div>
            <p style="width: 90%" class="text-gray-600 text-lg text-left">{{ comment.contents }}</p>
            <div class="text-red-500" v-if="errors[`comment_${comment.id}`]">{{ errors[`comment_${comment.id}`].updateContents }}</div>
        </div>
        <div class="text-red-500" v-if="errors[`comment_${comment.id}`]">{{ errors[`comment_${comment.id}`].contents }}</div>
        
        <!-- 수정이랑 답글 폼 부분 -->
        <form @submit.prevent="onUpdateComment" v-if="updating">
            <textarea v-model="updateContents" class="w-full shadow-inner p-4 border-0 rounded-lg focus:shadow-outline text-lg" cols="6" rows="2"></textarea>
            <button type="submit" class="font-bold py-2 px-4 w-1/2 bg-green-400 text-lg text-white shadow-md rounded-lg ">수정</button>
            <button @click="updating = false" class="font-bold py-2 px-4 w-1/2 bg-red-400 text-lg text-white shadow-md rounded-lg ">취소</button>
        </form>
        <form @submit.prevent="onSubmitReply" v-if="replying">
             <textarea v-model="replyContents" class="w-full shadow-inner p-4 border-0 rounded-lg focus:shadow-outline text-lg" cols="6" rows="2"></textarea>
            <button type="submit" class="font-bold py-2 px-4 w-1/2 bg-green-400 text-lg text-white shadow-md rounded-lg ">답글 작성</button>
            <button @click="replying = false" class="font-bold py-2 px-4 w-1/2 bg-red-400 text-lg text-white shadow-md rounded-lg ">취소</button>
        </form>

        <!-- 답글 -->
        <Reply v-for="reply in comment.replies" :key="reply.id" :reply="reply" :errors="errors" @on-update-reply="onUpdateReply" @on-delete-reply="onDeleteReply" />
    </div>
</template>
<script>
import dayjs from 'dayjs';
import Reply from '@/Components/Reply.vue';
export default {
    props: ['comment', 'errors'],
    components: {
        Reply,
    },
    data() {
        return {
            updating: false,
            replying: false,
            updateContents: '',
            replyContents: '',
        };
    },
    methods: {
        dateFormat(date) {
            return dayjs(date).format('YYYY년 MM월 DD일 HH:mm:ss');
        },
        onClickUpdate() {
            this.updateContents = this.comment.contents;
            this.updating = true;
        },
        onUpdateComment() {
            this.$emit('onUpdateComment', this.comment.id, this.updateContents );
            this.updating = false;
        },
        onClickReply() {
            this.replyContents = '';
            this.replying = true;
        },
        onSubmitReply() {
            this.$emit('onSubmitReply', this.comment.id, this.replyContents);
            this.replying = false;
        },
        onUpdateReply(id, updateContents) { //대댓글 업데이트
            this.$emit('onUpdateComment', id, updateContents);
        },
        onDeleteReply(id) { //대댓글 삭제
            this.$emit('onDeleteComment', id);
        },
    },

}
</script>
