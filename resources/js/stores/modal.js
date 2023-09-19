import {Modal} from "bootstrap";

const shop= {
    state: {
        modal: null,
    },
    mutations: {
        createModal(state, modalId) {
            state.modal = new Modal(document.getElementById(modalId));
        },
    },
    actions: {
        openModal(context, modalId) {
            context.commit('createModal', modalId);
            context.state.modal.show();
        },

        closeModal(context) {
            context.state.modal.hide();
            context.state.modal = null;
        },
    },
    modules: {}
};

export default shop;
