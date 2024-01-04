<template>
    <div class="balance-block atomic-block column-center">
        <h4>
            Ваш баланс: {{ balance }} <i class="fa-solid fa-coins"></i>
        </h4>
        <div id="block-title-separator"></div>
        <div class="deposit-bonus">
            <i id="deposit-bonus-label">Бонус при пополнении от:</i> <br>
            <div class="d-flex">
                <button class="atomic-block atomic-bg-dark" @click="setDepositValue(100)">
                    100 <i class="fa-solid fa-coins"></i> + 5%
                </button>
                <button class="atomic-block atomic-bg-dark" @click="setDepositValue(200)"
                        style="margin-left: 5px; margin-right: 5px">
                    200 <i class="fa-solid fa-coins"></i> + 10%
                </button>
                <button class="atomic-block atomic-bg-dark" @click="setDepositValue(500)">
                    500 <i class="fa-solid fa-coins"></i> + 30%
                </button>
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
            class="fa-solid fa-coins"></i>
        <template v-if="bonusValue"> Бонус: {{formattedBonusValue}} <i
            class="fa-solid fa-coins"></i></template></span>
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
            depositBonuses: {},
            bonusValue: 0,
        }
    },

    computed: {
        formattedTotalCoins() {
            return numeral(this.totalCoins).format('0,0');
        },

        formattedBonusValue() {
            return numeral(this.bonusValue).format('0,0');
        }
    },

    mounted() {
        this.getCommonCurrencyMultiplier();
        this.getDepositBonuses();
    },

    methods: {
        getCommonCurrencyMultiplier() {
            return axios.get('/cabinet/common-currency-multiplier', {params: {currency: this.depositSelectedCurrency}})
                .then(res => {
                    this.currencyMultiplier = res.data;
                    this.calculateTotalCoins();
                });
        },
        getDepositBonuses() {
            return axios.get('/cabinet/deposit-bonuses')
                .then(res => {
                    console.log(res.data);
                    this.depositBonuses = res.data;
                })
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
            let bonusFactor = 1;

            for (let key in this.depositBonuses) {
                if (this.totalCoins >= parseInt(key)) {
                    bonusFactor = 1 + (parseInt(this.depositBonuses[key]) / 100);
                }
            }
            this.bonusValue = this.totalCoins * bonusFactor - this.totalCoins;
        },


        makePayment(amount, currency) {
            axios.post('/cabinet/pay', {amount: amount, currency: currency})
                .then(res => {
                    console.log(res.data);
                    location.href = res.data.url + '?data=' + res.data.data + '&signature=' + res.data.signature;
                });
        },

        async setDepositValue(value) {
            try {
                this.depositValue = await this.getCurrencyValueByCoins(value, this.depositSelectedCurrency);
                this.handleDepositValue();
            } catch (error) {
                console.error(error);
            }
        },

        async getCurrencyValueByCoins(amountCoins, currency) {
            try {
                const response = await axios.get('/cabinet/currency-to-coins', {
                    params: { amountCoins: amountCoins, currency: currency }
                });
                return response.data;
            } catch (error) {
                throw error;
            }
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
    border: 1px solid rgba(255, 255, 255, 0.2);
    background: rgba(67, 2, 153, 0.4);
    font-size: 20px;
    width: 100%;
}

#deposit-select-currency {
    border-radius: 0 10px 10px 0;
    border: 1px solid rgba(255, 255, 255, 0.2);
    background: rgba(67, 2, 153, 0.4);
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
