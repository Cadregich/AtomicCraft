<template>
    <canvas ref="skinHead" :width="size" :height="size"></canvas>
</template>

<script>
export default {
    name: "SkinHead",
    props: ['texturePath', 'size', 'type'],
    mounted() {
        this.initializeFaceRender();
    },
    methods: {
        drawTexture(ctx, canvas, type, path) {
            if (!path) {
                path = this.texturePath;
            }
            const image = new Image();
            image.src = path + "?t=" + new Date().getTime();
            image.onload = () => {
                const canvasWidth = canvas.width;
                const canvasHeight = canvas.height;
                const textureWidth = image.width;
                const textureHeight = image.height;

                let indentsAndSize = {};

                if (type === 'cape') {
                    indentsAndSize = {
                        indentX: 0,
                        indentY: 5,
                        width: 12,
                        height: 8,
                    };
                } else if (type === 'skin') {
                    indentsAndSize = {
                        FirstLayer: 8,
                        SecondLayer: 8,
                        indentXSecondLayer: 40
                    };
                }

                const sizeMultiplier = 2 ** (Math.log2(textureWidth) - 6);
                for (let key in indentsAndSize) {
                    indentsAndSize[key] *= sizeMultiplier;
                }

                // We need to fill the background of the asset with black for transparent skins.
                ctx.fillStyle = 'black';

                if (type === 'skin') {
                    const imageWidth = canvasWidth - (canvasWidth * (8 / 100));    // To reduce the first layer so that the second is in front
                    const imageHeight = canvasHeight - (canvasHeight * (8 / 100)); // To reduce the first layer so that the second is in front
                    const canvasDX = (canvasWidth - imageWidth) / 2;   // To center the first layer
                    const canvasDY = (canvasHeight - imageHeight) / 2; // To center the first layer

                    ctx.fillRect(canvasDX, canvasDY, imageWidth, imageHeight); //Fill first layer of skin bg`s black

                    ctx.drawImage(
                        image,
                        indentsAndSize["FirstLayer"],
                        indentsAndSize["FirstLayer"],
                        indentsAndSize["FirstLayer"],
                        indentsAndSize["FirstLayer"],
                        canvasDX,
                        canvasDY,
                        imageWidth,
                        imageHeight
                    );

                    ctx.drawImage(
                        image,
                        indentsAndSize["indentXSecondLayer"],
                        indentsAndSize["SecondLayer"],
                        indentsAndSize["SecondLayer"],
                        indentsAndSize["SecondLayer"],
                        0,
                        0,
                        canvasWidth,
                        canvasHeight
                    );
                } else if (type === 'cape') {
                    /*
                     Since there are 22x17 capes that are not divisible by 2, we have to use
                     this hard method to support such capes manually specifying the desired dimensions.
                     */
                    if (textureWidth === 22 && textureHeight === 17) {
                        indentsAndSize['indentX'] = 2;
                        indentsAndSize['indentY'] = 5;
                        indentsAndSize['width'] = 8;
                        indentsAndSize['height'] = 7;
                    }

                    ctx.fillRect(0, 0, canvasWidth, canvasHeight); //Fill cape bg`s black

                    ctx.drawImage(
                        image,
                        indentsAndSize['indentX'],
                        indentsAndSize['indentY'],
                        indentsAndSize['width'],
                        indentsAndSize['height'],
                        0,
                        0,
                        canvasWidth,
                        canvasHeight
                    );
                }
            };
        },

        initializeFaceRender(path = null) {
            const canvas = this.$refs.skinHead;
            const ctx = canvas.getContext("2d");
            this.removeBlurFromCanvas(canvas, ctx);
            ctx.clearRect(0, 0, this.size, this.size);
            this.drawTexture(ctx, canvas, this.type, path);
        },

        removeBlurFromCanvas(canvas, ctx) {
            ctx.imageSmoothingEnabled = false;
            canvas.style.imageRendering = "pixelated";
        }
    }
}
</script>
