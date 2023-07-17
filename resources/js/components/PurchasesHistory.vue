<template>
    <div class="main-container">
        <div class="purchases-container">
            <h3 class="purchases-header mt-3 mt-sm-0 ml-sm-0">Блоки и предметы</h3>
            <div class="purchases-table mt-2 mt-mg-3">
                <table class="table text-white">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Предмет</th>
                        <th scope="col">Время</th>
                        <th scope="col">Кол-во</th>
                        <th scope="col">Цена</th>
                    </tr>
                    </thead>
                    <tbody v-for="(purchase, index) in purchases" id="purchases-table-body">
                    <tr>
                        <th>{{ index + 1 }}</th>
                        <th>{{ purchase.goods_name }}</th>
                        <th>{{ new Date(purchase.updated_at).toLocaleString() }}</th>
                        <th>{{ purchase.goods_count }}</th>
                        <th>{{ purchase.purchase_price }}</th>
                    </tr>
                    </tbody>
                </table>
                <div v-if="showLoadMoreButton" id="purchase-load-more-butt-area">
                    <button @click="loadMorePurchases" id="load-more-button">Загрузить ещё</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "GoodsHistory",
    data() {
        return {
            purchases: [],
            purchasesCount: 0,
            offset: 0,
            limit: 10,
            showLoadMoreButton: true
        }
    },
    mounted() {
        this.getItems();
    },
    methods: {
        getItems() {
            axios.get('/shop/get-more-items', {
                params: {
                    offset: this.offset,
                    limit: this.limit
                }
            })
                .then(res => {
                    console.log(res.data);
                    this.purchases = [...this.purchases, ...res.data.purchases];
                    this.purchasesCount = res.data.purchasesCount;
                    this.offset = this.offset + this.limit;
                    if (this.offset >= this.purchasesCount) {
                        this.showLoadMoreButton = false;
                    }
                })
                .catch(error => {
                    console.log(error);
                });
        },
        loadMorePurchases() {
            this.getItems();
        }
    }
}
</script>

<style scoped>
.main-container {
    display: flex;
    justify-content: center;
    color: white;
}

.purchases-container {
    display: flex;
    flex-direction: column;
    justify-content: center;
    margin-top: 30px;
    background-color: #25033D;
    padding: 25px 40px 40px 40px;
    border-radius: 20px;
    width: 50%;
    max-width: 1700px;
}

.purchases-table {
    background-color: indigo;
    padding: 10px;
    border-radius: 10px;
    width: 100%;
}

#purchases-table-body > tr > th {
    font-weight: 500;
}

#load-more-button {
//display: none; padding: 3px; background: #a800ff;
    color: white;
    border: 0;
    border-radius: 5px;
}

#load-more-button:hover {
    transition: 0.2s;
    background: #7e00b3;
}

#load-more-button:active {
    transition: 0.1s;
    background: #8521d2;
}

@media (max-width: 1000px) {
    .purchases-container {
        width: 90%;
        padding: 20px 35px 35px 35px;
    }
}

@media (max-width: 600px) {
    .purchases-container {
        width: 95%;
        padding: 20px 25px 25px 25px;
    }

    .purchases-header {
        font-size: 18px !important;
        margin-left: 7px;
    }

    #purchase-load-more-butt-area {
        display: flex;
        justify-content: center;
    }

    .purchases-table {
        font-size: 15px;
    }
}

@media (max-width: 400px) {
    .purchases-container {
        width: 100%;
        padding: 0;
        font-size: 12px;
    }

    .purchases-table {
        padding: 3px;
        font-size: 12px;
    }
}
</style>
