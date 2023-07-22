<template>
    <div class="skin">
        <div class="skin-animation-buttons">
            <button id="animation_none" name="animation" value=""><i class="fa-solid fa-person"></i></button>
            <button id="animation_walk" name="animation" value="walk"><i class="fa-solid fa-walking"></i>
            </button>
            <button id="animation_run" name="animation" value="run"><i class="fa-solid fa-running"></i></button>
        </div>
        <canvas id="skin_container" class="d-block"></canvas>
    </div>
</template>

<script>
import '../skinview3d.bundle.js';

let skinViewer;
export default {

    name: "SkinViewer",
    props: ['skinPath', 'capePath'],
    mounted() {
        const availableAnimations = {
            walk: new skinview3d.WalkingAnimation(),
            run: new skinview3d.RunningAnimation()
        };

        this.initializeViewer(availableAnimations);
    },
    methods: {
        initializeViewer(availableAnimations) {
            skinViewer = new skinview3d.SkinViewer({
                canvas: document.getElementById("skin_container"),
                skin: this.skinPath + "?t=" + new Date().getTime(),
                cape: this.capePath ? this.capePath + "?t=" + new Date().getTime() : undefined,
                width: '250',
                height: '400'
            });

            skinViewer.fov = 70;
            skinViewer.zoom = 1;
            skinViewer.globalLight.intensity = 0.5;
            skinViewer.cameraLight.intensity = 0.5;
            skinViewer.controls.enableRotate = true;
            skinViewer.controls.enableZoom = true;
            skinViewer.controls.enablePan = false;

            const animationButtons = document.querySelectorAll('button[name="animation"]');
            animationButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    const animationName = button.value;
                    if (animationName !== "") {
                        skinViewer.animation = availableAnimations[animationName];
                    } else {
                        skinViewer.animation = null;
                    }
                });
            });
        },
        reloadTexture(input, type) {
            let textureUrl = URL.createObjectURL(input);
            if (type === 'skin') {
                skinViewer.loadSkin(textureUrl, {
                    model: 'auto-detect',
                });
            } else if (type === 'cape') {
                skinViewer.loadCape(textureUrl, {backEquipment: 'cape'});
            }
        }
    }
}
</script>

<style scoped>
.skin {
    grid-area: top;
    margin-bottom: 40px;
}

.skin-animation-buttons {
    display: flex;
    flex-direction: column;
    justify-items: start;
    align-content: start;
    row-gap: 2px;
    position: absolute;
    margin-top: 5px;
    margin-left: 5px;
}

.skin-animation-buttons > button {
    border-radius: 5px;
    background-color: rgb(3, 135, 229);
    /*color: rgba(255,255,255, 0.9);*/
    color: lightgray;
    border: 1px solid white;
    font-size: 15px;
}

.skin-animation-buttons > button:hover {
    position: relative;
    bottom: 1px;
    background-color: rgb(5, 121, 204);
}

.skin-animation-buttons > button:active {
    top: 1px;
}

@media (max-width: 270px) {
    .skin {
        order: -1;
    }

    .skin-animation-buttons {
        margin-left: 30px;
    }
}
</style>
