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

                const validWidths = [64, 128, 256, 512, 1024];
                const validHeights = [32, 64, 128, 256, 512, 1024];

                if (!validWidths.includes(textureWidth) || !validHeights.includes(textureHeight)) {
                    console.error('Face Render: Invalid texture size');
                    return;
                }

                let indentsAndSize = {};

                if (type === 'cape') {
                    indentsAndSize = {
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

                /*
                Calculate Image Size Multiplier Based On Texture Width
                We need to increase the padding and texture sizes depending on the width of the texture,
                to correctly display the image on the canvas according to its size.
                Use base 2 logarithmic operation to determine magnification.
                Subtract 6 From The Logarithm To Consider That Initially For A 64px Texture
                the size has been set to 1, and the sizeMultiplier for it will be 1 (2^0 = 1).
                */

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

                    ctx.fillRect(0, 0, canvasWidth, canvasHeight); //Fill cape bg`s black

                    ctx.drawImage(
                        image,
                        0,
                        5,
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
