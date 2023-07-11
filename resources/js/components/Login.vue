<template>
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Login</div>
                    <div class="card-body">
                        <form @submit.prevent="login">

                            <div class="form-group row">
                                <label class="col-md-4 col-form-label text-md-right">Email</label>
                                <div class="col-md-6">
                                    <input v-model="email" type="email" class="form-control" required placeholder="example@mail.com">
                                </div>
                            </div>

                            <div class="form-group row mt-2">
                                <label class="col-md-4 col-form-label text-md-right">Password</label>
                                <div class="col-md-6">
                                    <input v-model="password" type="password" class="form-control" required placeholder="Password">
                                </div>
                            </div>

                            <div class="form-group row mt-2">
                                <div class="col-md-6 offset-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="remember" v-model="remember">
                                        <label class="form-check-label" for="remember">
                                            Remember Me
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row mb-0 mt-3">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        Login
                                    </button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'Login',
    data() {
        return {
            email: '',
            password: '',
            remember: false,
        };
    },
    methods: {
        async login() {
            try {
                const response = await axios.post('/api/login', {
                    email: this.email,
                    password: this.password,
                    remember: this.remember,
                });
                console.log(response.data);
                if (response) {
                    this.$store.dispatch('login');
                }
            } catch (error) {
                console.error(error);
            }

            this.$router.push('/');
        },
    },
};
</script>

<style scoped>

</style>
