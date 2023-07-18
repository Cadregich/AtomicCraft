<template>
    <div class="container">
        <form method="post" @submit.prevent="addProduct" class="text-center m-auto" id="create-form" enctype="multipart/form-data">
            <div class="h1 mt-3 mb-5">Добавить предмет</div>
            <div class="mb-3 ">
                <label for="addItemName" class="form-label">Название</label>
                <input type="text" class="form-control" name="name" id="addItemName" v-model="form.name">
            </div>
            <div class="mb-3">
                <label for="mods-inputs" class="form-label">Мод</label><br>
                <div id="mods-inputs">
                    <input class="form-control" id="mod-select-text" name="mod" placeholder="Добавить новый мод" v-model="form.mod">
                    <select class="form-select" id="mod-select-input" name="mod" v-model="form.mod">
                        <option selected id="mod-selected" disabled>Выбрать мод</option>
                        <template v-for="mod in mods">
                            <option :value="mod">{{ mod }}</option>
                        </template>
                    </select>
                </div>
            </div>
            <div class="mb-3">
                <label for="addItemImage" class="form-label">Картинка</label>
                <input class="form-control" type="file" name="img" id="addItemImage" @change="handleFileChange">
            </div>
            <div class="mb-3">
                <label for="addItemPrice" class="form-label">Цена</label>
                <input type="text" class="form-control" name="price" id="addItemPrice" v-model="form.price">
            </div>
            <div class="mb-4">
                <label for="addItemAssociations" class="form-label">Ассоциации для поиска (через запятую)</label>
                <input type="text" class="form-control" name="associations" id="addItemAssociations" v-model="form.associations">
            </div>
            <button type="submit" class="btn btn-primary">Сохранить</button>
            <div class="text-success mt-3" v-if="showSuccessMessage">
                Товар успешно добавлен
            </div>
        </form>
    </div>
</template>

<script>
export default {
    name: "ShopCreateProduct",
    data() {
        return {
            mods: [],
            showSuccessMessage: false,
            form: {
                name: "",
                mod: "",
                img: null,
                price: "",
                associations: ""
            }
        };
    },
    mounted() {
        this.fetchMods();
    },
    methods: {
        addProduct() {
            const formData = new FormData();
            formData.append('name', this.form.name);
            formData.append('mod', this.form.mod);
            formData.append('img', this.form.img);
            formData.append('price', this.form.price);
            formData.append('associations', this.form.associations);

            axios.post('/shop/create', formData)
                .then(response => {
                    console.log(response.data);
                    this.showSuccessMessage = true;
                    setTimeout(() => {
                        this.showSuccessMessage = false;
                    }, 4000);
                    this.resetForm();
                })
                .catch(error => {
                    console.log(error);
                });
        },
        fetchMods() {
            axios
                .get("/shop/goods-mods")
                .then((res) => {
                    this.mods = res.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        resetForm() {
            this.form.name = "";
            this.form.mod = "";
            this.form.img = null;
            this.form.price = "";
            this.form.associations = "";
        },
        handleFileChange(event) {
            this.form.img = event.target.files[0];
        }
    }
};
</script>

<style scoped>
#mods-inputs {
    display: flex;
}

#mod-select-input {
    width: 50%;
}

#mod-select-input > option {
    text-align: center;
}

@media (min-width: 1000px) {
    #create-form {
        width: 35%;
    }
}
</style>
