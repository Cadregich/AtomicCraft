<template>
    <div>
        <navbar/>
        <template v-if="(typeof userNickname === 'string' && userNickname) || (isQueryComplete)">
            <router-view></router-view>
        </template>
    </div>
</template>

<script>
import Navbar from "./Navbar.vue";

export default {
    name: 'Index',
    components: {
        Navbar
    },
    data() {
        return {
            isQueryComplete: false,
        }
    },
    mounted() {
        axios.get('/user/name')
            .then(res => {
                this.$store.commit('setUserName', res.data)
            })
            .catch(error => {
                console.log(error);
            });
        this.isQueryComplete = true;
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
