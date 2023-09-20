<template>
    <nav class="d-flex justify-items-center justify-content-between">
        <div class="justify-content-sm-between">
            <div>
                <ul class="pagination">
                    <!-- Arrow to the previous page -->
                    <li class="page-item" :class="{ 'disabled': currentPage === 1 }">
                        <button class="page-link" href="#" @click="changePage(currentPage - 1)"
                                aria-label="@lang('pagination.previous')">&lsaquo;
                        </button>
                    </li>
                    <!-- Links buttons -->
                    <li v-for="page in links" :key="page" class="page-item"
                        :class="{ 'active': page.active, 'disabled': page.label === '...' }">
                        <button class="page-link" href="#" @click="changePage(page.label)">{{ page.label }}</button>
                    </li>
                    <!-- Arrow to next page -->
                    <li class="page-item" :class="{ 'disabled': currentPage === lastPage }">
                        <button class="page-link" href="#" @click="changePage(currentPage + 1)"
                                aria-label="@lang('pagination.next')">&rsaquo;
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</template>

<script>
export default {
    name: "Paginator",
    props: ['path'],
    created() {
        console.log('Paginator created');
        if (!this.$route.query.page) {
            this.$router.push({path: '/shop', query: {...this.$route.query, page: 1}});
        } else {
            this.fetchData({path: this.path, ...this.$route.query});
        }
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
    methods: {
        changePage(page) {
            if (page >= 1 && page <= this.lastPage) {
                this.scrollToTop();
                setTimeout(() => {
                    this.$router.push({ path: '/shop', query: { ...this.$route.query, page: page } });
                }, 600);
            }
        },
        fetchData(payload) {
            const { path, page, clearCache } = payload;
            if (clearCache) {
                this.clearCache();
                console.log('Cache cleared successfully!');
            }
            if (this.isDataCached(page)) {
                this.loadCachedData(page);
                console.log('Data retrieved from cache');
                return;
            }
            console.log(payload);
            axios.get('/' + path, { params: payload })
                .then(response => {
                    console.log(response.data);
                    const products = response.data.data;
                    const paginatorData = { ...response.data };
                    //Remove arrows from links
                    paginatorData.links.shift();
                    paginatorData.links.pop();

                    this.setPaginatorDataToData(paginatorData);
                    this.$store.commit('setProducts', products);
                    this.cacheData(page, paginatorData, products);
                })
                .catch((error) => {
                    console.error(error);
                });
        },
        clearCache() {
            this.cache = {
                paginatorData: {},
                products: {}
            };
        },
        isDataCached(page) {
            return this.cache.paginatorData[page] && this.cache.products[page];
        },
        loadCachedData(page) {
            this.paginatorData = this.cache.paginatorData[page];
            this.setPaginatorDataToData(this.cache.paginatorData[page]);
            this.$store.commit('setProducts', this.cache.products[page].products);
        },
        cacheData(page, paginatorData, products) {
            this.cache.paginatorData[page] = {
                current_page: paginatorData.current_page,
                last_page: paginatorData.last_page,
                links: paginatorData.links
            };
            this.cache.products[page] = {
                products: products
            };
        },
        setPaginatorDataToData(paginatorData) {
            this.links = paginatorData.links;
            this.currentPage = paginatorData.current_page;
            this.lastPage = paginatorData.last_page;
        },
        scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
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

.pagination > .page-link, .disabled > .page-link {
    background-color: #410370 !important;
    color: white;
}

.pagination > .page-link, .active > .page-link {
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
