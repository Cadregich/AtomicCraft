import './bootstrap';
import { createApp } from 'vue';
import store from './vuex';
import router from './router';
import Index from './components/Index.vue';
import './axios';

const app = createApp({
    el: '#app',

    components: {
        Index
    }
});

app.use(router);
app.use(store);
app.mount('#app');
