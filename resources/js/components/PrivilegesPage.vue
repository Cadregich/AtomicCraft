<template>
    <div class="main-container container atomic-block" style="overflow: auto;">
        <div class="purchases-container">
            <div class="purchases-table mt-2 mt-mg-3">
                <table class="table text-white">
                    <thead>
                    <tr>
                        <th class="text-start h2">Возможности</th>
                        <template v-for="(privilege, key) in privileges">
                            <th class="text-end h3">{{ privilege }}</th>
                        </template>
                    </tr>
                    </thead>
                    <tbody>
                        <template v-for="(capability, capabilityText) in capabilities">
                            <tr>
                                <th class="text-start">{{ capabilityText }}</th>
                                <template v-for="privilegeCapability in capability">
                                    <th class="text-end">
                                        <i v-if="privilegeCapability === true" class="fa-solid fa-check"></i>
                                        <i v-else-if="privilegeCapability === false" class="fa-solid fa-times"></i>
                                        <span v-else>{{ privilegeCapability }}</span>
                                    </th>
                                </template>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "PrivilegesPage",
    data() {
        return {
            server: '',
            capabilities: {},
            privileges: {}
        }
    },
    computed: {

    },

    async mounted() {
        const server = this.$route.query.server;
        if (!server) {
            this.$router.push({path: this.$route.path, query: {server: 'Atomic Fragility'}});
        }
        this.server = server;
        await this.getPrivilegesData();
    },

    methods: {
        async getPrivilegesData() {
            try {
                const response = await axios.get('/privileges/data', {params: {server: this.server}});
                this.capabilities = response.data.capabilities;
                this.privileges = response.data.privileges;
                console.log(response.data);
            } catch (error) {
                console.error(error);
            }
        },
    }
}
</script>


<style scoped>
.font-size-17 {
    font-size: 17px;
}
</style>
