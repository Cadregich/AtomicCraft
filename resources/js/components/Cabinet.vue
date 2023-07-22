<template>
    <div class="skin-block">
        <div class="skin-butt atomic-block column-center row-gap-2" id="skin-butt-skin">
            <div class="skin-butt-title">Изменить скин</div>
            <template v-if="skinPath">
                <skin-head ref="skinHead" size="50" type="skin" :texture-path="skinPath"></skin-head>
            </template>
            <div class="upload-and-remove-skin column-center">
                <label for="skin-upload" class="change-skin-butt">
                    <i class="fa-sharp fa-solid fa-share"></i> Загрузить
                    <input @change="uploadSkin($event, 'skin')" ref="skinUpload" id="skin-upload" class="d-none" type="file"
                           name="file" accept="png">
                </label>
                <button @click="removeSkin('skin')" type="submit" class="remove-skin-butt" id="removeSkin"
                        title="Удалить скин"
                        value="">
                    <i class="fa-solid fa-trash-can"></i> Удалить
                </button>
            </div>
        </div>
        <template v-if="skinPath">
            <skinViewer ref="skinViewerRef" :skin-path="skinPath" :cape-path="capePath"></skinViewer>
        </template>
        <div class="skin-butt atomic-block column-center row-gap-2" id="skin-butt-cape">
            <div class="skin-butt-title">Изменить плащ</div>
            <template v-if="capePath">
                <skin-head ref="skinCape" size="50" type="cape" :texture-path="capePath"></skin-head>
            </template>
            <div class="upload-and-remove-skin column-center" id="cape-url">
                <label for="cape-upload" class="change-skin-butt">
                    <i class="fa-sharp fa-solid fa-share"></i> Загрузить
                    <input @change="uploadSkin($event, 'cape')" ref="capeUpload" id="cape-upload" class="d-none" type="file"
                           name="file" accept="png">
                </label>
                <button @click="removeSkin('cape')" type="submit" class="remove-skin-butt" id="removeCape"
                        title="Удалить скин">
                    <i class="fa-solid fa-trash-can"></i> Удалить
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import skinViewer from "./SkinViewer.vue";
import skinHead from "./SkinHead.vue";

export default {
    name: "Cabinet",
    components: {
        SkinViewer: skinViewer,
        skinHead
    },
    data() {
        return {
            skinPath: '',
            capePath: ''
        }
    },
    computed: {
        userName() {
            return this.$store.getters.userName;
        },
    },
    mounted() {
        axios.get('/cabinet')
            .then(res => {
                console.log(res.data);
                this.skinPath = res.data.skinPath;
                this.capePath = res.data.capePath;
            })
            .catch(error => {
                console.log(error);
            })
    },
    methods: {
        uploadSkin(event, type) {
            console.log('uploadSkin');
            const formData = new FormData();
            const asset = event.target.files[0];

            if (type === 'skin') {
                formData.append('skin', asset);
            } else if (type === 'cape') {
                formData.append('cape', asset);
            } else {
                console.error('Upload asset error: type error');
            }

            axios.post('/cabinet/skin', formData)
                .then(res => {
                    console.log(res.data);
                    this.updateSkinHeadTexture(type, res.data);
                    this.$refs.skinViewerRef.reloadTexture(formData.get(type), type);
                })
        },
        removeSkin(type) {
            axios.delete('/cabinet/skin', {
                data: {type: type}
            })
                .then(res => {
                    console.log(res.data);
                    this.resetSkinTextureFromInput(type);
                    this.updateSkinHeadTexture(type, res.data);
                    this.$nextTick(() => {
                        this.$refs.skinViewerRef.initializeViewer();
                    });
                })
                .catch(error => {
                    console.log(error);
                });
        },
        updateSkinHeadTexture(type, paths) {
            if (type === 'skin') {
                this.skinPath = paths.skinPath;
                this.$refs.skinHead.initializeFaceRender(this.skinPath);
            } else if (type === 'cape') {
                this.capePath = paths.capePath;
                this.$nextTick(() => {
                    if (this.capePath) {
                        this.$refs.skinCape.initializeFaceRender(this.capePath);
                    }
                });
            }
        },
        resetSkinTextureFromInput(type) {
            const fileInput = type === 'skin' ? this.$refs.skinUpload : this.$refs.capeUpload;
            if (fileInput) {
                fileInput.value = null;
            }
        }
    },
}
</script>

