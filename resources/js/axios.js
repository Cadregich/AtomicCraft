import axios from 'axios';
import router from "./router";
import store from "./vuex";

axios.interceptors.response.use(response => {
        return response;
    },
    async error => {
        if (error.response.status === 401) {
            document.cookie = 'jwt=;expires=Thu, 01 Jan 1970 00:00:00 GMT;path=/;';
            document.cookie = 'ait=;expires=Thu, 01 Jan 1970 00:00:00 GMT;path=/;';

            if (await store.dispatch('getCookie', 'ait')) {
                axios.post('api/clear-auth-cookie');
            }

            store.dispatch('logout');
            router.push({name: 'login'});
        }
        return Promise.reject(error);
    });

export default axios;
