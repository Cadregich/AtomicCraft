<template>
    <div class="modal fade" :id="id" tabindex="-1">
        <div class="modal-dialog modal-dialog-up-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">
                        <slot name="modalHeader"></slot>
                    </h4>
                    <button type="button" class="modal-close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <div class="modal-body thanks-modal-body">
                    <slot name="modalBody"></slot>
                </div>
                <div class="modal-footer w-100 d-flex justify-content-between">
                    <slot name="modalFooter"></slot>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {Modal} from "bootstrap";

export default {
    name: 'ModalLayout',
    data() {
        return {
            showFooter: false
        }
    },
    computed: {
        modal() {
            return new Modal(document.getElementById(this.id));
        }
    },
    props: {
        id: String
    }
}
</script>
