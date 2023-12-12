<template>
    <div class="balance-block atomic-block column-center">
        <h4>
            <i class="cabinet-block-title-icon fa-solid fa-money-check-dollar"></i>
            Баланс: {{ balance }} <i class="fa-solid fa-coins"></i>
        </h4>
        <div class="deposit-bonus">
            <i id="deposit-bonus-label">Бонус при пополнении от:</i> <br>
            <div class="d-flex">
                <div class="atomic-block atomic-bg-dark">100 <i class="fa-solid fa-coins"></i> + 5%</div>
                <div class="atomic-block atomic-bg-dark" style="margin-left: 5px; margin-right: 5px">
                    200 <i class="fa-solid fa-coins"></i> + 10%
                </div>
                <div class="atomic-block atomic-bg-dark">500 <i class="fa-solid fa-coins"></i> + 30%</div>
            </div>
        </div>
        <div class="mt-3">
            <form class="w-100 d-flex flex-column align-items-center" id="depositForm"
                  @submit.prevent="depositSubmit">
                <label for="deposit-input">На какую сумму вы хотите пополнить баланс</label>
                <div class="d-flex mt-1 w-100">
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
                <button class="text-white mt-3" id="deposit-btn" type="submit">Пополнить</button>

            </form>
        </div>
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
    props: ['balance'],
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
            if (this.totalCoins > 0) {
                this.makePayment(this.depositValue, this.depositSelectedCurrency);
            }
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
#deposit-bonus-label, label[for="deposit-input"] {
    font-size: 17px;
}

.balance-block {
    width: 430px;
}

#deposit-input {
    border-radius: 10px 0 0 10px;
    font-size: 20px;
    width: 100%;
}

#deposit-select-currency {
    border-radius: 0 10px 10px 0;
}

#deposit-btn {
    width: 100%;
    height: 38px;
    font-size: 18px;
    border: none;
    border-radius: 10px;
    background-color: #5618db;
}

#deposit-btn:hover {
    background-color: #6129e0;
    transition: 0.3s;
}

.deposit-bonus {
    font-size: 15px;
}

.deposit-final-sum {
    font-size: 17px;
}
</style>
