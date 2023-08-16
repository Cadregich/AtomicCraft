<template>
    <div class="cabinet-blocks">
        <div class="info-block atomic-block column-center">
            <h4><i class="info-block-title-icon cabinet-block-title-icon fa-solid fa-list">
            </i>Информация об аккаунте</h4>
            <table class="info-table w-100 mt-2">
                <tbody>
                <tr>
                    <td>Почта</td>
                    <td class="text-break">thekeykeyloy@gmail.com</td>
                </tr>
                <tr>
                    <td>Дата регистрации</td>
                    <td>24.11.2004</td>
                </tr>
                <tr>
                    <td>Последний вход в игру</td>
                    <td>11.24.2022</td>
                </tr>
                <tr>
                    <td>Статус</td>
                    <td>Игрок</td>
                </tr>
                <tr>
                    <td class="text-center text-info" colspan="2">Возможности</td>
                </tr>
                <tr>
                    <td>Смена ника</td>
                    <td>
                        <!--                    @if($capabilitiesFromTotalDonate['nickChange'])
                                            <i class="fa-solid fa-check"></i>
                                            @else
                                            <i class="fa-solid fa-xmark"></i>
                                            <a class="how-get" title="Необходимо 1 раз пополнить баланс от 100 монет">?</a>
                                            @endif-->
                        <i class="fa-solid fa-check"></i>
                    </td>
                </tr>
                <tr>
                    <td>Загружать плащи</td>
                    <td>
                        <!--                    @if($capabilitiesFromTotalDonate['skinAndCapeChange'])-->
                        <!--                    <i class="fa-solid fa-check"></i>-->
                        <!--                    @else-->
                        <!--                    <i class="fa-solid fa-xmark"></i>-->
                        <!--                    <a class="how-get" title="Необходимо 1 раз пополнить баланс от 250 монет">?</a>-->
                        <!--                    @endif-->
                        <i class="fa-solid fa-check"></i>
                    </td>
                </tr>
                <tr>
                    <td>Загружать hd скины</td>
                    <td>
                        <!--                    @if($capabilitiesFromTotalDonate['hdSkinsAndCapeUpload'])-->
                        <!--                    <i class="fa-solid fa-check"></i>-->
                        <!--                    @else-->
                        <!--                    <i class="fa-solid fa-xmark"></i>-->
                        <!--                    <a class="how-get" title="Необходимо 1 раз пополнить баланс от 400 монет">?</a>-->
                        <!--                    @endif-->
                        <i class="fa-solid fa-xmark"></i>
                        <a class="how-get" title="Необходимо 1 раз пополнить баланс от 400 монет">?</a>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <SkinBlock></SkinBlock>
    </div>

</template>

<script>
import SkinBlock from "./SkinBlock.vue";

export default {
    components: {
        SkinBlock
    },
    name: "Cabinet",
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
