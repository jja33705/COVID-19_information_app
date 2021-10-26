import { createStore } from 'vuex';

export default createStore({
    state() {
        return {
            myLocation: {},
        };
    },
    mutations: {
        setMyLocation (state, location) {
            state.myLocation = location;
        },

    },
});