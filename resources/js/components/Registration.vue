<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Registration</div>

                    <div class="card-body">
                        <form @submit.prevent="register">

                            <div class="form-group row mt-2">
                                <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control" v-model="form.name" required
                                           placeholder="Nickname">
                                </div>
                            </div>

                            <div class="form-group row mt-2">
                                <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail Address</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" v-model="form.email" required
                                           placeholder="example@mail.com">
                                </div>
                            </div>

                            <div class="form-group row mt-2">
                                <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control" v-model="form.password"
                                           required placeholder="Password">
                                </div>
                            </div>

                            <div class="form-group row mt-2">
                                <label for="password_confirmation" class="col-md-4 col-form-label text-md-right">Confirm
                                    Password</label>

                                <div class="col-md-6">
                                    <input id="password_confirmation" type="password" class="form-control"
                                           v-model="form.password_confirmation" required placeholder="Confirm password">
                                </div>
                            </div>

                            <div class="form-group row mb-0 mt-3">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        Register
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
export default {
    name: 'Registration',
    data() {
        return {
            form: {
                name: '',
                email: '',
                password: '',
                password_confirmation: ''
            }
        }
    },
    methods: {
        async register() {
            if (this.form.password !== this.form.password_confirmation) {
                alert('Passwords do not match');
                return;
            }
            try {
                const response = await axios.post('/api/register', this.form);
                console.log(response.data);
                this.$store.dispatch('login');
                this.$router.push('/')
            } catch (error) {
                alert('Registration failed, check console');
                console.log(error);
            }
        }
    }
}
</script>
