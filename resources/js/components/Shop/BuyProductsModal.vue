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
                    <input :value="productCount" id="buy-item-range" type="text" name="items-count"
                           maxlength="3" @input="validateProductCount" inputmode="numeric">
                    <input v-model="productCount" class="form-range" id="itemRange" type="range" min="1" max="100"
                           step="1">
                </form>
                <label class="buy-label" for="itemRange">Выберите количество</label>
                <p class="modal-cost"><i class="cost">{{ this.productCount * productData.price }}</i>
                    <i class="fa-solid fa-coins modal-coins"></i> за {{ this.productCount }} шт.</p>
            </template>

            <template v-slot:modalFooter>
                <button class="btn btn-primary" id="submit-buy-form"
                        @click="closeModal(); buyGoods(); openModal('thanks-modal')">
                    Перейти к оплате
                </button>
                <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Отменить</button>
            </template>
        </ModalLayout>

        <thanks-modal></thanks-modal>

    </div>
</template>

<script>
import ModalLayout from '../ModalLayout.vue'
import ThanksModal from './ThanksModal.vue'

export default {
    name: 'BuyGoodsModal',
    components: {
        ModalLayout,
        ThanksModal
    },
    computed: {
        productData() {
            return this.$store.state.shop.productData;
        }
    },
    data() {
        return {
            openThanksModal: false,
            productCount: 1
        }
    },
    methods: {
        validateProductCount(event) {
            let value = event.target.value.replace(/^0+/, '');
            if (value) {
                if (!isNaN(value)) {
                    value = parseInt(value); // Преобразуем в число
                    if (value < 1) {
                        this.productCount = 1;
                        event.target.value = 1;
                        value = 1;
                    }
                    if (value > 999) {
                        this.productCount = 999;
                        event.target.value = 999;
                        value = 999;
                    }
                    this.productCount = value;
                } else {
                    event.target.value = 1;
                }
            } else {
                this.productCount = 1;
            }
        },

        openModal(modalId) {
            this.$store.dispatch('openModal', modalId);
        },

        closeModal() {
            this.$store.dispatch('closeModal');
        },

        buyGoods() {
            const orderData = {
                'items-count': this.productCount,
                'item-id': this.productData.id
            };

            axios.post('/shop/buy', orderData)
                .then(response => {
                    console.log(response.data);
                })
                .catch(error => {
                    console.error(error);
                });
        }
    },
    watch: {
        productData: {
            handler() {
                this.productCount = 1;
            },
        }
    }
}
</script>

<style scoped>
#buy-item-range {
    text-align: center;
    outline: none;
    background: none;
    width: 50px;
    border: 1px solid #808080;
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
