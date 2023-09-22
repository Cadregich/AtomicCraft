<template>
    <buy-products-modal/>
    <div id="low-screen-balance-block">
        <div id="low-screen-balance-block-items">
            <div>
                <div id="balance-low-screen">
                    <div id="balance-low-screen-balance">
                        <div class="nobr" id="balance-text">Ваш баланс</div>
                        {{ balance }} <i class="fa-solid fa-coins" id="balance-coins"></i>
                    </div>
                    <button class="butt" id="balance-butt">Пополнить</button>
                </div>
                <div class="low-screen-purchases-history-butt-area d-flex justify-content-around">
                    <router-link :to="{ name: 'shop.purchases-history' }" class="butt purchases-history-butt">
                        История
                    </router-link>
                </div>
            </div>
        </div>
    </div>
    <div id="shop-header-panel">
        <div id="shop-header-panel-body">
            <form method="get" id="search-and-filter-mods" @submit.prevent="submitFilter">
                <div id="form-search">
                    <label for="search"></label>
                    <input v-model="filters.search" id="search" type="text" name="search" placeholder="Найти предметы">
                    <button id="sub-search" type="submit"></button>
                </div>
                <div v-if="showFiltersResetButton">
                    <button type="button" id="remove-filters-xmark" @click="resetFilters()"><i
                        class="fa-solid fa-xmark"></i></button>
                </div>
                <div class="btn-group">
                    <button class="butt dropdown-toggle" id="mod-butt" type="button"
                            data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                        Выбрать мод
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-sm-start">
                        <li v-for="Modification in Mods" :key="Modification">
                            <button class="dropdown-item" type="submit" name="mod" :value="Modification"
                                    @click="filters.mod = Modification">
                                {{ Modification }}
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="purchases-history-butt-area">
                    <router-link :to="{ name: 'shop.purchases-history' }" class="butt purchases-history-butt"
                                 id="normal-screen-purchases-history-butt">
                        История
                    </router-link>
                </div>
            </form>
            <div id="normal-screen-balance-block">
                <div id="balance">
                    <div class="nobr" id="balance-text">Ваш баланс</div>
                    {{ balance }} <i class="fa-solid fa-coins" id="balance-coins"></i>
                </div>
                <button class="butt" id="balance-butt">Пополнить</button>
            </div>
        </div>
    </div>


    <div id="cards-area">
        <div v-for="Product in Products" :key="Product.id">
            <div class="card">
                <img class="card-img-top" :alt="Product.name"
                     :src="'/storage/uploads/' + Product.img">
                <div class="card-body">
                    <div class="card-title">
                        <h4>{{ Product.name }}</h4>
                    </div>
                    <div class="card-text">
                        {{ Product.mod }}
                    </div>
                    <div class="card-button-area">
                        <button class="butt card-btn"
                                @click="openModal({ name: Product.name, id: Product.id, price: Product.price })">
                            {{ Product.price }}
                            <i class="fa-solid fa-coins" id="card-coins"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div style="width: 100%"></div> <!-- Block-hack so that the paginator is always positioned below the cards. -->
        <div class="mt-3">
            <paginator ref="paginator" :path="'shop/products'"></paginator>
        </div>
    </div>
</template>

<script>
import Paginator from "./Paginator.vue";
import BuyProductsModal from "./BuyProductsModal.vue";

export default {
    name: "Shop",
    components: {
        Paginator,
        BuyProductsModal
    },
    data() {
        return {
            balance: 0,
            filters: {
                search: '',
                mod: '',
            },
            Mods: [],
            showFiltersResetButton: false
        }
    },
    computed: {
        Products() {
            return this.$store.state.shop.products;
        },
    },
    mounted() {
        console.log('Shop mounted');
        this.fetchBalance();
        this.fetchMods();
        this.adaptiveBalanceBoard();

        const {search, mod} = this.$route.query;
        if (search || mod) {
            this.filters = {search, mod};
            this.$store.commit('setProductsFilters', this.filters);
            this.showFiltersResetButton = true;
        }
        window.addEventListener('resize', () => {
            this.adaptiveBalanceBoard();

        });
    },
    methods: {
        fetchBalance() {
            axios.get('/user/balance')
                .then(res => {
                    this.balance = res.data;
                })
                .catch(error => {
                    console.log(error);
                })
        },
        fetchMods() {
            axios.get('/shop/goods-mods')
                .then(res => {
                    this.Mods = res.data;
                })
                .catch(error => {
                    console.log(error);
                });
        },
        submitFilter() {
            this.$router.push({ path: '/shop', query: {page: 1, search: this.filters.search, mod: this.filters.mod}});
            if (this.filters.search !== '' || this.filters.mod !== '') {
                this.showFiltersResetButton = true;
            }
        },
        adaptiveBalanceBoard() {
            if (document.documentElement.clientWidth < 1150) {
                document.getElementById('normal-screen-balance-block').style.display = 'none';
                document.getElementById('normal-screen-purchases-history-butt').style.display = 'none';
                document.getElementById('low-screen-balance-block-items').style.display = 'block';
                document.getElementById('search-and-filter-mods').style.justifyContent = 'center';
            } else {
                document.getElementById('low-screen-balance-block-items').style.display = 'none';
                document.getElementById('normal-screen-purchases-history-butt').style.display = 'flex';
                document.getElementById('normal-screen-balance-block').style.display = 'flex';
                document.getElementById('search-and-filter-mods').style.justifyContent = 'start';
            }
        },
        resetFilters() {
            this.$router.push({ path: '/shop', query: {page: 1}});
            this.filters = {
                search: '',
                mod: ''
            }
            this.showFiltersResetButton = false;
        },
        openModal(productData) {
            // console.log(productData);
            this.$store.commit('addToCart', productData);
            this.$store.dispatch('openModal', 'buy-goods-modal');
        }
    },
    beforeRouteUpdate(to, from, next) {
        let clearCache;
        clearCache = to.query.search !== from.query.search || to.query.mod !== from.query.mod;
        this.$refs.paginator.fetchData({ path: 'shop/products', ...to.query, clearCache: clearCache });
        next();
    }
}
</script>

