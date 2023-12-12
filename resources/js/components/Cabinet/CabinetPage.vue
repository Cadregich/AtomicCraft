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
                <template v-if="userInfo.capabilitiesFromTotalDonate">
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
                            <template v-if="userInfo.capabilitiesFromTotalDonate.hdSkinsUpload">
                                <i class="fa-solid fa-check"></i>
                            </template>
                            <template v-else>
                                <i class="fa-solid fa-xmark"></i>
                                <a class="how-get" title="Необходимо за 1 раз пополнить баланс от 400 монет">?</a>
                            </template>
                        </td>
                    </tr>
                </template>

                </tbody>
            </table>
        </div>

        <SkinBlock></SkinBlock>

        <PaymentBlock :balance="userInfo.userBalance"></PaymentBlock>

        <div class="privileges-block atomic-block column-center">
            <h4><i class="cabinet-block-title-icon fa-solid fa-shopping-basket"></i>Привилегии</h4>
            <div style="font-size: 23px">
                <router-link :to="{ path: '/privileges', query: { server: privilegesServer}}"
                             style="color: white">
                    Информация о привилегиях
                </router-link>
            </div>
            <div class="d-flex mt-2 mb-1" style="font-size: 19px">
                <label for="select_server">Cервер:</label>
                <select v-model="privilegesServer" @change="" style="margin-left: 10px"
                        id="select_server" class="atomic-input" aria-label="Сервер"
                        name="currency">
                    <option selected :value="privilegesServer">{{ privilegesServer }}</option>
                </select>
            </div>
            <div class="privileges mt-2">
                <template v-for="privilege in privileges">
                    <div class="privilege-tile column-center">
                        <b>{{ privilege.title }}</b>
                        <div>{{ privilege.price }} <i class="fa-solid fa-coins"></i></div>
                        <button class="atomic-butt1 mt-2" @click="buyPrivilege(privilege)">
                            Купить
                        </button>
                    </div>
                </template>
            </div>
        </div>

        <div class="daily-gift-block atomic-block column-center">
            <h4>Подарок за ежедневный вход</h4>
            <div class="d-flex">
                <label for="select_server">Cервер:</label>
                <select v-model="dailyGiftServer" @change="" style="margin-left: 10px"
                        id="select_server" class="atomic-input" aria-label="Сервер"
                        name="currency">
                    <option selected value="Atomic Fragility">Atomic Fragility</option>
                </select>
            </div>

            <form @submit.prevent="checkDailyGift()">
                <button class="bg-none bg-transparent border-0" type="submit">
                    <i class="gift mt-3 fa-solid fa-gift"></i>
                </button>
            </form>
            <template v-if="dailyGiftData.nextGift">
                <template v-if="dailyGiftData.status">
                    <span class="mt-3">Вы получаете подарок <span
                        class="text-info fst-italic">{{ dailyGiftData.days_received }}</span> суток подряд</span>
                    <span class="">
                Сегодня вы можете получить <span class="text-info">{{ dailyGiftData.nextGift.title }} </span>
                <span class="fst-italic"> х{{ dailyGiftData.nextGift.count }}</span>
            </span>
                </template>
                <span v-else class="d-flex flex-column align-items-center">
                Подарок за сегодня успешно получен! <br>
                <span>Завра вы сможете получить <span class="text-info fst-italic">
                    {{ dailyGiftData.nextGift.title }}</span> х{{ dailyGiftData.nextGift.count }}</span>
            </span>
            </template>

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
    <buy-privilege-modal/>
</template>

<script>
import SkinBlock from "./SkinBlock.vue";
import PaymentBlock from "./PaymentBlock.vue";
import BuyPrivilegeModal from "./BuyPrivilegeModal.vue";
import {Notification} from "@/notifications.js";

export default {
    components: {
        BuyPrivilegeModal,
        PaymentBlock,
        SkinBlock
    },
    name: "Cabinet",
    data() {
        return {
            userInfo: [],
            skinPaths: {
                skinPath: '',
                capePath: '',
                defaultSkinPath: '',
            },
            privileges: {},
            dailyGiftData: {},
            privilegesServer: 'Atomic Fragility',
            dailyGiftServer: 'Atomic Fragility'
        }
    },
    async created() {
        await this.getUserInfo();
        await this.getPrivilegesData();
        this.getDailyGiftData();
    },
    methods: {
        getUserInfo() {
            axios.get('/cabinet/user-info')
                .then(res => {
                    console.log(res.data);
                    this.userInfo = res.data;
                })
                .catch(error => {
                    console.log(error);
                });
        },

        getPrivilegesData() {
            axios.get('/cabinet/privileges-data', {params: {server: this.privilegesServer}})
                .then(res => {
                    console.log(res.data);
                    this.privileges = res.data;
                })
                .catch(error => {
                    console.log(error);
                });
        },

        buyPrivilege(privilege) {
            this.$store.state.cabinet.buyPrivilegeData = {privilege: privilege, server: this.privilegesServer};
            console.log(this.$store.state.cabinet.buyPrivilegeData);
            this.$store.dispatch('openModal', 'buy-privileges-modal');
        },

        getDailyGiftData() {
            axios.get('/cabinet/daily-gift', {params: {server: this.dailyGiftServer}})
                .then(res => {
                    console.log(res.data)
                    this.dailyGiftData = res.data;
                })
        },

        checkDailyGift() {
            axios.post('/cabinet/daily-gift', {server: this.dailyGiftServer})
                .then(res => {
                    Notification.error('Подарок успешно получен!')
                    console.log(res.data);
                    this.getDailyGiftData();
                })
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

#deposit-select-currency > option {
    color: black;
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
