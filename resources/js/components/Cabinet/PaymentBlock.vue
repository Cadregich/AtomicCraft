<template>
    <div class="balance-block atomic-block column-center">
        <h4><i class="cabinet-block-title-icon fa-solid fa-money-check-dollar"></i>Баланс</h4>
        <div class="deposit-bonus d-flex column-gap-3">
            <i>От:</i>
            <div>100 <i class="fa-solid fa-coins"></i> + 5%</div>
            <div>200 <i class="fa-solid fa-coins"></i> + 10%</div>
            <div>500 <i class="fa-solid fa-coins"></i> + 30%</div>
        </div>
        <div class="container mt-3">
            <form class="w-100 d-flex flex-column align-items-center" id="depositForm"
                  @submit.prevent="depositSubmit">
                <label for="deposit-input">Введите сумму для пополнения баланса</label>
                <div class="d-flex mt-1">
                    <input v-model="depositValue" @input="handleDepositValue" id="deposit-input"
                           class="atomic-input" type="number" placeholder="" name="value">
                    <select v-model="depositSelectedCurrency" @change="getCommonCurrencyMultiplier()"
                            id="deposit-select-currency" class="atomic-input" aria-label="Выберите валюту"
                            name="currency">
                        <option value="UAH">&#8372; UAH</option>
                        <option value="RUB">&#8381; RUB</option>
                        <option value="USD">&#36; USD</option>
                    </select>
                </div>
            </form>
        </div>
        <button class="atomic-butt1 mt-3" type="submit" form="depositForm">Пополнить</button>
        <div class="deposit-final-sum mt-2" v-if="depositValue">
            Вам начислиться: <span class="nobr"><span style="font-size: 18px">{{ formattedTotalCoins }} </span> <i
            class="fa-solid fa-coins"></i></span>
        </div>
    </div>
</template>

<script>
import numeral from "numeral";

export default {
    name: "PaymentBlock",
    data() {
        return {
            depositValue: '',
            depositSelectedCurrency: 'UAH',
            currencyMultiplier: 0,
            totalCoins: 0,
        }
    },
    computed: {
        formattedTotalCoins() {
            return numeral(this.totalCoins).format('0,0.00');
        }
    },
    mounted() {
        this.getCommonCurrencyMultiplier()
    },
    methods: {
        getCommonCurrencyMultiplier() {
            return axios.get('/cabinet/common-currency-multiplier', {params: {currency: this.depositSelectedCurrency}})
                .then(res => {
                    this.currencyMultiplier = res.data;
                    this.calculateTotalCoins();
                });
        },
        handleDepositValue() {
            if (this.depositValue < 0) {
                this.depositValue = 0;
            }
            this.calculateTotalCoins();
        },
        depositSubmit() {
            this.makePayment(this.depositValue, this.depositSelectedCurrency);
        },
        calculateTotalCoins() {
            this.totalCoins = (this.depositValue * this.currencyMultiplier).toFixed(2);
        },
        makePayment(amount, currency) {
            axios.post('/cabinet/pay', {amount: amount, currency: currency})
                .then(res => {
                    console.log(res.data);
                    location.href = res.data.url + '?data=' + res.data.data + '&signature=' + res.data.signature;
                });
        }
    }
}
</script>

<style scoped>

</style>