<style scoped>
#shop-header-panel {
    margin: auto;
    width: 60%;
    height: 80px;
    background: #25033D;
    margin-top: 20px;
    border-radius: 5px;
    padding: 10px;
}

#shop-header-panel-body {
    display: flex;
    align-items: center;
    width: 100%;
    height: 100%;
}

#cards-area {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    margin: auto;
    margin-top: 5px;
    width: 80%;
}

#form-search {
    position: relative;
    margin-top: auto;
    margin-bottom: auto;
    margin-left: 5px;
    width: 250px;
    align-items: end;
}

#search-and-filter-mods {
    width: 100%;
    display: flex;
    align-items: center;
}

#search {
    z-index: 10;
    width: 100%;
    min-width: 125px;
    height: 42px;
    padding-left: 10px;
    border: 1px solid #7617AC;
    border-radius: 5px;
    outline: none;
    color: #9E9C9C;
}

#sub-search {
    position: absolute;
    top: 0;
    right: 0;
    width: 42px;
    height: 42px;
    border: none;
    background: #7617AC;
    border-radius: 0 5px 5px 0;
    cursor: pointer;
    font-size: 20px;
}

#sub-search:before {
    font-family: FontAwesome, serif;
    content: "\f002";

    color: #F9F0DA;
}

#sub-search:hover, #sub-search:active {
    font-size: 24px;
}

#remove-filters-xmark {
    width: 40px;
    height: 42px;
    color: white;
    background-color: #7617AC;
    border: 0;
    border-radius: 5px;
    font-size: 22px;
    margin-left: 3px;
}

#remove-filters-xmark:hover {
    color: red;
    font-size: 26px;
    transition: 0.2s;
}

#remove-filters-xmark:active {
    .fa-xmark {
        transform: rotate(180deg);
        font-size: 22px;
        transition: 0.3s;
    }
}

#balance {
    margin-right: 5px;
    font-size: 18px;
}

#balance-text {
    font-size: 16px !important;
}

#balance-butt {
    margin-left: 7px;
    min-width: 102px;
    font-size: 16px;
    font-weight: 500;
    width: 16%;
    height: 35px;
    border: none;
}

.fa-coins {
    color: lightgray;
}

#mod-butt {
    margin-left: 10px;
    height: 42px;
    font-size: 15px;
    font-weight: 500;
    border-radius: 10px;
    border: none;
}

#normal-screen-balance-block {
    display: flex;
    align-items: center;
    margin-left: auto;
}

#low-screen-balance-block {
    display: flex;
    justify-content: center;
    width: 100%;
    margin-top: 10px;
}

.low-screen-purchases-history-butt-area {
    display: flex;
    justify-content: center;
}

#balance-low-screen {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
    border-bottom: solid 1px pink;
}

#low-screen-balance-block-items {
    display: inline-flex;
    justify-content: center;
    background: #25033D;
    padding: 10px 30px 10px 30px;
    border-radius: 10px;
}

@media (max-width: 480px) {
    #shop-header-panel {
        width: 90% !important;
    }
}

@media (max-width: 1150px) {
    #search-and-filter-mods {
        justify-content: center;
    }
}

@media (max-width: 680px) {
    #mod-butt {
        white-space: normal;
        font-size: 13px;
    }

    #search {
        height: 35px;
        border: 0;
    }

    #mod-butt {
        margin-left: 10px;
    }

    #sub-search {
        display: none;
    }

    #remove-filters-xmark {
        width: 35px;
        height: 35px;
        font-size: 20px;
        margin-left: -5px;
    }

    .purchases-history-butt {
        margin-left: 0 !important;
        font-size: 12px;
    }

    #shop-header-panel {
        width: 80%;
    }
}

.card {
    width: 270px;
    height: 320px !important;
    background: #25033D !important;
    border: 2px solid rgba(0, 0, 0, 0.2) !important;
    margin-left: 10px;
    margin-top: 10px;
    border-radius: 20px !important;
}

@media (max-width: 800px) {
    .card {
        width: 250px;
        height: 280px;
        margin-left: 0;
    }
}

.card-img-top {
    border-radius: 7px;
    width: 90px !important;
    height: 90px;
    margin: auto;
    margin-top: 20px;
}

.card-title {
    text-align: center;
    height: 50px;
}

.card-text {
    display: flex;
    justify-content: center;
    text-align: center;
    margin-top: 15px !important;
    font-size: 15px;
    height: 30px;
    color: #808080;
}

.card-button-area {
    display: flex;
    justify-content: center;
}

.card-btn {
    font-size: 18px !important;
    margin-top: 30px;
    width: 100px;
    padding: 7px;
    background: #5400c0 !important;
}

.dropdown-item:hover {
    background: lightgray !important;
}

.purchases-history-butt-area {
    display: flex;
    align-items: center;
    margin-left: 10px;
}

.purchases-history-butt {
    font-size: 16px;
    padding: 5px 10px 5px 10px;
    text-decoration: none;
}

#normal-screen-purchases-history-butt {
    margin-left: 10px;
}

</style>
