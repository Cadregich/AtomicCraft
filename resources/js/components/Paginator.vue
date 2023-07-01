<template>
    <nav class="d-flex justify-items-center justify-content-between">
        <div class="justify-content-sm-between">
            <div>
                <ul class="pagination">
                    <!-- Arrow to the previous page -->
                    <li class="page-item" :class="{ 'disabled': currentPage === 1 }">
                        <a class="page-link" href="#" @click="changePage(currentPage - 1)"
                           aria-label="@lang('pagination.previous')">&lsaquo;</a>
                    </li>
                    <!-- Links buttons -->
                    <li v-for="page in links" :key="page" class="page-item"
                        :class="{ 'active': page.active, 'disabled': page.label === '...' }">
                        <a class="page-link" href="#" @click="changePage(page.label)">{{ page.label }}</a>
                    </li>
                    <!-- Arrow to next page -->
                    <li class="page-item" :class="{ 'disabled': currentPage === lastPage }">
                        <a class="page-link" href="#" @click="changePage(currentPage + 1)"
                           aria-label="@lang('pagination.next')">&rsaquo;</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</template>

<script>
export default {
    name: "Paginator",
    props: ['path', 'filters'],
    created() {
        for (const prop in this.$router.currentRoute.value.query) {
            if (this.filters.includes(prop)) {
                this.productsFilters[prop] = this.$router.currentRoute.value.query[prop];
            }
        }
        console.log(this.productsFilters);
        this.getPaginatorData({path: this.path, page: this.$route.query.page});
    },
    data() {
        return {
            paginatorData: [],
            cache: {
                paginatorData: {},
                products: {}
            },
            links: {},
            currentPage: '',
            lastPage: ''
        }
    },
    computed: {
        productsFilters() {
            return this.$store.getters.getProductsFilters;
        }
    },
    watch: {
        productsFilters: function() {
            this.cache = {
                paginatorData: {},
                products: {}
            }
            this.getPaginatorData({path: this.path, page: 1});
        }
    },
    methods: {
        changePage(page) {
            if (page >= 1 && page <= this.lastPage) {
                setTimeout(async () => {
                    this.getPaginatorData({path: this.path, page: page});
                    this.$router.replace({query: {...this.$route.query, page: page}});
                }, 600);
            } else {
                console.log(this.lastPage);
            }
        },
        getPaginatorData(payload) {
            const {path, page} = payload;
            if (this.cache.paginatorData[page] && this.cache.products[page]) {
                this.paginatorData = this.cache.paginatorData[page];
                this.setPaginatorDataToData(this.cache.paginatorData[page]);
                this.$store.commit('setProducts', this.cache.products[page].products);
                console.log('данные получены из кеша');
                return;
            }
            axios.get('/api/' + path, {params: {page: page, ...this.productsFilters}})
                .then(response => {
                    console.log(response.data);
                    const data = response.data.data;
                    const paginatorData = {...response.data};
                    paginatorData.links.shift();
                    paginatorData.links.pop();
                    this.setPaginatorDataToData(paginatorData);
                    this.$store.commit('setProducts', data);
                    this.cache.paginatorData[page] = {
                        current_page: paginatorData.current_page,
                        last_page: paginatorData.last_page,
                        links: paginatorData.links
                    }
                    this.cache.products[page] = {
                        products: data
                    }

                })
                .catch((error) => {
                    console.error(error);
                });
        },
        setPaginatorDataToData(paginatorData) {
            this.links = paginatorData.links;
            this.currentPage = paginatorData.current_page;
            this.lastPage = paginatorData.last_page;
        }
    },
};
</script>

<style scoped>
.page-item {
    user-select: none;
}

.page-link {
    background: #4b0082;
    color: white;
    border: black;
}

.pagination > .page-link.disabled, .disabled > .page-link {
    background-color: #410370 !important;
    color: white;
}

.pagination > .page-link.active, .active > .page-link {
    background-color: #5618db;
}

.page-link:hover {
    background-color: #c57aff !important;
    color: white;
}

.page-link:active {
    background-color: #35015b !important;
    color: white;
}

.page-link:focus {
    color: white;
    box-shadow: none;
    background: #4b0082;
}

@media (max-width: 500px) {
    .page-link {
        font-size: 10px;
    }
}
</style>
