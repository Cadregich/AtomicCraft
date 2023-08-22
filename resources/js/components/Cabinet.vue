<template>
    <div class="cabinet-blocks">
        <div class="info-block atomic-block column-center">
            <h4><i class="info-block-title-icon cabinet-block-title-icon fa-solid fa-list">
            </i>Информация об аккаунте</h4>
            <table class="info-table w-100 mt-2">
                <tbody>
                <tr>
                    <td>Почта</td>
                    <td class="text-break">{{ userInfo.userEmail }}</td>
                </tr>
                <tr>
                    <td>Дата регистрации</td>
                    <td>{{ userInfo.registrationDate }}</td>
                </tr>
                <tr>
                    <td>Последний вход в игру</td>
                    <td>{{ userInfo.lastGameLoginDate }}</td>
                </tr>
                <tr>
                    <td>Статус</td>
                    <td>{{ userInfo.privilegeTitle }}</td>
                </tr>
                <tr>
                    <td class="text-center text-info" colspan="2">Возможности</td>
                </tr>
                <tr>
                    <td>Смена ника</td>
                    <td>
                        <template v-if="userInfo.capabilitiesFromTotalDonate.nickChange ">
                            <i class="fa-solid fa-check"></i>
                        </template>
                        <template v-else>
                            <i class="fa-solid fa-xmark"></i>
                            <a class="how-get" title="Необходимо за 1 раз пополнить баланс от 100 монет">?</a>
                        </template>
                    </td>
                </tr>
                <tr>
                    <td>Загружать плащи</td>
                    <td>
                        <template v-if="userInfo.capabilitiesFromTotalDonate.capeChange">
                            <i class="fa-solid fa-check"></i>
                        </template>
                        <template v-else>
                            <i class="fa-solid fa-xmark"></i>
                            <a class="how-get" title="Необходимо за 1 раз пополнить баланс от 250 монет">?</a>
                        </template>
                    </td>
                </tr>
                <tr>
                    <td>Загружать hd скины</td>
                    <td>
                        <template v-if="userInfo.capabilitiesFromTotalDonate.hdSkinsAndCapeUpload">
                            <i class="fa-solid fa-check"></i>
                        </template>
                        <template v-else>
                            <i class="fa-solid fa-xmark"></i>
                            <a class="how-get" title="Необходимо за 1 раз пополнить баланс от 400 монет">?</a>
                        </template>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <SkinBlock></SkinBlock>

        <div class="balance-block atomic-block column-center">
            <h4><i class="cabinet-block-title-icon fa-solid fa-money-check-dollar"></i>Баланс</h4>
            <div class="deposit-bonus d-flex column-gap-3">
                <i>От:</i>
                <div>100 <i class="fa-solid fa-coins"></i> + 5%</div>
                <div>200 <i class="fa-solid fa-coins"></i> + 10%</div>
                <div>500 <i class="fa-solid fa-coins"></i> + 30%</div>
            </div>
            <div class="container mt-3">
                <form class="w-100 d-flex flex-column align-items-center" id="depositForm" @submit.prevent="depositSubmit">
                    <label for="deposit-input">Введите сумму для пополнения баланса</label>
                    <div class="d-flex mt-1">
                        <input v-model="depositValue" @input="handleDepositValue" id="deposit-input" class="atomic-input" type="number" placeholder="" name="value">
                        <select v-model="depositSelectedCurrency" @change="getCommonCurrencyMultiplier()" id="deposit-select-currency" class="atomic-input" aria-label="Выберите валюту" name="currency">
                            <option value="UAH">&#8372; UAH</option>
                            <option value="RUB">&#8381; RUB</option>
                            <option value="USD">&#36; USD</option>
                        </select>
                    </div>
                </form>
            </div>
            <button class="atomic-butt1 mt-3" type="submit" form="depositForm">Пополнить</button>
            <div class="deposit-final-sum mt-2" v-if="depositValue">
                Вам начислиться: <span class="nobr"><span style="font-size: 18px">{{ formattedTotalCoins }} </span> <i class="fa-solid fa-coins"></i></span>
            </div>
        </div>

        <div class="privileges-block atomic-block column-center">
            <h4><i class="cabinet-block-title-icon fa-solid fa-shopping-basket"></i>Привилегии</h4>
            <div class="privileges mt-2">
                <template v-for="i in 6">
                    <div class="privilege-tile column-center">
                        <b>Vip</b>
                        <div>100 <i class="fa-solid fa-coins"></i></div>
                        <button class="atomic-butt1 mt-2">Купить</button>
                    </div>
                </template>
            </div>
        </div>

        <div class="daily-gift-block atomic-block column-center">
            <h4>Подарок за ежедневный вход</h4>
            <form action="" method="post">
                <button class="bg-none bg-transparent border-0" type="submit">
                    <i class="gift mt-3 fa-solid fa-gift"></i>
                </button>
            </form>

            <span class="mt-3">Вы получаете подарок <span class="text-info fst-italic">5</span> суток подряд</span>
            <span class="">
                Сегодня вы можете получить <span class="text-info">Слиток железа </span>
                <span class="fst-italic"> х30</span>
            </span>
        </div>
        <div class="ref-and-promo-block">
            <div class="promo-block atomic-block w-100 column-center">
                <h4><i class="cabinet-block-title-icon fa-solid fa-keyboard"></i>Ввести промокод</h4>
                <label class="w-100">
                    <input class="atomic-input w-100 mt-2" type="text">
                </label>
                <div class="w-100 mt-1">
                    <a class="text-white" href="">Где взять промокод?</a>
                </div>

            </div>
            <div class="ref-block atomic-block w-100 mt-3 column-center">
                <b>Рефералка</b>
                <b class="mt-2">В разработке</b>
            </div>
        </div>
    </div>
