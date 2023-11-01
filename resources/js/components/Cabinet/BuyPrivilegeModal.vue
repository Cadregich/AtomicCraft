<template>
    <div>
        <ModalLayout id="buy-privileges-modal">
            <template v-if="privilege && server" v-slot:modalHeader>
                Покупка привилегии «{{ privilege.title }}»
            </template>

            <template v-if="privilege && server" v-slot:modalBody>
                Вы действительно хотите купить привилегию «{{ privilege.title }}»
                для сервера «{{ server }}»
            </template>

            <template v-if="privilege && server" v-slot:modalFooter>
                <button class="btn btn-primary" id="submit-buy-form"
                        @click="closeModal(); buyPrivilege()">
                    Перейти к оплате
                </button>
                <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Отменить</button>
            </template>
        </ModalLayout>
    </div>
</template>

<script>
import ModalLayout from '../ModalLayout.vue'

export default {
    name: 'BuyPrivilegeModal',
    components: {
        ModalLayout,
    },
    computed: {
        privilege() {
            return this.$store.state.cabinet.buyPrivilegeData.privilege;
        },

        server() {
            return this.$store.state.cabinet.buyPrivilegeData.server;
        }
    },

    methods: {
        openModal(modalId) {
            this.$store.dispatch('openModal', modalId);
        },

        closeModal() {
            this.$store.dispatch('closeModal');
        },

        buyPrivilege() {
            axios.post('/privileges/buy', this.$store.state.cabinet.buyPrivilegeData)
                .then(res => {
                    console.log(res.data);
                })
                .catch(error => {
                    console.log(error);
                })
        }
    },
}
</script>

<style scoped>

</style>
