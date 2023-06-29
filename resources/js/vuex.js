import Vuex from 'vuex';

const store = new Vuex.Store({
    state: {
        products: [],
        productsFilters: [],
        paginatorData: [],
        cache: {
            products: {},
            paginatorData: {}
        }
    },
    mutations: {
        addToCache(state, {products, paginatorData}) {
            state.cache.products += products;
            state.cache.paginatorData += paginatorData;
        },

        resetCache(state) {
            state.cache.products = {};
            state.cache.paginatorData = {};
        },

        updatePaginator(state, response) {
            state.paginatorData = response;
        },

        setProducts(state, products) {
            state.products = products;
        },

        setProductsFilters(state, filters) {
            state.productsFilters = filters;
        }
    },
    actions: {
        getPaginatorData({commit, state}, payload) {
            const {path, page} = payload;
            if (state.cache.paginatorData[page] && state.cache.products[page]) {
                commit('updatePaginator', state.cache.paginatorData[page]);
                commit('setProducts', state.cache.products[page].products);
                console.log('данные получены из кеша');
                return;
            }
            axios.get('/api/' + path, {params: {page: page, ...state.productsFilters}})
                .then(response => {
                    console.log(response.data);
                    const data = response.data.data;
                    const paginatorData = {...response.data};
                    paginatorData.links.shift();
                    paginatorData.links.pop();
                    commit('updatePaginator', paginatorData);
                    commit('setProducts', data);
                    state.cache.paginatorData[page] = {
                        current_page: paginatorData.current_page,
                        last_page: paginatorData.last_page,
                        links: paginatorData.links
                    }
                    state.cache.products[page] = {
                        products: data
                    }

                })
                .catch((error) => {
                    console.error(error);
                });
        },
        getPaginatorDataWithFilters({commit, state}, payload) {
            const {path, filters} = payload;
            commit('resetCache');
            if (filters.search !== '' || filters.mod !== '') {
                if (filters !== state.productsFilters) {
                    console.log('фильтры другие');
                }
                commit('setProductsFilters', filters);
            } else {
                commit('setProductsFilters', []);
            }
            this.dispatch('getPaginatorData', {path: path, page: 1});
        }
    },
    getters: {
        getPaginatorResult: (state) => {
            return state.products;
        },
        getPaginatorData: (state) => {
            return state.paginatorData;
        },
    }
});

export default store;
