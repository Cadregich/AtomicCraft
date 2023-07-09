<template>
    <div>
        <ModalLayout id="buy-goods-modal">
            <template v-slot:modalHeader>
                Покупка предмета «{{ productData.name }}»
            </template>

            <template v-slot:modalBody>
                <form id="buy-form">
                    <input id="goodsId" type="hidden" name="item-id">
                    <label for="buy-item-range"></label>
                    <input id="buy-item-range" type="text" value="1" name="items-count">
                    <input class="form-range" id="itemRange" type="range" value="1" min="1" max="100" step="1">
                </form>
                <label class="buy-label" for="itemRange">Выберите количество</label>
                <p class="modal-cost"></p>
            </template>

            <template v-slot:modalFooter>
                <button class="btn btn-primary" id="submit-buy-form"
                        @click="closeModal(); openModal('thanks-modal')">
                    Перейти к оплате
                </button>
                <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Отменить</button>
            </template>
        </ModalLayout>

        <thanks-modal></thanks-modal>

    </div>
</template>

<script>
import ModalLayout from './ModalLayout.vue'
import ThanksModal from './ThanksModal.vue'

export default {
    name: 'BuyGoodsModal',
    components: {
        ModalLayout,
        ThanksModal
    },
    computed: {
        productData() {
            return this.$store.getters.getCart;
        }
    },
    data() {
        return {
            openThanksModal: false,
        }
    },
    methods: {
        openModal(modalId) {
            this.$store.dispatch('openModal', modalId);
        },

        closeModal() {
            this.$store.dispatch('closeModal');
        }
    },
}
</script>

<style scoped>
#buy-item-range {
    text-align: center;
    outline: none;
    background: none;
    width: 50px;
    border: 1px solid gray;
    border-radius: 10px;
    color: lightgray;
    font-size: 15px;
}

#buy-form {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
}

.buy-label {
    color: lightgray;
}
</style>
