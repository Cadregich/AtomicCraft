<template>
    <div class="skin-block">
        <!-- Change skin block -->
        <div class="skin-butt atomic-block column-center row-gap-2" id="skin-butt-skin">
            <div class="skin-butt-title nobr">Изменить скин</div>
            <template v-if="skinPath">
                <skin-head ref="skinHead" size="50" type="skin" :texture-path="skinPath"></skin-head>
            </template>
            <div class="upload-and-remove-skin column-center">
                <label for="skin-upload" class="change-skin-butt nobr">
                    <i class="fa-sharp fa-solid fa-share"></i> Загрузить
                    <input @change="uploadSkin($event, 'skin')" ref="skinUpload" id="skin-upload" class="d-none"
                           type="file"
                           name="file" accept="png">
                </label>
                <button @click="removeSkin('skin')" type="submit" class="remove-skin-butt" id="removeSkin"
                        title="Удалить скин"
                        value="">
                    <i class="fa-solid fa-trash-can"></i> Удалить
                </button>
            </div>
        </div>
        <!-- Skin viewer block -->
        <template v-if="skinPath">
            <skinViewer ref="skinViewerRef" :skin-path="skinPath" :cape-path="capePath"></skinViewer>
        </template>
        <!-- Change cape block -->
        <div class="skin-butt atomic-block column-center row-gap-2" id="skin-butt-cape">
            <div class="skin-butt-title nobr">Изменить плащ</div>
            <template v-if="capePath">
                <skin-head ref="skinCape" size="50" type="cape" :texture-path="capePath"></skin-head>
            </template>
            <div class="upload-and-remove-skin column-center" id="cape-url">
                <label for="cape-upload" class="change-skin-butt nobr">
                        <i class="fa-sharp fa-solid fa-share"></i> Загрузить
                        <input @change="uploadSkin($event, 'cape')" ref="capeUpload" id="cape-upload" class="d-none"
                               type="file"
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
import {Notification} from '../notifications.js';

export default {
    name: "Cabinet",
    components: {
        skinViewer,
        skinHead,
    },
    data() {
        return {
            skinPath: '',
            capePath: '',
            defaultSkinPath: '',
            uploadErrorInfo: {
                validSkinSizes: ''
            }
        }
    },
    mounted() {
        axios.get('/cabinet')
            .then(res => {
                console.log(res.data);
                this.skinPath = res.data.skinPath;
                this.capePath = res.data.capePath;
                this.defaultSkinPath = res.data.defaultSkinPath;
            })
            .catch(error => {
                console.log(error);
            })
    },
    methods: {
        uploadSkin(event, type) {
            const formData = new FormData();
            const asset = event.target.files[0];

            try {
                this.validateUploadingAsset(asset);
            } catch (error) {
                this.resetSkinTextureFromInput(type);
                console.error(error);
                return;
            }

            formData.append('type', type);
            formData.append('file', asset);

            axios.post('/cabinet/skin', formData)
                .then(res => {
                    console.log(res.data);
                    this.updateSkinHeadTexture(type, res.data);
                    this.$refs.skinViewerRef.reloadTexture(formData.get('file'), type);
                })
                .catch(error => {
                    Notification.error(`Ошибка загрузки: Неверный размер ${type === 'skin' ? 'cкина' : (type === 'cape' ? 'плаща' : '')};
                    Допустимые размеры: ${error.response.data.validSizes}`);
                });
            this.resetSkinTextureFromInput(type);
        },

        removeSkin(type) {
            if (type === 'skin' && this.skinPath === this.defaultSkinPath) {
                return;
            } else if (type === 'cape' && this.capePath === '') {
                return;
            }

            axios.delete('/cabinet/skin', {data: {type: type}})
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
        },

        validateUploadingAsset(asset) {
            if (!asset) {
                throw new Error('Upload asset error: no file selected');
            }

            if (asset.size > 500 * 1024) {
                Notification.error('Ошибка: Файл слишком тяжёлый');
                throw new Error('Upload asset error: file is too heavy');
            }
        }
    },
}
</script>

<style>
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
</style>
