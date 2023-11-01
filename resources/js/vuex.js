import Vuex from 'vuex';
import shop from "./stores/shop.js";
import cabinet from "./stores/cabinet.js";
import modal from "./stores/modal.js"


const store = new Vuex.Store({
    state: {
        isAuthenticated: false,
        userName: '',
    },
    mutations: {
        setAuthentication(state, status) {
            state.isAuthenticated = status;
        },
        setUserName(state, name) {
            state.userName = name;
        },
    },
    actions: {
        login({commit}) {
            commit('setAuthentication', true);
        },
        logout({commit}) {
            commit('setAuthentication', false);
        },
        getCookie(context, name) {
            const cookies = document.cookie.split(";");

            for (let i = 0; i < cookies.length; i++) {
                const cookie = cookies[i].trim();
                if (cookie.startsWith(name + "=")) {
                    return cookie.substring(name.length + 1);
                }
            }
            return null;
        },
    },
    getters: {
        Auth: state => state.isAuthenticated,

        userName: (state) => {
            return state.userName;
        },
    },
    modules: {
        shop,
        cabinet,
        modal
    }
});

export default store;
