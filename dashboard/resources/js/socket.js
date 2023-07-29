import { io } from "socket.io-client";
import Toastify from "toastify-js";

const audio = new Audio("/sound/notif.mp3");

const socket = io(APP_SOCKETURL, {
    query: {
        ids: document.querySelector("#id_devices").value,
    },
});

window.io = io;
window.socket = socket;

socket.on("pesan-baru", (m) => {
    audio.play();

    var countingel = document.querySelector(".counting-pesan");
    countingel.innerHTML = parseInt(countingel.innerHTML) + 1;

    var toasts = document.querySelectorAll(".toastify");

    if (toasts.length >= 3) {
        toasts[0].remove();
        Toastify({
            text: `Pesan Baru Dari ${m.message.push_name} ( ${m.message.number} )`,
            duration: 1000,
            gravity: "bottom",
            position: "right",
            close: true,
        }).showToast();
    } else {
        Toastify({
            text: `Pesan Baru Dari ${m.message.push_name} ( ${m.message.number} )`,
            duration: 1000,
            gravity: "bottom",
            position: "right",
            close: true,
        }).showToast();
    }
});
