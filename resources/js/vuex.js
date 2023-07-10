import Vuex from 'vuex';
import {Modal} from "bootstrap";

const store = new Vuex.Store({
    state: {
        products: [],
        productsFilters: {},
        modal: null,
        cart: {}
    },
    mutations: {
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