</template>

<script>
import SkinBlock from "./SkinBlock.vue";
import numeral from 'numeral';

export default {
    components: {
        SkinBlock
    },
    name: "Cabinet",
    data() {
        return {
            userInfo: [],
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
    created() {
        axios.get('/cabinet/user-info')
            .then(res => {
                console.log(res.data);
                this.userInfo = res.data;
            })
            .catch(error => {
                console.log(error);
            });
    },
    mounted() {
        this.getCommonCurrencyMultiplier();
    },
    methods: {
        getCommonCurrencyMultiplier() {
            axios.get('/cabinet/common-currency-multiplier', { params: { currency: this.depositSelectedCurrency } })
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
            alert(this.totalCoins);
        },
        calculateTotalCoins() {
            this.totalCoins = (this.depositValue * this.currencyMultiplier).toFixed(2);
        }
    }
}
</script>

<style>
@import url('/resources/css/cabinet-grid.css');

.cabinet-block-title-icon {
    margin-right: 10px;
    font-size: 20px;
}

.info-block {
    font-size: 110%;
    max-width: 400px;
}

.info-block-title-icon {
    font-size: 15px !important;
    padding: 3px;
    border: 2px solid white;
    border-radius: 5px
}

.info-table > tbody > tr > td:nth-child(even) {
    text-align: end;
    padding-left: 60px;
}

.how-get {
    text-decoration: none;
    position: relative;
    bottom: 6px;
    left: 3px;
    font-size: 13px;
    color: rgb(223, 133, 203);
}

.how-get:hover {
    color: #3002dc;
    cursor: pointer;
}

.balance-block {
    width: 430px;
}

#deposit-input {
    border-radius: 5px 0 0 5px;
}

#deposit-select-currency {
    border-radius: 0 5px 5px 0;
}

#deposit-select-currency > option {
    color: black;
}

.deposit-bonus {
    font-size: 15px;
}

.deposit-final-sum {
    font-size: 17px;
}

.privilege-tile {
    background-color: rgba(0, 0, 0, 0.1);
    padding: 10px;
    border-radius: 20px;
    width: 110px;
}

.privilege-tile > b {
    font-size: 18px;
}

.privilege-tile > div {
    font-size: 17px;
}

.daily-gift-block > div {
    margin-top: 10px;
    font-size: 20px;
}

.daily-gift-block > span {
    font-size: 22px;
}

.gift {
    font-size: 80px;
    color: gold;
    transition: 0.1s;
}

.gift:hover {
    color: aqua;
    position: relative;
    bottom: 5px;
    transition: 0.1s;
}

.gift:active {
    color: #9812ff;
    transition: 0.2s;
}

.ref-and-promo-block {

}

.promo-block > div > a {
    font-style: italic;
    text-decoration: none;
}

.promo-block > div > a:hover {
    color: #1946da !important;
}

</style>
