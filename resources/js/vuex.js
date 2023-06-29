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

        setPaginatorResult(state, data) {
            state.products = data;
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
            if (!state.productsFilters.length > 0 || state.productsFilters.search === '' && state.productsFilters.mod === '') {
                if (state.cache.paginatorData[page] && state.cache.products[page]) {
                    commit('updatePaginator', state.cache.paginatorData[page]);
                    commit('setProducts', state.cache.products[page].products);
                    console.log('данные получены из кеша');
                    return;
                }
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

                    if (!state.productsFilters.length > 0  || state.productsFilters.search === '' && state.productsFilters.mod === '') {
                        state.cache.paginatorData[page] = {
                            current_page: paginatorData.current_page,
                            last_page: paginatorData.last_page,
                            links: paginatorData.links
                        }
                        state.cache.products[page] = {
                            products: data
                        }
                    }
                })
                .catch((error) => {
                    console.error(error);
                });
        },
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
