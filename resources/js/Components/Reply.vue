<template>
    <div>
        <div class="bg-white rounded-lg p-3  flex flex-col justify-center items-center md:items-start shadow-lg pl-8 bg-gray-100" v-if="!updating">
            <div class="flex">
                <div class="mr-5">
                    L
                </div>
                <div>
                    <div class="flex">
                        <span class="text-black font-semibold text-lg text-left ">{{ reply.user.name }}</span>
                        <span class="ml-3 text-gray-500">{{ dateFormat(reply.updated_at) }}</span>
                        <button v-if="$page.props.user && $page.props.user.id == reply.user.id" @click="onClickUpdate" class="ml-8 text-gray-500 hover:text-black">수정</button>
                        <button v-if="$page.props.user && $page.props.user.id == reply.user.id" @Click="$emit('onDeleteReply', reply.id)" class="ml-1 text-gray-500 hover:text-black">삭제</button>
                    </div>
                    <p style="width: 90%" class="text-gray-600 text-lg text-left">{{ reply.contents }}</p>
                </div>
            </div>
        </div>
        
        <!-- 수정이랑 답글 폼 부분 -->
        <form @submit.prevent="onUpdateReply" v-if="updating">
            <textarea v-model="updateContents" class="w-full shadow-inner p-4 border-0 rounded-lg focus:shadow-outline text-lg" cols="6" rows="2"></textarea>
            <button type="submit" class="font-bold py-2 px-4 w-1/2 bg-green-400 text-lg text-white shadow-md rounded-lg ">수정</button>
            <button @click="updating = false" class="font-bold py-2 px-4 w-1/2 bg-red-400 text-lg text-white shadow-md rounded-lg ">취소</button>
        </form>

    </div>
</template>
<script>
import dayjs from 'dayjs';
export default {
    props: ['reply'],
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
            this.updateContents = this.reply.contents;
            this.updating = true;
        },
        onUpdateReply() {
            this.$emit('onUpdateReply', this.reply.id, this.updateContents);
            this.updating = false;
        },
    },

}
</script>
