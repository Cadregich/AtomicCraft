<template>
    <div>
        Udata
    </div>
</template>

<script>
export default {
    name: "UserData",
    mounted() {
        axios.get('/api/user')
            .then(responce => {
                console.log(responce.data);
            })
            .catch(error => {
                console.log(error);
            });
    }
}
</script>

<style scoped>

</style>
