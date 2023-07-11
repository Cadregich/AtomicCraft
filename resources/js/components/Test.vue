<template>
    <div>There is a test vue component</div>
</template>

<script>
export default {
    name: "Test",
    mounted() {
        axios.get('/test')
            .then(res => {
                console.log(res.data);
            })
            .catch(error => {
                console.log(error);
            })
    }
}
</script>

<style scoped>

</style>
