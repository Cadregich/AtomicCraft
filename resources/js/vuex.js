import Vuex from 'vuex';

const store = new Vuex.Store({
    state: {
        products: [],
        productsFilters: {}
    },
    mutations: {
        setProducts(state, products) {
            state.products = products;
        },
        setProductsFilters(state, filters) {
            state.productsFilters = filters;
        }
    },
    actions: {

    },
    getters: {
        getPaginatorResult: (state) => {
            return state.products;
        },
        getProductsFilters: (state) => {
            return state.productsFilters;
        }
    }
});

export default store;
