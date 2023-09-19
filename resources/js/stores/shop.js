const shop= {
    state: {
        products: [],
        productsFilters: {},
        cart: {}
    },
    mutations: {
        setProducts(state, products) {
            state.products = products;
        },
        setProductsFilters(state, filters) {
            state.productsFilters = filters;
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
    },
    modules: {}
};

export default shop;
