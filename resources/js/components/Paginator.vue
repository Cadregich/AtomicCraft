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
    props: ['path'],
    created() {
        this.$store.dispatch('getPaginatorData', {path: this.path, page: 1});
    },
    computed: {
        links() {
            return this.$store.getters.getPaginatorData.links;
        },
        currentPage() {
            return this.$store.getters.getPaginatorData.current_page;
        },
        lastPage() {
            return this.$store.getters.getPaginatorData.last_page;
        }
    },
    methods: {
        changePage(page) {
            if (page >= 1 && page <= this.lastPage) {
                setTimeout(async () => {
                    this.$store.dispatch('getPaginatorData', {path: this.path, page: page});
                    // this.$router.replace({ query: { page: page } });
                }, 600);
            } else {
                console.log(this.lastPage);
            }
        },
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
