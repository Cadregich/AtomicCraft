<template>
    <modal-layout id="info-modal">
        <template v-slot:modalHeader>
            <slot name="modalHeader"></slot>
        </template>
        <template v-slot:modalBody>
            <slot name="modalBody"></slot>
        </template>
        <template v-slot:modalFooter>
            <slot name="modalFooter"></slot>
            <button class="ml-auto btn btn-danger" @click="closeModal()">Закрыть</button>
        </template>
    </modal-layout>
</template>

<script>
import ModalLayout from './ModalLayout.vue';
export default {
    name: "InfoModal",
    components: { ModalLayout },
    methods: {
        // openModal() {
        //     this.$store.dispatch('openModal', 'info-modal');
        // },

        closeModal() {
            this.$store.dispatch('closeModal', 'info-modal');
        }
    }
}
</script>

<style scoped>

</style>
