<template>
    <div>
        <navbar/>
        <router-view></router-view>
    </div>
</template>

<script>
import Navbar from "./Navbar.vue";

export default {
    name: 'Index',
    components: {
        Navbar
    },
    mounted() {
        axios.get('/user/name')
            .then(res => {
                this.$store.commit('setUserName', res.data);
            })
            .catch(error => {
                console.log(error);
            });
    },
    async created() {
        if (await this.$store.dispatch('getCookie', 'ait')) {
            this.$store.dispatch('login');
        }
    }
}
</script>

<style>
body {
    background-size: cover;
    background: url('/images/backgrounds/bg.png') no-repeat fixed center center;
    padding-bottom: 100px;
}

@media (min-width: 922px) {
    body {
        background-position: center top 63px;
    }
}
</style>
