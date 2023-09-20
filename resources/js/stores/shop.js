const shop= {
    state: {
        products: [],
        productsFilters: {},
        productData: {}
    },
    mutations: {
        setProducts(state, products) {
            state.products = products;
        },
        setProductsFilters(state, filters) {
            state.productsFilters = filters;
        },

        addToCart(state, product) {
            state.productData = product;
        },

        clearCart(state) {
            state.productData = [];
        }
    },
    actions: {
        addToCart(context, product) {
            context.commit('addToCart', product);
            console.log(context.state.productData);
        },

        clearCart(context) {
            context.commit('clearCart');
        },
    },
    modules: {}
};

export default shop;
