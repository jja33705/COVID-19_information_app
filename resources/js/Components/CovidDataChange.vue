<template>
    <span class="ml-1 self-center rounded-full px-2 font-bold text-sm leading-loose cursor-pointer" :class="isRed ? 'bg-red-100' : 'bg-blue-100'">
        <i :class="[isRed ? 'text-red-600' : 'text-blue-600', isUp ? 'fas fa-arrow-up' : 'fas fa-arrow-down']"></i>
        <span :class="isRed ? 'text-red-600' : 'text-blue-600'">{{ number > 0 ? number : number * -1 }}</span>
    </span>
</template>

<script>
export default {
    props: ['data', 'isRed', 'isUp'],
    data() {
        return {
            number: 0,
            increment: Math.ceil(this.data / 200),
        };
    },
    methods: {
        incrementNumber() {
            if (this.number < this.data) {
                this.number += this.increment;
                setTimeout(this.incrementNumber, 1);
            } else {
                this.number = this.data;
            }
        },
    },
    mounted() {  
        //숫자 올라가는 애니메이션
        this.incrementNumber();
    }
}
</script>