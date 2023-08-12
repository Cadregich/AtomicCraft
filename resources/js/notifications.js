import Toastify from 'toastify-js';
import "toastify-js/src/toastify.css"

export const Notification = {
    error(text) {
        Toastify({
            text: text,
            duration: 4000,
            backgroundColor: 'linear-gradient(to right, #ff6b6b, #e74c3c)',
            style: {
                color: "white",
                "font-size": "18px",
                "max-width": "400px",
            }
        }).showToast();
    },
}