<style scoped>
.cabinet-blocks {
    display: grid;
    justify-items: center;
    align-items: start;
    grid-row-gap: 20px;
    margin-top: 40px;
}

.skin-block {
    display: grid;
    grid-template-rows: auto;
    grid-template-columns: auto auto auto;
    grid-column-gap: 30px;
}

.privileges {
    display: grid;
    grid-template-columns: auto auto auto;
    grid-gap: 20px;
}

@media (min-width: 1600px) {
    .cabinet-blocks {
        grid-template-columns: 1fr 1fr 1fr;
        grid-template-rows: 1fr 1fr;
    }


}

@media (min-width: 1000px) and (max-width: 1600px) {
    .cabinet-blocks {
        grid-template-columns: 1fr 1fr;
    }

    .skin-block {
        grid-row: 1;
        grid-column: 1;
    }

    .info-block {
        grid-row: 1;
        grid-column: 2;
    }
}

@media (max-width: 1000px) and (min-width: 800px) {
    .cabinet-blocks {
        grid-template-columns: 1fr 1fr;
    }

    .skin-block {
        grid-row: 1;
        grid-column: 1 / span 2;
    }
}

@media (max-width: 800px) {
    .cabinet-blocks {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 5px;
    }

    .skin-block {
        order: -1; /* Make it on first row */
        display: grid;
        grid-column-gap: 0;
        grid-template-areas:
    "top top"
    "bottom1 bottom2";
        grid-row: 1;
        justify-items: center;
    }

    #skin-butt-skin {
        grid-area: bottom1;
        margin-right: 40px;
    }

    #skin-butt-cape {
        grid-area: bottom2;
    }
}

@media (max-width: 500px) {
    .privileges {
        grid-template-columns: auto auto;
    }
}

@media (max-width: 350px) {
    .info-block > h4 {
        text-align: center;
    }

    .info-table {
        border-collapse: separate;
        border-spacing: 0 10px;
    }

    .info-table > tbody > tr > td:nth-child(even) {
        padding-left: 30px;
    }

    .skin-block {
        padding: 0;
    }

    .skin-butt {
        text-align: center;
        width: 95%;
    }

    .change-skin-butt {
        width: 115px;
    }

    #skin-butt-skin {
        margin-right: 0;
    }
}

@media (max-width: 270px) {
    .cabinet-blocks {
        padding: 0;
    }

    .info-table {
        border-spacing: 0 10px;
        font-size: 13px;
    }

    .info-table > tbody > tr > td:nth-child(even) {
        padding-left: 0;
    }

    .skin-block {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .skin-butt {
        width: 150px;
    }

    #skin-butt-skin {
        margin-bottom: 10px;
    }

    .privileges {
        grid-template-columns: auto;
    }
}

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

.skin-block {
    display: flex;
    align-items: start;
    background-color: rgba(0, 0, 0, 0.05);
    padding: 15px;
    border-radius: 20px;
}

.skin-butt-title {
    font-size: 16px;
    font-weight: 500;
}

.skin-butt {
    padding: 10px !important;
}

.change-skin-butt {
    margin-top: 3px;
    background-color: #4E117E;
    color: white;
    border: 2px solid #9012FF;
    font-size: 14px;
    border-top: none;
    border-bottom: none;
    border-radius: 30px;
    transition: 0.2s;
    display: inline-block;
    padding: 8px 12px;
    cursor: pointer;
}

.change-skin-butt:hover {
    background: #7120B0;
}

.change-skin-butt:active {
    background: #942be7;
}

.remove-skin-butt {
    background-color: #3405C8;
    color: white;
    border: none;
    border-radius: 0 0 10px 10px;
    display: block;
    margin: 0 auto 10px;
    transition: 0.2s;
}

.remove-skin-butt:hover {
    background: #2c059f;
}

.remove-skin-butt:active {
    background: #3904e3;
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
