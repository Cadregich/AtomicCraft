import {createRouter, createWebHistory} from 'vue-router';

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: '/',
            component: () => import('./components/Home.vue'),
            name: 'home'
        },
        {
            path: '/cabinet',
            component: () => import('./components/Cabinet.vue'),
            name: 'cabinet'
        },
        {
            path: '/logout',
            component: () => import('./components/Logout.vue'),
            name: 'logout'
        },
        {
            path: '/login',
            component: () => import('./components/Login.vue'),
            name: 'login'
        },
        {
            path: '/test',
            component: () => import('./components/Test.vue'),
            name: 'test'
        },
        {
            path: '/shop',
            component: () => import('./components/Shop.vue'),
            name: 'shop'
        },
        {
            path: '/shop/goods-history',
            component: () => import('./components/GoodsHistory.vue'),
            name: 'shop.goods-history'
        },
        {
            path: '/shop/paginator',
            component: () => import('./components/Paginator.vue'),
            name: 'shop.paginator'
        },
    ],
});

export default router;
