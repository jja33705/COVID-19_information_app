<template>
    <div class="pt-4">
        <div class="bg-white rounded-lg p-3  flex flex-col justify-center items-center md:items-start shadow-lg mb-4" v-if="!updating">
            <div clss="flex">
                <span class="text-black font-semibold text-lg text-center md:text-left ">{{ comment.user.name }}</span>
                <span class="ml-3 text-gray-500">{{ dateFormat(comment.updated_at) }}</span>
                <button v-if="$page.props.user && $page.props.user.id == comment.user.id" @click="onClickUpdate" class="ml-4 text-gray-500 hover:text-black">수정</button>
                <button v-if="$page.props.user && $page.props.user.id == comment.user.id" @Click="$emit('onDeleteComment', comment.id)" class="ml-1 text-gray-500 hover:text-black">삭제</button>
            </div>
            <p style="width: 90%" class="text-gray-600 text-lg text-center md:text-left ">{{ comment.contents }}</p>
        </div>
        <div class="text-red-500" v-if="errors.updateContents">{{ errors.updateContents }}</div>
        <form @submit.prevent="onUpdateComment" v-if="updating">
            <textarea v-model="updateContents" class="w-full shadow-inner p-4 border-0 rounded-lg focus:shadow-outline text-lg" cols="6" rows="2"></textarea>
            <button type="submit" class="font-bold py-2 px-4 w-1/2 bg-green-400 text-lg text-white shadow-md rounded-lg ">수정</button>
            <button @click="updating = false" class="font-bold py-2 px-4 w-1/2 bg-red-400 text-lg text-white shadow-md rounded-lg ">취소</button>
        </form>
    </div>
</template>
<script>
import dayjs from 'dayjs';
export default {
    props: ['comment', 'errors'],
    data() {
        return {
            updating: false,
            updateContents: '',
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
            this.$emit('onUpdateComment', { id: this.comment.id, updateContents: this.updateContents });
            this.updating = false;
        }
    },

}
</script>