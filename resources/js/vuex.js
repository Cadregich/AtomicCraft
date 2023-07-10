import Vuex from 'vuex';
import {Modal} from "bootstrap";

const store = new Vuex.Store({
    state: {
        isAuthenticated: false,
        products: [],
        productsFilters: {},
        modal: null,
        cart: {}
    },
    mutations: {
        setAuthentication(state, status) {
            state.isAuthenticated = status;
        },
        setProducts(state, products) {
            state.products = products;
        },
        setProductsFilters(state, filters) {
            state.productsFilters = filters;
        },

        createModal(state, modalId) {
            state.modal = new Modal(document.getElementById(modalId));
        },

        addToCart(state, product) {
            state.cart = product;
        },

        clearCart(state) {
            state.cart = [];
        }
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

        addToCart(context, product) {
            context.commit('addToCart', product);
            console.log(context.state.cart);
        },

        clearCart(context) {
            context.commit('clearCart');
        },

        openModal(context, modalId) {
            context.commit('createModal', modalId);
            context.state.modal.show();
            console.log(context.state.modal);
        },

        closeModal(context) {
            context.state.modal.hide();
        },
    },
    getters: {
        Auth: state => state.isAuthenticated,

        getPaginatorResult: (state) => {
            return state.products;
        },
        getProductsFilters: (state) => {
            return state.productsFilters;
        },
        getCart: (state) => {
            return state.cart;
        }
    }
});

export default store;
