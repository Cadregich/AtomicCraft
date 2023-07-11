<template>
    <ModalLayout id="thanks-modal">

        <template v-slot:modalHeader>
            <div v-if="status !== 'error'">
                Спасибо за покупку!
            </div>
            <div v-else>
                Ошибка покупки
            </div>
        </template>

        <template v-slot:modalBody>
            <div v-if="status !== 'error'">
                <p>Благодарим вас за покупку.</p>
                Вы внесли свой вклад в развития нашего проекта.
                <p>Ваши пожертвования помогают нам разваваться и существовать.</p>
                <p>С свою очередь, мы продолжим развать наш проект и делать вас счастливее!</p>
            </div>
            <div v-else>
                <p>Произошла ошибка. Убедитесь, что вы выбрали правильное количество товара, и попробуйте еще раз.</p>
                <p>Если проблема на нашей стороне, пожалуйста, сообщите нам.</p>
            </div>
        </template>

        <template v-slot:modalFooter>
            <button @click="closeModal()">Закрыть</button>
        </template>

    </ModalLayout>
</template>

<script>
import ModalLayout from './ModalLayout.vue';

export default {
    name: 'ThanksModal',
    components: { ModalLayout },
    data() {
        return {
            status: ''
        }
    },
    methods: {
        async getStatus() {
            try {
                await axios.get('/data');
                return 'success';
            } catch (error) {
                return 'error';
            }
        },
        openModal() {
            this.$store.dispatch('openModal', 'thanks-modal');
        },

        closeModal() {
            this.$store.dispatch('closeModal', 'thanks-modal');
        }
    }
}
</script>
