import axios from 'axios';

axios.interceptors.response.use(response => {
        return response;
    },
    async error => {
        if (error.response.status === 401) {
            //
        }
        return Promise.reject(error);
    });

export default axios;
