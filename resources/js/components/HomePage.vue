<template>
    <div>
        <div class="content-block w-100 d-flex justify-content-center">
            <div style="display: flex; max-width: 1900px">
                <div class="content-block-content position-relative" style="width: 100% !important">
                    <img class="w-100 h-100" alt="Контент страницы" src="/images/main-page-content-1.jpg"
                         style="z-index: 1">
                    <img class="content-arrow-down" src="/images/svg/arrow_down.svg" alt="Описание изображения"
                    style="z-index: 3">
                    <img class="content-arrow-down" src="/images/svg/arrow_down_black.svg" alt="Описание изображения"
                    style="width: 16%; z-index: 2; left: 8.5%; top: 69%">
                    <div class="content-paginator d-flex w-100 h-100 justify-content-center position-absolute">
                        <div class="content-paginator-button content-paginator-button-active"></div>
                        <div class="content-paginator-button"></div>
                        <div class="content-paginator-button"></div>
                    </div>
                </div>
                <div class="content-block-game-items w-25" style="width: 25% !important;">
                    <div class="game-items w-100">
                        <div>
                            <img class="item item-1" alt="Item 1" src="/images/item1.png">
                            <img class="item item-2" alt="Item 2" src="/images/item1.png">
                        </div>
                        <div style="margin-top: 4%">
                            <img class="item item-3" alt="Item 3" src="/images/item1.png">
                            <img class="item item-4" alt="Item 4" src="/images/item1.png">
                            <img class="item item-5" alt="Item 5" src="/images/item1.png">
                        </div>
                        <div style="margin-top: 4%">
                            <img class="item item-6" alt="Item 6" src="/images/item1.png">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="step-container" style="margin-left: auto; margin-right: auto">
        <div class="step d-flex justify-content-center align-items-center flex-column">
            <div class="instruction-number d-flex justify-content-center align-items-center text-light">
                1
            </div>
            <button class="instruction-content">Зарегистрироваться</button>
        </div>
        <div class="arrow"><i class="fas fa-arrow-right"></i></div>
        <div class="step d-flex justify-content-center align-items-center flex-column">
            <div class="instruction-number d-flex justify-content-center align-items-center text-light">
                2
            </div>
            <button class="instruction-content">Скачать лаунчер</button>
        </div>
        <div class="arrow"><i class="fas fa-arrow-right"></i></div>
        <div class="step d-flex justify-content-center align-items-center flex-column">
            <div class="instruction-number d-flex justify-content-center align-items-center text-light">
                3
            </div>
            <button class="instruction-content">Наслаждайся</button>
        </div>
    </div>
</template>

<script>
export default {
    name: "Home",
}
</script>

<style scoped>
img {
    background: url('/images/backgrounds/items_bg.png');
}
.content-paginator {
    top: 93%;
    gap: 0.7%;
}

.content-paginator-button {
    width: 2.7%;
    height: 1.5%;
    background-color: rgba(0, 0, 0, 0.8);
    border: 0.01px solid rgba(0, 0, 0, 0.5);
}

.content-paginator-button-active {
    background: rgba(63, 62, 62, 0.8);
}

.content-block {
    padding: 4%;
    margin-top: 50px;
    overflow: hidden;
}


.content-block-content {
    width: 75%;
    max-width: 75%;
}

@media (max-width: 800px) {
    .content-block {
        padding: 1rem;
    }

    .content-block-content {
        width: 70%;
    }
}

@media (max-width: 400px) {
    .content-block {
        padding: 0 0.4rem;
    }
}

.content-arrow-down {
    width: 15%;
    position: absolute;
    left: 8.5%;
    top: 70%;
    animation: arrowAnimation 1.5s ease-in-out infinite;
}

@keyframes arrowAnimation {
    0% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(10%);
    }
    100% {
        transform: translateY(0);
    }
}

.game-items {
    margin-top: 10%;
    transform: rotate(10deg);
    margin-left: 5%;
}

.item {
    border: 0.01px solid black;
    position: relative;
    image-rendering: optimizeSpeed;
    image-rendering: -moz-crisp-edges;
    image-rendering: -o-crisp-edges;
    image-rendering: pixelated;
    -ms-interpolation-mode: nearest-neighbor;
}

.item-1 {
    width: 25%;
    margin-left: 12%;
}

.item-2 {
    width: 25%;
    margin-left: 13%;
}

.item-3 {
    margin-left: 13%;
    width: 14%;
    margin-top: 8%;
}

.item-4 {
    width: 18%;
    margin-left: 7%;
    margin-bottom: 2%;
}

.item-5 {
    margin-left: 9%;
    width: 14%;
    margin-top: 6%;
}

.item-6 {
    width: 18%;
    margin-left: 33%;
}

@media (max-width: 800px) {
    .item-1, .item-2 {
        width: 32%;
    }
    .item-3, .item-5 {
        width: 20%;
    }
    .item-4 {
        width: 26%;
    }
    .item-6 {
        width: 25%;
        margin-left: 38%;
    }

}

.step-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 70px;
    max-width: 2080px;
}

.step {
    flex-basis: 30%;
    text-align: center;
}

.arrow {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin: 0 10px;
    font-size: 40px;
    color: white;
}

.instruction-number {
    border-radius: 50%;
    background: indigo;
    width: 70px;
    height: 70px;
    font-size: 22px;
}

.instruction-content {
    padding: 7px;
    border: 1px;
    border-radius: 5px;
    margin-top: 10px;
    font-size: 23px;
}

@media (max-width: 800px) {
    .step-container {
        flex-direction: column;
    }

    .arrow {
        margin: 10px 0;
        transform: rotate(90deg);
    }

    .step {
        margin-bottom: 15px;
    }

    .arrow {
        margin-bottom: 30px;
    }

    .instruction-content {
        font-size: 90%;
    }
    .instruction-number {
        font-size: 25px;
        width: 60px;
        height: 60px;
    }
}
</style>
